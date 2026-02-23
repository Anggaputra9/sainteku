<x-masterdata::layouts.master>
  <div class="mb-6 flex items-center justify-between">
    <h3 class="text-xl font-semibold">Tambah User Baru</h3>
    <a href="{{ route('masterdata.admin.users.index') }}" class="inline-flex items-center gap-2 rounded bg-yellow-400 px-4 py-2 font-medium text-gray-800 hover:bg-yellow-500">
      <i class="fas fa-arrow-left"></i>
      Kembali
    </a>
  </div>

  <div class="rounded-lg border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark">
    <form action="{{ route('masterdata.admin.users.store') }}" method="POST" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- ID (Username) -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            ID Pengguna (Username) <span class="text-red-500">*</span>
          </label>
          <input type="text" name="id" class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white @error('id') border-red-500 @enderror" 
            placeholder="user001" value="{{ old('id') }}">
          @error('id')
            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
          @enderror
        </div>

        <!-- Nama -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            Nama Lengkap <span class="text-red-500">*</span>
          </label>
          <input type="text" name="name" class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white @error('name') border-red-500 @enderror" 
            placeholder="John Doe" value="{{ old('name') }}">
          @error('name')
            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
          @enderror
        </div>

        <!-- Email -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            Email <span class="text-red-500">*</span>
          </label>
          <input type="email" name="email" class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white @error('email') border-red-500 @enderror" 
            placeholder="user@example.com" value="{{ old('email') }}">
          @error('email')
            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
          @enderror
        </div>

        <!-- Password -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            Password <span class="text-red-500">*</span>
          </label>
          <input type="password" name="password" class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white @error('password') border-red-500 @enderror" 
            placeholder="••••••••">
          @error('password')
            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
          @enderror
        </div>

        <!-- Konfirm Password -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            Konfirm Password <span class="text-red-500">*</span>
          </label>
          <input type="password" name="password_confirmation" class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white" 
            placeholder="••••••••">
        </div>

        <!-- Roles (Multiple) -->
        <div class="md:col-span-2">
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            Role Pengguna (Pilih satu atau lebih) <span class="text-red-500">*</span>
          </label>
          <div class="space-y-2 rounded border border-gray-300 bg-white p-4 dark:border-gray-600 dark:bg-gray-800">
            @forelse($roles as $role)
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500" {{ in_array($role->id, old('role_ids', [])) ? 'checked' : '' }}>
                <span class="text-sm text-gray-900 dark:text-white">{{ $role->role_name }}</span>
              </label>
            @empty
              <p class="text-sm text-gray-500">Tidak ada role tersedia</p>
            @endforelse
          </div>
          @error('role_ids')
            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
          @enderror
        </div>

        <!-- Identity ID -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            No. Identitas
          </label>
          <input type="text" name="identity_id" class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white" 
            placeholder="123456789" value="{{ old('identity_id') }}">
        </div>

        <!-- User Type -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            Tipe Pengguna
          </label>
          <select name="user_type" class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            <option value="">-- Pilih Tipe Pengguna --</option>
            <option value="admin" {{ old('user_type') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="staff" {{ old('user_type') === 'staff' ? 'selected' : '' }}>Staff</option>
            <option value="dosen" {{ old('user_type') === 'dosen' ? 'selected' : '' }}>Dosen</option>
            <option value="mahasiswa" {{ old('user_type') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
          </select>
        </div>

        <!-- Unit ID -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            Unit ID
          </label>
          <input type="text" name="unit_id" class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white" 
            placeholder="U001" value="{{ old('unit_id') }}">
        </div>

        <!-- Status Aktif -->
        <div class="flex items-center">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500" {{ old('is_active') ? 'checked' : '' }}>
            <span class="text-sm font-medium text-gray-900 dark:text-white">Aktifkan User</span>
          </label>
        </div>
      </div>

      <!-- Success Message -->
      @if (session('success'))
        <div class="rounded-lg border border-emerald-300 bg-emerald-50 p-4 text-emerald-800">
          <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
          </div>
        </div>
      @endif

      <!-- Buttons -->
      <div class="flex gap-3 pt-6">
        <button type="submit" class="inline-flex items-center gap-2 rounded bg-emerald-500 px-6 py-2 font-medium text-white hover:bg-emerald-600 transition">
          <i class="fas fa-floppy-disk"></i>
          Simpan
        </button>
        <a href="{{ route('masterdata.admin.users.index') }}" class="inline-flex items-center gap-2 rounded bg-red-500 px-6 py-2 font-medium text-white hover:bg-red-600 transition">
          <i class="fas fa-xmark"></i>
          Batal
        </a>
      </div>
    </form>
  </div>
</x-masterdata::layouts.master>
