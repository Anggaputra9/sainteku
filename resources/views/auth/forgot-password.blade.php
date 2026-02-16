<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Reset Password - Sainteku</title>
    <link rel="stylesheet" href="{{ asset('tailadmin/css/style.css') }}">
  </head>
  <body class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
    <div class="w-full max-w-md rounded-lg bg-white p-8 shadow dark:bg-gray-800">
      <h2 class="mb-6 text-2xl font-bold">Reset Password</h2>

      @if(session('status'))<div class="mb-4 text-sm text-green-600">{{ session('status') }}</div>@endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-4">
          <label class="block mb-1 text-sm">Email</label>
          <input name="email" value="{{ old('email') }}" class="w-full rounded border px-3 py-2" />
        </div>

        <div class="mt-6">
          <button class="w-full rounded bg-blue-600 px-4 py-2 text-white">Kirim link reset</button>
        </div>
      </form>
    </div>
  </body>
</html>
<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
