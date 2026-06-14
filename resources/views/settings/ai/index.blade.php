@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="aiSettingsApp()" x-init="init()" x-cloak>

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

        {{-- Loader tes koneksi AI --}}
        @include('settings.ai.partials._modal-test-loader')

    </div>

    {{-- Alpine.js Scripts --}}
    @include('settings.ai.partials._scripts')
@endsection
