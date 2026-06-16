@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="whatsappSettingsApp({ health: @js($health ?? null), sessions: @js($sessions ?? []) })" x-init="init()" x-cloak>

        @include('settings.whatsapp.partials._header')
        @include('settings.whatsapp.partials._health')
        @include('settings.whatsapp.partials._alerts')
        @include('settings.whatsapp.partials._info')
        @include('settings.whatsapp.partials._sessions_table')
        @include('settings.whatsapp.partials._test_form')

        {{-- Modal create + panel QR (PLAN §4.3) --}}
        @include('settings.whatsapp.partials._modal_create')
        @include('settings.whatsapp.partials._qr_panel')

    </div>

    @include('settings.whatsapp.partials._scripts')
@endsection