@extends('layouts.site')

@section('title', 'Halaman tidak ditemukan — KIT Konsultan IT')
@section('meta-description', 'Halaman yang Anda cari tidak ditemukan.')

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-3xl px-4 py-24 text-center sm:px-6 sm:py-32">
            <p class="text-6xl font-extrabold tracking-tight text-navy-100">404</p>
            <h1 class="mt-6 text-3xl font-extrabold tracking-tight text-balance text-navy-950 sm:text-4xl">Halaman tidak ditemukan.</h1>
            <p class="mx-auto mt-4 max-w-md text-[15px] leading-relaxed text-neutral-500">Tautan yang Anda buka mungkin sudah dipindahkan atau dihapus. Mari kembali ke halaman utama.</p>
            <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ url('/') }}" class="rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">Kembali ke Beranda</a>
                <a href="{{ route('kontak') }}" class="rounded-full border border-neutral-300 px-8 py-3.5 text-sm font-bold text-navy-950 transition hover:border-navy-950">Hubungi Kami</a>
            </div>
        </div>
    </section>
@endsection
