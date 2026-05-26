@props(['title' => '', 'breadcrumbs' => []])

<div class="mb-6">
    @if($title)
        <h1 class="text-2xl font-semibold text-gray-900 mb-2">{{ $title }}</h1>
    @endif

    @if(count($breadcrumbs) > 0)
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                @foreach($breadcrumbs as $index => $breadcrumb)
                    <li class="inline-flex items-center">
                        @if($index > 0)
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                        @endif
                        @if(isset($breadcrumb['url']))
                            <a href="{{ $breadcrumb['url'] }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                {{ $breadcrumb['label'] }}
                            </a>
                        @else
                            <span class="text-sm font-medium text-gray-500">{{ $breadcrumb['label'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif
</div>
