@extends('layouts.site')
@section('title', 'Whitepaper — KIT Konsultan IT')
@section('meta-description', 'Kumpulan analisis dan kerangka kerja praktis untuk membantu keputusan teknologi dan bisnis.')
@section('content')
<section class="border-b border-neutral-200 bg-[#f8f9f7]">
    <div class="mx-auto grid max-w-7xl gap-9 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[minmax(0,1fr)_240px] lg:items-end lg:gap-20">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-700">Resources / Whitepaper</p>
            <h1 class="mt-5 max-w-4xl text-4xl font-extrabold leading-[1.08] tracking-tight text-navy-950 sm:text-6xl">Perspektif yang membantu keputusan lebih terarah.</h1>
            <p class="mt-6 max-w-2xl text-base leading-8 text-neutral-600">Baca analisis dan kerangka kerja tentang teknologi, proses, dan organisasi. Setiap topik disusun untuk membantu Anda melihat persoalan sebelum menentukan langkah.</p>
        </div>
        <div class="border-l-2 border-[#a9bdc8] pl-5">
            <p class="text-3xl font-bold tabular-nums text-navy-950">{{ str_pad((string) $resources->total(), 2, '0', STR_PAD_LEFT) }}</p>
            <p class="mt-1 text-sm leading-6 text-neutral-600">topik tersedia dalam koleksi Whitepaper KIT.</p>
        </div>
    </div>
</section>

<div class="mx-auto max-w-7xl px-4 pb-20 sm:px-6">
    @if ($resources->isEmpty())
        <div class="py-24 text-center">
            <h2 class="text-2xl font-bold text-navy-950">Whitepaper segera hadir.</h2>
            <p class="mt-3 text-neutral-600">Sementara itu, jelajahi artikel dan panduan dari tim kami.</p>
            <a href="{{ route('blog.index') }}" class="mt-6 inline-block text-sm font-bold text-navy-950 transition-colors hover:text-brand-700 focus-visible:text-brand-700">Baca Blog →</a>
        </div>
    @else
        @if ($resources->currentPage() === 1)
            @php($featured = $resources->first())
            <article class="grid gap-9 border-b border-neutral-200 py-12 sm:py-16 lg:grid-cols-[minmax(0,1fr)_350px] lg:items-center lg:gap-16">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.17em] text-neutral-600">Sorotan / Whitepaper terbaru</p>
                    <h2 class="mt-5 max-w-3xl text-3xl font-bold leading-[1.15] tracking-tight text-navy-950 sm:text-5xl"><a href="{{ route('resources.whitepaper.show', $featured->slug) }}" class="hover:text-brand-700">{{ $featured->title }}</a></h2>
                    @if ($featured->excerpt)<p class="mt-5 max-w-xl text-[16px] leading-8 text-neutral-600">{{ $featured->excerpt }}</p>@endif
                    <p class="mt-6 text-sm text-neutral-500">{{ ($featured->published_at ?? $featured->created_at)?->translatedFormat('d F Y') }}</p>
                    @if (!empty($featured->toc))
                        <div class="mt-8 border-t border-neutral-200 pt-6">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-navy-950">Pokok bahasan</p>
                            <p class="mt-3 max-w-xl text-sm leading-7 text-neutral-600">{{ collect($featured->toc)->take(2)->implode(' · ') }}</p>
                        </div>
                    @endif
                    <a href="{{ route('resources.whitepaper.show', $featured->slug) }}" class="mt-8 inline-flex items-center gap-3 text-sm font-bold text-navy-950 transition-colors hover:text-brand-700 focus-visible:text-brand-700">Baca ringkasan <span aria-hidden="true">→</span></a>
                </div>
                <a href="{{ route('resources.whitepaper.show', $featured->slug) }}" aria-label="Baca {{ $featured->title }}" class="flex min-h-[390px] items-center justify-center bg-[#e9edef] px-8 py-10 sm:min-h-[470px]">
                    <x-whitepaper-cover :resource="$featured" :priority="true" class="w-[225px] sm:w-[275px]" />
                </a>
            </article>
        @endif

        @php($items = $resources->currentPage() === 1 ? $resources->getCollection()->skip(1) : $resources->getCollection())
        @if ($items->isNotEmpty())
            <div class="flex flex-wrap items-end justify-between gap-3 pt-12">
                <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-600">Koleksi</p><h2 class="mt-2 text-2xl font-bold text-navy-950 sm:text-3xl">Jelajahi topik lainnya</h2></div>
                <p class="text-sm text-neutral-500">Analisis untuk tim bisnis dan teknologi</p>
            </div>
            <div class="mt-8 border-t border-neutral-200">
                @foreach ($items as $resource)
                    <article class="grid gap-5 border-b border-neutral-200 py-7 sm:grid-cols-[88px_minmax(0,1fr)_auto] sm:items-center sm:gap-7">
                        <a href="{{ route('resources.whitepaper.show', $resource->slug) }}" aria-label="Baca {{ $resource->title }}"><x-whitepaper-cover :resource="$resource" :compact="true" class="w-[88px]" /></a>
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-neutral-600">Whitepaper <span class="mx-1 text-neutral-300">·</span> <span class="font-medium tracking-normal text-neutral-500">{{ ($resource->published_at ?? $resource->created_at)?->translatedFormat('d M Y') }}</span></p>
                            <h3 class="mt-2 text-lg font-bold leading-snug text-navy-950 sm:text-xl"><a href="{{ route('resources.whitepaper.show', $resource->slug) }}" class="hover:text-brand-700">{{ $resource->title }}</a></h3>
                            @if ($resource->excerpt)<p class="mt-2 max-w-2xl text-sm leading-6 text-neutral-600">{{ $resource->excerpt }}</p>@endif
                        </div>
                        <a href="{{ route('resources.whitepaper.show', $resource->slug) }}" class="text-sm font-bold text-navy-950 transition-colors hover:text-brand-700 focus-visible:text-brand-700">Lihat kajian →</a>
                    </article>
                @endforeach
            </div>
        @endif
        <div class="mt-10">{{ $resources->links() }}</div>
    @endif
</div>

<section class="border-t border-neutral-200 bg-[#f5f7f8] py-14">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-4 sm:px-6">
        <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-600">Dari wawasan ke tindakan</p><h2 class="mt-2 text-2xl font-bold text-navy-950 sm:text-3xl">Bahas tantangan yang Anda hadapi.</h2><p class="mt-2 text-sm text-neutral-600">Tim KIT siap membantu menilai kebutuhan dan menentukan prioritas teknologi.</p></div>
        <a href="{{ route('kontak') }}" class="inline-flex rounded-full bg-navy-950 px-7 py-3 text-sm font-bold text-white hover:bg-brand-700">Mulai diskusi →</a>
    </div>
</section>
@endsection
