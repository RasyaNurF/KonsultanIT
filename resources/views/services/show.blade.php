@extends('layouts.site')

@section('title', $service->title.' — KIT Konsultan IT')
@section('meta-description', \Illuminate\Support\Str::limit($service->description ?? $service->title, 160))

@section('content')
    @php
        $imageUrl = $service->image_path
            ? (str_starts_with($service->image_path, 'img/') ? asset($service->image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($service->image_path))
            : null;
        $technologies = $service->technologyList();
        $industries = $service->industries;
        $hasCustomImage = filled($service->image_path) && ! str_starts_with($service->image_path, 'img/');
        $scopeItems = match ($service->slug) {
            'pengembangan-web-aplikasi' => [
                ['title' => 'Website dan portal', 'desc' => 'Sajikan informasi dan layanan digital dengan alur yang mudah diikuti pengunjung.'],
                ['title' => 'Aplikasi web internal', 'desc' => 'Bantu tim mengelola pekerjaan sehari-hari melalui sistem yang mengikuti proses bisnis.'],
                ['title' => 'Dashboard operasional', 'desc' => 'Satukan informasi penting agar pemantauan dan pengambilan keputusan lebih terarah.'],
            ],
            'sistem-informasi-bisnis' => [
                ['title' => 'Proses kerja terhubung', 'desc' => 'Kelola aktivitas antartim dalam alur yang lebih jelas dan terdokumentasi.'],
                ['title' => 'Modul sesuai kebutuhan', 'desc' => 'Susun fitur untuk keuangan, inventaris, SDM, atau kebutuhan operasional lain.'],
                ['title' => 'Laporan yang mudah ditelusuri', 'desc' => 'Temukan data yang dibutuhkan tanpa mengumpulkannya ulang dari banyak tempat.'],
            ],
            'integrasi-api-sistem' => [
                ['title' => 'Integrasi aplikasi', 'desc' => 'Hubungkan sistem internal dengan layanan dan aplikasi yang sudah digunakan.'],
                ['title' => 'Sinkronisasi data', 'desc' => 'Kurangi pengisian berulang melalui pertukaran data yang terencana.'],
                ['title' => 'Alur otomatis', 'desc' => 'Atur perpindahan informasi antarproses sesuai kebutuhan operasional.'],
            ],
            'aplikasi-mobile' => [
                ['title' => 'Layanan pelanggan', 'desc' => 'Hadirkan layanan bisnis melalui pengalaman mobile yang mudah digunakan.'],
                ['title' => 'Operasional lapangan', 'desc' => 'Bantu tim mengakses dan mencatat informasi saat bekerja di luar kantor.'],
                ['title' => 'Pengembangan lintas perangkat', 'desc' => 'Rencanakan pengalaman yang konsisten untuk kebutuhan Android dan iOS.'],
            ],
            'uiux-product-design' => [
                ['title' => 'Riset kebutuhan pengguna', 'desc' => 'Pahami tujuan, kendala, dan kebiasaan pengguna sebelum merancang antarmuka.'],
                ['title' => 'Alur dan prototipe', 'desc' => 'Uji arah produk melalui rancangan yang dapat ditinjau bersama.'],
                ['title' => 'Desain antarmuka', 'desc' => 'Susun tampilan yang konsisten dan mudah diterapkan saat pengembangan.'],
            ],
            default => [
                ['title' => 'Pemetaan kebutuhan', 'desc' => 'Tentukan masalah, prioritas, dan hasil yang ingin dicapai.'],
                ['title' => 'Pelaksanaan bertahap', 'desc' => 'Kerjakan solusi dengan kemajuan yang dapat ditinjau secara berkala.'],
                ['title' => 'Serah terima dan dukungan', 'desc' => 'Siapkan dokumentasi serta langkah lanjutan setelah solusi digunakan.'],
            ],
        };
        $steps = [
            ['title' => 'Konsultasi & lingkup', 'desc' => 'Kami petakan kebutuhan, prioritas, dan ukuran keberhasilan sebelum menyusun penawaran tertulis.'],
            ['title' => 'Desain & pengembangan', 'desc' => 'Pengerjaan bertahap oleh tim tetap, dengan demonstrasi berkala yang bisa Anda nilai.'],
            ['title' => 'Uji & serah terima', 'desc' => 'Pengujian menyeluruh, pelatihan operator, dan penyerahan kode, dokumentasi, serta kredensial.'],
            ['title' => 'Rawat & kembangkan', 'desc' => 'Pemeliharaan, pemantauan, dan peningkatan fitur berkelanjutan sesuai kebutuhan bisnis.'],
        ];
    @endphp

    {{-- ============ HERO ============ --}}
    <section class="border-b border-neutral-200 bg-[#f7f9fc]">
        <div class="mx-auto max-w-7xl px-4 pb-12 pt-10 sm:px-6 sm:pb-16 sm:pt-14" data-no-reveal>
            <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-xs font-semibold text-neutral-500">
                <a href="{{ route('solusi.index') }}" class="transition hover:text-brand-700">Solusi</a>
                <span class="text-neutral-300" aria-hidden="true">/</span>
                <span class="text-navy-800" aria-current="page">{{ $service->title }}</span>
            </nav>

            <div class="mt-12 grid gap-10 lg:grid-cols-[minmax(0,1.08fr)_minmax(0,0.92fr)] lg:items-center lg:gap-16">
                <div class="min-w-0">
                    <p class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.2em] text-brand-700"><span class="h-px w-9 bg-brand-600" aria-hidden="true"></span>Layanan pengembangan digital</p>
                    <h1 class="mt-5 max-w-2xl text-4xl font-extrabold leading-[1.08] tracking-tight text-balance text-navy-950 sm:text-5xl xl:text-6xl">{{ $service->title }}</h1>
                    @if ($service->description)
                        <p class="mt-6 max-w-xl text-base leading-8 text-neutral-600 sm:text-lg">{{ $service->description }}</p>
                    @endif
                    <div class="mt-9 flex flex-wrap items-center gap-4">
                        <a href="{{ route('kontak') }}#proyek" class="group inline-flex items-center justify-center gap-3 rounded-lg bg-brand-600 px-6 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                            Konsultasikan proyek
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                        <a href="{{ route('portfolio.index') }}" class="inline-flex items-center gap-2 py-3 text-sm font-bold text-navy-800 transition hover:text-brand-700">Lihat hasil kerja <span aria-hidden="true">↗</span></a>
                    </div>
                    <p class="mt-8 max-w-lg border-l-2 border-brand-200 pl-4 text-sm leading-6 text-neutral-500">Mulai dari kebutuhan bisnis, lalu tentukan lingkup, tahapan, dan hasil yang akan diserahkan.</p>
                </div>

                @if ($hasCustomImage)
                    <figure class="overflow-hidden rounded-2xl border border-neutral-200 bg-white p-2 shadow-[0_20px_50px_-36px_rgba(5,24,41,0.35)]">
                        <img src="{{ $imageUrl }}" alt="{{ $service->title }}" class="aspect-[4/3] w-full rounded-xl object-cover" loading="eager" fetchpriority="high">
                        <figcaption class="px-3 pb-2 pt-4 text-xs font-medium text-neutral-500">Visual layanan {{ $service->title }}</figcaption>
                    </figure>
                @else
                    <aside class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-[0_20px_50px_-36px_rgba(5,24,41,0.35)] sm:p-8" aria-label="Langkah memulai proyek">
                        <div class="flex items-center justify-between gap-4 border-b border-neutral-100 pb-5">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-brand-700">Memulai proyek</p>
                                <h2 class="mt-2 text-xl font-bold tracking-tight text-navy-950">Dari percakapan ke rencana kerja.</h2>
                            </div>
                        </div>
                        <ol class="divide-y divide-neutral-100">
                            @foreach ([
                                ['title' => 'Ceritakan kebutuhan', 'desc' => 'Jelaskan proses, kendala, dan tujuan yang ingin dicapai.'],
                                ['title' => 'Petakan ruang lingkup', 'desc' => 'Kami susun prioritas, pendekatan, dan hasil yang perlu disiapkan.'],
                                ['title' => 'Tinjau rencana kerja', 'desc' => 'Bahas tahapan, jadwal, dan penawaran tertulis sebelum memulai.'],
                            ] as $item)
                                <li class="flex gap-5 py-5 last:pb-0">
                                    <span class="pt-0.5 text-xs font-bold tabular-nums text-brand-700">0{{ $loop->iteration }}</span>
                                    <div>
                                        <h3 class="text-sm font-bold text-navy-950">{{ $item['title'] }}</h3>
                                        <p class="mt-1 text-sm leading-6 text-neutral-500">{{ $item['desc'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </aside>
                @endif
            </div>
        </div>
    </section>

    {{-- ============ KOMITMEN KERJA ============ --}}
    <section class="border-b border-neutral-200 bg-white">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 md:grid-cols-3 md:gap-0" data-no-reveal>
            @foreach ([
                ['title' => 'Tim yang bertanggung jawab', 'desc' => 'Pekerjaan ditangani tim tetap dengan alur komunikasi yang jelas.'],
                ['title' => 'Lingkup tertulis', 'desc' => 'Jadwal, biaya, dan hasil kerja disepakati sebelum pengembangan dimulai.'],
                ['title' => 'Serah terima terdokumentasi', 'desc' => 'Kode sumber, dokumentasi, dan pelatihan disiapkan saat proyek selesai.'],
            ] as $value)
                <div class="border-l-2 border-brand-500 pl-5 md:border-l md:border-neutral-200 md:px-8 md:first:border-brand-500 md:first:pl-5 md:last:pr-0">
                    <h2 class="text-sm font-bold text-navy-950">{{ $value['title'] }}</h2>
                    <p class="mt-2 text-sm leading-6 text-neutral-500">{{ $value['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ RUANG LINGKUP ============ --}}
    <section class="bg-white py-20 sm:py-24">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[minmax(0,0.43fr)_minmax(0,0.57fr)] lg:gap-20" data-no-reveal>
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-700">Ruang lingkup layanan</p>
                <h2 class="mt-4 max-w-md text-3xl font-extrabold tracking-tight text-balance text-navy-950 sm:text-4xl">Solusi yang dibangun untuk pekerjaan nyata.</h2>
                <p class="mt-5 max-w-md text-[15px] leading-7 text-neutral-500">Setiap proyek dimulai dari proses yang ingin diperbaiki. Fitur dan teknologi dipilih setelah tujuan, pengguna, dan prioritasnya jelas.</p>
                @if ($technologies)
                    <div class="mt-9 border-t border-neutral-200 pt-6">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-neutral-500">Teknologi dan fokus terkait</p>
                        <ul class="mt-4 flex flex-wrap gap-2">
                            @foreach ($technologies as $technology)
                                <li class="rounded-md border border-neutral-200 px-3 py-1.5 text-xs font-semibold text-navy-800">{{ $technology }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <ol class="border-t border-neutral-200">
                @foreach ($scopeItems as $item)
                    <li class="grid gap-3 border-b border-neutral-200 py-6 sm:grid-cols-[48px_minmax(0,1fr)] sm:gap-5">
                        <span class="text-sm font-bold tabular-nums text-brand-700">0{{ $loop->iteration }}</span>
                        <div>
                            <h3 class="text-lg font-bold text-navy-950">{{ $item['title'] }}</h3>
                            <p class="mt-2 max-w-lg text-sm leading-7 text-neutral-500">{{ $item['desc'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ============ PROSES ============ --}}
    <section class="border-t border-neutral-100 bg-neutral-50/70 py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6" data-no-reveal>
            <div class="max-w-2xl">
                <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.22em] text-brand-700"><span class="h-px w-8 bg-brand-500" aria-hidden="true"></span>Alur kerja</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-balance text-navy-950 sm:text-4xl">Cara kami mengerjakan solusi ini</h2>
            </div>

            <ol class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($steps as $step)
                    <li class="border-t-2 border-brand-500 pt-5">
                        <span class="text-xs font-bold tabular-nums tracking-[0.16em] text-brand-700">TAHAP {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="mt-5 text-base font-bold text-navy-950">{{ $step['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-neutral-500">{{ $step['desc'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ============ INDUSTRI TERKAIT ============ --}}
    @if ($industries->isNotEmpty())
        <section class="border-t border-white/5 bg-navy-950 py-20 text-white sm:py-28" aria-label="Industri yang dilayani">
            <div class="mx-auto max-w-7xl px-4 sm:px-6" data-no-reveal>
                <div class="flex flex-wrap items-end justify-between gap-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-400">Berpengalaman di</p>
                        <h2 class="mt-4 max-w-lg text-3xl font-extrabold tracking-tight text-balance sm:text-4xl">Industri yang cocok dengan solusi ini</h2>
                    </div>
                    <p class="max-w-sm text-[15px] leading-relaxed text-neutral-400">Pola pengerjaan kami menyesuaikan proses dan regulasi tiap sektor.</p>
                </div>

                <div class="mt-14 divide-y divide-white/10 border-t border-white/10">
                    @foreach ($industries as $industry)
                        <div class="grid gap-4 py-7 lg:grid-cols-12 lg:items-center lg:gap-8">
                            <div class="lg:col-span-1">
                                <span class="text-sm font-extrabold tabular-nums text-white/25">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="lg:col-span-4">
                                <h3 class="text-lg font-bold tracking-tight sm:text-xl">{{ $industry->name }}</h3>
                                @if ($industry->technologyList())
                                    <p class="mt-2 text-[13px] font-semibold text-neutral-500">{{ implode(' · ', $industry->technologyList()) }}</p>
                                @endif
                            </div>
                            <div class="lg:col-span-7">
                                <p class="text-[15px] leading-relaxed text-neutral-400">{{ $industry->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ SOLUSI TERKAIT ============ --}}
    @if ($related->isNotEmpty())
        <section class="border-t border-neutral-100 py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6" data-no-reveal>
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <h2 class="text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl">Solusi lain</h2>
                    <a href="{{ route('solusi.index') }}" class="group inline-flex items-center gap-2 text-sm font-bold text-navy-800 transition hover:text-brand-700">
                        Semua solusi
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        @php($itemImage = $item->image_path ? (str_starts_with($item->image_path, 'img/') ? asset($item->image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($item->image_path)) : null)
                        <a href="{{ route('solusi.show', $item->slug) }}" class="group flex flex-col overflow-hidden rounded-2xl border border-neutral-200 bg-white transition hover:-translate-y-1 hover:border-brand-200 hover:shadow-xl hover:shadow-navy-950/5">
                            <span class="block h-40 overflow-hidden bg-neutral-100">
                                @if ($itemImage)
                                    <img src="{{ $itemImage }}" alt="{{ $item->title }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy">
                                @else
                                    <span class="flex h-full w-full items-center justify-center bg-navy-950 text-lg font-black text-white/20">{{ str_pad((string) $item->sort_order, 2, '0', STR_PAD_LEFT) }}</span>
                                @endif
                            </span>
                            <span class="flex flex-1 flex-col p-6">
                                <span class="text-[11px] font-bold uppercase tracking-[0.16em] text-brand-700">Solusi</span>
                                <h3 class="mt-2 text-lg font-bold tracking-tight text-navy-950 transition group-hover:text-brand-700">{{ $item->title }}</h3>
                                @if ($item->description)
                                    <span class="mt-2 flex-1 text-sm leading-relaxed text-neutral-500">{{ \Illuminate\Support\Str::limit($item->description, 110) }}</span>
                                @endif
                                <span class="mt-5 inline-flex items-center gap-2 text-[13px] font-bold text-navy-800 transition group-hover:text-brand-700">Selengkapnya<svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ CTA ============ --}}
    <section class="bg-navy-900 py-16 text-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-4 sm:px-6">
            <div class="max-w-xl">
                <h2 class="text-2xl font-extrabold tracking-tight text-balance sm:text-3xl">Butuh solusi ini untuk bisnis Anda?</h2>
                <p class="mt-2 text-[15px] text-neutral-300">Ceritakan kebutuhan bisnis Anda. Kami bantu petakan solusi, lingkup, dan tahapan pengerjaannya.</p>
            </div>
            <a href="{{ route('kontak') }}" class="rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">Mulai Konsultasi</a>
        </div>
    </section>
@endsection
