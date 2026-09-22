@extends('layouts.site')

@section('title', 'Tentang Kami — Nusakode')
@section('meta-description', 'Profil PT Nusakode Teknologi: perusahaan jasa teknologi informasi di Jakarta sejak 2015. Visi, misi, nilai, dan tim kami.')

@section('content')
    {{-- ============ HERO ============ --}}
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-3xl py-20 sm:py-28">
                <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.24em] text-navy-950"><span class="h-px w-10 bg-brand-600" aria-hidden="true"></span>Tentang Nusakode</p>
                <h1 class="mt-6 text-4xl font-extrabold leading-[1.05] tracking-tight text-balance text-navy-950 sm:text-6xl">Bekerja seperti divisi internal Anda.</h1>
                <p class="mt-6 max-w-xl text-base leading-relaxed text-neutral-500">{{ $profile?->about ?: 'PT Nusakode Teknologi berdiri di Jakarta pada 2015. Kami melayani perusahaan menengah hingga enterprise — sebagai pelaksana proyek sekaligus mitra pemeliharaan jangka panjang.' }}</p>
                <dl class="mt-12 flex flex-col gap-6 pt-8 sm:flex-row sm:gap-0 sm:divide-x sm:divide-neutral-200">
                    <div class="sm:pr-12"><dt class="sr-only">Pengalaman</dt><dd class="text-2xl font-extrabold text-navy-950">{{ $stats['experience'] }}</dd><dd class="mt-1 text-xs font-medium text-neutral-500">Tahun pengalaman</dd></div>
                    <div class="sm:px-12"><dt class="sr-only">Proyek</dt><dd class="text-2xl font-extrabold text-navy-950">{{ $stats['projects'] }}</dd><dd class="mt-1 text-xs font-medium text-neutral-500">Proyek selesai</dd></div>
                    <div class="sm:pl-12"><dt class="sr-only">Retensi</dt><dd class="text-2xl font-extrabold text-navy-950">{{ $stats['retention'] }}</dd><dd class="mt-1 text-xs font-medium text-neutral-500">Klien melanjutkan</dd></div>
                </dl>
            </div>
        </div>
    </section>

    {{-- ============ VISI & MISI ============ --}}
    <section class="border-y border-neutral-100 bg-neutral-50/60 py-20 sm:py-28">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:gap-20">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-700">Visi</p>
                <p class="mt-4 text-2xl font-extrabold leading-snug tracking-tight text-balance text-navy-950">{{ $profile?->vision ?: 'Menjadi mitra teknologi paling dipercaya bagi perusahaan Indonesia.' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-700">Misi</p>
                <div class="mt-4 whitespace-pre-line text-[15px] leading-relaxed text-neutral-500">{{ $profile?->mission ?: "Menyediakan solusi digital yang rapi dan terukur.\nBekerja transparan dengan kontrak tertulis.\nMenyerahkan kode sumber dan pengetahuan sepenuhnya kepada klien." }}</div>
            </div>
        </div>
    </section>

    {{-- ============ NILAI ============ --}}
    <section class="py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-700">Nilai perusahaan</p>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-balance text-navy-950 sm:text-4xl">Prinsip yang kami pegang di setiap proyek</h2>
            </div>
            <ul class="mt-12 space-y-0 divide-y divide-neutral-200 border-y border-neutral-200">
                @foreach ($values as $value)
                    <li class="flex gap-6 py-6">
                        <span class="text-sm font-extrabold tabular-nums text-neutral-300">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-[15px] font-medium leading-relaxed text-navy-950">{{ $value }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ============ CARA KERJA ============ --}}
    <section class="py-20 sm:py-28" aria-label="Cara kerja">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-700">Cara kerja</p>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-balance text-navy-950 sm:text-4xl">Tahapan yang bisa Anda audit</h2>
            </div>

            <ol class="mt-14 grid gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
                @foreach ([
                    ['01', 'Penemuan & Analisis', 'Kami petakan proses bisnis, kendala, dan target terukur. Keluaran: dokumen lingkup, jadwal, dan biaya tetap.'],
                    ['02', 'Perancangan', 'Alur sistem, struktur data, dan tampilan disepakati di awal. Tidak ada pengerjaan tanpa persetujuan tertulis.'],
                    ['03', 'Pembangunan Bertahap', 'Pekerjaan dibagi per modul dan didemokan berkala. Anda melihat progres nyata, bukan laporan di atas kertas.'],
                    ['04', 'Serah Terima & Dukungan', 'Kode sumber, dokumentasi, dan akses sepenuhnya milik Anda — dilanjutkan opsi kontrak pemeliharaan ber-SLA.'],
                ] as [$no, $title, $desc])
                    <li class="group transition">
                        <p class="text-5xl font-extrabold tracking-tight text-navy-100 transition group-hover:text-brand-500">{{ $no }}</p>
                        <h3 class="mt-4 text-base font-bold text-navy-950 transition group-hover:text-navy-600">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-neutral-500 transition group-hover:text-neutral-700">{{ $desc }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ============ TIM ============ --}}
    <section class="border-t border-neutral-100 bg-neutral-50/60 py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <div class="max-w-2xl">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-700">Tim</p>
                    <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-balance text-navy-950 sm:text-4xl">Orang-orang di balik sistem Anda</h2>
                </div>
                <a href="{{ route('karier.index') }}" class="group inline-flex items-center gap-2 text-sm font-bold text-navy-800">
                    Bergabung dengan kami
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>

            @if ($teams->isEmpty())
                <div class="mt-12 border border-dashed border-neutral-300 bg-white px-6 py-16 text-center">
                    <p class="text-sm font-bold text-navy-950">Profil tim segera hadir.</p>
                    <p class="mt-2 text-sm text-neutral-500">Kami sedang menyusun halaman tim.</p>
                </div>
            @else
                <div class="mt-12 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($teams as $member)
                        <article>
                            @if ($member->photo_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($member->photo_path) }}" alt="{{ $member->name }}" class="aspect-square w-full object-cover" loading="lazy">
                            @else
                                <div class="flex aspect-square w-full items-center justify-center bg-navy-800 text-3xl font-extrabold text-white" aria-hidden="true">{{ $member->initials() }}</div>
                            @endif
                            <h3 class="mt-4 text-base font-bold text-navy-950">{{ $member->name }}</h3>
                            @if ($member->position)
                                <p class="mt-0.5 text-[13px] text-neutral-500">{{ $member->position }}</p>
                            @endif
                            @if ($member->bio)
                                <p class="mt-2 text-[13px] leading-relaxed text-neutral-500">{{ \Illuminate\Support\Str::limit($member->bio, 120) }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ============ CTA ============ --}}
    <section class="bg-navy-900 py-16 text-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-4 sm:px-6">
            <div class="max-w-xl">
                <h2 class="text-2xl font-extrabold tracking-tight text-balance sm:text-3xl">Ingin bekerja dengan tim kami?</h2>
                <p class="mt-2 text-[15px] text-neutral-300">Ceritakan kebutuhan Anda — kami balas dengan penawaran tertulis.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('kontak') }}" class="rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">Hubungi Kami</a>
                <a href="{{ route('portfolio.index') }}" class="rounded-full border border-white/30 px-8 py-3.5 text-sm font-bold transition hover:border-white hover:bg-white/10">Lihat Portfolio</a>
            </div>
        </div>
    </section>
@endsection
