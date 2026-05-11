{{--
    ============================
    BLOG / BERITA SECTION
    Tempel ini sebagai pengganti section id="blog" di landing.blade.php
    $latestNews dikirim dari NewsController
    ============================
--}}
<section class="py-24 bg-slate-50" id="blog">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl lg:text-4xl font-semibold mb-4">{{ __('messages.blog_title') }}</h2>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto">{{ __('messages.blog_desc') }}</p>
        </div>

        @if($latestNews->isEmpty())
            {{-- Fallback kalau belum ada data di DB --}}
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-newspaper-line text-3xl text-slate-400"></i>
                </div>
                <p class="text-slate-400 text-base">Berita belum tersedia. Jalankan <code class="bg-slate-100 px-2 py-0.5 rounded text-sm">php artisan news:scrape</code> untuk mengambil berita.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($latestNews as $news)
                <a href="{{ $news->url }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="card-hover bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 flex flex-col group">

                    {{-- Thumbnail --}}
                    <div class="relative overflow-hidden h-52 bg-slate-100 shrink-0">
                        @if($news->image_url)
                            <img src="{{ $news->image_url }}"
                                 alt="{{ $news->title }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 loading="lazy"
                                 onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-yellow-50\'><i class=\'ri-image-line text-4xl text-slate-300\'></i></div>'">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-yellow-50">
                                <i class="ri-newspaper-line text-4xl text-slate-300"></i>
                            </div>
                        @endif

                        {{-- Source badge --}}
                        <div class="absolute top-3 left-3">
                            <span class="bg-saintek-primary text-black text-xs font-semibold px-2.5 py-1 rounded-full">
                                Saintek UIN Saizu
                            </span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-5 flex flex-col flex-1">
                        {{-- Meta --}}
                        <div class="flex items-center gap-3 text-slate-400 text-xs mb-3">
                            @if($news->published_at)
                                <span class="flex items-center gap-1">
                                    <i class="ri-calendar-line"></i>
                                    {{ $news->published_at->translatedFormat('d M Y') }}
                                </span>
                            @endif
                            <span class="flex items-center gap-1">
                                <i class="ri-external-link-line"></i>
                                {{ $news->source }}
                            </span>
                        </div>

                        {{-- Judul --}}
                        <h4 class="font-bold text-base leading-snug mb-3 text-slate-800 group-hover:text-saintek-text transition-colors line-clamp-2">
                            {{ $news->title }}
                        </h4>

                        {{-- Excerpt --}}
                        @if($news->excerpt)
                            <p class="text-slate-500 text-sm leading-relaxed line-clamp-3 mb-4">
                                {{ $news->excerpt }}
                            </p>
                        @endif

                        {{-- CTA --}}
                        <div class="mt-auto flex items-center gap-1.5 text-saintek-text font-semibold text-sm group-hover:underline">
                            {{ __('messages.read_more') }}
                            <i class="ri-arrow-right-line transition-transform group-hover:translate-x-1"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Link ke semua berita --}}
            <div class="text-center mt-10">
                <a href="https://saintek.uinsaizu.ac.id/category/berita/"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 border-2 border-saintek-primary text-saintek-text font-semibold px-8 py-3 rounded-full hover:bg-saintek-primary hover:text-black transition-colors">
                    {{ __('messages.view_all') }} <i class="ri-external-link-line"></i>
                </a>
            </div>
        @endif
    </div>
</section>