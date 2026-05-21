<?php

namespace Modules\DocumentRepository\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Modules\DocumentRepository\app\Models\Document;
use Modules\DocumentRepository\app\Models\DocumentVersion;
use Modules\DocumentRepository\app\Models\DocumentType;
use Modules\MasterData\app\Models\Unit;
use App\Services\NotifService;


class DocumentRepositoryController extends Controller
{
    private $moduleId = 1;
    public function index(Request $request)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'R')) {
            abort(403, 'Unauthorized');
        }

        $filterStatus = $request->query('status', 'all');
        $query = Document::with([
            'type',
            'unit',
            'creator',
            'workflowStatus',
            'versions' => function ($q) {
                $q->orderBy('version', 'desc');
            }
        ]);

        if ($filterStatus === 'pending') {
            $query->whereIn('status', [1, 2]);
        } elseif ($filterStatus === 'approved') {
            $query->where('status', 3);
        } elseif ($filterStatus === 'rejected') {
            $query->where('status', 4);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());
        $documentTypes = DocumentType::all();
        $units = Unit::where('is_active', '1')->get();

        return view('documentrepository::index', compact('documents', 'documentTypes', 'units', 'filterStatus'))->with('title', 'Repositori Dokumen');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'C')) {
            abort(403, 'Unauthorized');
        }

        $request->merge([
            'effective_date' => $request->filled('effective_date') ? $request->effective_date : now()->toDateString(),
        ]);

        $request->validate([
            'document_title' => 'required|string|max:255',
            'document_type_id' => 'required|string|exists:ref_document_type,id',
            'unit_id' => 'required|string|exists:mst_unit,id',
            'document_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'effective_date' => 'required|date',
            'expired_date' => 'nullable|date|after_or_equal:effective_date',
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
                'document_id' => $documentId,
                'document_title' => $request->document_title,
                'document_type_id' => $request->document_type_id,
                'unit_id' => $request->unit_id,
                'version' => 1,
                'file_path' => $filePath,
                'status' => 1,
                'effective_date' => $request->effective_date,
                'expired_date' => $request->expired_date,
                'created_by' => auth()->id(),
                'created_at' => now(),
            ]);

            DocumentVersion::create([
                'document_id' => $document->id,
                'version' => 1,
                'file_path' => $filePath,
                'change_note' => 'Dokumen baru diunggah ke sistem',
                'approved_by' => auth()->id(),
                'approved_date' => now(),
            ]);

            DB::commit();
            
            // 1. Definisikan isi pesan notifikasinya
            $dataNotif = [
                'action' => 'telah mengunggah dokumen baru untuk divalidasi',
                'item_name' => $document->document_title,
                'type' => 'Repositori Dokumen',
                'url' => route('DocumentRepository.index'),
                'reference_id' => $document->id, // Lempar ID dokumennya biar gampang dicari
                'click_action' => 'redirect' // Ganti jadi 'open_modal_dokumen' kalau lu pake modal juga
            ];

            // 2. Tembak notif ke orang yang punya hak 'V' (Validasi) di modul DOC_REP
            NotifService::sendToApprovers('DOC_REP', 'V', Auth::user()->unit_id, $dataNotif);
            return redirect()->route('DocumentRepository.index')->with('success', 'Dokumen diunggah dengan kode ' . $documentId);
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }

    public function download($id)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'R')) {
            abort(403, 'Unauthorized');
        }

        $document = Document::findOrFail($id);
        if (Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->response($document->file_path);
        }
        return back()->with('error', 'File fisik tidak ditemukan!');
    }

    /**
     * DASHBOARD STATISTIK (Baru)
     */
    public function dashboard()
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'R')) {
            abort(403, 'Unauthorized');
        }

        $totalDokumen = Document::count();
        $totalPending = Document::whereIn('status', [1, 2])->count();
        $totalDisetujui = Document::where('status', 3)->count();
        $totalRevisi = Document::where('status', 4)->count();

        $dokumenTerbaru = Document::with(['type', 'unit', 'creator', 'workflowStatus'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $filterStatus = ''; // Menghindari error di layout
        return view('documentrepository::dashboard.index', compact('totalDokumen', 'totalPending', 'totalDisetujui', 'totalRevisi', 'dokumenTerbaru', 'filterStatus'));
    }

    /**
     * HALAMAN REVIEW (Baru)
     */
    public function reviewIndex(Request $request)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'A')) {
            abort(403, 'Unauthorized');
        }

        $filterStatus = $request->query('status', 'all');
        $query = Document::with(['type', 'unit', 'creator', 'workflowStatus']);

        if ($filterStatus === 'pending') {
            $query->whereIn('status', [1, 2]);
        } elseif ($filterStatus === 'approved') {
            $query->where('status', 3);
        } elseif ($filterStatus === 'rejected') {
            $query->where('status', 4);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());
        return view('documentrepository::review.index', compact('documents', 'filterStatus'))->with('title', 'Review Dokumen');
    }

    public function review(Request $request, $id)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'A')) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'change_note' => 'required_if:action,reject|string|nullable|max:500',
        ]);

        DB::beginTransaction();
        try {
            $document = Document::findOrFail($id);
            $newStatus = $request->action === 'approve' ? 3 : 4;
            $document->update(['status' => $newStatus]);

            $latestVersion = DocumentVersion::where('document_id', $document->id)->orderBy('version', 'desc')->first();
            if ($latestVersion) {
                $statusText = $request->action === 'approve' ? 'Disetujui' : 'Ditolak';
                $note = $request->change_note ? "($statusText) - " . $request->change_note : "Dokumen $statusText oleh Reviewer.";
                $latestVersion->update(['approved_by' => auth()->id(), 'approved_date' => now(), 'change_note' => $note]);
            }

            DB::commit();
            $pesan = $request->action === 'approve' ? 'Dokumen disetujui!' : 'Dokumen ditolak.';
            return back()->with('success', $pesan);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function revise(Request $request, $id)
    {
        if (!Auth::user()->hasPermission($this->moduleId, 'U')) {
            abort(403, 'Unauthorized');
        }

        $request->validate(['document_file' => 'required|file|mimes:pdf,doc,docx|max:10240']);
        DB::beginTransaction();
        try {
            $document = Document::findOrFail($id);
            $file = $request->file('document_file');
            $filename = time() . '_revisi_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('documents/' . date('Y/m'), $filename, 'public');

            $newVersion = $document->version + 1;
            $document->update(['file_path' => $filePath, 'version' => $newVersion, 'status' => 1]);

            DocumentVersion::create([
                'document_id' => $document->id,
                'version' => $newVersion,
                'file_path' => $filePath,
                'change_note' => 'File direvisi',
                'approved_by' => auth()->id(),
                'approved_date' => now(),
            ]);

            DB::commit();
            return back()->with('success', 'Dokumen direvisi menjadi versi ' . $newVersion);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal revisi: ' . $e->getMessage());
        }
    }
}
