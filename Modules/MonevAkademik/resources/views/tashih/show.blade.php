@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10" x-data="{ openApprove: false, openRevise: false }">
    <div class="space-y-6">

        {{-- Header & Tombol Kembali --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-file-lines text-blue-500"></i> Detail Pengajuan Soal
                </h2>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Review dan validasi butir soal sebelum ujian.</p>
            </div>
            <a href="{{ route('monevakademik.tashih.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition border border-gray-300 shadow-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        {{-- Pesan Alert (Sukses / Error) --}}
        @if(session('success'))
            <div class="flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 rounded-r-lg">
                <i class="fa-solid fa-check-circle text-green-500 text-xl mr-3"></i>
                <p class="text-sm font-bold text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Informasi Metadata (Header Card) --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Mata Kuliah</span>
                    <strong class="text-base text-gray-900 dark:text-white">{{ $proposal->course->course_name }}</strong>
                    <div class="text-xs text-gray-500 mt-0.5">{{ $proposal->course_id }}</div>
                </div>
                <div>
                    <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Dosen Pengaju</span>
                    <strong class="text-base text-gray-900 dark:text-white">{{ $proposal->creator->name ?? '-' }}</strong>
                </div>
                <div>
                    <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Jenis Ujian</span>
                    <strong class="text-base text-gray-900 dark:text-white">{{ $proposal->exam_type }}</strong>
                </div>
                <div>
                    <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1">Status Pengajuan</span>
                    @php 
                        $statusColor = $proposal->status == 'APPROVED' ? 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400' : 
                                      ($proposal->status == 'REVISED' ? 'bg-orange-100 text-orange-800 border-orange-200 dark:bg-orange-900/30 dark:text-orange-400' : 
                                      'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400'); 
                    @endphp
                    <span class="inline-flex rounded-md px-3 py-1.5 text-xs font-bold border {{ $statusColor }}">
                        <i class="fa-solid {{ $proposal->status == 'APPROVED' ? 'fa-check' : ($proposal->status == 'REVISED' ? 'fa-pen-to-square' : 'fa-clock') }} mr-1.5"></i> 
                        {{ $proposal->status }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Jika ada histori revisi dari Kaprodi, tampilkan alert orange --}}
        @if($proposal->reviews && $proposal->reviews->count() > 0)
        <div class="rounded-xl border border-orange-200 bg-orange-50 p-5 shadow-sm dark:bg-orange-900/20 dark:border-orange-500/30">
            <h4 class="text-sm font-bold text-orange-800 dark:text-orange-400 flex items-center gap-2 mb-2">
                <i class="fa-solid fa-clock-rotate-left"></i> Catatan Revisi Terakhir ({{ $proposal->reviews->first()->created_at->format('d M Y') }})
            </h4>
            <p class="text-sm text-orange-700 dark:text-orange-300 italic">"{{ $proposal->reviews->first()->comment }}"</p>
        </div>
        @endif

        {{-- Daftar Butir Soal --}}
        <div class="mt-8">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-list-ol text-gray-400"></i> Daftar Butir Soal
            </h3>
            
            <div class="space-y-4">
                @foreach($proposal->examQuestions as $eq)
                <div class="rounded-xl border-l-4 border-l-indigo-500 border-y border-r border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-y-gray-700 dark:border-r-gray-700 dark:bg-gray-800">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-4 border-b border-gray-100 pb-4 dark:border-gray-700">
                        <strong class="text-gray-900 dark:text-white text-lg">Soal No. {{ $eq->order_no }}</strong>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700 border border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400">
                                <i class="fa-solid fa-bullseye mr-1.5 opacity-70"></i> CPMK: {{ $eq->question->cpmk_id }}
                            </span>
                            <span class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-700 border border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                <i class="fa-solid fa-weight-hanging mr-1.5 opacity-70"></i> Bobot: {{ $eq->weight }}%
                            </span>
                        </div>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">{{ $eq->question->question_text }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- AKSI DOSEN PEMBUAT (Muncul jika yang buka adalah pembuatnya & status SUBMITTED/REVISED) --}}
        @if(Auth::id() == $proposal->created_by && in_array($proposal->status, ['SUBMITTED', 'REVISED']))
        <div class="mt-8 rounded-xl border border-gray-200 bg-gray-50 p-6 shadow-sm dark:bg-gray-800/50 dark:border-gray-700" x-data="{ openDelete: false }">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">Kelola Pengajuan</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Anda masih dapat mengubah butir soal atau membatalkan pengajuan ini.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('monevakademik.tashih.edit', $proposal->uuid) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                        <i class="fas fa-edit"></i> Edit Pengajuan
                    </a>
                    <button type="button" @click="openDelete = true" class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-red-600 transition">
                        <i class="fas fa-trash-alt"></i> Batalkan
                    </button>
                </div>
            </div>

            {{-- Modal Konfirmasi Batal (Alpine.js) --}}
            <div x-show="openDelete" class="fixed inset-0 z-[999999] flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">
                
                <div @click.away="openDelete = false" class="w-full max-w-md transform rounded-2xl bg-white p-6 shadow-2xl text-center dark:bg-gray-800">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 mb-4 dark:bg-red-900/30">
                        <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600 dark:text-red-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Batalkan Pengajuan?</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Tindakan ini akan menghapus data pengajuan secara permanen dan tidak dapat dikembalikan.</p>
                    
                    <form action="{{ route('monevakademik.tashih.destroy', $proposal->uuid) }}" method="POST" class="flex justify-center gap-3">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="openDelete = false" class="rounded-lg bg-gray-100 px-5 py-2 text-sm font-bold text-gray-700 hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-300">Kembali</button>
                        <button type="submit" class="rounded-lg bg-red-600 px-5 py-2 text-sm font-bold text-white hover:bg-red-700 shadow-md">Ya, Batalkan</button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- AKSI REVIEWER (Hanya muncul jika yang buka bukan pembuatnya & status masih SUBMITTED) --}}
        @if(Auth::id() != $proposal->created_by && $proposal->status == 'SUBMITTED')
        <div class="mt-10 rounded-2xl border-2 border-dashed border-indigo-200 bg-indigo-50/50 p-8 text-center shadow-sm dark:border-indigo-500/30 dark:bg-indigo-900/10">
            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Validasi Kaprodi</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Silakan baca soal dengan saksama. Apakah setuju untuk diujikan atau perlu revisi?</p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <button @click="openApprove = true" class="inline-flex justify-center items-center gap-2 rounded-lg bg-green-600 px-8 py-3 text-sm font-bold text-white shadow-md hover:bg-green-700 transition focus:ring-4 focus:ring-green-200">
                    <i class="fas fa-check-circle text-lg"></i> Setujui & Tanda Tangani
                </button>
                <button @click="openRevise = true" class="inline-flex justify-center items-center gap-2 rounded-lg bg-red-500 px-8 py-3 text-sm font-bold text-white shadow-md hover:bg-red-600 transition focus:ring-4 focus:ring-red-200">
                    <i class="fas fa-times-circle text-lg"></i> Minta Revisi
                </button>
            </div>
        </div>
        @endif

        {{-- MODAL APPROVE & CANVAS (ALPINE.JS) --}}
        <div x-show="openApprove" class="fixed inset-0 z-[999999] flex items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">
            
            <div @click.away="openApprove = false" class="relative w-full max-w-lg transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">
                <div class="mb-5 border-b border-gray-100 pb-4 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-file-signature text-green-500"></i> Persetujuan Pengajuan
                    </h3>
                </div>
                <form action="{{ route('monevakademik.tashih.approve', $proposal->uuid) }}" method="POST" id="approveForm">
                    @csrf
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-6 text-center leading-relaxed">Dengan menekan tombol setuju, Anda memvalidasi bahwa seluruh butir soal ini telah sesuai dengan standar kompetensi matakuliah dan siap untuk dicetak.</p>
                    
                    {{-- Logika Tanda Tangan --}}
                    @if(empty(Auth::user()->signature))
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-red-600 dark:text-red-400 mb-3 text-center">
                                <i class="fa-solid fa-pen-nib mr-1"></i> Gambar Tanda Tangan Digital Anda
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 dark:bg-gray-900 dark:border-gray-600 overflow-hidden relative" style="touch-action: none;">
                                <canvas id="signaturePad" width="400" height="200" class="cursor-crosshair w-full"></canvas>
                                <span class="absolute top-2 left-3 text-[10px] font-bold text-gray-400 uppercase">Area TTD</span>
                            </div>
                            <div class="text-center mt-3">
                                <button type="button" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-red-500 transition" onclick="clearCanvas()">
                                    <i class="fa-solid fa-eraser"></i> Hapus & Ulangi
                                </button>
                            </div>
                            <input type="hidden" name="signature_base64" id="signature_base64" required>
                        </div>
                    @else
                        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-center shadow-sm dark:bg-blue-900/20 dark:border-blue-800/50 mb-6">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 mb-3 dark:bg-blue-800">
                                <i class="fa-solid fa-shield-check text-2xl text-blue-600 dark:text-blue-300"></i>
                            </div>
                            <p class="text-sm font-bold text-blue-800 dark:text-blue-300">Tanda Tangan Tersimpan</p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Tanda tangan digital Anda sudah terenkripsi di sistem dan akan dilampirkan otomatis pada dokumen ini.</p>
                        </div>
                    @endif

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-8 border-t border-gray-100 pt-6 dark:border-gray-700">
                        <button type="button" @click="openApprove = false" class="rounded-lg bg-gray-100 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-200 transition border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Batal</button>
                        <button type="submit" onclick="prepareSignature()" class="rounded-lg bg-green-600 px-8 py-2.5 text-sm font-bold text-white hover:bg-green-700 transition shadow-md">Simpan & Setujui</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL MINTA REVISI (ALPINE.JS) --}}
        <div x-show="openRevise" class="fixed inset-0 z-[999999] flex items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak style="display: none;">
            
            <div @click.away="openRevise = false" class="relative w-full max-w-lg transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">
                <div class="mb-5 border-b border-gray-100 pb-4 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-red-500"></i> Kirim Catatan Revisi
                    </h3>
                </div>
                <form action="{{ route('monevakademik.tashih.revise', $proposal->uuid) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Instruksi Revisi untuk Dosen <span class="text-red-500">*</span></label>
                        <textarea name="comment" rows="5" class="w-full rounded-lg border-[1.5px] border-gray-300 bg-gray-50 px-5 py-3 text-sm font-medium outline-none transition focus:border-red-500 focus:bg-white active:border-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:bg-gray-800" required placeholder="Contoh: Soal nomor 2 terlalu mudah untuk standar CPMK-02. Mohon tingkatkan kesulitan menjadi level analisis (C4)..."></textarea>
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-8 border-t border-gray-100 pt-6 dark:border-gray-700">
                        <button type="button" @click="openRevise = false" class="rounded-lg bg-gray-100 px-6 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-200 transition border border-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Batal</button>
                        <button type="submit" class="rounded-lg bg-red-600 px-8 py-2.5 text-sm font-bold text-white hover:bg-red-700 transition shadow-md flex items-center gap-2"><i class="fas fa-paper-plane"></i> Kirim Revisi</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- SCRIPT CANVAS TANDA TANGAN --}}
@if(empty(Auth::user()->signature))
<script>
    const canvas = document.getElementById('signaturePad');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let isDrawing = false;

        function resizeCanvas() {
            const rect = canvas.parentElement.getBoundingClientRect();
            canvas.width = rect.width;
            canvas.height = 200; 
        }
        window.addEventListener('resize', resizeCanvas);
        
        // Fix untuk Alpine: resize canvas pas modal di-trigger (biar ga penyok)
        document.addEventListener('alpine:initialized', () => {
             setTimeout(resizeCanvas, 100);
        });
        
        // Pantau mutasi Alpine buat jaga-jaga modal baru muncul
        const observer = new MutationObserver(() => {
            if (canvas.offsetWidth > 0) resizeCanvas();
        });
        observer.observe(canvas.parentElement, { attributes: true, childList: true, subtree: true });

        // Event Mouse
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        // Event Touch (HP/Tablet) - Prevent Default biar layar gak ikut scrolling pas ttd
        canvas.addEventListener('touchstart', (e) => { e.preventDefault(); startDrawing(e.touches[0]); }, {passive: false});
        canvas.addEventListener('touchmove', (e) => { e.preventDefault(); draw(e.touches[0]); }, {passive: false});
        canvas.addEventListener('touchend', stopDrawing);

        function startDrawing(e) {
            isDrawing = true;
            const rect = canvas.getBoundingClientRect();
            ctx.beginPath();
            ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
        }

        function draw(e) {
            if (!isDrawing) return;
            const rect = canvas.getBoundingClientRect();
            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#0f172a'; // Warna tinta hitam elegan (slate-900)
            ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
        }

        function stopDrawing() { isDrawing = false; ctx.beginPath(); }
        
        function clearCanvas() { 
            ctx.clearRect(0, 0, canvas.width, canvas.height); 
            document.getElementById('signature_base64').value = ""; // reset value
        }
        
        function prepareSignature() { 
            // Ubah gambar coretan ke base64 string buat disimpen ke database
            const dataUrl = canvas.toDataURL('image/png');
            // Sedikit validasi, kalau kosong banget jangan diset (tergantung kebutuhan)
            document.getElementById('signature_base64').value = dataUrl; 
        }
    }
</script>
@endif
@endsection