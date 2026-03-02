<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - Sainteku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f5f5f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .reset-card {
      max-width: 500px;
      margin: 50px auto;
      border-radius: 0;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      border: none;
    }

    .btn-reset {
      background-color: #FEEB04;
      color: #000;
      border: none;
      border-radius: 0;
      padding: 12px;
      font-weight: 500;
      width: 100%;
    }

    .btn-reset:hover {
      background-color: #CBB800;
      color: #000;
    }

    .form-control {
      border-radius: 0;
      border: 1px solid #dee2e6;
      padding: 10px 12px;
    }

    .form-control:focus {
      border-color: #FEEB04;
      box-shadow: 0 0 0 3px rgba(254, 235, 4, 0.15);
      outline: none;
    }

    .header-logo {
      text-align: center;
      margin-bottom: 20px;
    }

    .header-logo h2 {
      color: #FEEB04;
      font-weight: bold;
      margin-bottom: 0;
    }

    .header-logo p {
      color: #666;
      margin-bottom: 0;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="reset-card card">
      <div class="card-body p-4">
        <div class="header-logo">
          <h2>Sainteku</h2>
          <p>Fakultas Sains & Teknologi</p>
          <p class="small">UIN Prof. K.H. Saifuddin Zuhri</p>
        </div>

        <h4 class="text-center mb-4">Reset Password</h4>

        <?php if(session('error')): ?>
        <div class="alert alert-danger py-2 small"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger py-2 small">
          <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <p class="mb-0"><?php echo e($error); ?></p>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.update')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="token" value="<?php echo e($token); ?>">
          <input type="hidden" name="email" value="<?php echo e($email); ?>">

          <div class="mb-3">
            <label class="form-label small fw-medium">Email</label>
            <input type="email" class="form-control" value="<?php echo e($email); ?>" disabled readonly>
            <small class="text-muted">Email tidak dapat diubah</small>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-medium">Password Baru</label>
            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
          </div>

          <div class="mb-4">
            <label class="form-label small fw-medium">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang password baru" required>
          </div>

          <button type="submit" class="btn btn-reset">
            Reset Password
          </button>
        </form>

        <div class="text-center mt-3">
          <a href="/" class="small text-decoration-none" style="color: #FEEB04;">Kembali ke Beranda</a>
        </div>
      </div>
    </div>
  </div>
</body>

</html><?php /**PATH E:\kuliah\semester6\laravel\sainteku\resources\views/auth/reset-password.blade.php ENDPATH**/ ?>