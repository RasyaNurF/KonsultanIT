@extends('layouts.site')

@section('title', $category->name.' — Nusakode')
@section('meta-description', \Illuminate\Support\Str::limit($category->description ?? $category->tagline ?? $category->name, 160))

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-3xl py-20 sm:py-28">
                <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.24em] text-navy-950">
                    <a href="{{route('solusi.index')}}" class="transition hover:text-brand-700">Solusi</a>
                    <span class="text-neutral-300" aria-hidden="true">/</span>
                    <span class="text-neutral-400">{{$category->name}}</span>
                </p>
                <span class="mt-8 block h-px w-16 bg-brand-600" aria-hidden="true"></span>

                <h1 class="mt-6 text-4xl font-extrabold leading-[1.05] tracking-tight text-balance text-navy-950 sm:text-5xl">{{$category->name}}</h1>
                @if($category->tagline)
                    <p class="mt-4 text-sm font-bold uppercase tracking-[0.18em] text-brand-700">{{$category->tagline}}</p>
                @endif
                @if($category->description)
                    <p class="mt-6 max-w-xl text-base leading-relaxed text-neutral-500">{{$category->description}}</p>
                @endif
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{route('kontak')}}" class="group inline-flex items-center gap-2 rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">Konsultasi Kategori Ini<svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
                    <span class="text-[13px] font-semibold text-neutral-400">{{$solutions->count()}} solusi dalam kategori ini</span>
                </div>
            </div>
        </div>
    </section>

    @if($category->image_path)
        <section aria-label="Ilustrasi {{$category->name}}">
            <img src="{{str_starts_with($category->image_path,'img/')?asset($category->image_path):\Illuminate\Support\Facades\Storage::disk('public')->url($category->image_path)}}" alt="{{$category->name}}" class="h-72 w-full object-cover sm:h-[420px]" loading="lazy">
        </section>
    @endif

    @if($solutions->isEmpty())
        <section class="border-t border-neutral-100">
            <div class="mx-auto max-w-3xl px-4 py-20 text-center sm:px-6 sm:py-28">
                <p class="text-lg font-bold text-navy-950">Solusi pada kategori ini segera hadir.</p>
            </div>
        </section>
    @else
        <section class="border-t border-neutral-100">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($solutions as $solution)
                        <a href="{{route('solusi.show',[$category->slug,$solution->slug])}}" class="group flex flex-col overflow-hidden rounded-2xl border border-neutral-200 bg-white transition hover:-translate-y-1 hover:border-brand-200 hover:shadow-xl hover:shadow-navy-950/5">
                            <div class="relative h-40 overflow-hidden bg-neutral-100">
                                @if($solution->cover_image_path)
                                    <img src="{{str_starts_with($solution->cover_image_path,'img/')?asset($solution->cover_image_path):\Illuminate\Support\Facades\Storage::disk('public')->url($solution->cover_image_path)}}" alt="{{$solution->title}}" class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-navy-950"><span class="text-sm font-bold uppercase tracking-[0.2em] text-white/25">{{$solution->partner_name?:$category->name}}</span></div>
                                @endif
                                @if($solution->logo_path)
                                    <img src="{{\Illuminate\Support\Facades\Storage::disk('public')->url($solution->logo_path)}}" alt="Logo {{$solution->title}}" class="absolute bottom-4 left-4 h-10 max-w-[60%] rounded-lg border border-neutral-200 bg-white object-contain px-2 py-1.5">
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col p-6">
                                <p class="inline-flex w-fit items-center rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.16em] {{$solution->partner_name?'bg-neutral-100 text-neutral-500':'bg-brand-50 text-brand-700'}}">{{$solution->partner_name?'Mitra: '.$solution->partner_name:'Layanan terkelola'}}</p>
                                <h2 class="mt-2 text-lg font-bold tracking-tight text-navy-950 transition group-hover:text-brand-700">{{$solution->title}}</h2>
                                @if($solution->excerpt)
                                    <p class="mt-2 flex-1 text-sm leading-relaxed text-neutral-500">{{\Illuminate\Support\Str::limit($solution->excerpt, 120)}}</p>
                                @endif
                                @if(!empty($solution->features))
                                    <ul class="mt-4 space-y-1.5">
                                        @foreach(array_slice($solution->features,0,3) as $feature)
                                            <li class="flex gap-2 text-[13px] text-neutral-500"><span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-brand-500" aria-hidden="true"></span><span>{{is_array($feature)?($feature['text']??''):$feature}}</span></li>
                                        @endforeach
                                    </ul>
                                @endif
                                <p class="mt-5 inline-flex items-center gap-2 text-[13px] font-bold text-navy-800 transition group-hover:text-brand-700">Selengkapnya<svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="bg-navy-900 py-16 text-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-4 sm:px-6">
            <div class="max-w-xl">
                <h2 class="text-2xl font-extrabold tracking-tight text-balance sm:text-3xl">Tertarik dengan {{$category->name}}?</h2>
                <p class="mt-2 text-[15px] text-neutral-300">Ceritakan kebutuhan Anda, kami susun penawaran tertulis.</p>
            </div>
            <a href="{{route('kontak')}}" class="rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">Diskusikan Kebutuhan</a>
        </div>
    </section>
@endsection
