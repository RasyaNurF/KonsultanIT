@extends('layouts.site')

@section('title', 'Atur Ulang Kata Sandi — KIT Konsultan IT')
@section('meta-description', 'Buat kata sandi baru untuk akun KIT Konsultan IT Anda.')

@section('content')
<section class="py-20 sm:py-28">
    <div class="mx-auto max-w-md px-4 sm:px-6">
        <div class="text-center">
            <a href="{{ url('/') }}" class="text-xl font-extrabold tracking-tight text-navy-900">KIT Konsultan IT</a>
            <h1 class="mt-6 text-3xl font-extrabold tracking-tight text-balance text-navy-950">Buat kata sandi baru</h1>
            <p class="mt-2 text-[15px] text-neutral-500">Minimal 8 karakter.</p>
        </div>

        <form method="post" action="{{ route('password.update') }}" class="mt-10 space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="text-sm font-bold text-navy-950">Alamat email</label>
                <input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email', $email) }}"
                    class="mt-2 w-full rounded-xl border px-4 py-3 text-[15px] focus:outline-none focus:ring-2 @error('email') border-red-600 focus:ring-red-200 @else border-neutral-300 focus:border-navy-700 focus:ring-navy-100 @enderror">
                @error('email')
                    <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="text-sm font-bold text-navy-950">Kata sandi baru</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" minlength="8"
                    class="mt-2 w-full rounded-xl border border-neutral-300 px-4 py-3 text-[15px] focus:border-navy-700 focus:outline-none focus:ring-2 focus:ring-navy-100 @error('password') border-red-600 focus:ring-red-200 @enderror">
                @error('password')
                    <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="text-sm font-bold text-navy-950">Ulangi kata sandi baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" minlength="8"
                    class="mt-2 w-full rounded-xl border border-neutral-300 px-4 py-3 text-[15px] focus:border-navy-700 focus:outline-none focus:ring-2 focus:ring-navy-100">
            </div>

            <button type="submit" class="w-full rounded-xl bg-navy-800 py-3.5 text-sm font-bold text-white transition hover:bg-navy-900">
                Simpan Kata Sandi Baru
            </button>
        </form>
    </div>
</section>
@endsection
