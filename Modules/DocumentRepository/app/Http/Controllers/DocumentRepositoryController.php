<?php

namespace Modules\DocumentRepository\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\NotifService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\DocumentRepository\app\Models\Document;
use Modules\DocumentRepository\app\Models\DocumentType;
use Modules\DocumentRepository\app\Models\DocumentVersion;
use Modules\MasterData\app\Models\Unit;

class DocumentRepositoryController extends Controller
{
    private int $moduleId = 1;

    public function index(Request $request)
    {
        if (! Auth::user()->hasPermission($this->moduleId, 'R')) {
            abort(403, 'Unauthorized');
        }

        $documentTypes = DocumentType::orderBy('description')->get();
        $units = Unit::where('is_active', '1')->orderBy('unit_name')->get();

        return view('documentrepository::index', compact('documentTypes', 'units'))
            ->with('title', 'Repositori Dokumen');
    }

    public function getDocumentsData(Request $request)
    {
        if (! Auth::user()->hasPermission($this->moduleId, 'R')) {
            abort(403, 'Unauthorized');
        }

        $allowedPerPage = [10, 25, 50, 100, 150, 250];
        $perPage = (int) $request->query('per_page', 50);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 50;
        }

        $documents = $this->buildDocumentsQuery($request, false)
            ->paginate($perPage)
            ->through(fn (Document $doc): array => $this->formatDocumentForApi($doc));

        return response()->json($documents)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function reviewIndex(Request $request)
    {
        if (! Auth::user()->hasPermission($this->moduleId, 'A')) {
            abort(403, 'Unauthorized');
        }

        $documentTypes = DocumentType::orderBy('description')->get();

        return view('documentrepository::review.index', compact('documentTypes'))
            ->with('title', 'Review Dokumen');
    }

    public function getReviewDocumentsData(Request $request)
    {
        if (! Auth::user()->hasPermission($this->moduleId, 'A')) {
            abort(403, 'Unauthorized');
        }

        $allowedPerPage = [10, 25, 50, 100, 150, 250];
        $perPage = (int) $request->query('per_page', 50);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 50;
        }

        $documents = $this->buildDocumentsQuery($request, true)
            ->paginate($perPage)
            ->through(fn (Document $doc): array => $this->formatDocumentForApi($doc, true));

        return response()->json($documents)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function buildDocumentsQuery(Request $request, bool $reviewContext = false)
    {
        $query = Document::with([
            'type',
            'unit',
            'creator',
            'workflowStatus',
            'versions' => fn ($q) => $q->orderByDesc('version'),
        ]);

        if (! $reviewContext) {
            $query->where(function ($q) {
                $q->where('sifat_dokumen', 'Publik')
                    ->orWhere('created_by', auth()->id());
            });
        }

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('document_title', 'like', '%' . $search . '%')
                    ->orWhere('document_id', 'like', '%' . $search . '%')
                    ->orWhereHas('creator', fn ($c) => $c->where('name', 'like', '%' . $search . '%'))
                    ->orWhereHas('unit', fn ($u) => $u->where('unit_name', 'like', '%' . $search . '%'))
                    ->orWhereHas('type', fn ($t) => $t->where('description', 'like', '%' . $search . '%'));
            });
        }

        $status = (string) $request->query('status', '');
        match ($status) {
            'pending' => $query->whereIn('status', [1, 2]),
            'approved' => $query->where('status', 3),
            'rejected' => $query->where('status', 4),
            default => null,
        };

        $typeId = $request->query('document_type_id');
        if ($typeId !== null && $typeId !== '') {
            $query->where('document_type_id', $typeId);
        }

        $visibility = (string) $request->query('visibility', '');
        if (! $reviewContext && $visibility !== '') {
            match ($visibility) {
                'public' => $query->where('sifat_dokumen', 'Publik'),
                'private' => $query->where('sifat_dokumen', 'Private'),
                default => null,
            };
        }

        $sort = (string) $request->query('sort', 'newest');
        match ($sort) {
            'oldest' => $query->orderBy('created_at')->orderBy('id'),
            'title_asc' => $query->orderBy('document_title'),
            'title_desc' => $query->orderByDesc('document_title'),
            'code_asc' => $query->orderBy('document_id'),
            'code_desc' => $query->orderByDesc('document_id'),
            default => $query->orderByDesc('created_at')->orderByDesc('id'),
        };

        return $query;
    }

    private function formatDocumentForApi(Document $doc, bool $reviewContext = false): array
    {
        $revisionNote = null;
        if ((int) $doc->status === 4 && $doc->versions->isNotEmpty()) {
            $revisionNote = $doc->versions->first()->change_note;
        }

        $canDownload = Auth::user()->hasPermission($this->moduleId, 'R');
        $canRevise = Auth::user()->hasPermission($this->moduleId, 'U') && (int) $doc->status === 4;
        $canReview = $reviewContext
            && Auth::user()->hasPermission($this->moduleId, 'A')
            && (int) $doc->status !== 3;
        $canEdit = Auth::user()->hasPermission($this->moduleId, 'U')
            && $doc->created_by === auth()->id();

        return [
            'id' => $doc->id,
            'document_id' => $doc->document_id,
            'document_title' => $doc->document_title,
            'document_type_id' => $doc->document_type_id,
            'type_name' => $doc->type->description ?? '-',
            'unit_id' => $doc->unit_id,
            'unit_name' => $doc->unit->unit_name ?? '-',
            'status' => $doc->status,
            'status_label' => $doc->workflowStatus->description ?? 'Menunggu...',
            'version' => $doc->version,
            'creator_name' => $doc->creator->name ?? 'Sistem',
            'effective_date' => $doc->effective_date,
            'expired_date' => $doc->expired_date,
            'sifat_dokumen' => $doc->sifat_dokumen ?? 'Private',
            'is_ppid' => (bool) ($doc->is_ppid ?? false),
            'revision_note' => $revisionNote,
            'initial' => mb_strtoupper(mb_substr($doc->document_title, 0, 1)),
            'download_url' => route('DocumentRepository.download', $doc->id),
            'revise_url' => route('DocumentRepository.revise', $doc->id),
            'review_url' => route('DocumentRepository.review', $doc->id),
            'update_url' => route('DocumentRepository.update', $doc->id),
            'can_download' => $canDownload && ($reviewContext || (int) $doc->status === 3),
            'can_revise' => $canRevise,
            'can_review' => $canReview,
            'can_edit' => $canEdit,
            'is_locked' => in_array((int) $doc->status, [1, 2], true),
        ];
    }

    public function store(Request $request)
    {
        if (! Auth::user()->hasPermission($this->moduleId, 'C')) {
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
            'sifat_dokumen' => 'required|in:Publik,Private',
            'is_ppid' => 'nullable|boolean',
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
                'sifat_dokumen' => $request->sifat_dokumen,
                'is_ppid' => $request->has('is_ppid'),
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

            NotifService::sendToApprovers('DOC_REP', 'V', Auth::user()->unit_id, [
                'action' => 'telah mengunggah dokumen baru untuk divalidasi',
                'item_name' => $document->document_title,
                'type' => 'Repositori Dokumen',
                'url' => route('DocumentRepository.index'),
                'reference_id' => $document->id,
                'click_action' => 'redirect',
            ]);

            return redirect()->route('DocumentRepository.index')
                ->with('success', 'Dokumen diunggah dengan kode ' . $documentId);
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
        if (! Auth::user()->hasPermission($this->moduleId, 'R')) {
            abort(403, 'Unauthorized');
        }

        $document = Document::findOrFail($id);

        if ($document->sifat_dokumen === 'Private'
            && $document->created_by !== auth()->id()
            && ! Auth::user()->hasPermission($this->moduleId, 'A')) {
            abort(403, 'Anda tidak memiliki akses ke dokumen Private ini.');
        }

        if (Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->response($document->file_path);
        }

        return back()->with('error', 'File fisik tidak ditemukan!');
    }

    public function dashboard()
    {
        if (! Auth::user()->hasPermission($this->moduleId, 'R')) {
            abort(403, 'Unauthorized');
        }

        $totalDokumen = Document::count();
        $totalPending = Document::whereIn('status', [1, 2])->count();
        $totalDisetujui = Document::where('status', 3)->count();
        $totalRevisi = Document::where('status', 4)->count();

        $dokumenTerbaru = Document::with(['type', 'unit', 'creator', 'workflowStatus'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('documentrepository::dashboard.index', compact(
            'totalDokumen',
            'totalPending',
            'totalDisetujui',
            'totalRevisi',
            'dokumenTerbaru',
        ))->with('title', 'Dashboard Dokumen');
    }

    public function review(Request $request, $id)
    {
        if (! Auth::user()->hasPermission($this->moduleId, 'A')) {
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

            $latestVersion = DocumentVersion::where('document_id', $document->id)
                ->orderByDesc('version')
                ->first();

            if ($latestVersion) {
                $statusText = $request->action === 'approve' ? 'Disetujui' : 'Ditolak';
                $note = $request->change_note
                    ? "($statusText) - " . $request->change_note
                    : "Dokumen $statusText oleh Reviewer.";
                $latestVersion->update([
                    'approved_by' => auth()->id(),
                    'approved_date' => now(),
                    'change_note' => $note,
                ]);
            }

            DB::commit();

            try {
                NotifService::sendToUser($document->created_by, [
                    'action' => $request->action === 'approve'
                        ? 'menyetujui dokumen yang Anda unggah'
                        : 'menolak dokumen yang Anda unggah',
                    'item_name' => $document->document_title,
                    'type' => 'Repositori Dokumen',
                    'url' => route('DocumentRepository.index'),
                    'reference_id' => $document->id,
                    'click_action' => 'redirect',
                    'status' => $request->action === 'approve' ? 'online' : 'offline',
                ]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Notif review dokumen gagal: ' . $e->getMessage());
            }

            $pesan = $request->action === 'approve' ? 'Dokumen disetujui!' : 'Dokumen ditolak.';

            return redirect()->route('DocumentRepository.review.index')->with('success', $pesan);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function revise(Request $request, $id)
    {
        if (! Auth::user()->hasPermission($this->moduleId, 'U')) {
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

            return redirect()->route('DocumentRepository.index')
                ->with('success', 'Dokumen direvisi menjadi versi ' . $newVersion);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal revisi: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (! Auth::user()->hasPermission($this->moduleId, 'U')) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'document_title' => 'required|string|max:255',
            'document_type_id' => 'required|string|exists:ref_document_type,id',
            'unit_id' => 'required|string|exists:mst_unit,id',
            'effective_date' => 'required|date',
            'expired_date' => 'nullable|date|after_or_equal:effective_date',
            'sifat_dokumen' => 'required|in:Publik,Private',
            'is_ppid' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $document = Document::findOrFail($id);

            if ($document->created_by !== auth()->id()) {
                abort(403, 'Anda hanya dapat mengedit dokumen milik sendiri.');
            }

            $document->update([
                'document_title' => $request->document_title,
                'document_type_id' => $request->document_type_id,
                'unit_id' => $request->unit_id,
                'effective_date' => $request->effective_date,
                'expired_date' => $request->expired_date,
                'sifat_dokumen' => $request->sifat_dokumen,
                'is_ppid' => $request->has('is_ppid'),
            ]);

            DB::commit();

            return redirect()->route('DocumentRepository.index')
                ->with('success', 'Dokumen berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memperbarui dokumen: ' . $e->getMessage())->withInput();
        }
    }
}