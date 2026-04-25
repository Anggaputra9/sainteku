<section class="section" id="repository">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-6 mb-3 ff-secondary fw-semibold">Repository Dokumen</h1>
            <p class="fs-5 text-muted col-lg-8 mx-auto">Koleksi dokumen, pedoman, dan karya ilmiah Fakultas Sains dan Teknologi UIN Saizu Purwokerto</p>
        </div>

        {{-- Search Form --}}
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto">
                <form action="#repository" method="GET" class="bg-white p-3 rounded-4 shadow-sm">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <input type="search" name="search"
                                class="form-control form-control-lg"
                                placeholder="Cari dokumen..."
                                value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100 h-100">
                                <i class="ri-search-2-line align-bottom me-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Grid Dokumen --}}
        <div class="row g-4">
            @forelse($documents as $doc)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 p-4">
                    {{-- Header Card --}}
                    <div class="d-flex align-items-start mb-3">
                        <div class="avatar-sm me-3 flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle rounded-3">
                                <i class="ri-file-text-line fs-3 text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <span class="badge bg-success-subtle text-success mb-1" style="font-size: 0.7rem;">
                                <i class="ri-check-line me-1"></i>Tersedia
                            </span>
                            <h5 class="fw-bold mb-0 fs-16">{{ Str::limit($doc->document_title, 50) }}</h5>
                        </div>
                    </div>

                    {{-- Info Dokumen --}}
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">
                            <i class="ri-hashtag me-1"></i> {{ $doc->document_id }}
                        </small>
                        <small class="text-muted d-block mb-1">
                            <i class="ri-price-tag-3-line me-1"></i> {{ $doc->type->description ?? 'Umum' }}
                        </small>
                        <small class="text-muted d-block">
                            <i class="ri-building-2-line me-1"></i> {{ $doc->unit->unit_name ?? 'Fakultas' }}
                        </small>
                    </div>

                    {{-- Divider --}}
                    <hr class="my-3">

                    {{-- Footer Card --}}
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <small class="text-muted">
                            <i class="ri-user-line me-1"></i> {{ $doc->creator->name ?? 'Admin' }}
                        </small>
                        <small class="text-muted">
                            <i class="ri-calendar-line me-1"></i> {{ $doc->created_at->format('d M Y') }}
                        </small>
                    </div>

                    {{-- Tombol Download --}}
                    <a href="{{ route('DocumentRepository.download', $doc->id) }}"
                        class="btn btn-primary btn-sm w-100 mt-3" target="_blank">
                        <i class="ri-download-2-line me-1"></i> Download Dokumen
                    </a>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="avatar-lg mx-auto mb-4">
                        <div class="avatar-title bg-light rounded-circle">
                            <i class="ri-folder-open-line fs-1 text-muted"></i>
                        </div>
                    </div>
                    <h5 class="text-muted mb-2">Belum Ada Dokumen</h5>
                    <p class="text-muted">Dokumen akan muncul setelah disetujui oleh reviewer.</p>
                </div>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($documents->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $documents->links() }}
        </div>
        @endif
    </div>
</section>