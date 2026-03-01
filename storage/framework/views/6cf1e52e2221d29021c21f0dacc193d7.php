<?php $__env->startSection('content'); ?>
  <div class="mb-8 flex items-center justify-between">
    <div>
      <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah User Baru</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400">Daftarkan pengguna baru ke dalam sistem <span class="font-semibold text-blue-600 dark:text-blue-400">Sainteku</span></p>
    </div>
    <a href="<?php echo e(route('masterdata.admin.users.index')); ?>" 
       class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700 dark:hover:bg-gray-700 transition">
      <i class="fas fa-arrow-left"></i>
      Kembali
    </a>
  </div>

  <div class="rounded-xl bg-white p-8 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
    <form action="<?php echo e(route('masterdata.admin.users.store')); ?>" method="POST" class="space-y-8">
      <?php echo csrf_field(); ?>

      <div class="grid grid-cols-1 gap-x-8 gap-y-6 md:grid-cols-2">
        
        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
            ID Pengguna (Username) <span class="text-red-500">*</span>
          </label>
          <input type="text" name="id" 
            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600 <?php $__errorArgs = ['id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
            placeholder="Contoh: user001" value="<?php echo e(old('id')); ?>">
          <?php $__errorArgs = ['id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-2 text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i> <?php echo e($message); ?></p>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
            Nama Lengkap <span class="text-red-500">*</span>
          </label>
          <input type="text" name="name" 
            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
            placeholder="Masukkan nama lengkap" value="<?php echo e(old('name')); ?>">
          <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-2 text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i> <?php echo e($message); ?></p>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
            Email <span class="text-red-500">*</span>
          </label>
          <input type="email" name="email" 
            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
            placeholder="user@example.com" value="<?php echo e(old('email')); ?>">
          <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-2 text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i> <?php echo e($message); ?></p>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">NIM / NIP / NIK</label>
          <input type="text" name="identity_id" 
            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
            placeholder="123456789" value="<?php echo e(old('identity_id')); ?>">
        </div>

        <div class="border-t border-gray-100 pt-4 dark:border-gray-700 md:col-span-2">
            <h4 class="mb-4 text-sm font-bold uppercase tracking-wider text-gray-400">Kata Sandi</h4>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                  <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                    Password <span class="text-red-500">*</span>
                  </label>
                  <input type="password" name="password" 
                    class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                    placeholder="••••••••">
                  <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-2 text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i> <?php echo e($message); ?></p>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                  <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">
                    Konfirmasi Password <span class="text-red-500">*</span>
                  </label>
                  <input type="password" name="password_confirmation" 
                    class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
                    placeholder="••••••••">
                </div>
            </div>
        </div>

        <div class="md:col-span-2">
          <label class="mb-3 block text-sm font-semibold text-gray-900 dark:text-white">
            Pilih Role Pengguna <span class="text-red-500">*</span>
          </label>
          <div class="grid grid-cols-2 gap-3 rounded-xl bg-gray-50/50 p-5 ring-1 ring-gray-200 dark:bg-gray-900/30 dark:ring-gray-700 sm:grid-cols-3 lg:grid-cols-4">
            <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white dark:hover:bg-gray-800 transition cursor-pointer group">
                <input type="checkbox" name="role_ids[]" value="<?php echo e($role->id); ?>" 
                  class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700" 
                  <?php echo e(in_array($role->id, old('role_ids', [])) ? 'checked' : ''); ?>>
                <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition"><?php echo e($role->role_name); ?></span>
              </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <p class="col-span-full text-center text-sm text-gray-500 italic py-2">Tidak ada role tersedia</p>
            <?php endif; ?>
          </div>
          <?php $__errorArgs = ['role_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-2 text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i> <?php echo e($message); ?></p>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Tipe Pengguna</label>
          <select name="user_type" 
            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600">
            <option value="">-- Pilih Tipe --</option>
            <option value="admin" <?php echo e(old('user_type') === 'admin' ? 'selected' : ''); ?>>Admin</option>
            <option value="staff" <?php echo e(old('user_type') === 'staff' ? 'selected' : ''); ?>>Staff</option>
            <option value="dosen" <?php echo e(old('user_type') === 'dosen' ? 'selected' : ''); ?>>Dosen</option>
            <option value="mahasiswa" <?php echo e(old('user_type') === 'mahasiswa' ? 'selected' : ''); ?>>Mahasiswa</option>
          </select>
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-gray-900 dark:text-white">Unit ID</label>
          <input type="text" name="unit_id" 
            class="w-full rounded-lg border-0 px-4 py-2.5 text-gray-900 ring-1 ring-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:ring-gray-600" 
            placeholder="Contoh: U001" value="<?php echo e(old('unit_id')); ?>">
        </div>

        <div class="flex items-end pb-2">
          <label class="relative inline-flex cursor-pointer items-center gap-3">
            <input type="checkbox" name="is_active" value="1" class="peer sr-only" <?php echo e(old('is_active') ? 'checked' : ''); ?>>
            <div class="h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:bg-gray-700"></div>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">Aktifkan Akun Langsung</span>
          </label>
        </div>
      </div>

      <div class="flex flex-col-reverse gap-3 pt-6 border-t border-gray-100 dark:border-gray-700 sm:flex-row sm:justify-end">
        <a href="<?php echo e(route('masterdata.admin.users.index')); ?>" 
           class="inline-flex justify-center rounded-lg px-6 py-2.5 text-sm font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50 dark:text-gray-300 dark:ring-gray-600 dark:hover:bg-gray-700 transition">
          Batal
        </a>
        <button type="submit" 
                class="inline-flex justify-center items-center gap-2 rounded-lg bg-emerald-600 px-8 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition dark:focus:ring-offset-gray-800">
          <i class="fas fa-save"></i>
          Simpan User
        </button>
      </div>
    </form>
  </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\kuliah\semester6\laravel\sainteku\Modules/MasterData\resources/views/admin/create.blade.php ENDPATH**/ ?>