<div>
    <form method="post" action="{{ route('profile.update.post') }}" class="space-y-6" enctype="multipart/form-data">
        @csrf

        {{-- Foto Profil --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Foto Profil
            </label>
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div id="avatarPreview" class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xl font-bold overflow-hidden">
                        @if($user->avatar && file_exists(public_path('storage/avatars/' . $user->avatar)))
                        <img src="{{ asset('storage/avatars/' . $user->avatar) }}" class="w-full h-full object-cover">
                        @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                </div>
                <div class="flex-1">
                    <input id="avatar"
                        name="avatar"
                        type="file"
                        accept="image/jpeg,image/png,image/jpg"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-600 dark:file:text-gray-300">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, PNG (Max 2MB)</p>
                </div>
            </div>
            @error('avatar')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nama Lengkap --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input id="name"
                name="name"
                type="text"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                value="{{ old('name', $user->name) }}"
                required>
            @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Email <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center gap-2">
                <input id="email"
                    name="email"
                    type="email"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                    value="{{ old('email', $user->email) }}"
                    required>

                @if ($user->email_verified_at)
                    <span class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200 whitespace-nowrap">
                        <i class="fas fa-check-circle"></i> Terverifikasi
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-200 whitespace-nowrap">
                        <i class="fas fa-exclamation-triangle"></i> Belum diverifikasi
                    </span>
                @endif
            </div>

            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            @unless ($user->email_verified_at)
                <div class="mt-2 flex items-center gap-2">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-1 rounded-md bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-amber-600 transition">
                            <i class="fas fa-envelope"></i>
                            Kirim Email Verifikasi
                        </button>
                    </form>
                    <span class="text-xs text-gray-500">Tautan verifikasi akan dikirim ke email di atas.</span>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm text-emerald-600">
                        <i class="fas fa-check-circle mr-1"></i>
                        Tautan verifikasi sudah kami kirim ke email Anda. Silakan cek inbox / folder spam.
                    </p>
                @endif
            @endunless

            @if (session('status') === 'email-verified')
                <p class="mt-2 text-sm text-emerald-600">
                    <i class="fas fa-check-circle mr-1"></i> Email berhasil diverifikasi.
                </p>
            @endif

            @if (session('error'))
                <p class="mt-2 text-sm text-red-600">
                    <i class="fas fa-times-circle mr-1"></i> {{ session('error') }}
                </p>
            @endif
        </div>

        {{-- Nomor WhatsApp --}}
        <div>
            <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Nomor WhatsApp
            </label>
            <input id="phone_number"
                name="phone_number"
                type="text"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                value="{{ old('phone_number', $user->phone_number) }}"
                placeholder="081234567890" />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Digunakan untuk notifikasi status prestasi via WhatsApp
            </p>
            @error('phone_number')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tanda Tangan Digital --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Tanda Tangan Digital
            </label>

            <button type="button"
                id="openSignatureModal"
                class="inline-flex items-center gap-2 rounded-lg bg-gray-600 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700 transition">
                <i class="fas fa-pen"></i>
                Buat Tanda Tangan
            </button>

            <input type="hidden" name="signature" id="signatureData" value="{{ old('signature', $user->signature) }}">

            <div id="signaturePreview" class="mt-3">
                @if($user->signature)
                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-xs text-gray-500 mb-2">Tanda tangan tersimpan:</p>
                    <img src="{{ $user->signature }}" alt="Tanda Tangan" style="max-height: 80px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                @else
                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg text-center">
                    <p class="text-xs text-gray-400 italic">Belum ada tanda tangan. Klik "Buat Tanda Tangan"</p>
                </div>
                @endif
            </div>

            @error('signature')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('signature_file')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                <i class="fas fa-save"></i>
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
            <p class="text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-check-circle mr-1"></i> Tersimpan.
            </p>
            @endif
        </div>
    </form>
</div>

{{-- Preview Avatar dengan JavaScript --}}
<script>
    document.getElementById('avatar')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('avatarPreview');
                if (preview) {
                    preview.innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover">`;
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>

{{-- MODAL TANDA TANGAN (sama seperti sebelumnya) --}}
<template x-teleport="#modal-root">
    <div id="signatureModal"
    class="app-modal-overlay fixed inset-0 hidden items-center justify-center bg-black/50 overflow-y-auto backdrop-blur-sm"
    style="display: none;">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4">
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                <i class="fas fa-pen mr-2"></i>
                Tanda Tangan Digital
            </h3>
            <button type="button" id="closeSignatureModal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="p-6">
            <div class="flex gap-2 mb-4">
                <button type="button"
                    id="modalTabUploadBtn"
                    class="flex-1 px-4 py-2 text-sm font-semibold rounded-lg bg-blue-600 text-white transition">
                    <i class="fas fa-upload"></i> Upload File
                </button>
                <button type="button"
                    id="modalTabCanvasBtn"
                    class="flex-1 px-4 py-2 text-sm font-semibold rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                    <i class="fas fa-pen"></i> Buat Canvas
                </button>
            </div>

            <div id="modalUploadPanel" class="space-y-3">
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition">
                    <input type="file"
                        id="modalSignatureFile"
                        accept="image/jpeg,image/png,image/jpg"
                        class="hidden">
                    <button type="button"
                        id="modalTriggerFileUpload"
                        class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700">
                        <i class="fas fa-cloud-upload-alt text-2xl"></i>
                        <span>Klik untuk upload file (JPG/PNG)</span>
                    </button>
                    <p class="text-xs text-gray-500 mt-2">Maksimal 2MB. Disarankan gambar dengan background putih.</p>
                </div>
                <div id="modalUploadPreview" class="hidden p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-xs text-gray-500 mb-2">Preview gambar:</p>
                    <img id="modalUploadPreviewImg" src="" alt="Preview Tanda Tangan" style="max-height: 80px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
            </div>

            <div id="modalCanvasPanel" class="hidden space-y-3">
                <div class="border border-gray-300 rounded-lg p-3 bg-white">
                    <canvas id="modalSignatureCanvas"
                        width="400"
                        height="150"
                        style="width: 100%; height: auto; border-radius: 4px; background: white;"></canvas>
                </div>
                <div class="flex gap-2">
                    <button type="button"
                        id="modalClearCanvasBtn"
                        class="flex-1 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-eraser"></i> Hapus
                    </button>
                    <button type="button"
                        id="modalSaveCanvasBtn"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal elements
        const modal = document.getElementById('signatureModal');
        const openBtn = document.getElementById('openSignatureModal');
        const closeBtn = document.getElementById('closeSignatureModal');

        const tabUpload = document.getElementById('modalTabUploadBtn');
        const tabCanvas = document.getElementById('modalTabCanvasBtn');
        const uploadPanel = document.getElementById('modalUploadPanel');
        const canvasPanel = document.getElementById('modalCanvasPanel');

        const signatureFile = document.getElementById('modalSignatureFile');
        const triggerUpload = document.getElementById('modalTriggerFileUpload');
        const uploadPreview = document.getElementById('modalUploadPreview');
        const uploadPreviewImg = document.getElementById('modalUploadPreviewImg');

        const canvas = document.getElementById('modalSignatureCanvas');
        const clearCanvas = document.getElementById('modalClearCanvasBtn');
        const saveCanvas = document.getElementById('modalSaveCanvasBtn');

        const signatureInput = document.getElementById('signatureData');
        const signaturePreview = document.getElementById('signaturePreview');

        let signaturePad = null;

        openBtn.addEventListener('click', function() {
            modal.style.display = 'flex';
        });

        function closeModal() {
            modal.style.display = 'none';
            if (signaturePad) signaturePad.clear();
        }
        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        tabUpload.addEventListener('click', function() {
            tabUpload.className = 'flex-1 px-4 py-2 text-sm font-semibold rounded-lg bg-blue-600 text-white transition';
            tabCanvas.className = 'flex-1 px-4 py-2 text-sm font-semibold rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition';
            uploadPanel.classList.remove('hidden');
            canvasPanel.classList.add('hidden');
        });

        tabCanvas.addEventListener('click', function() {
            tabCanvas.className = 'flex-1 px-4 py-2 text-sm font-semibold rounded-lg bg-blue-600 text-white transition';
            tabUpload.className = 'flex-1 px-4 py-2 text-sm font-semibold rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition';
            canvasPanel.classList.remove('hidden');
            uploadPanel.classList.add('hidden');

            if (!signaturePad) {
                const container = canvas.parentElement;
                const width = container.clientWidth;
                canvas.width = width;
                canvas.height = 150;
                canvas.style.width = width + 'px';
                canvas.style.height = '150px';
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'white',
                    penColor: '#1e293b',
                    minWidth: 1,
                    maxWidth: 2
                });
            }
        });

        triggerUpload.addEventListener('click', function() {
            signatureFile.click();
        });

        signatureFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg')) {
                alert('Format file harus JPG/PNG!');
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB!');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                const dataURL = event.target.result;
                signatureInput.value = dataURL;

                uploadPreviewImg.src = dataURL;
                uploadPreview.classList.remove('hidden');

                signaturePreview.innerHTML = `
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-xs text-gray-500 mb-2">Tanda tangan tersimpan:</p>
                        <img src="${dataURL}" alt="Tanda Tangan" style="max-height: 80px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                `;

                alert('Tanda tangan berhasil disimpan!');
                closeModal();
            };
            reader.readAsDataURL(file);
        });

        clearCanvas.addEventListener('click', function() {
            if (signaturePad) signaturePad.clear();
        });

        saveCanvas.addEventListener('click', function() {
            if (!signaturePad) return;

            if (signaturePad.isEmpty()) {
                alert('Silakan buat tanda tangan terlebih dahulu!');
                return;
            }

            const dataURL = signaturePad.toDataURL('image/png');
            signatureInput.value = dataURL;

            signaturePreview.innerHTML = `
                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-xs text-gray-500 mb-2">Tanda tangan tersimpan:</p>
                    <img src="${dataURL}" alt="Tanda Tangan" style="max-height: 80px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
            `;

            alert('Tanda tangan berhasil disimpan!');
            closeModal();
        });

        tabUpload.click();
    });
</script>