<?php

namespace Modules\DocumentRepository\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\DocumentRepository\app\Models\Document;
use Modules\DocumentRepository\app\Models\DocumentVersion;
use Modules\DocumentRepository\app\Models\DocumentType;
use Modules\MasterData\app\Models\Unit;

class DocumentRepositoryController extends Controller
{
    /**
     * Menampilkan daftar dokumen beserta data untuk Modal Upload
     */
    public function index(Request $request)
    {
        // 1. Tangkap status filter dari URL (default: 'all')
        $filterStatus = $request->query('status', 'all');

        // 2. Siapkan Query Dasar
        $query = Document::with(['type', 'unit', 'creator', 'workflowStatus', 'versions' => function($q) {
            $q->orderBy('version', 'desc');
        }]);

        // 3. Terapkan Filter jika ada
        if ($filterStatus === 'pending') {
            $query->whereIn('status', [1, 2]); // Draft atau Menunggu Persetujuan
        } elseif ($filterStatus === 'approved') {
            $query->where('status', 3); // Disetujui
        } elseif ($filterStatus === 'rejected') {
            $query->where('status', 4); // Ditolak / Revisi
        }

        // 4. Eksekusi query dengan pagination dan tambahkan query string agar pagination tidak error saat di-filter
        $documents = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());
        
        $documentTypes = DocumentType::all();
        $units = Unit::where('is_active', '1')->get();

        // 5. Kirim variabel $filterStatus ke view agar kita tahu tombol mana yang sedang aktif
        return view('documentrepository::index', compact('documents', 'documentTypes', 'units', 'filterStatus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_title'   => 'required|string|max:255',
            'document_type_id' => 'required|string|exists:ref_document_type,id',
            'unit_id'          => 'required|string|exists:mst_unit,id',
            'document_file'    => 'required|file|mimes:pdf,doc,docx|max:10240', 
            'effective_date'   => 'nullable|date',
            'expired_date'     => 'nullable|date|after_or_equal:effective_date',
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('document_file');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            $folderPath = 'documents/' . date('Y/m');
            $filePath = $file->storeAs($folderPath, $filename, 'public');

            $lastDoc = Document::orderBy('id', 'desc')->first();
            $nextNumber = $lastDoc ? ((int) substr($lastDoc->document_id, -4)) + 1 : 1;
            $documentId = 'DC' . date('y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $document = Document::create([
                'document_id'      => $documentId,
                'document_title'   => $request->document_title,
                'document_type_id' => $request->document_type_id,
                'unit_id'          => $request->unit_id,
                'version'          => 1,
                'file_path'        => $filePath,
                'status'           => 1, 
                'effective_date'   => $request->effective_date,
                'expired_date'     => $request->expired_date,
                'created_by'       => auth()->id(),
                'created_at'       => now(),
            ]);

            DocumentVersion::create([
                'document_id'   => $document->id,
                'version'       => 1,
                'file_path'     => $filePath,
                'change_note'   => 'Dokumen baru diunggah ke sistem',
                'approved_by'   => auth()->id(),
                'approved_date' => now(),
            ]);

            DB::commit();

            // PERBAIKAN NAMA ROUTE (Menggunakan huruf besar D dan R sesuai web.php)
            return redirect()->route('DocumentRepository.index')
                ->with('success', 'Dokumen berhasil diunggah dengan kode ' . $documentId);

        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Logika untuk mengunduh/membuka file secara aman
     */
    public function download($id)
    {
        $document = Document::findOrFail($id);
        
        if (Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->response($document->file_path);
        }

        return back()->with('error', 'Peringatan: File fisik tidak ditemukan di dalam server!');
    }

    /**
     * Menampilkan Dashboard untuk Reviewer
     */
    public function dashboard(Request $request)
    {
        // 1. Tangkap status filter dari URL (default: 'all')
        $filterStatus = $request->query('status', 'all');

        // 2. Siapkan Query Dasar
        $query = Document::with(['type', 'unit', 'creator', 'workflowStatus']);

        // 3. Terapkan Filter jika ada
        if ($filterStatus === 'pending') {
            $query->whereIn('status', [1, 2]); // Draft atau Menunggu Review
        } elseif ($filterStatus === 'approved') {
            $query->where('status', 3); // Disetujui
        } elseif ($filterStatus === 'rejected') {
            $query->where('status', 4); // Ditolak / Revisi
        }

        // 4. Eksekusi query
        $documents = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        // 5. Kirim variabel ke view (pastikan nama view-nya sesuai dengan milik Anda)
        return view('documentrepository::dashboard.index', compact('documents', 'filterStatus')); 
    }

    /**
     * Memproses aksi Approve atau Reject dari Reviewer
     */
    public function review(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'change_note' => 'required_if:action,reject|string|nullable|max:500',
        ]);

        DB::beginTransaction();
        try {
            $document = Document::findOrFail($id);
            $newStatus = $request->action === 'approve' ? 3 : 4; 
            
            $document->update(['status' => $newStatus]);

            $latestVersion = DocumentVersion::where('document_id', $document->id)
                                ->orderBy('version', 'desc')
                                ->first();

            if ($latestVersion) {
                $statusText = $request->action === 'approve' ? 'Disetujui' : 'Ditolak';
                $note = $request->change_note ? "($statusText) - " . $request->change_note : "Dokumen $statusText oleh Reviewer.";

                $latestVersion->update([
                    'approved_by' => auth()->id(),
                    'approved_date' => now(),
                    'change_note' => $note,
                ]);
            }

            DB::commit();
            
            $pesan = $request->action === 'approve' ? 'Dokumen berhasil disetujui!' : 'Dokumen telah ditolak.';
            return back()->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Logika untuk mengunggah file revisi (Update Versi)
     */
    public function revise(Request $request, $id)
    {
        $request->validate([
            'document_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $document = Document::findOrFail($id);

            $file = $request->file('document_file');
            $filename = time() . '_revisi_' . str_replace(' ', '_', $file->getClientOriginalName());
            $folderPath = 'documents/' . date('Y/m');
            $filePath = $file->storeAs($folderPath, $filename, 'public');

            $newVersion = $document->version + 1;

            $document->update([
                'file_path' => $filePath,
                'version'   => $newVersion,
                'status'    => 1, // Status kembali ke Draft / Menunggu Review
            ]);

            DocumentVersion::create([
                'document_id'   => $document->id,
                'version'       => $newVersion,
                'file_path'     => $filePath,
                'change_note'   => 'File direvisi oleh pengunggah',
                'approved_by'   => auth()->id(),
                'approved_date' => now(),
            ]);

            DB::commit();

            return back()->with('success', 'Dokumen berhasil direvisi menjadi versi ' . $newVersion . ' dan sedang menunggu review ulang.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            return back()->with('error', 'Terjadi kesalahan saat merevisi dokumen: ' . $e->getMessage());
        }
    }
}