@extends('layouts.site')

@section('title', 'Dashboard — KIT Konsultan IT')
@section('meta-description', 'Ringkasan akun dan percakapan KIT Konsultan IT Anda.')

@section('content')
@php($user = auth()->user())

{{-- ============ HERO ============ --}}
<section class="relative overflow-hidden bg-navy-950 pb-28 pt-16 sm:pt-20">
    <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-brand-600/25 blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -right-16 top-8 h-72 w-72 rounded-full bg-brand-500/10 blur-3xl" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6" data-no-reveal>
        <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.24em] text-brand-400">
            <span class="h-px w-10 bg-brand-500" aria-hidden="true"></span>
            Dashboard Akun
        </p>

        <div class="mt-7 flex flex-col gap-7 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <span class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-white/10 text-lg font-extrabold text-white ring-1 ring-white/15">
                    @if ($user->avatarUrl())
                        <img src="{{ $user->avatarUrl() }}" alt="" class="h-full w-full object-cover">
                    @else
                        {{ $user->initials() }}
                    @endif
                </span>
                <div class="min-w-0">
                    <h1 class="truncate text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Halo, {{ $user->name }}</h1>
                    <p class="mt-1 truncate text-sm text-white/60">{{ $user->email }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="button" data-chat-toggle
                    class="group inline-flex items-center gap-2 rounded-full bg-brand-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-brand-500">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.5 11.6a8.4 8.4 0 0 1-12.2 7.5L3.5 20.5l1.5-5.2a8.4 8.4 0 1 1 15.5-3.7Z"/></svg>
                    Buka Pesan
                    @if ($unread > 0)
                        <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-white px-1.5 text-[11px] font-bold text-brand-700">{{ $unread > 9 ? '9+' : $unread }}</span>
                    @endif
                </button>
                <a href="{{ route('kontak') }}" class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-bold text-white ring-1 ring-white/30 transition hover:bg-white/10">Hubungi Kami</a>
            </div>
        </div>
    </div>
</section>

{{-- ============ STATISTIK ============ --}}
<section class="relative -mt-16">
    <div class="mx-auto max-w-6xl px-4 sm:px-6" data-no-reveal>
        <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm shadow-navy-950/5">
                <dt class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.14em] text-neutral-500">
                    <x-admin.icon name="message" class="h-4 w-4 text-brand-600" /> Total Pesan
                </dt>
                <dd class="mt-3 text-3xl font-extrabold tracking-tight text-navy-950">{{ $messageCount }}</dd>
            </div>
            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm shadow-navy-950/5">
                <dt class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.14em] text-neutral-500">
                    <x-admin.icon name="bell" class="h-4 w-4 text-brand-600" /> Belum Dibaca
                </dt>
                <dd class="mt-3 text-3xl font-extrabold tracking-tight text-navy-950">{{ $unread }}</dd>
            </div>
            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm shadow-navy-950/5">
                <dt class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.14em] text-neutral-500">
                    <x-admin.icon name="calendar" class="h-4 w-4 text-brand-600" /> Aktivitas Terakhir
                </dt>
                <dd class="mt-3 text-lg font-extrabold leading-tight text-navy-950">{{ $participant?->last_message_at?->diffForHumans() ?? 'Belum ada' }}</dd>
            </div>
            <div class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm shadow-navy-950/5">
                <dt class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.14em] text-neutral-500">
                    <x-admin.icon name="check" class="h-4 w-4 text-brand-600" /> Status Akun
                </dt>
                <dd class="mt-3 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500" aria-hidden="true"></span>
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </dd>
            </div>
        </dl>
    </div>
</section>

{{-- ============ KONTEN UTAMA ============ --}}
<section class="py-12 sm:py-16">
    <div class="mx-auto grid max-w-6xl gap-8 px-4 sm:px-6 lg:grid-cols-[1.6fr_1fr]" data-no-reveal>
        <div class="space-y-8">
            {{-- Percakapan terakhir --}}
            <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
                <div class="flex items-center justify-between gap-4 border-b border-neutral-100 px-6 py-5">
                    <div>
                        <h2 class="text-base font-extrabold text-navy-950">Percakapan Terakhir</h2>
                        <p class="mt-0.5 text-xs text-neutral-500">Balasan tim KIT Konsultan IT muncul otomatis di sini.</p>
                    </div>
                    <button type="button" data-chat-toggle class="shrink-0 rounded-full bg-navy-950 px-4 py-2 text-[13px] font-bold text-white transition hover:bg-navy-800">Buka</button>
                </div>

                @if ($messages->isEmpty())
                    <div class="px-6 py-14 text-center">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-neutral-100 text-neutral-400">
                            <x-admin.icon name="message" class="h-6 w-6" />
                        </span>
                        <p class="mt-4 text-sm font-bold text-navy-950">Belum ada percakapan.</p>
                        <p class="mt-1 text-sm text-neutral-500">Mulai diskusi dengan tim kami — kami biasanya membalas cepat.</p>
                        <button type="button" data-chat-toggle class="mt-6 inline-flex items-center gap-2 rounded-full bg-brand-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-brand-500">
                            Kirim Pesan Pertama
                        </button>
                    </div>
                @else
                    <ul class="divide-y divide-neutral-100">
                        @foreach ($messages as $message)
                            @php($isOwn = $message->sender !== 'admin')
                            <li class="flex items-start gap-3 px-6 py-4">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-[11px] font-bold {{ $isOwn ? 'bg-brand-100 text-brand-700' : 'bg-navy-950 text-white' }}">
                                    @if ($isOwn)
                                        {{ $user->initials() }}
                                    @else
                                        <x-admin.icon name="message" class="h-4 w-4" />
                                    @endif
                                </span>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-baseline justify-between gap-3">
                                        <p class="text-[13px] font-bold text-navy-950">{{ $isOwn ? 'Anda' : ($message->name ?: 'Tim KIT Konsultan IT') }}</p>
                                        <span class="shrink-0 text-[11px] text-neutral-400">{{ $message->created_at->diffForHumans(short: true) }}</span>
                                    </div>
                                    <p class="mt-0.5 line-clamp-2 text-sm text-neutral-600">{{ $message->body }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Bacaan untuk Anda --}}
            @if ($insights->isNotEmpty())
                <div>
                    <div class="flex items-end justify-between gap-4">
                        <h2 class="text-base font-extrabold text-navy-950">Bacaan untuk Anda</h2>
                        <a href="{{ route('blog.index') }}" class="text-[13px] font-bold text-brand-600 transition hover:text-brand-700">Lihat semua artikel â†’</a>
                    </div>

                    <div class="mt-4 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($insights as $article)
                            @php($img = $article->featured_image_path ? (str_starts_with($article->featured_image_path, 'img/') ? asset($article->featured_image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($article->featured_image_path)) : null)
                            <a href="{{ route('blog.show', $article->slug) }}" class="group flex flex-col overflow-hidden rounded-2xl border border-neutral-200 bg-white transition hover:border-brand-300 hover:shadow-md">
                                <span class="block overflow-hidden bg-neutral-100">
                                    @if ($img)
                                        <img src="{{ $img }}" alt="{{ $article->title }}" class="aspect-[16/10] w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy">
                                    @else
                                        <span class="flex aspect-[16/10] w-full items-center justify-center bg-navy-950 text-[10px] font-bold uppercase tracking-[0.2em] text-white/30">{{ $article->categoryName() }}</span>
                                    @endif
                                </span>
                                <span class="flex flex-1 flex-col p-4">
                                    <span class="text-[11px] font-bold uppercase tracking-[0.16em] text-brand-700">{{ $article->categoryName() }}</span>
                                    <span class="mt-2 line-clamp-3 text-sm font-bold leading-snug text-navy-950 transition group-hover:text-brand-700">{{ $article->title }}</span>
                                    <span class="mt-3 text-[11px] text-neutral-400">{{ ($article->published_at ?? $article->created_at)?->translatedFormat('d M Y') }}</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar: akses cepat + detail akun --}}
        <aside class="space-y-6">
            <div class="rounded-2xl border border-neutral-200 bg-white p-6">
                <h2 class="text-xs font-bold uppercase tracking-[0.18em] text-neutral-500">Akses Cepat</h2>
                <nav class="mt-4 space-y-1">
                    <button type="button" data-chat-toggle class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-bold text-navy-950 transition hover:bg-neutral-50">
                        <x-admin.icon name="message" class="h-4 w-4 text-brand-600" /> Pesan Saya
                    </button>
                    <a href="{{ route('kontak') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-bold text-navy-950 transition hover:bg-neutral-50">
                        <x-admin.icon name="inbox" class="h-4 w-4 text-brand-600" /> Hubungi Kami
                    </a>
                    <a href="{{ route('portfolio.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-bold text-navy-950 transition hover:bg-neutral-50">
                        <x-admin.icon name="briefcase" class="h-4 w-4 text-brand-600" /> Portfolio
                    </a>
                    <a href="{{ route('blog.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-bold text-navy-950 transition hover:bg-neutral-50">
                        <x-admin.icon name="article" class="h-4 w-4 text-brand-600" /> Blog
                    </a>
                </nav>
            </div>

            <div class="rounded-2xl border border-neutral-200 bg-white p-6">
                <h2 class="text-xs font-bold uppercase tracking-[0.18em] text-neutral-500">Detail Akun</h2>
                <dl class="mt-4 divide-y divide-neutral-100 text-sm">
                    <div class="flex items-start justify-between gap-4 py-3">
                        <dt class="text-neutral-500">Nama</dt>
                        <dd class="text-right font-semibold text-navy-950">{{ $user->name }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-4 py-3">
                        <dt class="text-neutral-500">Email</dt>
                        <dd class="break-all text-right font-semibold text-navy-950">{{ $user->email }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-4 py-3">
                        <dt class="text-neutral-500">Peran</dt>
                        <dd class="text-right font-semibold text-navy-950">{{ $user->role->label() }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-4 py-3">
                        <dt class="text-neutral-500">Anggota sejak</dt>
                        <dd class="text-right font-semibold text-navy-950">{{ $user->created_at->translatedFormat('d F Y') }}</dd>
                    </div>
                </dl>
            </div>

            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full rounded-full border border-navy-800 px-7 py-3 text-sm font-bold text-navy-800 transition hover:bg-navy-800 hover:text-white">
                    Keluar
                </button>
            </form>
        </aside>
    </div>
</section>
@endsection
