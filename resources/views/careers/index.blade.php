@extends('layouts.site')

@section('title', 'Karier — KIT Konsultan IT')
@section('meta-description', 'Karier di KIT Konsultan IT: bergabung dengan tim teknologi yang membangun sistem perusahaan Indonesia.')

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-3xl py-20 sm:py-28">
                <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.24em] text-navy-950"><span class="h-px w-10 bg-brand-600" aria-hidden="true"></span>Karier</p>
                <h1 class="mt-6 text-4xl font-extrabold leading-[1.05] tracking-tight text-balance text-navy-950 sm:text-6xl">Bangun sistem penting bersama kami.</h1>
                <p class="mt-6 max-w-xl text-base leading-relaxed text-neutral-500">Kami mencari engineer dan desainer yang rapi, komunikatif, dan bertanggung jawab pada hasil.</p>
            </div>
        </div>
    </section>

    <section class="border-t border-neutral-100 py-20 sm:py-28">
        <div class="mx-auto max-w-5xl px-4 sm:px-6">
            @if ($careers->isEmpty())
                <div class="border border-dashed border-neutral-300 px-6 py-20 text-center">
                    <p class="text-lg font-bold text-navy-950">Belum ada lowongan dibuka.</p>
                    <p class="mt-2 text-sm text-neutral-500">Silakan kembali lagi atau kirim profil Anda ke halo@nusakode.id.</p>
                </div>
            @else
                <div class="divide-y divide-neutral-200 border-y border-neutral-200">
                    @foreach ($careers as $career)
                        <a href="{{ route('karier.show', $career->slug) }}" class="group grid gap-2 py-7 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-center sm:gap-8 sm:px-2">
                            <span>
                                <span class="text-xs font-bold uppercase tracking-[0.18em] text-neutral-400">{{ $career->department ?: 'KIT Konsultan IT' }}{{ $career->location ? ' · '.$career->location : '' }}</span>
                                <span class="mt-1.5 block text-xl font-bold tracking-tight text-navy-950 transition group-hover:text-brand-700">{{ $career->title }}</span>
                                <span class="mt-1 block text-[13px] text-neutral-500">{{ $career->employment_type ?: 'Full-time' }}{{ $career->is_remote ? ' · Remote' : '' }}{{ $career->deadline ? ' · Tutup '.$career->deadline->translatedFormat('d M Y') : '' }}</span>
                            </span>
                            <svg class="hidden h-5 w-5 text-neutral-300 transition-all group-hover:translate-x-1 group-hover:text-navy-800 sm:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    @endforeach
                </div>
                <div class="mt-12">
                    {{ $careers->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
