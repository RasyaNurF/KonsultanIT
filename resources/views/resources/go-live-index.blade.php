@extends('layouts.site')
@section('title', 'Go-Live — KIT Konsultan IT')
@section('meta-description', 'Cerita implementasi solusi digital dan pendekatan kerja KIT Konsultan IT.')
@section('content')
<section class="border-b border-neutral-200 bg-[#fafbfc]">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-700">Resources / Go-Live</p>
        <h1 class="mt-4 max-w-4xl text-4xl font-extrabold leading-[1.08] tracking-tight text-navy-950 sm:text-6xl">Dari rencana menjadi sistem yang digunakan.</h1>
        <p class="mt-5 max-w-2xl text-base leading-8 text-neutral-600">Jelajahi pendekatan implementasi digital: kebutuhan yang dihadapi, proses yang dirancang, dan cara solusi dapat diterapkan.</p>
        @if ($resources->contains(fn ($item) => str_starts_with($item->title, 'Contoh ')))
            <p class="mt-5 max-w-2xl border-l-2 border-brand-500 pl-4 text-sm leading-6 text-neutral-500">Konten berlabel “Contoh” adalah skenario ilustratif untuk pratinjau, bukan dokumentasi klien atau hasil proyek yang telah diverifikasi.</p>
        @endif
    </div>
</section>

<div class="mx-auto max-w-7xl px-4 pb-20 sm:px-6">
    @if ($resources->isEmpty())
        <div class="py-24 text-center">
            <h2 class="text-2xl font-bold text-navy-950">Cerita implementasi segera hadir.</h2>
            <p class="mt-3 text-neutral-500">Kunjungi kembali halaman ini untuk melihat proyek terbaru.</p>
            <a href="{{ route('kontak') }}" class="mt-7 inline-block text-sm font-bold text-brand-700">Diskusikan proyek Anda →</a>
        </div>
    @else
        @php($featured = $resources->first())
        @php($featuredPath = $featured->cover_image_path ?: data_get($featured->gallery, '0'))
        @php($featuredImage = $featuredPath ? (str_starts_with($featuredPath, 'img/') ? asset($featuredPath) : \Illuminate\Support\Facades\Storage::disk('public')->url($featuredPath)) : asset('img/editorial/news-workspace.png'))
        <article class="grid gap-8 border-b border-neutral-200 py-10 lg:grid-cols-[minmax(0,1.25fr)_minmax(0,0.75fr)] lg:items-center lg:gap-14">
            <a href="{{ route('resources.go-live.show', $featured->slug) }}" class="group block overflow-hidden rounded-xl bg-neutral-100">
                <img src="{{ $featuredImage }}" alt="Ilustrasi {{ $featured->title }}" class="aspect-[16/10] w-full object-cover transition duration-500 group-hover:scale-[1.025]" fetchpriority="high">
            </a>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-700">Sorotan Implementasi <span class="mx-2 text-neutral-300">/</span> {{ $featured->industry ?: 'Digital' }}</p>
                <h2 class="mt-4 text-3xl font-bold leading-tight tracking-tight text-navy-950 sm:text-4xl"><a href="{{ route('resources.go-live.show', $featured->slug) }}" class="hover:text-brand-700">{{ $featured->title }}</a></h2>
                @if ($featured->excerpt)<p class="mt-5 text-[15px] leading-7 text-neutral-600">{{ $featured->excerpt }}</p>@endif
                <p class="mt-6 text-sm text-neutral-500">{{ ($featured->published_at ?? $featured->created_at)?->translatedFormat('d F Y') }}</p>
                <a href="{{ route('resources.go-live.show', $featured->slug) }}" class="mt-7 inline-block text-sm font-bold text-brand-700">Lihat cerita implementasi →</a>
            </div>
        </article>

        <div class="flex flex-wrap items-end justify-between gap-3 pt-12">
            <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-700">Eksplorasi proyek</p><h2 class="mt-2 text-2xl font-bold text-navy-950 sm:text-3xl">Implementasi lainnya</h2></div>
            <span class="text-sm text-neutral-500">{{ $resources->total() }} cerita</span>
        </div>
        <div class="mt-7 grid gap-x-8 gap-y-12 sm:grid-cols-2">
            @foreach ($resources->skip(1) as $resource)
                @php($imagePath = $resource->cover_image_path ?: data_get($resource->gallery, '0'))
                @php($image = $imagePath ? (str_starts_with($imagePath, 'img/') ? asset($imagePath) : \Illuminate\Support\Facades\Storage::disk('public')->url($imagePath)) : asset('img/editorial/news-workspace.png'))
                <article class="group border-b border-neutral-200 pb-8">
                    <a href="{{ route('resources.go-live.show', $resource->slug) }}" class="block overflow-hidden rounded-lg bg-neutral-100"><img src="{{ $image }}" alt="Ilustrasi {{ $resource->title }}" class="aspect-[16/9] w-full object-cover transition duration-500 group-hover:scale-[1.025]" loading="lazy"></a>
                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.14em] text-brand-700">{{ $resource->industry ?: 'Implementasi Digital' }} <span class="mx-1 text-neutral-300">·</span> <span class="font-medium tracking-normal text-neutral-500">{{ ($resource->published_at ?? $resource->created_at)?->translatedFormat('d M Y') }}</span></p>
                    <h3 class="mt-2 text-xl font-bold leading-snug text-navy-950 sm:text-2xl"><a href="{{ route('resources.go-live.show', $resource->slug) }}" class="hover:text-brand-700">{{ $resource->title }}</a></h3>
                    @if ($resource->excerpt)<p class="mt-3 max-w-xl text-sm leading-6 text-neutral-600">{{ \Illuminate\Support\Str::limit($resource->excerpt, 160) }}</p>@endif
                    <a href="{{ route('resources.go-live.show', $resource->slug) }}" class="mt-4 inline-block text-sm font-bold text-brand-700">Baca ceritanya →</a>
                </article>
            @endforeach
        </div>
        <div class="mt-14">{{ $resources->links() }}</div>
    @endif
</div>
<section class="bg-[#f2f6fa] py-14">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-4 sm:px-6">
        <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-700">Proyek berikutnya</p><h2 class="mt-2 text-2xl font-bold text-navy-950 sm:text-3xl">Punya tantangan yang ingin diselesaikan?</h2><p class="mt-2 text-sm text-neutral-600">Ceritakan kebutuhan Anda dan temukan langkah yang tepat bersama tim kami.</p></div>
        <a href="{{ route('kontak') }}" class="inline-flex rounded-full bg-navy-950 px-7 py-3 text-sm font-bold text-white transition hover:bg-brand-700">Mulai diskusi →</a>
    </div>
</section>
@endsection
