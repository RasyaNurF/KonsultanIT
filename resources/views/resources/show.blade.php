@extends('layouts.site')

@section('title', $resource->title.' — KIT Konsultan IT')
@section('meta-description', \Illuminate\Support\Str::limit($resource->excerpt ?? $resource->title, 160))

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-3xl py-20 sm:py-28">
                <p class="flex flex-wrap items-center gap-3 text-xs font-bold uppercase tracking-[0.24em] text-navy-950">
                    <a href="{{route('resources.type',$resource->type->value)}}" class="transition hover:text-brand-700">{{$resource->type->label()}}</a>

                </p>
                <h1 class="mt-6 text-4xl font-extrabold leading-[1.05] tracking-tight text-balance text-navy-950 sm:text-5xl">{{$resource->title}}</h1>
                @if($resource->excerpt)
                    <p class="mt-6 max-w-xl text-base leading-relaxed text-neutral-500">{{$resource->excerpt}}</p>
                @endif
                <div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-2 text-[13px] font-semibold text-neutral-400">
                    <span>{{$resource->published_at?->translatedFormat('d F Y')?:'—'}}</span>
                    @if($resource->location)<span>{{$resource->location}}</span>@endif
                    @if($resource->organizer)<span>{{$resource->organizer}}</span>@endif
                    @if($resource->starts_at)<span>{{$resource->starts_at->translatedFormat('d M Y H:i')}}@if($resource->ends_at) – {{$resource->ends_at->translatedFormat('d M Y H:i')}}@endif</span>@endif
                </div>
                @php($ctaUrl = $resource->cta_url ?: $resource->external_url ?: $resource->file_path)
                @if($ctaUrl)
                    <div class="mt-8">
                        <a href="{{$resource->file_path? \Illuminate\Support\Facades\Storage::disk('public')->url($resource->file_path):$ctaUrl}}" @if($resource->file_path||$resource->external_url) target="_blank" rel="noopener" @endif class="group inline-flex items-center gap-2 rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">
                            {{$resource->cta_label?:($resource->file_path?'Unduh':'Selengkapnya')}}
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if($resource->cover_image_path)
        <section aria-label="Ilustrasi {{$resource->title}}">
            <img src="{{str_starts_with($resource->cover_image_path,'img/')?asset($resource->cover_image_path):\Illuminate\Support\Facades\Storage::disk('public')->url($resource->cover_image_path)}}" alt="{{$resource->title}}" class="h-72 w-full object-cover sm:h-[420px]" loading="lazy">
        </section>
    @endif

    @if($resource->body)
        <section class="border-t border-neutral-100">
            <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 sm:py-20">
                <div class="text-[15px] leading-relaxed text-neutral-600">{!!nl2br(e($resource->body))!!}</div>
            </div>
        </section>
    @endif

        @if(!empty($resource->metrics) || !empty($resource->agenda) || !empty($resource->speakers) || !empty($resource->toc) || !empty($resource->chapters) || !empty($resource->gallery))
        <section class="border-t border-neutral-100">
            <div class="mx-auto max-w-5xl px-4 py-16 sm:px-6 sm:py-20 space-y-14">

                @if(!empty($resource->metrics))
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-navy-950">Hasil terukur</h2>
                        <div class="mt-5 grid gap-4 sm:grid-cols-3">
                            @foreach($resource->metrics as $metric)
                                <div class="rounded-2xl border border-neutral-200 bg-white p-6 text-center">
                                    <p class="text-3xl font-extrabold tabular-nums text-navy-950">{{$metric['value'] ?? '—'}}</p>
                                    <p class="mt-1 text-xs font-semibold uppercase tracking-[0.14em] text-neutral-500">{{$metric['label'] ?? ''}}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!empty($resource->agenda))
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-navy-950">Agenda</h2>
                        <ol class="mt-5 divide-y divide-neutral-200 border-y border-neutral-200">
                            @foreach($resource->agenda as $item)
                                <li class="flex gap-6 py-4">
                                    <span class="w-16 shrink-0 text-sm font-bold tabular-nums text-brand-700">{{$item['time'] ?? ''}}</span>
                                    <span>
                                        <span class="block font-bold text-navy-950">{{$item['title'] ?? ''}}</span>
                                        @if(!empty($item['description']))<span class="mt-1 block text-sm text-neutral-500">{{$item['description']}}</span>@endif
                                    </span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                @if(!empty($resource->speakers))
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-navy-950">Pembicara</h2>
                        <div class="mt-5 grid gap-6 sm:grid-cols-3">
                            @foreach($resource->speakers as $speaker)
                                <div class="text-center">
                                    @if(!empty($speaker['photo']))
                                        <img src="{{str_starts_with($speaker['photo'],'img/')?asset($speaker['photo']):\Illuminate\Support\Facades\Storage::disk('public')->url($speaker['photo'])}}" alt="{{$speaker['name'] ?? ''}}" class="mx-auto h-24 w-24 rounded-full object-cover" loading="lazy">
                                    @else
                                        <span class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-navy-950 text-lg font-bold text-white">{{mb_strtoupper(mb_substr($speaker['name'] ?? '?',0,2))}}</span>
                                    @endif
                                    <p class="mt-3 font-bold text-navy-950">{{$speaker['name'] ?? ''}}</p>
                                    <p class="text-sm text-neutral-500">{{$speaker['position'] ?? ''}}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!empty($resource->toc))
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-navy-950">Daftar isi</h2>
                        <ul class="mt-5 space-y-3">
                            @foreach($resource->toc as $item)
                                <li class="flex gap-3 text-[15px] text-neutral-600"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-600" aria-hidden="true"></span><span>{{$item}}</span></li>
                            @endforeach
                        </ul>
                        @if($resource->page_count)<p class="mt-4 text-sm text-neutral-400">{{$resource->page_count}} halaman</p>@endif
                    </div>
                @endif

                @if(!empty($resource->chapters))
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-navy-950">Daftar bab</h2>
                        <ol class="mt-5 space-y-5">
                            @foreach($resource->chapters as $index => $chapter)
                                <li class="flex gap-4">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-navy-950 text-sm font-bold text-white">{{$index+1}}</span>
                                    <span>
                                        <span class="block font-bold text-navy-950">{{$chapter['title'] ?? ''}}</span>
                                        @if(!empty($chapter['description']))<span class="mt-1 block text-sm text-neutral-500">{{$chapter['description']}}</span>@endif
                                    </span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                @if(!empty($resource->gallery))
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-navy-950">Dokumentasi</h2>
                        <div class="mt-5 grid gap-4 sm:grid-cols-3">
                            @foreach($resource->gallery as $photo)
                                <img src="{{str_starts_with($photo,'img/')?asset($photo):\Illuminate\Support\Facades\Storage::disk('public')->url($photo)}}" alt="Dokumentasi {{$resource->title}}" class="h-48 w-full rounded-2xl object-cover" loading="lazy">
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($resource->recording_url)
                    <div>
                        <a href="{{$resource->recording_url}}" target="_blank" rel="noopener" class="group inline-flex items-center gap-2 rounded-full bg-navy-950 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-navy-800">Lihat Rekaman<svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
                    </div>
                @endif

            </div>
        </section>
    @endif

@if($related->isNotEmpty())

        <section class="border-t border-neutral-100 py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6">
                <h2 class="text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl">{{$resource->type->label()}} lainnya</h2>
                <div class="mt-10 grid gap-px overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-200 sm:grid-cols-3">
                    @foreach($related as $item)
                        <a href="{{route('resources.show',[$resourceType->value,$item->slug])}}"
 class="group bg-white p-8 transition hover:bg-neutral-50">
                            <h3 class="text-lg font-bold tracking-tight text-navy-950 transition group-hover:text-brand-700">{{$item->title}}</h3>
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
