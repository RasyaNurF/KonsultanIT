@extends('layouts.site')

@section('title', $solution->title.' — Nusakode')
@section('meta-description', \Illuminate\Support\Str::limit($solution->excerpt ?? $solution->subtitle ?? $solution->title, 160))

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="grid gap-12 py-20 sm:py-28 lg:grid-cols-[minmax(0,1.6fr)_minmax(0,1fr)]">
                <div class="min-w-0 max-w-3xl">
                    <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.24em] text-navy-950">
                        <a href="{{route('solusi.index')}}" class="transition hover:text-brand-700">Solusi</a>
                        <span class="text-neutral-300" aria-hidden="true">/</span>
                        <a href="{{route('solusi.category',$category->slug)}}" class="transition hover:text-brand-700">{{$category->name}}</a>
                    </p>
                    <p class="mt-6 inline-flex w-fit items-center rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] {{ $solution->partner_name ? 'bg-neutral-100 text-neutral-500' : 'bg-brand-50 text-brand-700' }}">
                        {{ $solution->partner_name ? 'Mitra: '.$solution->partner_name : 'Layanan terkelola' }}
                    </p>
                    <h1 class="mt-3 text-4xl font-extrabold leading-[1.05] tracking-tight text-balance text-navy-950 sm:text-5xl">{{$solution->title}}</h1>
                    @if($solution->subtitle)
                        <p class="mt-4 text-lg font-semibold text-neutral-500">{{$solution->subtitle}}</p>
                    @endif
                    @if($solution->excerpt)
                        <p class="mt-6 max-w-xl text-base leading-relaxed text-neutral-500">{{$solution->excerpt}}</p>
                    @endif
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{$solution->cta_url?:route('kontak')}}" class="group inline-flex items-center gap-2 rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">
                            {{$solution->cta_label?:'Diskusikan kebutuhan ini'}}
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                        <a href="{{route('solusi.category',$category->slug)}}" class="inline-flex items-center gap-2 rounded-full px-7 py-3.5 text-sm font-bold text-navy-950 ring-1 ring-neutral-300 transition hover:ring-navy-800">Lihat kategori</a>
                    </div>
                </div>

                <div class="flex items-center lg:justify-end">
                    <div class="w-full max-w-sm rounded-2xl border border-neutral-200 bg-neutral-50 p-10 text-center">
                        @if($solution->logo_path)
                            <img src="{{\Illuminate\Support\Facades\Storage::disk('public')->url($solution->logo_path)}}" alt="Logo {{$solution->title}}" class="mx-auto h-16 max-w-[70%] object-contain">
                        @elseif($solution->partner_name)
                            <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-navy-950 text-lg font-extrabold text-white">{{mb_strtoupper(mb_substr($solution->partner_name,0,2))}}</span>
                        @else
                            <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-navy-950 text-white"><svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg></span>
                        @endif
                        <p class="mt-5 text-[13px] font-bold uppercase tracking-[0.18em] text-neutral-400">{{$solution->partner_name?'Produk mitra '.$solution->partner_name:'Layanan terkelola Nusakode'}}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($solution->cover_image_path)
        <section aria-label="Ilustrasi {{$solution->title}}">
            <img src="{{str_starts_with($solution->cover_image_path,'img/')?asset($solution->cover_image_path):\Illuminate\Support\Facades\Storage::disk('public')->url($solution->cover_image_path)}}" alt="{{$solution->title}}" class="h-72 w-full object-cover sm:h-[420px]" loading="lazy">
        </section>
    @endif

    @if(!empty($solution->features)||!empty($solution->benefits)||$solution->body)
        <section class="border-t border-neutral-100">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20">
                <div class="grid gap-12 lg:grid-cols-[1.6fr_1fr]">
                    <div>
                        @if($solution->body)
                            <div class="text-[15px] leading-relaxed text-neutral-600">{!!nl2br(e($solution->body))!!}</div>
                        @endif

                        @if(!empty($solution->features))
                            <div class="{{$solution->body?'mt-12':''}}">
                                <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-navy-950">Fitur</h2>
                                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                                    @foreach($solution->features as $feature)
                                        @php($featureText = is_array($feature)?($feature['text']??''):$feature)
                                        @if($featureText)
                                            <div class="flex items-start gap-3 rounded-xl border border-neutral-200 bg-white p-4">
                                                <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-600" aria-hidden="true"></span>

                                                <span class="text-sm leading-relaxed text-neutral-600">{{$featureText}}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($solution->benefits))
                            <div class="mt-12">
                                <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-navy-950">Manfaat</h2>
                                <ul class="mt-5 space-y-3">
                                    @foreach($solution->benefits as $benefit)
                                        @php($benefitText = is_array($benefit)?($benefit['text']??''):$benefit)
                                        @if($benefitText)
                                            <li class="flex gap-3 text-[15px] leading-relaxed text-neutral-600"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-600" aria-hidden="true"></span><span>{{$benefitText}}</span></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <aside class="lg:sticky lg:top-32 lg:h-fit">
                        <div class="rounded-2xl border border-neutral-200 bg-navy-950 p-8 text-white">
                            <h2 class="text-lg font-extrabold tracking-tight">Tertarik dengan solusi ini?</h2>
                            <p class="mt-2 text-[14px] leading-relaxed text-neutral-300">Bicarakan kebutuhan Anda dengan tim kami. Kami susun penawaran tertulis sesuai lingkup Anda.</p>
                            <a href="{{route('kontak')}}" class="group mt-6 inline-flex items-center gap-2 rounded-full bg-brand-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-brand-500">Mulai Konsultasi<svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
    @endif

    @if($related->isNotEmpty())
        <section class="border-t border-neutral-100 py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6">
                <h2 class="text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl">Solusi lain di {{$category->name}}</h2>
                <div class="mt-10 grid gap-px overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-200 sm:grid-cols-3">
                    @foreach($related as $item)
                        <a href="{{route('solusi.show',[$category->slug,$item->slug])}}" class="group bg-white p-8 transition hover:bg-neutral-50">
                            @if($item->partner_name)
                                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-neutral-400">{{$item->partner_name}}</p>
                            @endif
                            <h3 class="mt-2 text-lg font-bold tracking-tight text-navy-950 transition group-hover:text-brand-700">{{$item->title}}</h3>
                            @if($item->excerpt)
                                <p class="mt-2 text-sm leading-relaxed text-neutral-500">{{\Illuminate\Support\Str::limit($item->excerpt, 120)}}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if(isset($more)&&$more->isNotEmpty())
        <section class="border-t border-neutral-100 bg-neutral-50/60 py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6">
                <h2 class="text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl">Jelajahi solusi lainnya</h2>
                <div class="mt-10 grid gap-px overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-200 sm:grid-cols-3">
                    @foreach($more as $item)
                        <a href="{{route('solusi.show',[$item->category->slug,$item->slug])}}" class="group bg-white p-8 transition hover:bg-neutral-50">
                            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-brand-700">{{$item->category->name}}</p>
                            <h3 class="mt-2 text-lg font-bold tracking-tight text-navy-950 transition group-hover:text-brand-700">{{$item->title}}</h3>
                            @if($item->excerpt)
                                <p class="mt-2 text-sm leading-relaxed text-neutral-500">{{\Illuminate\Support\Str::limit($item->excerpt, 120)}}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
