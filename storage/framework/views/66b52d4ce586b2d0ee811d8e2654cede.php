<?php if (isset($component)) { $__componentOriginald29c8d5614b8cfa9d0a98fd837300603 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald29c8d5614b8cfa9d0a98fd837300603 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'masterdata::components.layouts.master','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('masterdata::layouts.master'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
  <div class="mb-6 flex items-center justify-between">
    <h3 class="text-xl font-semibold">Edit User: <?php echo e($user->name); ?></h3>
    <a href="<?php echo e(route('masterdata.admin.users.index')); ?>" class="inline-flex items-center gap-2 rounded bg-yellow-400 px-4 py-2 font-medium text-gray-800 hover:bg-yellow-500">
      <i class="fas fa-arrow-left"></i>
      Kembali
    </a>
  </div>

  <div class="rounded-lg border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark">
    <form action="<?php echo e(route('masterdata.admin.users.update', $user->id)); ?>" method="POST" class="space-y-6">
      <?php echo csrf_field(); ?>
      <?php echo method_field('PUT'); ?>

      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- ID (Username) - Readonly -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            ID Pengguna (Username)
          </label>
          <input type="text" class="w-full rounded border border-gray-300 bg-gray-100 px-4 py-2 text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white" 
            placeholder="<?php echo e($user->id); ?>" value="<?php echo e($user->id); ?>" disabled>
          <p class="mt-1 text-xs text-gray-500">ID tidak dapat diubah</p>
        </div>

        <!-- Nama -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            Nama Lengkap <span class="text-red-500">*</span>
          </label>
          <input type="text" name="name" class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
            placeholder="John Doe" value="<?php echo e(old('name', $user->name)); ?>">
          <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Email -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            Email <span class="text-red-500">*</span>
          </label>
          <input type="email" name="email" class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
            placeholder="user@example.com" value="<?php echo e(old('email', $user->email)); ?>">
          <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Password -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            Password
          </label>
          <input type="password" name="password" class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
            placeholder="••••••••">
          <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
          <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Konfirm Password -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            Konfirm Password
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
            <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="role_ids[]" value="<?php echo e($role->id); ?>" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500" <?php echo e(in_array($role->id, old('role_ids', $userRoles)) ? 'checked' : ''); ?>>
                <span class="text-sm text-gray-900 dark:text-white"><?php echo e($role->role_name); ?></span>
              </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <p class="text-sm text-gray-500">Tidak ada role tersedia</p>
            <?php endif; ?>
          </div>
          <?php $__errorArgs = ['role_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Identity ID -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            No. Identitas
          </label>
          <input type="text" name="identity_id" class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white" 
            placeholder="123456789" value="<?php echo e(old('identity_id', $user->identity_id)); ?>">
        </div>

        <!-- User Type -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            Tipe Pengguna
          </label>
          <select name="user_type" class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            <option value="">-- Pilih Tipe Pengguna --</option>
            <option value="admin" <?php echo e(old('user_type', $user->user_type) == 'admin' ? 'selected' : ''); ?>>Admin</option>
            <option value="staff" <?php echo e(old('user_type', $user->user_type) == 'staff' ? 'selected' : ''); ?>>Staff</option>
            <option value="dosen" <?php echo e(old('user_type', $user->user_type) == 'dosen' ? 'selected' : ''); ?>>Dosen</option>
            <option value="mahasiswa" <?php echo e(old('user_type', $user->user_type) == 'mahasiswa' ? 'selected' : ''); ?>>Mahasiswa</option>
          </select>
        </div>

        <!-- Unit ID -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
            Unit ID
          </label>
          <input type="text" name="unit_id" class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white" 
            placeholder="U001" value="<?php echo e(old('unit_id', $user->unit_id)); ?>">
        </div>

        <!-- Status Aktif -->
        <div class="flex items-center">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500" <?php echo e(old('is_active', $user->is_active) ? 'checked' : ''); ?>>
            <span class="text-sm font-medium text-gray-900 dark:text-white">Aktifkan User</span>
          </label>
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex gap-3 pt-6">
        <button type="submit" class="inline-flex items-center gap-2 rounded bg-emerald-500 px-6 py-2 font-medium text-white hover:bg-emerald-600 transition">
          <i class="fas fa-floppy-disk"></i>
          Simpan Perubahan
        </button>
        <a href="<?php echo e(route('masterdata.admin.users.index')); ?>" class="inline-flex items-center gap-2 rounded bg-red-500 px-6 py-2 font-medium text-white hover:bg-red-600 transition">
          <i class="fas fa-xmark"></i>
          Batal
        </a>
      </div>
    </form>
  </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald29c8d5614b8cfa9d0a98fd837300603)): ?>
<?php $attributes = $__attributesOriginald29c8d5614b8cfa9d0a98fd837300603; ?>
<?php unset($__attributesOriginald29c8d5614b8cfa9d0a98fd837300603); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald29c8d5614b8cfa9d0a98fd837300603)): ?>
<?php $component = $__componentOriginald29c8d5614b8cfa9d0a98fd837300603; ?>
<?php unset($__componentOriginald29c8d5614b8cfa9d0a98fd837300603); ?>
<?php endif; ?>
<?php /**PATH C:\sainteku\Modules/MasterData\resources/views/admin/edit.blade.php ENDPATH**/ ?>