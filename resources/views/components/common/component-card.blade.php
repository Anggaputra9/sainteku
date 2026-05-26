@props(['title' => '', 'icon' => '', 'color' => 'blue'])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300']) }}>
    @if($title || $icon)
        <div class="flex items-center mb-4">
            @if($icon)
                <div class="w-12 h-12 rounded-full bg-{{ $color }}-100 flex items-center justify-center mr-4">
                    <i class="{{ $icon }} text-{{ $color }}-600 text-xl"></i>
                </div>
            @endif
            @if($title)
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            @endif
        </div>
    @endif

    <div>
        {{ $slot }}
    </div>
</div>
