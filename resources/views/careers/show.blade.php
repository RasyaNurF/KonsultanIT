@extends('layouts.site')

@section('title', $career->title.' — Karier KIT Konsultan IT')
@section('meta-description', \Illuminate\Support\Str::limit($career->description ?? $career->title, 160))

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-3xl py-20 sm:py-28">
                <p class="flex flex-wrap items-center gap-3 text-xs font-bold uppercase tracking-[0.24em] text-navy-950">
                    <a href="{{ route('karier.index') }}" class="transition hover:text-brand-700">Karier</a>
                    <span class="text-neutral-300" aria-hidden="true">/</span>
                    <span class="text-neutral-400">{{ $career->department ?: 'Lowongan' }}</span>
                </p>
                <h1 class="mt-6 text-4xl font-extrabold leading-[1.05] tracking-tight text-balance text-navy-950 sm:text-5xl">{{ $career->title }}</h1>
                <p class="mt-4 text-[15px] text-neutral-500">{{ $career->location ?: 'Jakarta' }} · {{ $career->employment_type ?: 'Full-time' }}{{ $career->is_remote ? ' · Remote' : '' }}</p>
                @if ($career->deadline)
                    <p class="mt-2 text-[13px] font-semibold {{ $career->isOpen() ? 'text-neutral-500' : 'text-red-600' }}">
                        {{ $career->isOpen() ? 'Pendaftaran tutup '.$career->deadline->translatedFormat('d F Y') : 'Pendaftaran sudah ditutup' }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    <section class="border-t border-neutral-100 bg-neutral-50/60 py-20 sm:py-28">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-[minmax(0,1.6fr)_minmax(0,1fr)] lg:gap-20">
            <div class="space-y-12">
                @if ($career->description)
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-700">Tentang peran ini</p>
                        <div class="mt-4 whitespace-pre-line text-[15px] leading-relaxed text-neutral-600">{{ $career->description }}</div>
                    </div>
                @endif
                @if ($career->responsibilities)
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-700">Tanggung jawab</p>
                        <ul class="mt-4 space-y-3">
                            @foreach (preg_split('/\r?\n/', trim($career->responsibilities)) as $item)
                                @continue(! trim($item))
                                <li class="flex gap-3 text-[15px] leading-relaxed text-neutral-600">
                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-navy-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                                    <span>{{ ltrim(trim($item), '-• ') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if ($career->requirements)
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-700">Persyaratan</p>
                        <ul class="mt-4 space-y-3">
                            @foreach (preg_split('/\r?\n/', trim($career->requirements)) as $item)
                                @continue(! trim($item))
                                <li class="flex gap-3 text-[15px] leading-relaxed text-neutral-600">
                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-navy-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                                    <span>{{ ltrim(trim($item), '-• ') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <aside>
                <form method="post" enctype="multipart/form-data" action="{{ route('karier.apply', $career->slug) }}" class="border border-neutral-200 bg-white p-8">
                    @csrf
                    <h2 class="text-lg font-bold tracking-tight text-navy-950">Lamar posisi ini</h2>

                    @if (session('success'))
                        <p class="mt-4 border-l-2 border-brand-600 bg-neutral-50 px-4 py-3 text-sm leading-relaxed text-navy-950" role="status">{{ session('success') }}</p>
                    @endif

                    <div class="mt-6 space-y-5">
                        <div>
                            <label for="name" class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-500">Nama</label>
                            <input id="name" name="name" type="text" required maxlength="120" value="{{ old('name') }}"
                                class="mt-1 w-full border-b bg-transparent py-3 text-[15px] text-navy-950 placeholder:text-neutral-400 focus:outline-none @error('name') border-red-600 @else border-neutral-300 focus:border-brand-600 @enderror">
                            @error('name')
                                <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-500">Email</label>
                            <input id="email" name="email" type="email" required maxlength="255" value="{{ old('email') }}"
                                class="mt-1 w-full border-b bg-transparent py-3 text-[15px] text-navy-950 placeholder:text-neutral-400 focus:outline-none @error('email') border-red-600 @else border-neutral-300 focus:border-brand-600 @enderror">
                            @error('email')
                                <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-500">Telepon</label>
                            <input id="phone" name="phone" type="text" maxlength="50" value="{{ old('phone') }}"
                                class="mt-1 w-full border-b bg-transparent py-3 text-[15px] text-navy-950 placeholder:text-neutral-400 focus:outline-none @error('phone') border-red-600 @else border-neutral-300 focus:border-brand-600 @enderror">
                            @error('phone')
                                <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="portfolio_url" class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-500">Portfolio URL</label>
                            <input id="portfolio_url" name="portfolio_url" type="url" maxlength="255" value="{{ old('portfolio_url') }}" placeholder="https://"
                                class="mt-1 w-full border-b bg-transparent py-3 text-[15px] text-navy-950 placeholder:text-neutral-400 focus:outline-none @error('portfolio_url') border-red-600 @else border-neutral-300 focus:border-brand-600 @enderror">
                            @error('portfolio_url')
                                <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="cv" class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-500">CV (PDF/DOC, maks 5MB)</label>
                            <input id="cv" name="cv" type="file" accept=".pdf,.doc,.docx"
                                class="mt-2 block w-full text-sm text-neutral-600 file:mr-3 file:rounded-full file:border file:border-neutral-300 file:bg-white file:px-4 file:py-2 file:text-[13px] file:font-bold">
                            @error('cv')
                                <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="cover_letter" class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-500">Surat pengantar</label>
                            <textarea id="cover_letter" name="cover_letter" rows="4" maxlength="5000"
                                class="mt-1 w-full resize-y border-b bg-transparent py-3 text-[15px] leading-relaxed text-navy-950 placeholder:text-neutral-400 focus:outline-none @error('cover_letter') border-red-600 @else border-neutral-300 focus:border-brand-600 @enderror">{{ old('cover_letter') }}</textarea>
                            @error('cover_letter')
                                <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="mt-8 inline-flex w-full items-center justify-center gap-2 rounded-full bg-navy-950 px-8 py-4 text-sm font-bold text-white transition hover:bg-brand-600">
                        Kirim Lamaran
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </button>
                </form>
            </aside>
        </div>
    </section>

    @if ($others->isNotEmpty())
        <section class="border-t border-neutral-100 py-20 sm:py-24">
            <div class="mx-auto max-w-5xl px-4 sm:px-6">
                <h2 class="text-2xl font-extrabold tracking-tight text-navy-950">Lowongan lain</h2>
                <div class="mt-8 divide-y divide-neutral-200 border-y border-neutral-200">
                    @foreach ($others as $item)
                        <a href="{{ route('karier.show', $item->slug) }}" class="group flex items-center justify-between gap-6 py-5">
                            <span>
                                <span class="text-xs font-bold uppercase tracking-[0.18em] text-neutral-400">{{ $item->department ?: 'KIT Konsultan IT' }}</span>
                                <span class="mt-1 block font-bold tracking-tight text-navy-950 transition group-hover:text-brand-700">{{ $item->title }}</span>
                            </span>
                            <svg class="h-5 w-5 shrink-0 text-neutral-300 transition-all group-hover:translate-x-1 group-hover:text-navy-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
