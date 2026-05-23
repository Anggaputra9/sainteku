@extends('layouts.app')

@section('content')
    {{--
        Halaman join ujian. Mahasiswa bisa:
        1. Ketik manual kode 6 digit, atau
        2. Klik "Scan QR" untuk buka kamera (HTML5 getUserMedia + jsQR).
        Kamera akan terus scanning sampai dapat string yang valid; kalau
        QR berisi URL kita arahkan ke URL itu, kalau berisi kode polos
        kita auto-fill ke input.
    --}}
    <div class="max-w-xl mx-auto py-8" x-data="joinExamApp()" x-init="init()" x-cloak>
        <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
            <div class="text-center mb-6">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30">
                    <i class="fa-solid fa-door-open text-2xl"></i>
                </div>
                <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">Masuk Ruang Ujian</h2>
                <p class="text-sm text-gray-500 mt-1">Scan QR atau ketik kode dari dosen Anda.</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 border-l-4 border-red-500 bg-red-50 p-4 rounded-r-lg">
                    <div class="text-sm font-bold text-red-700">
                        @foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach
                    </div>
                </div>
            @endif

            <template x-if="scanError">
                <div class="mb-4 border-l-4 border-amber-500 bg-amber-50 p-4 rounded-r-lg text-sm font-bold text-amber-800" x-text="scanError"></div>
            </template>

            {{-- Tombol Scan --}}
            <button type="button" @click="openScanner()"
                class="w-full mb-4 inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-bold text-white hover:bg-emerald-700">
                <i class="fa-solid fa-qrcode"></i> Scan QR Ruang Ujian
            </button>

            <div class="relative mb-4">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-300 dark:border-gray-700"></div></div>
                <div class="relative flex justify-center"><span class="bg-white dark:bg-[#1e293b] px-3 text-xs text-gray-500 uppercase tracking-widest">atau</span></div>
            </div>

            <form @submit.prevent="confirmJoin()" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-2">Kode Ruang Ujian</label>
                    <input name="room_code" required maxlength="20" autofocus
                        x-ref="codeInput"
                        x-model="roomCode"
                        value="{{ old('room_code') }}"
                        placeholder="Contoh: AB12CD"
                        class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 text-center text-2xl tracking-[0.3em] font-mono font-bold uppercase dark:bg-[#0f172a] dark:border-gray-600 dark:text-white"
                        oninput="this.value = this.value.toUpperCase()">
                </div>
                <button type="submit" class="w-full rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white hover:bg-indigo-700">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk Ruang Ujian
                </button>
            </form>

            <div class="mt-6 rounded-xl bg-amber-50 border border-amber-200 p-4 text-xs text-amber-800 dark:bg-amber-900/20 dark:border-amber-900/40 dark:text-amber-200">
                <p class="font-bold mb-1"><i class="fa-solid fa-triangle-exclamation"></i> Sebelum mulai, perhatikan:</p>
                <ul class="list-disc pl-5 space-y-0.5">
                    <li>Pastikan koneksi internet stabil.</li>
                    <li>Tutup tab/aplikasi lain yang tidak diperlukan.</li>
                    <li>Tab switch / pindah window bisa membuat ujian otomatis tersubmit.</li>
                    <li>Jawaban tersimpan otomatis saat Anda mengetik.</li>
                </ul>
            </div>
        </div>

        {{-- ============== MODAL KONFIRMASI MULAI UJIAN ============== --}}
        <div x-show="confirmOpen"
            class="fixed inset-0 z-[999991] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/50"
            x-transition x-cloak>
            <div @click.away="!confirmSubmitting && (confirmOpen = false)"
                class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl ring-1 ring-gray-900/5 dark:bg-[#0f172a] dark:ring-gray-700 overflow-hidden">
                <div class="border-b border-gray-200 bg-white px-6 py-4 dark:bg-[#1e293b] dark:border-gray-700">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-amber-500"></i> Konfirmasi Mulai Ujian
                    </h3>
                </div>
                <div class="p-6 space-y-4 bg-slate-50 dark:bg-[#0f172a]">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Anda akan memulai ujian dengan kode <strong class="font-mono text-indigo-600 dark:text-indigo-400" x-text="roomCode"></strong>.
                    </p>
                    <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-xs text-amber-800 dark:bg-amber-900/20 dark:border-amber-900/40 dark:text-amber-200">
                        <p class="font-bold mb-2"><i class="fa-solid fa-triangle-exclamation"></i> Perhatian:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Waktu ujian akan dimulai setelah Anda klik "Mulai Ujian"</li>
                            <li>Pastikan koneksi internet stabil</li>
                            <li>Jangan pindah tab atau window selama ujian</li>
                            <li>Pelanggaran dapat menyebabkan ujian otomatis tersubmit</li>
                        </ul>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="confirmOpen = false" :disabled="confirmSubmitting"
                            class="rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-300 disabled:opacity-60 dark:bg-gray-700 dark:text-gray-200">
                            Batal
                        </button>
                        <button type="button" @click="submitJoin()" :disabled="confirmSubmitting"
                            class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-indigo-700 disabled:opacity-60">
                            <span x-text="confirmSubmitting ? 'Memproses…' : 'Mulai Ujian'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============== MODAL SCANNER ============== --}}
        <div x-show="scannerOpen"
            class="fixed inset-0 z-[999990] flex items-center justify-center p-3 sm:p-6 backdrop-blur-sm bg-gray-900/70"
            x-transition x-cloak>
            <div class="relative w-full max-w-md rounded-2xl bg-[#0f172a] shadow-2xl flex flex-col overflow-hidden">
                <div class="flex items-center justify-between border-b border-gray-700 px-5 py-3 text-white">
                    <h3 class="text-base font-bold flex items-center gap-2"><i class="fa-solid fa-qrcode text-emerald-400"></i> Scan QR Ruang Ujian</h3>
                    <button type="button" @click="closeScanner()" class="text-gray-300 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>
                <div class="relative aspect-square bg-black">
                    <video x-ref="video" playsinline class="absolute inset-0 w-full h-full object-cover"></video>
                    <canvas x-ref="canvas" class="hidden"></canvas>
                    {{-- Frame guide --}}
                    <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                        <div class="w-3/4 aspect-square rounded-2xl border-4 border-emerald-400/80 shadow-[0_0_0_9999px_rgba(0,0,0,0.35)]"></div>
                    </div>
                </div>
                <div class="px-5 py-3 text-xs text-gray-300 text-center" x-text="scannerStatus"></div>
            </div>
        </div>
    </div>

    {{-- jsQR via CDN: pure JS QR decoder, ringan ~50KB --}}
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

    <script>
        function joinExamApp() {
            return {
                scannerOpen: false,
                scannerStatus: 'Mengarahkan kamera ke QR...',
                scanError: '',
                stream: null,
                rafId: null,
                confirmOpen: false,
                confirmSubmitting: false,
                roomCode: '{{ old('room_code') }}',

                init() {},

                confirmJoin() {
                    if (!this.roomCode || this.roomCode.trim() === '') {
                        this.scanError = 'Kode ruang ujian tidak boleh kosong.';
                        return;
                    }
                    this.confirmOpen = true;
                },

                submitJoin() {
                    this.confirmSubmitting = true;
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('ujian.attempt.join.submit') }}';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    const codeInput = document.createElement('input');
                    codeInput.type = 'hidden';
                    codeInput.name = 'room_code';
                    codeInput.value = this.roomCode;
                    form.appendChild(codeInput);

                    document.body.appendChild(form);
                    form.submit();
                },

                async openScanner() {
                    this.scanError = '';
                    if (!navigator.mediaDevices?.getUserMedia) {
                        this.scanError = 'Browser ini tidak mendukung akses kamera.';
                        return;
                    }
                    this.scannerOpen = true;
                    this.scannerStatus = 'Meminta izin kamera...';

                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: 'environment' }, // pakai kamera belakang kalau ada
                            audio: false,
                        });
                        const video = this.$refs.video;
                        video.srcObject = this.stream;
                        await video.play();
                        this.scannerStatus = 'Arahkan kamera ke QR ruang ujian.';
                        this.tick();
                    } catch (e) {
                        this.scanError = 'Tidak bisa akses kamera: ' + e.message;
                        this.closeScanner();
                    }
                },

                tick() {
                    const video = this.$refs.video;
                    const canvas = this.$refs.canvas;
                    if (!video || video.readyState !== video.HAVE_ENOUGH_DATA) {
                        this.rafId = requestAnimationFrame(() => this.tick());
                        return;
                    }

                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    const ctx = canvas.getContext('2d', { willReadFrequently: true });
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    try {
                        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        const code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'dontInvert' });
                        if (code && code.data) {
                            this.handleResult(code.data);
                            return;
                        }
                    } catch (e) {
                        // abaikan, lanjut frame berikutnya
                    }
                    this.rafId = requestAnimationFrame(() => this.tick());
                },

                handleResult(text) {
                    this.scannerStatus = 'QR ditemukan, memproses...';
                    this.closeScanner();

                    // Kalau hasilnya URL valid milik aplikasi (mengandung /ujian/attempt/scan),
                    // langsung redirect — controller akan validate kode-nya.
                    if (/^https?:\/\//i.test(text) && text.indexOf('/ujian/attempt/scan') !== -1) {
                        window.location.href = text;
                        return;
                    }

                    // Kalau cuma 6-12 char alfanumerik, anggap room_code → isi input + tampilkan konfirmasi
                    const codeOnly = text.trim().toUpperCase();
                    if (/^[A-Z0-9]{4,12}$/.test(codeOnly)) {
                        this.roomCode = codeOnly;
                        this.confirmOpen = true;
                        return;
                    }

                    this.scanError = 'QR tidak dikenali: ' + text;
                },

                closeScanner() {
                    if (this.rafId) cancelAnimationFrame(this.rafId);
                    this.rafId = null;
                    if (this.stream) {
                        this.stream.getTracks().forEach(t => t.stop());
                        this.stream = null;
                    }
                    this.scannerOpen = false;
                },
            };
        }
    </script>
@endsection
