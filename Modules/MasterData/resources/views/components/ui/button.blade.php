@props([
    'type' => 'button',
    'variant' => 'primary',
    'icon' => null,
    'iconOnly' => false,
])
@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition duration-200';

    $variantClass = match ($variant) {
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700',
        'warning' => 'bg-amber-500 text-white hover:bg-amber-600',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        'info' => 'bg-sky-600 text-white hover:bg-sky-700',
        default => 'bg-indigo-600 text-white hover:bg-indigo-700',
    };

    $sizeClass = $iconOnly
        ? 'w-9 h-9'
        : 'px-4 py-2 text-sm';
@endphp
<button type="{{ $type }}"
    {{ $attributes->merge(['class' => "$base $sizeClass $variantClass"]) }}>

@if($icon)
    {!! \App\Helpers\IconHelper::render($icon) !!}
@endif

    @unless($iconOnly)
        {{ $slot }}
    @endunless
</button>