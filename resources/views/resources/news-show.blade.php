@extends('layouts.site')
@section('title', $resource->title.' — KIT Konsultan IT')
@section('meta-description', \Illuminate\Support\Str::limit($resource->excerpt ?: $resource->title, 160))
@if ($resource->cover_image_path)
    @section('og-image', str_starts_with($resource->cover_image_path, 'img/') ? asset($resource->cover_image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($resource->cover_image_path))
@endif
@section('content')
@php($image = $resource->cover_image_path ? (str_starts_with($resource->cover_image_path, 'img/') ? asset($resource->cover_image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($resource->cover_image_path)) : asset('img/editorial/insight-team.png'))
<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16">
    <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_300px] lg:gap-16">
        <article class="min-w-0">
            <nav class="text-xs font-bold uppercase tracking-[0.16em] text-brand-700" aria-label="Breadcrumb"><a href="{{ route('resources.news') }}" class="hover:underline">News</a><span class="mx-2 text-neutral-300">/</span><span class="text-neutral-500">Artikel</span></nav>
            <h1 class="mt-5 text-3xl font-extrabold leading-[1.14] tracking-tight text-navy-950 sm:text-5xl">{{ $resource->title }}</h1>
            <p class="mt-5 text-sm text-neutral-500">{{ ($resource->published_at ?? $resource->created_at)?->translatedFormat('d F Y') }} <span class="mx-2">·</span> Tim KIT Konsultan IT</p>
            <figure class="mt-8"><img src="{{ $image }}" alt="{{ $resource->title }}" class="aspect-[16/9] w-full rounded-xl object-cover" fetchpriority="high"></figure>
            @if ($resource->excerpt)<p class="mt-10 text-xl font-medium leading-8 text-navy-950">{{ $resource->excerpt }}</p>@endif
            <div class="mt-8 space-y-6 text-[16px] leading-8 text-neutral-700">
                @foreach (preg_split('/\R{2,}/', trim((string) $resource->body)) ?: [] as $paragraph)
                    @if (trim($paragraph) !== '')<p>{{ trim($paragraph) }}</p>@endif
                @endforeach
            </div>
            <div class="mt-12 border-t border-neutral-200 pt-6 text-sm text-neutral-500">Diterbitkan oleh <span class="font-semibold text-navy-950">KIT Konsultan IT</span></div>
            <div class="mt-10 rounded-xl bg-[#f2f6fa] p-7 sm:p-9">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-700">Mari berdiskusi</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-950">Punya kebutuhan digital yang serupa?</h2>
                <p class="mt-3 text-sm leading-6 text-neutral-600">Ceritakan tantangan bisnis Anda. Tim kami siap membantu memetakan langkah yang tepat.</p>
                <a href="{{ route('kontak') }}" class="mt-6 inline-flex rounded-full bg-navy-950 px-6 py-3 text-sm font-bold text-white hover:bg-brand-700">Hubungi Kami →</a>
            </div>
        </article>
        <aside class="border-t border-neutral-200 pt-8 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0">
            <h2 class="text-sm font-bold uppercase tracking-[0.14em] text-navy-950">Berita terbaru</h2>
            <div class="mt-5 space-y-6">
                @foreach ($related as $item)
                    <a href="{{ route('resources.news.show', $item->slug) }}" class="group block border-b border-neutral-200 pb-6">
                        <span class="text-xs text-neutral-500">{{ ($item->published_at ?? $item->created_at)?->translatedFormat('d M Y') }}</span>
                        <span class="mt-2 block text-[15px] font-bold leading-snug text-navy-950 group-hover:text-brand-700">{{ $item->title }}</span>
                    </a>
                @endforeach
            </div>
            <a href="{{ route('resources.news') }}" class="mt-5 inline-block text-sm font-bold text-brand-700">Lihat semua berita →</a>
        </aside>
    </div>
</div>
@endsection
