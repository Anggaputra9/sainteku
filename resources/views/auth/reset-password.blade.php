<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - Sainteku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: #f8fafc;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 12px;
      margin: 0;
    }

    /* CARD LEBIH KECIL - 380px */
    .reset-card {
      max-width: 380px;
      width: 100%;
      margin: 0 auto;
      border-radius: 20px;
      box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(0, 0, 0, 0.03);
      overflow: hidden;
      background: white;
    }

    .card-body {
      padding: 1.75rem;
    }

    .header-logo {
      text-align: center;
      margin-bottom: 1.25rem;
    }

    .header-logo h2 {
      color: #1e293b;
      font-weight: 700;
      font-size: 1.8rem;
      margin-bottom: 0.1rem;
      letter-spacing: -0.5px;
    }

    .header-logo p {
      color: #64748b;
      margin-bottom: 0.1rem;
      font-size: 0.85rem;
    }

    .header-logo .small {
      color: #94a3b8;
      font-size: 0.75rem;
    }

    h4 {
      color: #1e293b;
      font-weight: 600;
      font-size: 1.15rem;
      margin-bottom: 1.25rem;
      padding-bottom: 0.6rem;
      border-bottom: 2px solid #f1f5f9;
      text-align: center;
    }

    .form-label {
      color: #1e293b;
      font-weight: 500;
      font-size: 0.8rem;
      margin-bottom: 0.3rem;
    }

    .form-control {
      border-radius: 12px;
      border: 1.5px solid #e2e8f0;
      padding: 0.6rem 1rem;
      font-size: 0.85rem;
      transition: all 0.15s ease;
      background: #f8fafc;
      width: 100%;
      height: 40px;
      line-height: 1.5;
    }

    .form-control:focus {
      border-color: #FEEB04;
      box-shadow: 0 0 0 4px rgba(254, 235, 4, 0.1);
      outline: none;
      background: white;
    }

    .form-control:disabled,
    .form-control[readonly] {
      background-color: #f1f5f9;
      border-color: #e2e8f0;
      color: #475569;
    }

    .text-muted {
      color: #94a3b8 !important;
      font-size: 0.7rem;
      margin-top: 0.25rem;
      display: block;
    }

    /* PASSWORD WRAPPER */
    .password-wrapper {
      position: relative;
      width: 100%;
    }

    .password-wrapper .form-control {
      padding-right: 38px;
    }

    .toggle-password {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #94a3b8;
      font-size: 1rem;
      transition: color 0.15s ease;
      z-index: 10;
      background: transparent;
      border: none;
      padding: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      line-height: 1;
    }

    .toggle-password:hover {
      color: #FEEB04;
    }

    .toggle-password:focus {
      outline: none;
    }

    /* Email field specific */
    .email-field {
      background-color: #f1f5f9 !important;
      cursor: not-allowed;
    }

    .btn-reset {
      background-color: #FEEB04;
      color: #1e293b;
      border: none;
      border-radius: 12px;
      padding: 0.6rem 1rem;
      font-weight: 600;
      font-size: 0.9rem;
      width: 100%;
      transition: all 0.2s ease;
      margin-top: 0.25rem;
      border: 1px solid transparent;
      height: 40px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .btn-reset:hover {
      background-color: #e6d403;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(254, 235, 4, 0.25);
    }

    .btn-reset:active {
      transform: translateY(0);
    }

    .btn-reset:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    .alert {
      border-radius: 12px;
      padding: 0.6rem 0.9rem;
      margin-bottom: 1rem;
      border: none;
      font-size: 0.8rem;
      background-color: #fee2e2;
      color: #991b1b;
      border-left: 4px solid #ef4444;
    }

    .alert ul {
      margin: 0;
      padding-left: 1.2rem;
    }

    .alert li {
      margin-bottom: 0.2rem;
    }

    .alert li:last-child {
      margin-bottom: 0;
    }

    /* Error states */
    .is-invalid {
      border-color: #ef4444 !important;
    }

    .is-invalid:focus {
      box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important;
    }

    .invalid-feedback {
      color: #ef4444;
      font-size: 0.7rem;
      margin-top: 0.2rem;
    }

    /* Loading state */
    .spinner-border-sm {
      width: 0.9rem;
      height: 0.9rem;
      border-width: 2px;
      margin-right: 0.4rem;
    }

    /* Spacing */
    .mb-3 {
      margin-bottom: 0.9rem !important;
    }

    .mb-4 {
      margin-bottom: 1.1rem !important;
    }
  </style>
</head>

<body>
  <div class="container d-flex align-items-center justify-content-center p-0">
    <div class="reset-card card">
      <div class="card-body">

        <div class="header-logo">
          <h2>Sainteku</h2>
          <p>Fakultas Sains & Teknologi</p>
          <p class="small">UIN Prof. K.H. Saifuddin Zuhri</p>
        </div>

        <h4 class="text-center">Atur Ulang Kata Sandi</h4>

        <!-- Error Alert -->
        @if(session('error'))
        <div class="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          {{ session('error') }}
        </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
        <div class="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" id="resetForm">
          @csrf
          <input type="hidden" name="token" value="{{ $token }}">
          <input type="hidden" name="email" value="{{ $email }}">

          <!-- Email Field -->
          <div class="mb-3">
            <label class="form-label">
              <i class="bi bi-envelope me-1"></i>Email
            </label>
            <input type="email"
              class="form-control email-field"
              value="{{ $email }}"
              disabled
              readonly>
            <small class="text-muted">
              <i class="bi bi-info-circle me-1"></i>Email tidak dapat diubah
            </small>
          </div>

          <!-- Password Baru -->
          <div class="mb-3">
            <label class="form-label" for="password">
              <i class="bi bi-lock me-1"></i>Kata Sandi Baru
            </label>
            <div class="password-wrapper">
              <input type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Minimal 8 karakter"
                minlength="8"
                required>
              <i class="bi bi-eye toggle-password" onclick="togglePassword('password', this)"></i>
            </div>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Konfirmasi Password -->
          <div class="mb-4">
            <label class="form-label" for="password_confirmation">
              <i class="bi bi-lock me-1"></i>Konfirmasi Kata Sandi
            </label>
            <div class="password-wrapper">
              <input type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control"
                placeholder="Ketik ulang kata sandi"
                minlength="8"
                required>
              <i class="bi bi-eye toggle-password" onclick="togglePassword('password_confirmation', this)"></i>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-reset" id="submitBtn">
            <span>Atur Ulang Kata Sandi</span>
            <i class="bi bi-arrow-right"></i>
          </button>
        </form>

      </div>
    </div>
  </div>

  <script>
    // Toggle password visibility
    function togglePassword(fieldId, icon) {
      const field = document.getElementById(fieldId);
      if (field.type === "password") {
        field.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
      } else {
        field.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
      }
      field.focus();
    }

    // Form loading state
    const resetForm = document.getElementById('resetForm');
    if (resetForm) {
      resetForm.addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          <span>Memproses...</span>
        `;
      });
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
      });
    }, 5000);
  </script>

</body>

</html>