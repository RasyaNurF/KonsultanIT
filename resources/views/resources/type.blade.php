@extends('layouts.site')

@php
    $meta = match($resourceType) {
        \App\Enums\ResourceType::Event => [
            'eyebrow' => 'Event',
            'title' => 'Event dan webinar mendatang.',
            'description' => 'Ikuti sesi langsung bersama tim KIT Konsultan IT dan mitra teknologi untuk membahas tantangan nyata di lapangan.',
            'accent' => 'text-brand-400',
        ],
        \App\Enums\ResourceType::Whitepaper => [
            'eyebrow' => 'Whitepaper',
            'title' => 'Laporan dan panduan mendalam.',
            'description' => 'Riset dan kerangka kerja yang bisa langsung Anda gunakan untuk mengambil keputusan teknologi.',
            'accent' => 'text-sky-400',
        ],
        \App\Enums\ResourceType::Ebook => [
            'eyebrow' => 'E-book',
            'title' => 'Panduan praktis untuk tim Anda.',
            'description' => 'Materi belajar bertahap yang dirancang untuk tim internal maupun pengambil keputusan.',
            'accent' => 'text-violet-400',
        ],
        \App\Enums\ResourceType::News => [
            'eyebrow' => 'News',
            'title' => 'Kabar terbaru dari KIT Konsultan IT.',
            'description' => 'Kemitraan, pencapaian, dan pengumuman terbaru seputar perusahaan dan ekosistem kami.',
            'accent' => 'text-emerald-400',
        ],
        \App\Enums\ResourceType::GoLive => [
            'eyebrow' => 'Go-Live',
            'title' => 'Cerita implementasi yang berjalan.',
            'description' => 'Dokumentasi proyek yang telah mengudara: lingkup, tantangan, dan hasil yang terukur.',
            'accent' => 'text-amber-400',
        ],
        default => [
            'eyebrow' => 'Resources',
            'title' => $resourceType->label(),
            'description' => 'Kumpulan resource dari KIT Konsultan IT.',
            'accent' => 'text-brand-400',
        ],
    };
@endphp

@section('title', $meta['eyebrow'].' — KIT Konsultan IT')
@section('meta-description', \Illuminate\Support\Str::limit($meta['description'], 160))

@section('content')
    <section class="bg-navy-950 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-3xl py-20 sm:py-28">
                <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.24em]"><span class="h-px w-10 bg-brand-500" aria-hidden="true"></span>{{$meta['eyebrow']}}</p>
                <h1 class="mt-6 text-4xl font-extrabold leading-[1.05] tracking-tight text-balance sm:text-6xl">{{$meta['title']}}</h1>
                <p class="mt-6 max-w-xl text-base leading-relaxed text-neutral-300">{{$meta['description']}}</p>
                <div class="mt-8 flex flex-wrap items-center gap-x-8 gap-y-3">
                    <span class="text-sm font-semibold text-neutral-400">{{$resources->total()}} {{$meta['eyebrow']}}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="border-b border-neutral-100 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <nav class="flex flex-wrap gap-2 py-6" aria-label="Kategori resources">
                @foreach($types as $type)
                    <a href="{{route('resources.type',$type->value)}}" class="rounded-full px-4 py-2 text-[13px] font-bold transition {{$type===$resourceType?'bg-navy-950 text-white':'text-neutral-500 ring-1 ring-neutral-200 hover:text-navy-950 hover:ring-neutral-300'}}">{{$type->label()}}</a>
                @endforeach
            </nav>
        </div>
    </section>

    @if($resources->isEmpty())
        <section>
            <div class="mx-auto max-w-3xl px-4 py-20 text-center sm:px-6 sm:py-28">
                <p class="text-lg font-bold text-navy-950">Belum ada {{$meta['eyebrow']}}.</p>
                <p class="mt-2 text-sm text-neutral-500">Kembali lagi nanti atau jelajahi kategori lain.</p>
                <a href="{{route('blog.index')}}" class="mt-8 inline-flex items-center gap-2 rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">Baca Blog</a>
            </div>
        </section>
    @else
        <section>
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20">
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($resources as $resource)
                        <article class="group flex flex-col">
                            <a href="{{route('resources.show',[$resourceType->value,$resource->slug])}}" class="block overflow-hidden rounded-2xl bg-neutral-100">
                                @if($resource->cover_image_path)
                                    <img src="{{str_starts_with($resource->cover_image_path,'img/')?asset($resource->cover_image_path):\Illuminate\Support\Facades\Storage::disk('public')->url($resource->cover_image_path)}}" alt="{{$resource->title}}" class="h-52 w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy">
                                @else
                                    <div class="flex h-52 w-full items-center justify-center bg-navy-900"><span class="text-sm font-bold uppercase tracking-[0.2em] text-white/30">{{$resource->type->label()}}</span></div>
                                @endif
                            </a>
                            <div class="mt-5 flex flex-1 flex-col">
                                @if($resource->location||$resource->starts_at)
                                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-brand-700">{{$resource->starts_at?->translatedFormat('d M Y')}}@if($resource->location) · {{$resource->location}}@endif</p>
                                @else
                                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-brand-700">{{$resource->type->label()}}</p>
                                @endif
                                <h2 class="mt-2 text-lg font-bold leading-snug tracking-tight text-navy-950"><a href="{{route('resources.show',[$resourceType->value,$resource->slug])}}" class="transition hover:text-brand-700">{{$resource->title}}</a></h2>
                                @if($resource->excerpt)
                                    <p class="mt-2 flex-1 text-sm leading-relaxed text-neutral-500">{{\Illuminate\Support\Str::limit($resource->excerpt, 140)}}</p>
                                @endif
                                                                <p class="mt-4 text-xs font-semibold text-neutral-400">{{$resource->published_at?->translatedFormat('d F Y')?:'—'}}</p>
                                <a href="{{route('resources.show',[$resourceType->value,$resource->slug])}}" class="mt-4 inline-flex items-center gap-2 text-[13px] font-bold text-navy-800 transition hover:text-brand-700">Selengkapnya<svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
                            </div>

                        </article>
                    @endforeach
                </div>
            </div>
        </section>
        <div class="mx-auto max-w-7xl px-4 pb-20 sm:px-6">{{$resources->links()}}</div>
    @endif

    <section class="bg-navy-900 py-16 text-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-4 sm:px-6">
            <div class="max-w-xl">
                <h2 class="text-2xl font-extrabold tracking-tight text-balance sm:text-3xl">Ingin membahas topik ini lebih lanjut?</h2>
                <p class="mt-2 text-[15px] text-neutral-300">Tim kami siap berdiskusi sesuai kebutuhan Anda.</p>
            </div>
            <a href="{{route('kontak')}}" class="rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">Hubungi Kami</a>
        </div>
    </section>
@endsection
