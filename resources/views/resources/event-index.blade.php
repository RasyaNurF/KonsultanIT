@extends('layouts.site')
@section('title', 'Event — KIT Konsultan IT')
@section('meta-description', 'Agenda diskusi, webinar, dan kegiatan teknologi dari KIT Konsultan IT.')
@section('content')
<section class="border-b border-neutral-200 bg-[#f8f9f7]">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-700">Event / KIT Konsultan IT</p>
        <h1 class="mt-5 max-w-4xl text-4xl font-extrabold leading-[1.08] tracking-tight text-navy-950 sm:text-6xl">Ruang bertemu, belajar, dan bertukar gagasan.</h1>
        <p class="mt-6 max-w-2xl text-base leading-8 text-neutral-600">Temukan agenda diskusi tentang teknologi dan cara kerjanya dalam bisnis. Lihat topik, jadwal, dan informasi setiap kegiatan dalam satu tempat.</p>
    </div>
</section>

<div class="mx-auto max-w-7xl px-4 pb-20 sm:px-6">
    @if ($resources->isEmpty())
        <div class="py-24 text-center">
            <h2 class="text-2xl font-bold text-navy-950">Belum ada agenda yang dipublikasikan.</h2>
            <p class="mt-3 text-neutral-600">Sementara itu, Anda bisa menjelajahi artikel dan panduan kami.</p>
            <a href="{{ route('blog.index') }}" class="mt-6 inline-block text-sm font-bold text-navy-950 hover:text-brand-700">Baca Blog →</a>
        </div>
    @else
        @if ($resources->currentPage() === 1)
            @php($featured = $resources->first())
            @php($featuredIsSample = str_starts_with($featured->title, 'Contoh:'))
            <article class="grid gap-9 border-b border-neutral-200 py-12 sm:py-16 lg:grid-cols-[minmax(0,1fr)_300px] lg:items-center lg:gap-16">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-600">Sorotan agenda <span class="mx-2 text-neutral-300">/</span> {{ $featuredIsSample ? 'Contoh kegiatan' : ($featured->starts_at ? ($featured->isUpcoming() ? 'Akan datang' : ($featured->isPast() ? 'Telah berlangsung' : 'Sedang berlangsung')) : 'Jadwal menyusul') }}</p>
                    <h2 class="mt-5 max-w-3xl text-3xl font-bold leading-[1.14] tracking-tight text-navy-950 sm:text-5xl"><a href="{{ route('resources.events.show', $featured->slug) }}" class="hover:text-brand-700">{{ $featured->title }}</a></h2>
                    @if ($featured->excerpt)<p class="mt-5 max-w-2xl text-[16px] leading-8 text-neutral-600">{{ $featured->excerpt }}</p>@endif
                    <div class="mt-7 flex flex-wrap gap-x-5 gap-y-2 text-sm text-neutral-600">
                        @if ($featured->starts_at)<span>{{ $featured->starts_at->locale('id')->translatedFormat('l, d F Y') }}</span>@endif
                        @if ($featured->location)<span>{{ $featured->location }}</span>@endif
                    </div>
                    @if ($featuredIsSample)<p class="mt-5 max-w-xl border-l-2 border-[#a9bdc8] pl-4 text-sm leading-6 text-neutral-600">Agenda ini merupakan contoh konsep kegiatan. Jadwal dan pendaftaran belum dikonfirmasi.</p>@endif
                    <a href="{{ route('resources.events.show', $featured->slug) }}" class="mt-8 inline-flex text-sm font-bold text-navy-950 transition-colors hover:text-brand-700">Lihat detail acara →</a>
                </div>
                <a href="{{ route('resources.events.show', $featured->slug) }}" aria-label="Lihat {{ $featured->title }}" class="flex min-h-[340px] items-center justify-center bg-[#e9edef] p-8 sm:min-h-[390px]">
                    <x-event-date :resource="$featured" class="w-[230px] shadow-[0_18px_38px_-28px_rgba(12,29,45,0.45)]" />
                </a>
            </article>
        @endif

        @php($items = $resources->currentPage() === 1 ? $resources->getCollection()->skip(1) : $resources->getCollection())
        @if ($items->isNotEmpty())
            <div class="flex flex-wrap items-end justify-between gap-3 pt-12">
                <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-600">Kegiatan</p><h2 class="mt-2 text-2xl font-bold text-navy-950 sm:text-3xl">Jelajahi agenda lainnya</h2></div>
                <p class="text-sm text-neutral-500">Diskusi, webinar, dan kegiatan bersama</p>
            </div>
            <div class="mt-8 border-t border-neutral-200">
                @foreach ($items as $resource)
                    @php($isSample = str_starts_with($resource->title, 'Contoh:'))
                    <article class="grid grid-cols-[76px_minmax(0,1fr)] gap-5 border-b border-neutral-200 py-7 sm:grid-cols-[92px_minmax(0,1fr)_auto] sm:items-center sm:gap-7">
                        <a href="{{ route('resources.events.show', $resource->slug) }}" aria-label="Lihat {{ $resource->title }}"><x-event-date :resource="$resource" :compact="true" class="w-full" /></a>
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-neutral-600">{{ $isSample ? 'Contoh kegiatan' : ($resource->starts_at ? ($resource->isUpcoming() ? 'Akan datang' : ($resource->isPast() ? 'Telah berlangsung' : 'Sedang berlangsung')) : 'Jadwal menyusul') }} @if ($resource->location)<span class="mx-1 text-neutral-300">·</span> <span class="font-medium normal-case tracking-normal text-neutral-500">{{ $resource->location }}</span>@endif</p>
                            <h3 class="mt-2 text-lg font-bold leading-snug text-navy-950 sm:text-xl"><a href="{{ route('resources.events.show', $resource->slug) }}" class="hover:text-brand-700">{{ $resource->title }}</a></h3>
                            @if ($resource->excerpt)<p class="mt-2 max-w-2xl text-sm leading-6 text-neutral-600">{{ $resource->excerpt }}</p>@endif
                        </div>
                        <a href="{{ route('resources.events.show', $resource->slug) }}" class="col-start-2 text-sm font-bold text-navy-950 hover:text-brand-700 sm:col-auto">Lihat acara →</a>
                    </article>
                @endforeach
            </div>
        @endif
        <div class="mt-10">{{ $resources->links() }}</div>
    @endif
</div>

<section class="border-t border-neutral-200 bg-[#f5f7f8] py-14">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-4 sm:px-6">
        <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-600">Punya topik untuk dibahas?</p><h2 class="mt-2 text-2xl font-bold text-navy-950 sm:text-3xl">Mari mulai percakapan.</h2><p class="mt-2 text-sm text-neutral-600">Ceritakan tantangan teknologi yang ingin Anda diskusikan bersama tim kami.</p></div>
        <a href="{{ route('kontak') }}" class="inline-flex rounded-full bg-navy-950 px-7 py-3 text-sm font-bold text-white hover:bg-brand-700">Hubungi tim →</a>
    </div>
</section>
@endsection
