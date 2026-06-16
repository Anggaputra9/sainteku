<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>{{ __('messages.forgot_password_title') }} - Sainteku</title>
    <link rel="stylesheet" href="{{ asset('tailadmin/css/style.css') }}">
  </head>
  <body class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
    <div class="w-full max-w-md rounded-lg bg-white p-8 shadow dark:bg-gray-800">
      <h2 class="mb-2 text-2xl font-bold">{{ __('messages.forgot_password_title') }}</h2>
      <p class="mb-6 text-sm text-gray-500">{{ __('messages.forgot_password_desc') }}</p>

      @if(session('status'))
        <div class="mb-4 text-sm text-green-600">{{ session('status') }}</div>
      @endif

      @if($errors->any())
        <div class="mb-4 text-sm text-red-600">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-4">
          <label class="block mb-1 text-sm font-medium">{{ __('messages.forgot_password_credential_label') }}</label>
          <input
            name="credential"
            value="{{ old('credential') }}"
            placeholder="{{ __('messages.forgot_password_credential_placeholder') }}"
            class="w-full rounded border px-3 py-2"
            required
          />
        </div>

        <div class="mt-6">
          <button class="w-full rounded bg-blue-600 px-4 py-2 text-white">{{ __('messages.send_reset_link') }}</button>
        </div>
      </form>
    </div>
  </body>
</html>