@extends('layouts.app')

@section('content')
    <div class="mx-auto" x-data="{ 
                    // Logic Sakti & Ketat: Biar modal nggak ketuker pas ada error validasi!
                    editProfileOpen: {{ $errors->hasAny(['name', 'email', 'phone_number', 'birth_date', 'gender', 'avatar', 'bio', 'address']) ? 'true' : 'false' }}, 
                    changePasswordOpen: {{ $errors->updatePassword->any() || $errors->hasAny(['current_password', 'password']) ? 'true' : 'false' }},

                    signatureOpen: false,
                    signatureData: '{{ $user->signature ?? '' }}',
                    sigMode: 'draw',
                    sigIsDrawing: false,
                    sigHasDrawn: false,
                    sigCtx: null,
                    alert: { type: '', message: '' },
                    flash(type, message) {
                        this.alert = { type, message };
                        setTimeout(() => { this.alert.message = ''; }, 4000);
                    },
                    initSigCanvas() {
                        const canvas = this.$refs.sigCanvas;
                        if (!canvas) return;
                        this.sigCtx = canvas.getContext('2d');
                        this.sigCtx.clearRect(0, 0, canvas.width, canvas.height);
                        this.sigCtx.lineWidth = 3;
                        this.sigCtx.lineCap = 'round';
                        this.sigCtx.lineJoin = 'round';
                        this.sigCtx.strokeStyle = '#000000';
                        this.sigHasDrawn = false;
                    },
                    startSigDraw(e) {
                        this.sigIsDrawing = true;
                        this.sigDraw(e);
                    },
                    sigDraw(e) {
                        if (!this.sigIsDrawing || !this.sigCtx) return;
                        const canvas = this.$refs.sigCanvas;
                        const rect = canvas.getBoundingClientRect();
                        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                        const x = clientX - rect.left;
                        const y = clientY - rect.top;
                        this.sigCtx.lineTo(x, y);
                        this.sigCtx.stroke();
                        this.sigCtx.beginPath();
                        this.sigCtx.moveTo(x, y);
                        this.sigHasDrawn = true;
                        e.preventDefault();
                    },
                    stopSigDraw() {
                        this.sigIsDrawing = false;
                        if (this.sigCtx) this.sigCtx.beginPath();
                    },
                    clearSigPad() {
                        const canvas = this.$refs.sigCanvas;
                        if (!canvas || !this.sigCtx) return;
                        this.sigCtx.clearRect(0, 0, canvas.width, canvas.height);
                        this.sigHasDrawn = false;
                        this.signatureData = '';
                    },
                    closeSignatureModal() {
                        this.signatureOpen = false;
                    },
                    submitSignatureForm(form) {
                        if (this.sigMode === 'draw' && !this.sigHasDrawn) {
                            this.signatureOpen = false;
                            this.flash('warning', 'Gambar tanda tangan di kanvas terlebih dahulu.');
                            return;
                        }
                        if (this.sigMode === 'upload' && !this.$refs.profileSignatureFile?.files?.length) {
                            this.flash('warning', 'Pilih file gambar tanda tangan terlebih dahulu.');
                            return;
                        }
                        submitProfileSignature(form, this.sigMode);
                    }
                 }"
                 x-init="
                    $watch('signatureOpen', value => {
                        if (value && sigMode === 'draw') {
                            setTimeout(() => initSigCanvas(), 100);
                        }
                    });
                    if (window.location.hash === '#tanda-tangan') {
                        $nextTick(() => {
                            document.getElementById('tanda-tangan')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            signatureOpen = true;
                        });
                    }
                 "
                 @profile-flash.window="flash($event.detail.type, $event.detail.message)"
                 @resize.window="if (signatureOpen && sigMode === 'draw') initSigCanvas()" x-cloak>

        {{-- BREADCRUMB --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Profile Settings</h2>
                <nav>
                    <ol class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                        <li>Dashboard /</li>
                        <li class="text-blue-600 dark:text-blue-400">Profile</li>
                    </ol>
                </nav>
            </div>

            <button @click="changePasswordOpen = true"
                class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-red-600 transition">
                <i class="fas fa-key"></i> Ubah Password
            </button>
        </div>

        {{-- FLASH NOTIF --}}
        <template x-if="alert.message">
            <div class="mb-6 flex items-center gap-3 p-4 border-l-4 rounded-r-lg shadow-sm"
                :class="{
                    'border-red-500 bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400': alert.type === 'error',
                    'border-amber-500 bg-amber-50 text-amber-800 dark:bg-amber-900/20 dark:text-amber-400': alert.type === 'warning',
                    'border-green-500 bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400': alert.type === 'success'
                }">
                <i class="fa-solid text-lg shrink-0"
                    :class="{
                        'fa-circle-xmark': alert.type === 'error',
                        'fa-triangle-exclamation': alert.type === 'warning',
                        'fa-check-circle': alert.type === 'success'
                    }"></i>
                <span class="text-sm font-bold" x-text="alert.message"></span>
            </div>
        </template>

        {{-- ALERT SUCCESS --}}
        @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div
                class="mb-6 flex items-center w-full border-l-4 border-green-500 bg-green-50 p-4 shadow-sm dark:bg-gray-800 dark:border-green-400 rounded-r-lg">
                <i class="fa-solid fa-check-circle text-green-500 text-xl mr-3"></i>
                <p class="text-sm font-bold text-green-700 dark:text-green-400">Pembaruan berhasil disimpan!</p>
            </div>
        @endif

        @if (session('error'))
            <div
                class="mb-6 flex items-center w-full border-l-4 border-red-500 bg-red-50 p-4 shadow-sm dark:bg-gray-800 dark:border-red-400 rounded-r-lg">
                <i class="fa-solid fa-circle-xmark text-red-500 text-xl mr-3"></i>
                <p class="text-sm font-bold text-red-700 dark:text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        {{-- CARD 1: INFORMASI UTAMA --}}
        <div
            class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="h-32 bg-gray-200 dark:bg-gray-900/50 relative border-b border-gray-200 dark:border-gray-700">
                <button @click="editProfileOpen = true"
                    class="absolute top-4 right-4 inline-flex items-center gap-2 rounded-lg bg-white/50 px-3 py-1.5 text-sm font-medium text-gray-800 backdrop-blur-sm hover:bg-white/80 dark:bg-gray-800/50 dark:text-gray-200 dark:hover:bg-gray-700/80 transition ring-1 ring-gray-300 dark:ring-gray-600">
                    <i class="fas fa-edit"></i> Edit Profil
                </button>
            </div>

            <div class="px-6 pb-8 relative">
                <div class="flex flex-col sm:flex-row sm:items-end gap-5 -mt-12 mb-6">
                    <div
                        class="relative h-24 w-24 rounded-full border-4 border-white dark:border-gray-800 shadow-md bg-white overflow-hidden shrink-0">
                        <img src="{{ $user->avatar ? asset('storage/avatars/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}"
                            alt="Avatar" class="h-full w-full object-cover">
                    </div>
                    <div class="pb-2">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $user->user_type ?? 'User' }} | {{ $user->unit_id ?? 'Unit Belum Diatur' }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">NIM / NIP /
                            NIK</span>
                        <span
                            class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $user->identity_id ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Email
                            Address</span>
                        <span class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Phone
                            Number</span>
                        <span
                            class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $user->phone_number ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Gender</span>
                        <span class="text-base font-medium text-gray-900 dark:text-gray-100">
                            @if($user->gender == 'L') Laki-laki @elseif($user->gender == 'P') Perempuan @else - @endif
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Birth
                            Date</span>
                        <span class="text-base font-medium text-gray-900 dark:text-gray-100">
                            {{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d F Y') : '-' }}
                        </span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Bio
                            Singkat</span>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed max-w-3xl">
                            {{ $user->bio ?? 'Belum ada bio singkat.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 2: ALAMAT & TANDA TANGAN --}}
        <div id="tanda-tangan"
            class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 relative scroll-mt-24">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Informasi Tambahan & Tanda Tangan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                <div class="md:col-span-2">
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Alamat
                        Lengkap</span>
                    <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                        {{ $user->address ?? 'Alamat belum diatur.' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Tanda Tangan
                        Digital</span>
                    @if($user->signature)
                        <div
                            class="rounded-lg border border-gray-200 p-3 inline-block bg-white dark:bg-gray-200 shadow-sm mb-3">
                            <img src="{{ $user->signature }}" alt="Tanda Tangan" class="h-16 object-contain">
                        </div>
                        <div>
                            <button @click="signatureOpen = true"
                                class="text-sm font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 transition">
                                <i class="fas fa-edit mr-1"></i> Perbarui Tanda Tangan
                            </button>
                        </div>
                    @else
                        <span class="text-sm italic text-gray-500 block mb-3">Belum ada tanda tangan yang tersimpan.</span>
                        <button @click="signatureOpen = true"
                            class="inline-flex items-center gap-2 rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-teal-700 transition">
                            <i class="fas fa-pen-nib"></i> Buat Tanda Tangan
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- ======================================================= --}}
        {{-- MODAL 1: EDIT PROFIL UMUM (PAKAI X-TELEPORT) --}}
        {{-- ======================================================= --}}
        <template x-teleport="#modal-root">
            <div x-show="editProfileOpen"
                class="app-modal-overlay fixed inset-0 flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak>

                <div @click.away="editProfileOpen = false"
                    class="relative w-full max-w-4xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">

                    <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Informasi Profil</h3>
                        <button @click="editProfileOpen = false" class="text-gray-400 hover:text-red-500 transition"><i
                                class="fas fa-times text-xl"></i></button>
                    </div>

                    <form action="{{ route('profile.update.post') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Nama
                                    Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full rounded-lg border-0 bg-gray-50 py-2.5 px-4 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full rounded-lg border-0 bg-gray-50 py-2.5 px-4 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Nomor
                                    HP/WhatsApp</label>
                                <input type="text" name="phone_number"
                                    value="{{ old('phone_number', $user->phone_number) }}"
                                    class="w-full rounded-lg border-0 bg-gray-50 py-2.5 px-4 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tanggal
                                    Lahir</label>
                                <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}"
                                    class="w-full rounded-lg border-0 bg-gray-50 py-2.5 px-4 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Jenis
                                    Kelamin</label>
                                <select name="gender"
                                    class="w-full rounded-lg border-0 bg-gray-50 py-2.5 px-4 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('gender', $user->gender) == 'L' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="P" {{ old('gender', $user->gender) == 'P' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Foto Profil
                                    (Max
                                    2MB)</label>
                                <input type="file" name="avatar" accept="image/*"
                                    class="w-full rounded-lg border-0 bg-gray-50 py-2 px-3 text-sm ring-1 ring-gray-300 file:mr-4 file:rounded-md file:border-0 file:bg-blue-600 file:py-1 file:px-4 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700 dark:bg-gray-900 dark:text-gray-300 dark:ring-gray-600">
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Bio
                                    Singkat</label>
                                <textarea name="bio" rows="2"
                                    class="w-full rounded-lg border-0 bg-gray-50 py-2.5 px-4 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">{{ old('bio', $user->bio) }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Alamat
                                    Lengkap</label>
                                <textarea name="address" rows="2"
                                    class="w-full rounded-lg border-0 bg-gray-50 py-2.5 px-4 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700 mt-6">
                            <button type="button" @click="editProfileOpen = false"
                                class="mr-3 rounded-lg px-5 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition">Batal</button>
                            <button type="submit"
                                class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-bold text-white shadow-md hover:bg-blue-700 transition">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- ======================================================= --}}
        {{-- MODAL 2: TANDA TANGAN KHUSUS (PAKAI X-TELEPORT) --}}
        {{-- ======================================================= --}}
        <template x-teleport="#modal-root">
            <div x-show="signatureOpen"
                class="app-modal-overlay fixed inset-0 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
                x-transition x-cloak>

                <div @click.away="closeSignatureModal()"
                    class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-800">

                    <div class="mb-4 border-b border-gray-100 pb-3 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Perbarui Tanda Tangan</h3>
                    </div>

                    <form action="{{ route('profile.signature.store') }}" method="POST" enctype="multipart/form-data"
                        @submit.prevent="submitSignatureForm($event.target)">
                        @csrf

                        <div class="mb-4 flex gap-2 rounded-lg bg-gray-100 p-1 dark:bg-gray-900">
                            <button type="button" @click="sigMode = 'draw'; setTimeout(() => initSigCanvas(), 50)"
                                :class="sigMode === 'draw' ? 'bg-white shadow-sm dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                                class="w-1/2 rounded-md py-1.5 text-sm font-semibold transition">Gambar Langsung</button>
                            <button type="button" @click="sigMode = 'upload'"
                                :class="sigMode === 'upload' ? 'bg-white shadow-sm dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                                class="w-1/2 rounded-md py-1.5 text-sm font-semibold transition">Upload File PNG</button>
                        </div>

                        <div x-show="sigMode === 'draw'">
                            <div
                                class="mb-3 rounded-xl border-2 border-dashed border-gray-300 bg-white overflow-hidden dark:bg-gray-200">
                                <canvas x-ref="sigCanvas" width="450" height="200"
                                    class="w-full cursor-crosshair touch-none" @mousedown="startSigDraw" @mousemove="sigDraw"
                                    @mouseup="stopSigDraw" @mouseleave="stopSigDraw" @touchstart="startSigDraw" @touchmove="sigDraw"
                                    @touchend="stopSigDraw"></canvas>
                            </div>
                            <button type="button" @click="clearSigPad()"
                                class="text-sm font-semibold text-red-500 hover:text-red-600 mb-4"><i
                                    class="fas fa-eraser"></i>
                                Bersihkan Kanvas</button>
                        </div>

                        <div x-show="sigMode === 'upload'" class="py-2" x-cloak>
                            <label for="profileSignatureFile"
                                class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                                Pilih File TTD <span class="text-red-500">*</span>
                                <span class="font-normal text-gray-500 dark:text-gray-400">(PNG atau JPEG)</span>
                            </label>
                            <input type="file" name="signature_file" id="profileSignatureFile" x-ref="profileSignatureFile"
                                accept="image/png,image/jpeg"
                                :required="sigMode === 'upload'"
                                class="w-full rounded-lg border-0 bg-gray-50 py-2 px-3 text-sm ring-1 ring-gray-300 file:mr-4 file:rounded-md file:border-0 file:bg-blue-600 file:py-1 file:px-4 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700 dark:bg-gray-900 dark:text-gray-300 dark:ring-gray-600">
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
                            <button type="button" @click="closeSignatureModal()"
                                class="mr-3 rounded-lg px-5 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition">Batal</button>
                            <button type="submit"
                                class="rounded-lg bg-teal-600 px-6 py-2 text-sm font-bold text-white shadow-md hover:bg-teal-700 transition">Simpan
                                Tanda Tangan</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- ======================================================= --}}
        {{-- MODAL 3: UBAH PASSWORD (PAKAI X-TELEPORT) --}}
        {{-- ======================================================= --}}
        <template x-teleport="#modal-root">
            <div x-show="changePasswordOpen"
                class="app-modal-overlay fixed inset-0 flex items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-300" x-transition:opacity x-cloak>

                {{-- State showPassword untuk fitur intip password --}}
                <div @click.away="changePasswordOpen = false" x-data="{ showPassword: false }"
                    class="relative w-full max-w-lg transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">

                    <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white"><i
                                class="fas fa-shield-alt text-red-500 mr-2"></i> Keamanan Akun</h3>
                        <button @click="changePasswordOpen = false" class="text-gray-400 hover:text-red-500 transition"><i
                                class="fas fa-times text-xl"></i></button>
                    </div>

                    <form action="{{ route('profile.password.update.post') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Password Saat
                                Ini</label>
                            <input :type="showPassword ? 'text' : 'password'" name="current_password" required
                                class="w-full rounded-lg border-0 bg-gray-50 py-2.5 px-4 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-red-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                            @error('current_password', 'updatePassword') <span
                            class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Password
                                Baru</label>
                            <input :type="showPassword ? 'text' : 'password'" name="password" required
                                class="w-full rounded-lg border-0 bg-gray-50 py-2.5 px-4 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-red-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                            @error('password', 'updatePassword') <span
                            class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Konfirmasi
                                Password
                                Baru</label>
                            <input :type="showPassword ? 'text' : 'password'" name="password_confirmation" required
                                class="w-full rounded-lg border-0 bg-gray-50 py-2.5 px-4 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-red-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        </div>

                        {{-- Checkbox Lihat Password --}}
                        <div class="flex items-center mt-2">
                            <input type="checkbox" id="show-password" x-model="showPassword"
                                class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-900 dark:ring-offset-gray-800">
                            <label for="show-password"
                                class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                Tampilkan Password
                            </label>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700 mt-6">
                            <button type="button" @click="changePasswordOpen = false"
                                class="mr-3 rounded-lg px-5 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition">Batal</button>
                            <button type="submit"
                                class="rounded-lg bg-red-600 px-6 py-2 text-sm font-bold text-white shadow-md hover:bg-red-700 transition">Ubah
                                Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

    </div>

    @push('scripts')
    <script>
        function profileFlash(type, message) {
            window.dispatchEvent(new CustomEvent('profile-flash', {
                detail: { type, message },
            }));
        }

        function isCanvasBlank(canvas) {
            const ctx = canvas.getContext('2d');
            const { data } = ctx.getImageData(0, 0, canvas.width, canvas.height);
            for (let i = 3; i < data.length; i += 4) {
                if (data[i] > 0) {
                    return false;
                }
            }
            return true;
        }

        async function submitProfileSignature(form, sigMode) {
            const fileInput = form.querySelector('#profileSignatureFile');

            if (sigMode === 'draw') {
                const canvas = form.querySelector('canvas');
                if (!canvas) return;

                if (isCanvasBlank(canvas)) {
                    profileFlash('warning', 'Gambar tanda tangan di kanvas terlebih dahulu.');
                    return;
                }

                const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
                if (!blob) {
                    profileFlash('warning', 'Gambar tanda tangan di kanvas terlebih dahulu.');
                    return;
                }
                const dt = new DataTransfer();
                dt.items.add(new File([blob], 'signature.png', { type: 'image/png' }));
                fileInput.files = dt.files;
            }

            if (sigMode === 'upload' && (!fileInput || !fileInput.files.length)) {
                profileFlash('warning', 'Pilih file gambar tanda tangan terlebih dahulu.');
                return;
            }

            form.submit();
        }
    </script>
    @endpush
@endsection