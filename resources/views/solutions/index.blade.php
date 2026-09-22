@extends('layouts.site')

@section('title', 'Solusi — Nusakode')
@section('meta-description', 'Kategori solusi Nusakode: Human Capital Management, CRM & Customer Experience, Infrastruktur TI, IT Security, ERP & Business Intelligence.')

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-3xl py-20 sm:py-28">
                <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.24em] text-navy-950"><span class="h-px w-10 bg-brand-600" aria-hidden="true"></span>Solusi</p>
                <h1 class="mt-6 text-4xl font-extrabold leading-[1.05] tracking-tight text-balance text-navy-950 sm:text-6xl">Produk mitra untuk setiap tantangan bisnis.</h1>
                <p class="mt-6 max-w-xl text-base leading-relaxed text-neutral-500">Solusi adalah produk mitra teknologi global yang kami implementasikan, integrasikan, dan dukung — dikelompokkan per kebutuhan bisnis. Untuk jasa pengembangan custom, lihat <a href="{{ route('layanan.index') }}" class="font-semibold text-brand-700 underline underline-offset-2 transition hover:text-brand-600">Layanan</a>.</p>
                <a href="{{ route('layanan.index') }}" class="group mt-8 inline-flex items-center gap-2 text-sm font-bold text-navy-950 transition hover:text-brand-700">Lihat layanan kami<svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>

                <div class="mt-10 flex flex-wrap items-center gap-x-10 gap-y-4">
                    <div>
                        <p class="text-3xl font-extrabold tabular-nums text-navy-950">{{$categories->count()}}</p>
                        <p class="mt-1 text-xs font-bold uppercase tracking-[0.16em] text-neutral-400">Kategori</p>
                    </div>
                    <span class="hidden h-10 w-px bg-neutral-200 sm:block" aria-hidden="true"></span>
                    <div>
                        <p class="text-3xl font-extrabold tabular-nums text-navy-950">{{$solutionCount}}</p>
                        <p class="mt-1 text-xs font-bold uppercase tracking-[0.16em] text-neutral-400">Solusi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($featured->isNotEmpty())
        <section class="border-t border-neutral-100 bg-neutral-50/60">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.22em] text-brand-700"><span class="h-px w-8 bg-brand-500" aria-hidden="true"></span>Solusi unggulan</p>
                        <h2 class="mt-4 text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl">Paling banyak diminta klien</h2>
                    </div>
                </div>
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($featured as $item)
                        <a href="{{route('solusi.show',[$item->category->slug,$item->slug])}}" class="group relative flex flex-col overflow-hidden rounded-2xl border border-neutral-200 bg-white p-8 transition hover:-translate-y-1 hover:border-brand-200 hover:shadow-xl hover:shadow-navy-950/5">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-brand-50 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.14em] text-brand-700">Unggulan</span>
                                <span class="inline-flex w-fit items-center rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.14em] {{$item->partner_name?'bg-neutral-100 text-neutral-500':'bg-navy-950 text-white'}}">{{$item->partner_name?'Mitra: '.$item->partner_name:'Layanan terkelola'}}</span>
                            </div>
                            <h3 class="mt-5 text-xl font-extrabold tracking-tight text-navy-950 transition group-hover:text-brand-700">{{$item->title}}</h3>
                            @if($item->subtitle)
                                <p class="mt-1 text-[13px] font-semibold text-neutral-400">{{$item->subtitle}}</p>
                            @endif
                            @if($item->excerpt)
                                <p class="mt-3 flex-1 text-sm leading-relaxed text-neutral-500">{{\Illuminate\Support\Str::limit($item->excerpt, 130)}}</p>
                            @endif
                            <p class="mt-6 inline-flex items-center gap-2 text-[13px] font-bold text-navy-800">{{$item->category->name}}<svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($categories->isEmpty())
        <section class="border-t border-neutral-100">
            <div class="mx-auto max-w-3xl px-4 py-20 text-center sm:px-6 sm:py-28">
                <p class="text-lg font-bold text-navy-950">Daftar solusi segera hadir.</p>
                <a href="{{route('kontak')}}" class="mt-8 inline-flex items-center gap-2 rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">Hubungi Kami</a>
            </div>
        </section>
    @else
        <section class="border-t border-neutral-100">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20">
                <div class="max-w-2xl">
                    <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.22em] text-brand-700"><span class="h-px w-8 bg-brand-500" aria-hidden="true"></span>Kategori</p>
                    <h2 class="mt-4 text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl">Telusuri berdasarkan kebutuhan</h2>
                </div>

                <div class="mt-10 grid gap-6 lg:grid-cols-2">
                    @foreach($categories as $category)
                        <div class="group flex flex-col rounded-2xl border border-neutral-200 bg-white p-8 transition hover:border-brand-200 hover:shadow-lg hover:shadow-navy-950/5">
                            <div class="flex items-start justify-between gap-4">
                                <span class="text-3xl font-extrabold tabular-nums leading-none text-neutral-200">{{str_pad($loop->iteration,2,'0',STR_PAD_LEFT)}}</span>
                                <span class="rounded-full bg-neutral-100 px-3 py-1 text-[11px] font-bold text-neutral-500">{{$category->solutions->count()}} solusi</span>

                            </div>
                            <h3 class="mt-5 text-xl font-extrabold tracking-tight text-navy-950"><a href="{{route('solusi.category',$category->slug)}}" class="transition hover:text-brand-700">{{$category->name}}</a></h3>
                            @if($category->tagline)
                                <p class="mt-1.5 text-[13px] font-semibold text-brand-700">{{$category->tagline}}</p>
                            @endif
                            @if($category->description)
                                <p class="mt-3 text-[15px] leading-relaxed text-neutral-500">{{\Illuminate\Support\Str::limit($category->description, 160)}}</p>
                            @endif

                            @if($category->solutions->isNotEmpty())
                                <ul class="mt-5 flex flex-wrap gap-2">
                                    @foreach($category->solutions->take(4) as $solution)
                                        <li><a href="{{route('solusi.show',[$category->slug,$solution->slug])}}" class="inline-flex rounded-full bg-neutral-100 px-3 py-1.5 text-xs font-semibold text-neutral-600 transition hover:bg-brand-50 hover:text-brand-700">{{$solution->title}}</a></li>
                                    @endforeach
                                </ul>
                            @endif

                            <a href="{{route('solusi.category',$category->slug)}}" class="group/link mt-6 inline-flex items-center gap-2 text-[13px] font-bold text-navy-800 transition hover:text-brand-700">Lihat kategori<svg class="h-4 w-4 transition-transform group-hover/link:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="bg-navy-900 py-16 text-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-4 sm:px-6">
            <div class="max-w-xl">
                <h2 class="text-2xl font-extrabold tracking-tight text-balance sm:text-3xl">Butuh kombinasi solusi yang tepat?</h2>
                <p class="mt-2 text-[15px] text-neutral-300">Kami bantu petakan kebutuhan dan menyusun penawaran tertulis.</p>
            </div>
            <a href="{{route('kontak')}}" class="rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">Diskusikan Kebutuhan</a>
        </div>
    </section>
@endsection
