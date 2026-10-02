@extends('layouts.site')
@section('title', 'News — KIT Konsultan IT')
@section('meta-description', 'Kabar, pembaruan, dan catatan terbaru dari KIT Konsultan IT.')
@section('content')
<section class="border-b border-neutral-200 bg-[#fafbfc]">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-700">Resources / News</p>
        <h1 class="mt-4 max-w-3xl text-4xl font-extrabold leading-[1.08] tracking-tight text-navy-950 sm:text-6xl">Kabar dan perspektif terbaru.</h1>
        <p class="mt-5 max-w-2xl text-base leading-8 text-neutral-600">Ikuti perkembangan seputar teknologi, layanan digital, dan cara kerja kami dalam membantu bisnis bertumbuh.</p>
    </div>
</section>
<div class="mx-auto max-w-7xl px-4 pb-20 sm:px-6">
    @if ($resources->isEmpty())
        <div class="py-24 text-center"><h2 class="text-2xl font-bold text-navy-950">Belum ada berita.</h2><p class="mt-2 text-neutral-500">Kunjungi kembali halaman ini nanti.</p></div>
    @else
        @php($featured = $resources->first())
        @php($featuredImage = $featured->cover_image_path ? (str_starts_with($featured->cover_image_path, 'img/') ? asset($featured->cover_image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($featured->cover_image_path)) : asset('img/editorial/insight-team.png'))
        <article class="grid gap-8 border-b border-neutral-200 py-10 lg:grid-cols-[minmax(0,1.25fr)_minmax(0,0.75fr)] lg:items-center lg:gap-14">
            <a href="{{ route('resources.news.show', $featured->slug) }}" class="group block overflow-hidden rounded-xl bg-neutral-100"><img src="{{ $featuredImage }}" alt="{{ $featured->title }}" class="aspect-[16/10] w-full object-cover transition duration-500 group-hover:scale-[1.025]" fetchpriority="high"></a>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-700">Sorotan News</p>
                <h2 class="mt-4 text-3xl font-bold leading-tight tracking-tight text-navy-950 sm:text-4xl"><a href="{{ route('resources.news.show', $featured->slug) }}" class="hover:text-brand-700">{{ $featured->title }}</a></h2>
                <p class="mt-5 text-[15px] leading-7 text-neutral-600">{{ $featured->excerpt }}</p>
                <p class="mt-6 text-sm text-neutral-500">{{ ($featured->published_at ?? $featured->created_at)?->translatedFormat('d F Y') }}</p>
                <a href="{{ route('resources.news.show', $featured->slug) }}" class="mt-7 inline-block text-sm font-bold text-brand-700">Baca selengkapnya →</a>
            </div>
        </article>
        <div class="flex items-center justify-between pt-12"><h2 class="text-2xl font-bold text-navy-950 sm:text-3xl">Berita lainnya</h2><span class="text-sm text-neutral-500">{{ $resources->total() }} tulisan</span></div>
        <div class="mt-7 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($resources->skip(1) as $resource)
                @php($image = $resource->cover_image_path ? (str_starts_with($resource->cover_image_path, 'img/') ? asset($resource->cover_image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($resource->cover_image_path)) : asset('img/editorial/insight-team.png'))
                <article class="group">
                    <a href="{{ route('resources.news.show', $resource->slug) }}" class="block overflow-hidden rounded-lg bg-neutral-100"><img src="{{ $image }}" alt="{{ $resource->title }}" class="aspect-[16/10] w-full object-cover transition duration-500 group-hover:scale-[1.025]" loading="lazy"></a>
                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.14em] text-brand-700">News <span class="mx-1 text-neutral-300">·</span> <span class="font-medium tracking-normal text-neutral-500">{{ ($resource->published_at ?? $resource->created_at)?->translatedFormat('d M Y') }}</span></p>
                    <h3 class="mt-2 text-xl font-bold leading-snug text-navy-950"><a href="{{ route('resources.news.show', $resource->slug) }}" class="hover:text-brand-700">{{ $resource->title }}</a></h3>
                    <p class="mt-3 text-sm leading-6 text-neutral-600">{{ \Illuminate\Support\Str::limit($resource->excerpt, 145) }}</p>
                    <a href="{{ route('resources.news.show', $resource->slug) }}" class="mt-4 inline-block text-sm font-bold text-brand-700">Baca selengkapnya →</a>
                </article>
            @endforeach
        </div>
        <div class="mt-14">{{ $resources->links() }}</div>
    @endif
</div>
@endsection
