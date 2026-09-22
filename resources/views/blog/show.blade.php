@extends('layouts.site')

@section('title', $article->seo_title ?: $article->title.' — Nusakode')
@section('meta-description', $article->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($article->excerpt ?? $article->body ?? $article->title), 160))
@if ($article->featured_image_path)
    @section('og-image', str_starts_with($article->featured_image_path, 'img/') ? asset($article->featured_image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($article->featured_image_path))
@endif

@section('content')
    @php($img = $article->featured_image_path ? (str_starts_with($article->featured_image_path, 'img/') ? asset($article->featured_image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($article->featured_image_path)) : null)
    @php($shareUrl = url()->current())
    @php($shareText = $article->title.' '.$shareUrl)
    @php($author = $article->author)

    <article>
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="py-16 sm:py-20">
                <p class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-neutral-500">
                    <a href="{{ route('blog.index') }}" class="text-navy-950 transition hover:text-brand-700">Blog</a>
                    <span class="text-neutral-300" aria-hidden="true">/</span>
                    <span>{{ $article->categoryName() }}</span>
                </p>

                <div class="mt-8 grid gap-10 lg:grid-cols-[minmax(0,1fr)_300px] lg:items-start">
                    <div>
                        <h1 class="text-3xl font-extrabold leading-tight tracking-tight text-balance text-navy-950 sm:text-5xl">{{ $article->title }}</h1>

                        <div class="mt-8 flex flex-wrap items-center gap-x-5 gap-y-4">
                            @if ($author)
                                <span class="flex items-center gap-3">
                                    @if ($author->avatarUrl())
                                        <img src="{{ $author->avatarUrl() }}" alt="{{ $author->name }}" class="h-11 w-11 rounded-full object-cover" loading="lazy">
                                    @else
                                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-navy-950 text-sm font-bold text-white" aria-hidden="true">{{ $author->initials() }}</span>
                                    @endif
                                    <span>
                                        <span class="block text-sm font-bold text-navy-950">{{ $author->name }}</span>
                                        @if ($author->job_title)
                                            <span class="block text-xs text-neutral-500">{{ $author->job_title }}</span>
                                        @endif
                                    </span>
                                </span>
                            @endif
                            <span class="text-[13px] text-neutral-500">{{ ($article->published_at ?? $article->created_at)?->translatedFormat('d F Y') }}</span>
                            <span class="text-[13px] text-neutral-500" aria-hidden="true">·</span>
                            <span class="text-[13px] text-neutral-500">{{ $article->readingTime() }} menit baca</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 lg:justify-end" aria-label="Bagikan artikel">
                        <span class="mr-1 text-xs font-bold uppercase tracking-[0.18em] text-neutral-400">Share</span>
                        <a href="https://wa.me/?text={{ urlencode($shareText) }}" target="_blank" rel="noopener" aria-label="Bagikan ke WhatsApp" class="flex h-10 w-10 items-center justify-center rounded-full bg-[#25D366] text-sm font-extrabold text-white transition hover:brightness-95">WA</a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener" aria-label="Bagikan ke LinkedIn" class="flex h-10 w-10 items-center justify-center rounded-full bg-[#0A66C2] text-sm font-extrabold text-white transition hover:brightness-95">in</a>
                        <button type="button" data-copy-link="{{ $shareUrl }}" class="flex h-10 w-10 items-center justify-center rounded-full border border-neutral-200 text-neutral-500 transition hover:border-navy-800 hover:text-navy-800" aria-label="Salin tautan artikel">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 14a5 5 0 0 0 7.07 0l3-3a5 5 0 0 0-7.07-7.07l-1.5 1.5"/><path d="M14 10a5 5 0 0 0-7.07 0l-3 3a5 5 0 0 0 7.07 7.07l1.5-1.5"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if ($img)
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <img src="{{ $img }}" alt="{{ $article->title }}" class="aspect-[16/8] w-full rounded-2xl object-cover" loading="lazy">
            </div>
        @endif

        <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6">
            <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_300px] lg:items-start">
                <div class="min-w-0">
                    @if ($article->excerpt)
                        <p class="border-l-2 border-brand-600 pl-5 text-lg font-medium leading-relaxed text-navy-950">{{ $article->excerpt }}</p>
                    @endif

                    @if ($keyPoints)
                        <div class="mt-8 rounded-2xl border border-brand-100 bg-brand-50/60 p-6 sm:p-8">
                            <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-navy-950">Poin Utama</h2>
                            <ul class="mt-4 space-y-3">
                                @foreach ($keyPoints as $point)
                                    <li class="flex gap-3 text-[15px] leading-relaxed text-neutral-700">
                                        <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-600" aria-hidden="true"></span>
                                        <span>{{ $point }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($sections)
                        <div class="mt-10 space-y-12">
                            @foreach ($sections as $section)
                                <section id="{{ $section['id'] }}" aria-label="{{ $section['heading'] }}" class="scroll-mt-28">
                                    <h2 class="text-2xl font-extrabold tracking-tight text-navy-950">{{ $section['heading'] }}</h2>
                                    <div class="mt-4 whitespace-pre-line text-[15px] leading-relaxed text-neutral-600">{{ $section['body'] }}</div>
                                </section>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-8 whitespace-pre-line text-[15px] leading-relaxed text-neutral-600">{{ $article->body ?: 'Isi artikel segera hadir.' }}</div>
                    @endif

                    @if ($article->tagList())
                        <ul class="mt-12 flex flex-wrap gap-2" aria-label="Tag">
                            @foreach ($article->tagList() as $tag)
                                <li class="rounded-full border border-neutral-200 px-4 py-1.5 text-xs font-semibold text-neutral-500">{{ $tag }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <section class="mt-12 rounded-2xl bg-navy-950 p-8 text-white sm:p-10">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-400">Konsultasi Gratis</p>
                        <h2 class="mt-3 max-w-xl text-2xl font-extrabold tracking-tight text-balance sm:text-3xl">Konsultasikan kebutuhan {{ $article->categoryName() }} perusahaan Anda</h2>
                        <p class="mt-3 max-w-xl text-[15px] leading-relaxed text-neutral-300">Ceritakan kebutuhan Anda, tim Nusakode akan membantu memetakan solusi yang paling sesuai.</p>
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('kontak') }}" class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-7 py-3 text-sm font-bold text-white transition hover:bg-brand-500">Hubungi Kami</a>
                            <a href="{{ route('layanan.index') }}" class="inline-flex items-center gap-2 rounded-full border border-white/25 px-7 py-3 text-sm font-bold text-white transition hover:border-white hover:bg-white/10">Mulai Konsultasi</a>
                        </div>
                    </section>

                    @if ($author)
                        <section class="mt-12 rounded-2xl border border-neutral-200 p-6 sm:p-8" aria-label="Penulis">
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-neutral-400">Author</p>
                            <div class="mt-4 flex items-start gap-4">
                                @if ($author->avatarUrl())
                                    <img src="{{ $author->avatarUrl() }}" alt="{{ $author->name }}" class="h-14 w-14 rounded-full object-cover" loading="lazy">
                                @else
                                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-navy-950 text-base font-bold text-white" aria-hidden="true">{{ $author->initials() }}</span>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-950">{{ $author->name }}</p>
                                    @if ($author->job_title)
                                        <p class="mt-1 text-sm text-neutral-500">{{ $author->job_title }}</p>
                                    @endif
                                </div>
                            </div>
                        </section>
                    @endif

                    <div class="mt-8 flex flex-wrap items-center gap-3 text-sm text-neutral-500">
                        <span class="font-bold text-navy-950">Kategori:</span>
                        <span class="rounded-full bg-neutral-100 px-4 py-1.5 text-xs font-bold text-neutral-600">{{ $article->categoryName() }}</span>
                    </div>
                </div>

                <aside class="space-y-6 lg:sticky lg:top-28">
                    @if (count($sections) > 1)
                        <nav class="rounded-2xl border border-neutral-200 bg-white p-6" aria-label="Daftar isi">
                            <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-navy-950">Table of Contents</h2>
                            <ol class="mt-4 space-y-3">
                                @foreach ($sections as $section)
                                    <li>
                                        <a href="#{{ $section['id'] }}" class="text-sm font-semibold leading-relaxed text-neutral-500 transition hover:text-brand-700">{{ $section['heading'] }}</a>
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    @endif

                    <div class="overflow-hidden rounded-2xl bg-navy-950 text-white">
                        <div class="p-6">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-400">Butuh solusi serupa?</p>
                            <p class="mt-3 text-lg font-extrabold leading-snug">Diskusikan kebutuhan {{ $article->categoryName() }} Anda dengan tim kami.</p>
                            <a href="{{ route('kontak') }}" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full bg-brand-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-brand-500">Konsultasi Sekarang</a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="border-t border-neutral-100 bg-neutral-50/60 py-20 sm:py-24">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <h2 class="text-2xl font-extrabold tracking-tight text-navy-950">Artikel terkait</h2>
                    <a href="{{ route('blog.index') }}" class="text-sm font-bold text-navy-800 transition hover:text-brand-700">Semua artikel</a>
                </div>
                <div class="mt-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        @php($itemImg = $item->featured_image_path ? (str_starts_with($item->featured_image_path, 'img/') ? asset($item->featured_image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($item->featured_image_path)) : null)
                        <article class="group flex flex-col">
                            <a href="{{ route('blog.show', $item->slug) }}" class="block overflow-hidden rounded-2xl bg-neutral-100">
                                @if ($itemImg)
                                    <img src="{{ $itemImg }}" alt="{{ $item->title }}" class="aspect-[16/9] w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy">
                                @else
                                    <div class="flex aspect-[16/9] w-full items-center justify-center bg-navy-950"><span class="text-xs font-bold uppercase tracking-[0.2em] text-white/30">{{ $item->categoryName() }}</span></div>
                                @endif
                            </a>
                            <div class="mt-5 flex flex-1 flex-col">
                                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-brand-700">{{ $item->categoryName() }}<span class="text-neutral-300"> · </span><span class="text-neutral-400">{{ ($item->published_at ?? $item->created_at)?->translatedFormat('d M Y') }}</span></p>
                                <h3 class="mt-2 text-lg font-bold leading-snug tracking-tight text-navy-950"><a href="{{ route('blog.show', $item->slug) }}" class="transition hover:text-brand-700">{{ $item->title }}</a></h3>
                                @if ($item->excerpt)
                                    <p class="mt-2 flex-1 text-sm leading-relaxed text-neutral-500">{{ \Illuminate\Support\Str::limit($item->excerpt, 120) }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <script>
        document.querySelectorAll('[data-copy-link]').forEach((button) => {
            button.addEventListener('click', async () => {
                const link = button.getAttribute('data-copy-link') || window.location.href;

                try {
                    await navigator.clipboard.writeText(link);
                    button.setAttribute('aria-label', 'Tautan artikel tersalin');
                } catch (error) {
                    window.prompt('Salin tautan artikel:', link);
                }
            });
        });
    </script>
@endsection
