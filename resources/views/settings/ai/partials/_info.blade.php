<div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-200">
    <p class="font-semibold flex items-center gap-2">
        <i class="fa-solid fa-circle-info"></i> Daftar AI provider yang didukung:
    </p>
    <ul class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1.5 text-xs">
        @foreach($providers as $key => $info)
            <li>
                <span class="font-bold uppercase">{{ $info['label'] }}</span>
                @if(isset($info['note']))
                    <span class="opacity-80">— {{ $info['note'] }}</span>
                @endif
            </li>
        @endforeach
    </ul>
    <p class="mt-3 text-xs opacity-80">
        <i class="fa-solid fa-lightbulb"></i> AI ini akan digunakan untuk koreksi otomatis hasil ujian essay.
    </p>
</div>
