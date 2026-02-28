@extends('layouts.app')

@section('content')
  <div class="mb-8 flex items-center justify-between">
    <div>
      <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Edit User</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400">Memperbarui data pengguna: <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $user->name }}</span></p>
    </div>
    <a href="{{ route('masterdata.admin.users.index') }}" 
       class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700 dark:hover:bg-gray-700 transition">
      <i class="fas fa-arrow-left"></i>
      Kembali
    </a>
  </div>

  <div class="rounded-xl bg-white p-8 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
    <form action="{{ route('masterdata.admin.users.update', $user->id) }}" method="POST" class="space-y-8">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
        
        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">ID Pengguna (Username)</label>
          <input type="text" class="w-full rounded-lg border-0 bg-gray-50 px-4 py-2.5 text-gray-500 ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900/50 dark:text-gray-400 dark:ring-gray-700" 
            value="{{ $user->id }}" disabled title="ID tidak dapat diubah">
          <p class="mt-2 text-xs text-gray-400 italic">ID bersifat permanen dan tidak dapat diubah.</p>
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
            Nama Lengkap <span class="text-red-500">*</span>
          </label>
          <input type="text" name="name" 
            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600 @error('name') ring-red-500 @enderror" 
            placeholder="Contoh: Muhammad Angga" value="{{ old('name', $user->name) }}">
          @error('name')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
            Email Institusi <span class="text-red-500">*</span>
          </label>
          <input type="email" name="email" 
            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600 @error('email') ring-red-500 @enderror" 
            placeholder="user@uinsaizu.ac.id" value="{{ old('email', $user->email) }}">
          @error('email')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">NIM / NIP / NIK</label>
          <input type="text" name="identity_id" 
            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
            placeholder="Masukkan nomor identitas" value="{{ old('identity_id', $user->identity_id) }}">
        </div>

        <div class="border-t border-gray-100 pt-4 dark:border-gray-700 md:col-span-2">
            <h4 class="mb-4 text-sm font-bold uppercase tracking-wider text-gray-400">Keamanan</h4>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                  <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Password Baru</label>
                  <input type="password" name="password" 
                    class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600 @error('password') ring-red-500 @enderror" 
                    placeholder="••••••••">
                  <p class="mt-2 text-xs text-gray-400 italic">Kosongkan jika tidak ingin mengubah password.</p>
                </div>

                <div>
                  <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Konfirmasi Password</label>
                  <input type="password" name="password_confirmation" 
                    class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
                    placeholder="••••••••">
                </div>
            </div>
        </div>

        <div class="md:col-span-2">
          <label class="mb-3 block text-sm font-semibold text-gray-900 dark:text-white">
            Hak Akses / Role <span class="text-red-500">*</span>
          </label>
          <div class="grid grid-cols-2 gap-3 rounded-xl bg-gray-50/50 p-5 ring-1 ring-gray-200 dark:bg-gray-900/30 dark:ring-gray-700 sm:grid-cols-3 lg:grid-cols-4">
            @forelse($roles as $role)
              <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white dark:hover:bg-gray-800 transition cursor-pointer group">
                <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" 
                  class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700" 
                  {{ in_array($role->id, old('role_ids', $userRoles)) ? 'checked' : '' }}>
                <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">{{ $role->role_name }}</span>
              </label>
            @empty
              <p class="col-span-full text-center text-sm text-gray-500">Tidak ada role tersedia</p>
            @endforelse
          </div>
          @error('role_ids')
            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tipe Pengguna</label>
          <select name="user_type" 
            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
            <option value="">-- Pilih Tipe --</option>
            <option value="admin" {{ old('user_type', $user->user_type) == 'admin' ? 'selected' : '' }}>Admin Sistem</option>
            <option value="staff" {{ old('user_type', $user->user_type) == 'staff' ? 'selected' : '' }}>Staff Pegawai</option>
            <option value="dosen" {{ old('user_type', $user->user_type) == 'dosen' ? 'selected' : '' }}>Dosen</option>
            <option value="mahasiswa" {{ old('user_type', $user->user_type) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
          </select>
        </div>

        <div class="flex items-end pb-2">
          <label class="relative inline-flex cursor-pointer items-center gap-3">
            <input type="checkbox" name="is_active" value="1" class="peer sr-only" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
            <div class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:ext-gray-900 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:bg-gray-700"></div>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">Status Akun Aktif</span>
          </label>
        </div>
      </div>

      <div class="flex flex-col-reverse gap-3 pt-6 border-t border-gray-100 dark:border-gray-700 sm:flex-row sm:justify-end">
        <a href="{{ route('masterdata.admin.users.index') }}" 
           class="inline-flex justify-center rounded-lg px-6 py-2.5 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50 dark:text-gray-300 dark:ring-gray-600 dark:hover:bg-gray-700 transition">
          Batalkan
        </a>
        <button type="submit" 
                class="inline-flex justify-center items-center gap-2 rounded-lg bg-blue-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition dark:focus:ring-offset-gray-800">
          <i class="fas fa-save"></i>
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
@endsection