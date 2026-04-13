<div x-data="{
    openEdit: false,
    url: '',
    userData: { name: '', email: '', identity: '', type: '', unit: '', active: true, roles: [] }
}"
    @open-edit-modal.window="
        openEdit = true; 
        url = $event.detail.url;
        userData = $event.detail;
    "
    x-show="openEdit"
    class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto bg-black/50 p-4 py-10 backdrop-blur-md"
    x-cloak>

    <div @click.away="openEdit = false"
        class="relative my-auto w-full max-w-4xl transform rounded-2xl bg-white p-8 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">

        <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-700">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Edit User</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Memperbarui data pengguna: <span
                        class="font-semibold text-blue-600" x-text="userData.name"></span></p>
            </div>
            <button @click="openEdit = false"
                class="inline-flex items-center gap-2 rounded-lg bg-amber-400 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-amber-500 transition focus:ring-4 focus:ring-amber-200 dark:focus:ring-amber-900">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
        </div>

        <form :action="url" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="userData.name" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Email Institusi <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" x-model="userData.email" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">NIM / NIP /
                        NIK</label>
                    <input type="text" name="identity_id" x-model="userData.identity"
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                </div>

                <div class="rounded-xl bg-gray-50 p-5 dark:bg-gray-900/50 md:col-span-2">
                    <h4 class="mb-2 text-xs font-bold uppercase tracking-widest text-gray-400 italic">Keamanan (Isi jika
                        ingin ubah)</h4>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <input type="password" name="password" placeholder="Password Baru"
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-400 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:ring-gray-600">
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-400 text-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:ring-gray-600">
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tipe Pengguna</label>
                    <select name="user_type" x-model="userData.type" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        <option value="">-- Pilih Tipe --</option>
                        @foreach ($userTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->description }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Unit /
                        Fakultas</label>
                    <select name="unit_id" x-model="userData.unit" required
                        class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-3 block text-sm font-semibold text-gray-900 dark:text-white">Hak Akses / Role <span
                            class="text-red-500">*</span></label>
                    <div
                        class="grid grid-cols-2 gap-3 rounded-xl bg-gray-50/50 p-4 ring-1 ring-gray-200 dark:bg-gray-900/30 dark:ring-gray-700 sm:grid-cols-3">
                        @foreach ($roles as $role)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="role_ids[]" value="{{ $role->id }}"
                                    x-model="userData.roles"
                                    class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                <span
                                    class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-blue-600 transition">{{ $role->role_name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-end pb-2">
                    <label class="relative inline-flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" class="peer sr-only"
                            :checked="userData.active">
                        <div
                            class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-emerald-500 peer-checked:after:translate-x-full dark:bg-gray-700">
                        </div>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Akun Aktif</span>
                    </label>
                </div>
            </div>

            <div
                class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 dark:border-gray-700 sm:flex-row sm:justify-end">
                <button type="submit"
                    class="rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
