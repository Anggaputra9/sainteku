@extends('layouts.app')

@section('content')
    <div class="space-y-6 relative" x-data="aiSettingsApp()" x-init="init()" x-cloak>

        {{-- Loader tes koneksi AI --}}
        <div x-show="testingId"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[10000003] flex items-center justify-center bg-gray-900/40 backdrop-blur-sm p-4"
            style="display: none;">
            <div class="w-full max-w-sm rounded-2xl border border-gray-200 bg-white p-6 text-center shadow-2xl dark:border-gray-700 dark:bg-gray-900">
                <i class="fa-solid fa-circle-notch fa-spin text-4xl text-indigo-600 mb-4"></i>
                <p class="text-base font-bold text-gray-900 dark:text-white">Mengetes koneksi AI...</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400" x-text="testingLabel"></p>
                <p class="mt-3 text-xs text-gray-400 dark:text-gray-500">Model reasoning bisa butuh beberapa detik. Jangan tutup halaman.</p>
            </div>
        </div>

        {{-- Header --}}
        @include('settings.ai.partials._header')

        {{-- Info Provider --}}
        @include('settings.ai.partials._info')

        {{-- Filters --}}
        @include('settings.ai.partials._filters')

        {{-- Alerts --}}
        @include('settings.ai.partials._alerts')

        {{-- Table --}}
        @include('settings.ai.partials._table')

        {{-- Pagination --}}
        @if($settings->hasPages())
            <div class="px-2">{{ $settings->links() }}</div>
        @endif

        {{-- Modal Create/Edit --}}
        @include('settings.ai.partials._modal-form')

        {{-- Modal Detail --}}
        @include('settings.ai.partials._modal-detail')

    </div>

    {{-- Alpine.js Scripts --}}
    @include('settings.ai.partials._scripts')
@endsection
