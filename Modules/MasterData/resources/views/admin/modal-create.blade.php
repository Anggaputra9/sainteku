<div x-show="openCreate" 
     class="fixed inset-0 z-[999999] flex items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-md"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     x-cloak>
    
    <div @click.away="openCreate = false" 
         class="relative w-full max-w-4xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 transition-all">
        
        <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah User Baru</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Daftarkan pengguna baru ke sistem <span class="font-semibold text-blue-600 dark:text-blue-400">Sainteku</span></p>
            </div>
            <button @click="openCreate = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('masterdata.admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
                        placeholder="Masukkan nama lengkap" value="{{ old('name') }}">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
                        placeholder="user@example.com" value="{{ old('email') }}">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">NIM / NIP / NIK</label>
                    <input type="text" name="identity_id" 
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
                        placeholder="123456789" value="{{ old('identity_id') }}">
                </div>

                <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50 md:col-span-2">
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-widest text-gray-400 italic">Kata Sandi Akun</h4>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
                                placeholder="••••••••">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required
                                class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tipe Pengguna</label>
                    <select name="user_type" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="dosen">Dosen</option>
                        <option value="staff">Staff</option>
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="Eksternal">Eksternal</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Unit ID</label>
                    <input type="text" name="unit_id" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
                        placeholder="Contoh: U001" value="{{ old('unit_id') }}">
                </div>

                <div class="flex items-end pb-2">
                    <label class="relative inline-flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" class="peer sr-only" checked>
                        <div class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:bg-gray-700"></div>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Aktifkan Akun Langsung</span>
                    </label>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                <button type="button" @click="openCreate = false" 
                        class="inline-flex justify-center rounded-lg px-6 py-2.5 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50 dark:text-gray-300 dark:ring-gray-600 dark:hover:bg-gray-700 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="inline-flex justify-center items-center gap-2 rounded-lg bg-emerald-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-emerald-700 transition">
                    <i class="fas fa-save"></i>
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>