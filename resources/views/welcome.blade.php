@extends('layouts.site')

@section('content')
@php
$clients = [
    ['file' => 'googlecloud', 'name' => 'Google Cloud', 'hover' => 'hover:text-[#4285F4]'],
    ['file' => 'github', 'name' => 'GitHub', 'hover' => 'hover:text-[#181717]'],
    ['file' => 'cloudflare', 'name' => 'Cloudflare', 'hover' => 'hover:text-[#F38020]'],
    ['file' => 'stripe', 'name' => 'Stripe', 'hover' => 'hover:text-[#635BFF]'],
    ['file' => 'vercel', 'name' => 'Vercel', 'hover' => 'hover:text-black'],
    ['file' => 'atlassian', 'name' => 'Atlassian', 'hover' => 'hover:text-[#0052CC]'],
    ['file' => 'docker', 'name' => 'Docker', 'hover' => 'hover:text-[#2496ED]'],
    ['file' => 'kubernetes', 'name' => 'Kubernetes', 'hover' => 'hover:text-[#326CE5]'],
    ['file' => 'gitlab', 'name' => 'GitLab', 'hover' => 'hover:text-[#FC6D26]'],
    ['file' => 'mongodb', 'name' => 'MongoDB', 'hover' => 'hover:text-[#47A248]'],
    ['file' => 'postgresql', 'name' => 'PostgreSQL', 'hover' => 'hover:text-[#4169E1]'],
    ['file' => 'laravel', 'name' => 'Laravel', 'hover' => 'hover:text-[#FF2D20]'],
    ['file' => 'php', 'name' => 'PHP', 'hover' => 'hover:text-[#777BB4]'],
    ['file' => 'python', 'name' => 'Python', 'hover' => 'hover:text-[#3776AB]'],
];

$services = [
    ['no' => '01', 'title' => 'Pengembangan Web & Aplikasi', 'desc' => 'Kami membangun website, web application, dashboard, dan platform digital sesuai kebutuhan bisnis Anda.', 'tags' => ['Laravel', 'Vue', 'React', 'MySQL'], 'img' => 'img/work-1.jpg', 'alt' => 'Dashboard aplikasi web di layar laptop'],
    ['no' => '02', 'title' => 'Sistem Informasi Bisnis', 'desc' => 'Sistem internal yang membantu perusahaan mengelola proses kerja secara lebih terstruktur, mulai dari keuangan, inventaris, SDM, hingga laporan.', 'tags' => ['Keuangan', 'Inventaris', 'SDM', 'Laporan'], 'img' => 'img/work-3.jpg', 'alt' => 'Sistem informasi bisnis di layar tablet'],
    ['no' => '03', 'title' => 'Integrasi API & Sistem', 'desc' => 'Menghubungkan aplikasi, payment gateway, layanan pihak ketiga, dan sistem internal agar data dapat berjalan otomatis dan real-time.', 'tags' => ['API', 'Payment Gateway', 'Database'], 'diagram' => true],
    ['no' => '04', 'title' => 'Aplikasi Mobile', 'desc' => 'Aplikasi Android dan iOS untuk layanan pelanggan, operasional, maupun kebutuhan bisnis khusus.', 'tags' => ['Android', 'iOS', 'Cross-Platform'], 'img' => 'img/work-2.jpg', 'alt' => 'Aplikasi mobile di layar ponsel'],
    ['no' => '05', 'title' => 'UI/UX & Product Design', 'desc' => 'Merancang antarmuka dan pengalaman pengguna yang sederhana, konsisten, dan mudah digunakan.', 'tags' => ['UI Design', 'UX Research', 'Prototyping'], 'img' => 'img/work-4.jpg', 'alt' => 'Desain antarmuka dan pengalaman pengguna'],
    ['no' => '06', 'title' => 'Maintenance & Pengembangan', 'desc' => 'Pemeliharaan sistem, peningkatan fitur, monitoring, perbaikan bug, dan dukungan teknis berkelanjutan.', 'tags' => ['Monitoring', 'Bug Fix', 'SLA'], 'img' => 'img/hero.jpg', 'alt' => 'Pemeliharaan dan monitoring sistem'],
];

$works = [
    ['img' => 'img/work-1.jpg', 'tag' => 'Perbankan', 'title' => 'Dashboard Keuangan Internal'],
    ['img' => 'img/work-2.jpg', 'tag' => 'Distribusi', 'title' => 'Portal Pelaporan Penjualan'],
    ['img' => 'img/work-3.jpg', 'tag' => 'Pendidikan', 'title' => 'Sistem Penerimaan Peserta Didik'],
    ['img' => 'img/work-4.jpg', 'tag' => 'Logistik', 'title' => 'Integrasi Sistem Pergudangan'],
    ['img' => 'img/about.jpg', 'tag' => 'Kesehatan', 'title' => 'Rekam Medis Elektronik'],
    ['img' => 'img/hero.jpg', 'tag' => 'Manufaktur', 'title' => 'Monitoring Lini Produksi'],
    ['img' => 'img/work-2.jpg', 'tag' => 'Pemerintahan', 'title' => 'Portal Layanan Publik'],
    ['img' => 'img/work-1.jpg', 'tag' => 'Ritel', 'title' => 'Katalog & Pemesanan Multi-Cabang'],
];

$quotes = [
    ['text' => 'Lingkup pekerjaan disepakati di awal dan tidak berubah-ubah di tengah jalan. Setiap termin dikaitkan dengan hasil yang bisa kami uji sendiri.', 'name' => 'Rani Prameswari', 'role' => 'Head of Operations, Bank Arta', 'initials' => 'RP'],
    ['text' => 'Tim kami yang awalnya mencatat di spreadsheet sekarang bekerja dalam satu sistem. Laporan bulanan yang dulu seminggu, sekarang selesai sehari.', 'name' => 'Bayu Santoso', 'role' => 'Direktur, Sinar Niaga', 'initials' => 'BS'],
    ['text' => 'Dokumentasinya lengkap dan kode diserahterimakan penuh. Tim internal kami bisa melanjutkan pengembangan tanpa ketergantungan.', 'name' => 'Dr. Lestari Widodo', 'role' => 'Wakil Rektor, Universitas Cendana', 'initials' => 'LW'],
];

$insights = $dbInsights ?: [
    ['cat' => 'Panduan', 'img' => 'img/work-3.jpg', 'title' => 'Menyusun Kerangka Acuan Kerja Proyek Software Agar Tidak Bengkak', 'date' => '11 September 2026'],
    ['cat' => 'Keamanan', 'img' => 'img/work-4.jpg', 'title' => 'Jadwal Pengujian Penetrasi yang Wajar untuk Aplikasi Perusahaan', 'date' => '9 September 2026'],
    ['cat' => 'Operasional', 'img' => 'img/work-2.jpg', 'title' => 'Checklist Serah Terima Aplikasi dari Vendor ke Tim Internal', 'date' => '5 September 2026'],
];

// Data dari basis data bila admin sudah mengisinya; kalau masih kosong pakai contoh di atas.
$services = $dbServices ?: $services;
$works = $dbWorks ?: $works;
$quotes = $dbQuotes ?: $quotes;
@endphp

    {{-- ============ HERO ============ --}}
    <section id="beranda-hero" class="relative overflow-hidden bg-navy-950 text-white">
        <img src="{{ $cmsHero['image'] ?? $heroImage }}" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover" loading="eager" fetchpriority="high">
        <div class="absolute inset-0 bg-navy-950/70" aria-hidden="true" data-no-reveal></div>
        <div class="relative mx-auto max-w-7xl px-4 pb-20 pt-60 sm:px-6 sm:pb-28 sm:pt-72">
            <div class="max-w-2xl">
                <p class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.28em] text-neutral-300"><span class="h-0.5 w-10 bg-brand-500" aria-hidden="true"></span>Teknologi &amp; Solusi Digital</p>
                @if (! empty($cmsHero))
                    <h1 class="mt-6 text-4xl font-extrabold leading-[1.05] tracking-tight text-balance sm:text-6xl">
                        {{ \Illuminate\Support\Str::replaceLast(' '.$cmsHero['highlight'], '', $cmsHero['title']) }}
                        @if ($cmsHero['highlight'])<span class="text-brand-400">{{ $cmsHero['highlight'] }}</span>@endif
                    </h1>
                    @if ($cmsHero['description'])
                        <p class="mt-5 max-w-xl text-base leading-relaxed text-neutral-200 sm:text-lg">{{ $cmsHero['description'] }}</p>
                    @endif
                @else
                    <h1 class="mt-6 text-4xl font-extrabold leading-[1.05] tracking-tight text-balance sm:text-6xl">
                        Teknologi yang merapikan cara bisnis Anda <span class="text-brand-400">bekerja</span>
                    </h1>
                    <p class="mt-5 max-w-xl text-base leading-relaxed text-neutral-200 sm:text-lg">
                        Aplikasi, sistem, dan infrastruktur TI untuk perusahaan — lingkup tertulis, biaya tetap, serah terima penuh.
                    </p>
                @endif
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ $cmsHero['cta_url'] ?? route('kontak') }}" class="group inline-flex items-center gap-2 rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">
                        {{ $cmsHero['cta_label'] ?? 'Diskusikan Kebutuhan Anda' }}
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                    <a href="{{ $cmsHero['secondary_cta_url'] ?? route('portfolio.index') }}" class="group inline-flex items-center gap-2 px-2 py-3.5 text-sm font-bold text-white">
                        {{ $cmsHero['secondary_cta_label'] ?? 'Lihat hasil kerja kami' }}
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>
                <dl class="mt-12 flex flex-col gap-6 pt-8 sm:flex-row sm:gap-0 sm:divide-x sm:divide-white/15">
                    <div class="sm:pr-12"><dt class="sr-only">Pengalaman</dt><dd class="text-2xl font-extrabold">{{ $heroStats['experience'] }}</dd><dd class="mt-1 text-xs font-medium text-neutral-300">Tahun pengalaman</dd></div>
                    <div class="sm:px-12"><dt class="sr-only">Proyek</dt><dd class="text-2xl font-extrabold">{{ $heroStats['projects'] }}</dd><dd class="mt-1 text-xs font-medium text-neutral-300">Proyek selesai</dd></div>
                    <div class="sm:pl-12"><dt class="sr-only">Retensi</dt><dd class="text-2xl font-extrabold">{{ $heroStats['retention'] }}</dd><dd class="mt-1 text-xs font-medium text-neutral-300">Klien melanjutkan</dd></div>
                </dl>
            </div>
        </div>
    </section>

    {{-- ============ KLIEN ============ --}}
    <section class="border-y border-neutral-100 bg-neutral-50/60 py-10" aria-label="Klien yang bekerja sama">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="overflow-hidden">
                <div class="flex w-max animate-marquee items-center">
                    @foreach ([false, true] as $duplicate)
                        <div class="flex items-center gap-14 pr-14 text-neutral-400" @if ($duplicate) aria-hidden="true" @endif>
                            @foreach ($clients as $c)
                                @php($svg = file_get_contents(public_path("img/brands/{$c['file']}.svg")))
                                <span class="inline-flex items-center gap-2.5 transition {{ $c['hover'] }}" title="{{ $c['name'] }}">
                                    <span class="block h-7 w-7 shrink-0 [&>svg]:h-full [&>svg]:w-full">{!! $svg !!}</span>
                                    <span class="whitespace-nowrap text-sm font-bold">{{ $c['name'] }}</span>
                                </span>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============ SOLUSI INTERAKTIF ============ --}}
    <section id="solusi" class="scroll-mt-24 py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <div class="max-w-2xl">
                    <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.22em] text-neutral-400"><span class="h-px w-8 bg-neutral-300" aria-hidden="true"></span>Solusi</p>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-balance text-navy-950 sm:text-4xl">Pilih solusi sesuai kebutuhan Anda.</h2>
                </div>
                <a href="{{ route('layanan.index') }}" class="group inline-flex items-center gap-2 text-sm font-bold text-navy-800">
                    Lihat semua layanan
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>

            <div class="solusi-tabs mt-12 flex gap-2 overflow-x-auto pb-2" role="tablist" aria-label="Kategori solusi">
                @foreach ($services as $s)
                    <button type="button" role="tab" data-solusi-tab="{{ $loop->index }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-controls="solusi-panel-{{ $loop->index }}" id="solusi-tab-{{ $loop->index }}"
                        class="shrink-0 rounded-full px-5 py-2.5 text-[13px] font-bold transition {{ $loop->first ? 'bg-navy-950 text-white' : 'text-neutral-500 ring-1 ring-neutral-200 hover:text-navy-950 hover:ring-neutral-300' }}">
                        {{ $s['title'] }}
                    </button>
                @endforeach
            </div>

            <div class="mt-6">
                @foreach ($services as $s)
                    @php($img = str_starts_with($s['img'] ?? '', 'img/') ? asset($s['img']) : ($s['img'] ?? asset('img/work-1.jpg')))
                    <div data-solusi-panel="{{ $loop->index }}" role="tabpanel" id="solusi-panel-{{ $loop->index }}" aria-labelledby="solusi-tab-{{ $loop->index }}" @if (! $loop->first) hidden @endif
                        class="grid overflow-hidden rounded-2xl ring-1 ring-neutral-200 lg:grid-cols-2">
                        <div class="group relative min-h-64 overflow-hidden bg-neutral-100 sm:min-h-80 lg:min-h-[420px]">
                            @if (! empty($s['diagram']))
                                <div class="absolute inset-0 flex items-center justify-center gap-4 bg-navy-950 p-8">
                                    <span class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white/5 text-2xl font-extrabold text-white ring-1 ring-brand-500/50" aria-hidden="true">&lt;/&gt;</span>
                                    <span class="flex flex-col gap-2.5">
                                        @foreach (['REST API', 'Database', 'Layanan Pihak Ketiga'] as $node)
                                            <span class="rounded-xl bg-white/5 px-4 py-2.5 text-[13px] font-semibold text-neutral-200 ring-1 ring-white/10">{{ $node }}</span>
                                        @endforeach
                                    </span>
                                </div>
                            @else
                                <img src="{{ $img }}" alt="{{ $s['alt'] ?? $s['title'] }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy">
                            @endif
                        </div>
                        <div class="flex flex-col justify-center p-8 sm:p-12">
                            <p class="flex items-center gap-3 text-sm font-extrabold tabular-nums text-neutral-400"><span>{{ $s['no'] }}</span><span class="h-px w-10 bg-neutral-300" aria-hidden="true"></span></p>
                            <h3 class="mt-4 text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl">{{ $s['title'] }}</h3>
                            <p class="mt-4 text-[15px] leading-relaxed text-neutral-500">{{ $s['desc'] }}</p>
                            @if (! empty($s['tags']))
                                <p class="mt-4 text-[13px] font-medium text-neutral-400">{{ implode(' · ', $s['tags']) }}</p>
                            @endif
                            <div class="mt-8 flex flex-wrap gap-3">
                                <a href="{{ $s['url'] ?? route('layanan.index') }}" class="group inline-flex items-center gap-2 rounded-full bg-navy-950 px-7 py-3 text-sm font-bold text-white transition hover:bg-navy-800">
                                    Pelajari Selengkapnya
                                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                                </a>
                                <a href="{{ route('kontak') }}" class="inline-flex items-center gap-2 rounded-full px-7 py-3 text-sm font-bold text-navy-950 ring-1 ring-neutral-300 transition hover:ring-navy-800">Diskusikan Kebutuhan</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ PORTOFOLIO RINGKAS ============ --}}
    <section id="portofolio" class="scroll-mt-24 bg-neutral-50/60 py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <div class="max-w-2xl">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-700">Portofolio</p>
                    <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-balance text-navy-950 sm:text-4xl">Pekerjaan yang sudah berjalan produksi</h2>
                </div>
                <a href="{{ route('portfolio.index') }}" class="group inline-flex items-center gap-2 text-sm font-bold text-navy-800">
                    Lihat semua project
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
        </div>

        <div class="marquee-fade mt-12 overflow-hidden">
            <div class="flex w-max animate-marquee-left">
                @foreach ([0, 1] as $copy)
                    <div class="flex shrink-0 gap-6 pr-6"@if ($copy > 0) aria-hidden="true"@endif>
                        @foreach ($works as $w)
                            <article class="w-[280px] shrink-0 sm:w-[360px]">
                                <a href="{{ $w['url'] ?? route('portfolio.index') }}" class="group relative block overflow-hidden rounded-2xl">
                                    <img src="{{ asset($w['img']) }}" alt="{{ $w['title'] }}" class="h-64 w-full object-cover transition duration-700 group-hover:scale-[1.06] sm:h-72" loading="lazy">
                                    <span class="absolute inset-0 bg-gradient-to-t from-navy-950/90 via-navy-950/25 to-transparent" aria-hidden="true"></span>
                                    <span class="absolute inset-x-0 bottom-0 p-5 sm:p-6">
                                        <span class="block text-[11px] font-bold uppercase tracking-[0.2em] text-brand-400">{{ $w['tag'] }}</span>
                                        <span class="mt-1.5 block text-lg font-bold leading-snug text-white">{{ $w['title'] }}</span>
                                    </span>
                                </a>
                            </article>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ TENTANG ============ --}}
    <section id="tentang" class="scroll-mt-24 py-20 sm:py-28">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:gap-20">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-700">Tentang kami</p>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-balance text-navy-950 sm:text-4xl">Bekerja seperti divisi internal Anda</h2>
                <p class="mt-5 leading-relaxed text-neutral-500">
                    PT Nusakode Teknologi berdiri di Jakarta pada 2015. Kami melayani perusahaan menengah hingga enterprise — sebagai pelaksana proyek sekaligus mitra pemeliharaan jangka panjang.
                </p>
                <ul class="mt-8 space-y-4">
                    @foreach (['Kontrak kerja tertulis: lingkup, jadwal, biaya, dan garansi tercantum jelas.', 'Tim tetap, bukan lepas: engineer yang mengerjakan proyek Anda adalah karyawan kami.', 'Serah terima penuh: kode sumber, dokumentasi, kredensial, dan pelatihan operator.'] as $point)
                        <li class="flex gap-3 text-[15px]">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-navy-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>{{ $point }}</span>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('tentang') }}" class="group mt-8 inline-flex items-center gap-2 text-sm font-bold text-navy-800">
                    Selengkapnya tentang kami
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
            <img src="{{ $aboutImage }}" alt="Rapat perencanaan proyek di kantor Nusakode" class="h-80 w-full object-cover sm:h-[460px]" loading="lazy">
        </div>
    </section>

    {{-- ============ TESTIMONI ============ --}}
    <section class="border-y border-neutral-100 bg-neutral-50/60 py-20 sm:py-28" aria-label="Testimoni klien">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-700">Testimoni</p>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-balance text-navy-950 sm:text-4xl">Kata mereka yang bekerja bersama kami</h2>
            </div>

            <div class="mt-12 overflow-hidden">
                <div class="flex w-max animate-marquee">
                    @foreach ([false, true] as $duplicate)
                        <div class="flex gap-14 pr-14" @if ($duplicate) aria-hidden="true" @endif>
                            @foreach ($quotes as $q)
                                <figure class="w-[300px] shrink-0 sm:w-[380px]">
                                    <span class="font-serif text-5xl leading-none text-brand-500" aria-hidden="true">&ldquo;</span>
                                    <blockquote class="-mt-2 text-[15px] leading-relaxed text-neutral-600">{{ $q['text'] }}</blockquote>
                                    <figcaption class="mt-6 flex items-center gap-3">
                                        @if ($q['photo'] ?? null)
                                            <img src="{{ $q['photo'] }}" alt="{{ $q['name'] }}" class="h-11 w-11 shrink-0 rounded-full object-cover" loading="lazy">
                                        @else
                                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-navy-800 text-sm font-bold text-white" aria-hidden="true">{{ $q['initials'] }}</span>
                                        @endif
                                        <span>
                                            <span class="block text-sm font-bold text-navy-950">{{ $q['name'] }}</span>
                                            <span class="block text-xs text-neutral-500">{{ $q['role'] }}</span>
                                        </span>
                                    </figcaption>
                                </figure>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============ INSIGHT ============ --}}
    <section id="insight" class="scroll-mt-24 py-20 sm:py-28">
        <div class="mx-auto max-w-5xl px-4 sm:px-6">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-700">Insight</p>
                    <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-balance text-navy-950 sm:text-4xl">Tulisan praktis dari tim kami</h2>
                </div>
                <a href="{{ route('blog.index') }}" class="group inline-flex items-center gap-2 text-sm font-bold text-navy-800">
                    Semua artikel
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>

            <div class="mt-10 divide-y divide-neutral-200 border-y border-neutral-200">
                @foreach ($insights as $a)
                    <a href="{{ $a['url'] ?? route('blog.index') }}" class="group flex items-center gap-5 py-6 sm:gap-8 sm:px-2">
                        <span class="hidden w-36 shrink-0 overflow-hidden sm:block" aria-hidden="true"><img src="{{ asset($a['img']) }}" alt="" class="h-24 w-36 object-cover transition duration-500 group-hover:scale-[1.05]" loading="lazy"></span>
                        <span class="flex-1">
                            <span class="text-xs font-bold uppercase tracking-[0.18em] text-neutral-400">{{ $a['cat'] }} · {{ $a['date'] }}</span>
                            <span class="mt-1.5 block text-lg font-bold tracking-tight text-navy-950 transition group-hover:text-navy-600">{{ $a['title'] }}</span>
                        </span>
                        <svg class="h-5 w-5 shrink-0 text-neutral-300 transition-all group-hover:translate-x-1 group-hover:text-navy-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ CTA KONTAK ============ --}}
    <section class="bg-navy-900 py-16 text-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-4 sm:px-6">
            <div class="max-w-xl">
                <h2 class="text-2xl font-extrabold tracking-tight text-balance sm:text-3xl">Punya kebutuhan teknologi untuk dibahas?</h2>
                <p class="mt-2 text-[15px] text-neutral-300">Ceritakan lewat halaman kontak — kami balas dengan penawaran tertulis.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('kontak') }}" class="rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">Hubungi Kami</a>
                <a href="tel:+622150001234" class="rounded-full border border-white/30 px-8 py-3.5 text-sm font-bold transition hover:border-white hover:bg-white/10">+62 21 5000 1234</a>
            </div>
        </div>
    </section>

@endsection
