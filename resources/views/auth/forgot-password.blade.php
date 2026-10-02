@extends('layouts.site')

@section('title', 'Lupa Kata Sandi — KIT Konsultan IT')
@section('meta-description', 'Minta tautan atur ulang kata sandi akun KIT Konsultan IT Anda.')

@section('content')
<section class="py-20 sm:py-28">
    <div class="mx-auto max-w-md px-4 sm:px-6">
        <div class="text-center">
            <a href="{{ url('/') }}" class="text-xl font-extrabold tracking-tight text-navy-900">KIT Konsultan IT</a>
            <h1 class="mt-6 text-3xl font-extrabold tracking-tight text-balance text-navy-950">Lupa kata sandi?</h1>
            <p class="mt-2 text-[15px] text-neutral-500">Masukkan email akun Anda — kami kirim tautan atur ulang.</p>
        </div>

        @if (session('status'))
            <p class="mt-8 border border-emerald-600/30 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800" role="status">{{ session('status') }}</p>
        @endif

        <form method="post" action="{{ route('password.email') }}" class="mt-8 space-y-5">
            @csrf

            <div>
                <label for="email" class="text-sm font-bold text-navy-950">Alamat email</label>
                <input id="email" name="email" type="email" required autofocus autocomplete="email" value="{{ old('email') }}" placeholder="nama@perusahaan.co.id"
                    class="mt-2 w-full rounded-xl border border-neutral-300 px-4 py-3 text-[15px] placeholder:text-neutral-400 focus:border-navy-700 focus:outline-none focus:ring-2 focus:ring-navy-100">
            </div>

            <button type="submit" class="w-full rounded-xl bg-navy-800 py-3.5 text-sm font-bold text-white transition hover:bg-navy-900">
                Kirim Tautan Atur Ulang
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-neutral-500">
            <a href="{{ route('login') }}" class="font-semibold text-navy-700 hover:underline">Kembali masuk</a>
        </p>
    </div>
</section>
@endsection
