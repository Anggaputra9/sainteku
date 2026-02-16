<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Setel Ulang Password - Sainteku</title>
    <link rel="stylesheet" href="{{ asset('tailadmin/css/style.css') }}">
  </head>
  <body class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center">
    <div class="w-full max-w-md rounded-lg bg-white p-8 shadow dark:bg-gray-800">
      <h2 class="mb-6 text-2xl font-bold">Setel Ulang Password</h2>

      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mb-4">
          <label class="block mb-1 text-sm">Email</label>
          <input name="email" value="{{ $email ?? old('email') }}" class="w-full rounded border px-3 py-2" />
        </div>
        <div class="mb-4">
          <label class="block mb-1 text-sm">Password baru</label>
          <input name="password" type="password" class="w-full rounded border px-3 py-2" />
        </div>
        <div class="mb-4">
          <label class="block mb-1 text-sm">Konfirmasi Password</label>
          <input name="password_confirmation" type="password" class="w-full rounded border px-3 py-2" />
        </div>

        <div class="mt-6">
          <button class="w-full rounded bg-blue-600 px-4 py-2 text-white">Reset Password</button>
        </div>
      </form>
    </div>
  </body>
</html>
<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
