@extends('layouts.site')
@section('title', 'Blog — KIT Konsultan IT')
@section('meta-description', 'Panduan praktis dari tim KIT Konsultan IT tentang software, keamanan, dan operasional teknologi.')
@section('content')
<section class="border-b border-neutral-200 bg-[#fafbfc]">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-700">Wawasan KIT</p>
        <h1 class="mt-4 max-w-4xl text-4xl font-extrabold leading-[1.08] tracking-tight text-navy-950 sm:text-6xl">Ide dan panduan untuk membangun digital yang lebih baik.</h1>
        <p class="mt-5 max-w-2xl text-base leading-8 text-neutral-600">Bacaan praktis untuk pemilik bisnis dan tim teknologi: dari merencanakan proyek hingga menjaga sistem tetap relevan setelah diluncurkan.</p>
    </div>
</section>
<div class="mx-auto max-w-7xl px-4 pb-20 sm:px-6">
    <div class="flex flex-col gap-5 border-b border-neutral-200 py-6 lg:flex-row lg:items-center lg:justify-between">
        <nav class="flex gap-2 overflow-x-auto pb-1" aria-label="Filter kategori blog">
            <a href="{{ route('blog.index') }}" class="shrink-0 rounded-full px-4 py-2 text-sm font-semibold {{ $activeCategory ? 'text-neutral-600 hover:bg-neutral-100' : 'bg-navy-950 text-white' }}">Semua</a>
            @foreach ($categories as $category)
                <a href="{{ route('blog.index', ['kategori' => $category->slug]) }}" class="shrink-0 rounded-full px-4 py-2 text-sm font-semibold {{ $activeCategory === $category->slug ? 'bg-navy-950 text-white' : 'text-neutral-600 hover:bg-neutral-100' }}">{{ $category->name }}</a>
            @endforeach
        </nav>
        <form action="{{ route('blog.index') }}" method="get" class="w-full lg:w-72">
            @if ($activeCategory)<input type="hidden" name="kategori" value="{{ $activeCategory }}">@endif
            <label for="blog-search" class="sr-only">Cari artikel</label>
            <input id="blog-search" type="search" name="q" value="{{ request('q') }}" placeholder="Cari artikel..." class="h-11 w-full rounded-full border border-neutral-200 bg-white px-5 text-sm outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/15">
        </form>
    </div>
    @if ($articles->isEmpty())
        <div class="py-24 text-center"><h2 class="text-2xl font-bold text-navy-950">Artikel belum ditemukan</h2><p class="mt-2 text-neutral-500">Coba kata kunci atau kategori lain.</p><a href="{{ route('blog.index') }}" class="mt-6 inline-block font-bold text-brand-700">Lihat semua artikel →</a></div>
    @else
        @php($featured = $articles->first())
        @php($featuredImage = $featured->featured_image_path ? (str_starts_with($featured->featured_image_path, 'img/') ? asset($featured->featured_image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($featured->featured_image_path)) : asset('img/editorial/insight-team.png'))
        <article class="grid gap-8 border-b border-neutral-200 py-10 lg:grid-cols-[minmax(0,1.25fr)_minmax(0,0.75fr)] lg:items-center lg:gap-14">
            <a href="{{ route('blog.show', $featured->slug) }}" class="group block overflow-hidden rounded-xl bg-neutral-100"><img src="{{ $featuredImage }}" alt="{{ $featured->title }}" class="aspect-[16/10] w-full object-cover transition duration-500 group-hover:scale-[1.025]" fetchpriority="high"></a>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-700">Pilihan Redaksi <span class="mx-2 text-neutral-300">/</span> {{ $featured->categoryName() }}</p>
                <h2 class="mt-4 text-3xl font-bold leading-tight tracking-tight text-navy-950 sm:text-4xl"><a href="{{ route('blog.show', $featured->slug) }}" class="hover:text-brand-700">{{ $featured->title }}</a></h2>
                <p class="mt-5 text-[15px] leading-7 text-neutral-600">{{ $featured->excerpt }}</p>
                <p class="mt-6 text-sm text-neutral-500">{{ ($featured->published_at ?? $featured->created_at)?->translatedFormat('d F Y') }} · {{ $featured->readingTime() }} menit baca</p>
                <a href="{{ route('blog.show', $featured->slug) }}" class="mt-7 inline-block text-sm font-bold text-brand-700">Baca artikel →</a>
            </div>
        </article>
        <div class="flex items-center justify-between pt-12"><h2 class="text-2xl font-bold text-navy-950 sm:text-3xl">Artikel terbaru</h2><span class="text-sm text-neutral-500">{{ $articles->total() }} artikel</span></div>
        <div class="mt-7 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($articles->skip(1) as $article)
                @php($image = $article->featured_image_path ? (str_starts_with($article->featured_image_path, 'img/') ? asset($article->featured_image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($article->featured_image_path)) : asset('img/editorial/insight-team.png'))
                <article class="group">
                    <a href="{{ route('blog.show', $article->slug) }}" class="block overflow-hidden rounded-lg bg-neutral-100"><img src="{{ $image }}" alt="{{ $article->title }}" class="aspect-[16/10] w-full object-cover transition duration-500 group-hover:scale-[1.025]" loading="lazy"></a>
                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.14em] text-brand-700">{{ $article->categoryName() }} <span class="mx-1 text-neutral-300">·</span> <span class="font-medium tracking-normal text-neutral-500">{{ ($article->published_at ?? $article->created_at)?->translatedFormat('d M Y') }}</span></p>
                    <h3 class="mt-2 text-xl font-bold leading-snug text-navy-950"><a href="{{ route('blog.show', $article->slug) }}" class="hover:text-brand-700">{{ $article->title }}</a></h3>
                    <p class="mt-3 text-sm leading-6 text-neutral-600">{{ \Illuminate\Support\Str::limit($article->excerpt, 145) }}</p>
                    <a href="{{ route('blog.show', $article->slug) }}" class="mt-4 inline-block text-sm font-bold text-brand-700">Baca selengkapnya →</a>
                </article>
            @endforeach
        </div>
        <div class="mt-14">{{ $articles->links() }}</div>
    @endif
</div>
@endsection
