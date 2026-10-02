@extends('layouts.site')

@section('title', 'Daftar — KIT Konsultan IT')
@section('meta-description', 'Buat akun KIT Konsultan IT untuk mengelola kerja sama dengan tim kami.')

@section('content')
<section class="py-20 sm:py-28">
    <div class="mx-auto max-w-md px-4 sm:px-6">
        <div class="text-center">
            <a href="{{ url('/') }}" class="text-xl font-extrabold tracking-tight text-navy-900">KIT Konsultan IT</a>
            <h1 class="mt-6 text-3xl font-extrabold tracking-tight text-balance text-navy-950">Buat akun baru</h1>
            <p class="mt-2 text-[15px] text-neutral-500">Gratis, cukup semenit.</p>
        </div>

        <form method="post" action="{{ route('register') }}" class="mt-10 space-y-5">
            @csrf

            <div>
                <label for="name" class="text-sm font-bold text-navy-950">Nama lengkap</label>
                <input id="name" name="name" type="text" required autofocus autocomplete="name" maxlength="100" value="{{ old('name') }}" placeholder="Nama Anda"
                    class="mt-2 w-full rounded-xl border px-4 py-3 text-[15px] placeholder:text-neutral-400 focus:outline-none focus:ring-2 @error('name') border-red-600 focus:ring-red-200 @else border-neutral-300 focus:border-navy-700 focus:ring-navy-100 @enderror">
                @error('name')
                    <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="text-sm font-bold text-navy-950">Alamat email</label>
                <input id="email" name="email" type="email" required autocomplete="email" maxlength="255" value="{{ old('email') }}" placeholder="nama@perusahaan.co.id"
                    class="mt-2 w-full rounded-xl border px-4 py-3 text-[15px] placeholder:text-neutral-400 focus:outline-none focus:ring-2 @error('email') border-red-600 focus:ring-red-200 @else border-neutral-300 focus:border-navy-700 focus:ring-navy-100 @enderror">
                @error('email')
                    <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="text-sm font-bold text-navy-950">Kata sandi</label>
                <div class="relative mt-2">
                    <input id="password" name="password" type="password" required autocomplete="new-password" minlength="8" placeholder="Minimal 8 karakter"
                        class="w-full rounded-xl border border-neutral-300 py-3 pl-4 pr-12 text-[15px] placeholder:text-neutral-400 focus:border-navy-700 focus:outline-none focus:ring-2 focus:ring-navy-100 @error('password') border-red-600 focus:ring-red-200 @enderror">
                    <button type="button" data-pw-toggle aria-controls="password" aria-pressed="false" aria-label="Tampilkan kata sandi"
                        class="absolute inset-y-0 right-0 flex w-12 items-center justify-center rounded-r-xl text-neutral-400 transition hover:text-navy-800">
                        <svg data-eye class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg data-eye-off class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="text-sm font-bold text-navy-950">Ulangi kata sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" minlength="8" placeholder="Ketik ulang kata sandi"
                    class="mt-2 w-full rounded-xl border border-neutral-300 px-4 py-3 text-[15px] placeholder:text-neutral-400 focus:border-navy-700 focus:outline-none focus:ring-2 focus:ring-navy-100">
            </div>

            <button type="submit" class="w-full rounded-xl bg-navy-800 py-3.5 text-sm font-bold text-white transition hover:bg-navy-900">
                Daftar
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-neutral-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-navy-700 hover:underline">Masuk</a>
        </p>
    </div>
</section>
@endsection
