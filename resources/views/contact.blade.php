@extends('layouts.site')

@section('title', 'Kontak — Nusakode')
@section('meta-description', 'Hubungi PT Nusakode Teknologi untuk diskusi kebutuhan aplikasi, sistem informasi, dan infrastruktur TI perusahaan Anda.')

@section('content')
@php
$faqs = [
    ['q' => 'Berapa biaya pengerjaan sebuah proyek?', 'a' => 'Tergantung lingkupnya. Setelah diskusi awal, Anda menerima penawaran tertulis dengan biaya tetap — tidak ada tagihan kejutan di tengah jalan. Sebagai gambaran, aplikasi operasional skala menengah umumnya berada di kisaran puluhan hingga ratusan juta rupiah.'],
    ['q' => 'Berapa lama waktu pengerjaannya?', 'a' => 'Modul tunggal biasanya 4–8 minggu, sedangkan sistem terintegrasi 3–6 bulan. Jadwal rinci per modul tercantum dalam dokumen penawaran dan didemokan berkala.'],
    ['q' => 'Apakah kode sumber menjadi milik kami?', 'a' => 'Ya, sepenuhnya. Kode sumber, dokumentasi, kredensial, dan aset lainnya diserahterimakan 100% kepada Anda, berikut pelatihan untuk operator dan tim internal.'],
    ['q' => 'Bagaimana dukungan setelah proyek selesai?', 'a' => 'Anda dapat melanjutkan dengan kontrak pemeliharaan ber-SLA: waktu respons terukur, pemantauan uptime, pembaruan keamanan rutin, dan laporan berkala. Masa garansi perbaikan bug berlaku untuk setiap serah terima modul.'],
];
@endphp

    {{-- ============ 1. HERO ============ --}}
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-3xl py-20 sm:py-28">
                <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.24em] text-navy-950"><span class="h-px w-10 bg-brand-600" aria-hidden="true"></span>Hubungi Nusakode</p>
                <h1 class="mt-6 text-4xl font-extrabold leading-[1.05] tracking-tight text-balance text-navy-950 sm:text-6xl">Bangun sesuatu yang berarti bersama kami.</h1>
                <p class="mt-6 max-w-xl text-base leading-relaxed text-neutral-500">Ceritakan kebutuhan digital Anda. Kami akan membantu menerjemahkannya menjadi solusi yang jelas, terukur, dan siap dikembangkan.</p>
                <div class="mt-10 flex flex-wrap items-center gap-3">
                    <a href="#proyek" class="group inline-flex items-center gap-2 rounded-full bg-brand-600 px-8 py-4 text-sm font-bold text-white transition hover:bg-brand-700">
                        Mulai Percakapan
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                    <a href="https://wa.me/622150001234?text=Halo%20Nusakode%2C%20saya%20ingin%20bertanya." target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border border-neutral-300 px-8 py-4 text-sm font-bold text-navy-950 transition hover:border-navy-950">
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ 2. FORMULIR PROYEK ============ --}}
    <section id="proyek" class="scroll-mt-24 bg-neutral-50">
        <div class="mx-auto grid max-w-7xl gap-14 px-4 py-20 sm:px-6 sm:py-28 lg:grid-cols-12 lg:gap-20">
            <div class="lg:col-span-5">
                <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.24em] text-navy-950"><span class="h-px w-10 bg-brand-600" aria-hidden="true"></span>Memulai Proyek</p>
                <h2 class="mt-6 text-3xl font-extrabold leading-tight tracking-tight text-balance text-navy-950 sm:text-4xl">Jelaskan apa yang ingin Anda bangun.</h2>
                <p class="mt-5 max-w-md text-[15px] leading-relaxed text-neutral-500">Ceritakan kebutuhan, tujuan, dan gambaran proyek Anda. Tim Nusakode akan mempelajarinya sebelum menghubungi Anda.</p>

                <ol class="mt-12 border-t border-neutral-300">
                    @foreach (['Ceritakan kebutuhan', 'Kami mempelajari proyek', 'Diskusi dan penyusunan solusi'] as $step)
                        <li class="flex items-baseline gap-6 border-b border-neutral-300 py-5">
                            <span class="text-sm font-bold tabular-nums text-brand-600">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-[15px] font-bold text-navy-950">{{ $step }}</span>
                        </li>
                    @endforeach
                </ol>

                <div class="mt-12 border-t border-neutral-300 pt-8">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-neutral-500">Atau hubungi langsung</p>
                    <ul class="mt-4 space-y-2.5 text-[15px] font-bold text-navy-950">
                        <li><a href="mailto:halo@nusakode.id?subject=Diskusi%20Kebutuhan%20TI" class="transition hover:text-brand-600">halo@nusakode.id</a></li>
                        <li><a href="tel:+622150001234" class="transition hover:text-brand-600">+62 21 5000 1234</a> <span class="font-medium text-neutral-500">— Telepon / WhatsApp</span></li>
                        <li class="font-medium text-neutral-500">Jakarta Selatan, Indonesia</li>
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-7">
                <form method="post" action="{{ route('kontak.permintaan') }}" class="border border-neutral-200 bg-white p-8 sm:p-12">
                    @csrf

                    @if (session('success'))
                        <p class="mb-10 border-l-2 border-brand-600 bg-neutral-50 px-5 py-4 text-sm leading-relaxed text-navy-950" role="status">{{ session('success') }}</p>
                    @endif

                    <div class="grid gap-x-8 gap-y-9 sm:grid-cols-2">
                        <div>
                            <label for="name" class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-500">Nama</label>
                            <input id="name" name="name" type="text" required autocomplete="name" maxlength="100" value="{{ old('name') }}" placeholder="Nama Anda"
                                class="mt-1 w-full border-b bg-transparent py-3 text-[15px] text-navy-950 placeholder:text-neutral-400 focus:outline-none @error('name') border-red-600 @else border-neutral-300 focus:border-brand-600 @enderror">
                            @error('name')
                                <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-500">Email</label>
                            <input id="email" name="email" type="email" required autocomplete="email" maxlength="255" value="{{ old('email') }}" placeholder="nama@perusahaan.co.id"
                                class="mt-1 w-full border-b bg-transparent py-3 text-[15px] text-navy-950 placeholder:text-neutral-400 focus:outline-none @error('email') border-red-600 @else border-neutral-300 focus:border-brand-600 @enderror">
                            @error('email')
                                <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="company" class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-500">Perusahaan</label>
                            <input id="company" name="company" type="text" autocomplete="organization" maxlength="150" value="{{ old('company') }}" placeholder="Nama perusahaan (opsional)"
                                class="mt-1 w-full border-b bg-transparent py-3 text-[15px] text-navy-950 placeholder:text-neutral-400 focus:outline-none @error('company') border-red-600 @else border-neutral-300 focus:border-brand-600 @enderror">
                            @error('company')
                                <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="project-type" class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-500">Jenis Proyek</label>
                            <select id="project-type" name="project_type" required
                                class="mt-1 w-full border-b bg-transparent py-3 text-[15px] text-navy-950 focus:outline-none @error('project_type') border-red-600 @else border-neutral-300 focus:border-brand-600 @enderror">
                                <option value="" disabled @selected(! old('project_type'))>Pilih jenis proyek</option>
                                @foreach (\App\Models\ProjectInquiry::PROJECT_TYPES as $type)
                                    <option value="{{ $type }}" @selected(old('project_type') === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('project_type')
                                <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="budget-range" class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-500">Perkiraan Anggaran</label>
                            <select id="budget-range" name="budget_range"
                                class="mt-1 w-full border-b bg-transparent py-3 text-[15px] text-navy-950 focus:outline-none @error('budget_range') border-red-600 @else border-neutral-300 focus:border-brand-600 @enderror">
                                <option value="" disabled @selected(! old('budget_range'))>Pilih rentang anggaran (opsional)</option>
                                @foreach (\App\Models\ProjectInquiry::BUDGET_RANGES as $range)
                                    <option value="{{ $range }}" @selected(old('budget_range') === $range)>{{ $range }}</option>
                                @endforeach
                            </select>
                            @error('budget_range')
                                <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="project-detail" class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-500">Ceritakan Proyek Anda</label>
                            <textarea id="project-detail" name="project_detail" rows="5" required maxlength="5000" placeholder="Latar belakang, tujuan, dan gambaran kebutuhan Anda…"
                                class="mt-1 w-full resize-y border-b bg-transparent py-3 text-[15px] leading-relaxed text-navy-950 placeholder:text-neutral-400 focus:outline-none @error('project_detail') border-red-600 @else border-neutral-300 focus:border-brand-600 @enderror">{{ old('project_detail') }}</textarea>
                            @error('project_detail')
                                <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="group mt-10 inline-flex w-full items-center justify-center gap-2 rounded-full bg-navy-950 px-8 py-4 text-sm font-bold text-white transition hover:bg-brand-600">
                        Kirim Permintaan
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </button>
                    <p class="mt-4 text-xs leading-relaxed text-neutral-500">Data Anda hanya digunakan untuk menindaklanjuti permintaan ini.</p>
                </form>
            </div>
        </div>
    </section>

    {{-- ============ 3. FAQ ============ --}}
    <section class="bg-white">
        <div class="mx-auto max-w-3xl px-4 py-20 sm:px-6 sm:py-28">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-neutral-500">Pertanyaan Umum</p>
            <h2 class="mt-5 text-3xl font-extrabold tracking-tight text-balance text-navy-950 sm:text-4xl">Sebelum kita mulai.</h2>

            <div class="mt-12 border-t border-neutral-200">
                @foreach ($faqs as $faq)
                    <details class="group border-b border-neutral-200">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-6 py-6 text-base font-bold text-navy-950 transition hover:text-brand-600 [&::-webkit-details-marker]:hidden">
                            {{ $faq['q'] }}
                            <svg class="h-4 w-4 shrink-0 text-neutral-400 transition-transform duration-300 group-open:rotate-45 group-open:text-navy-950" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                        </summary>
                        <p class="max-w-2xl pb-7 pr-10 text-sm leading-relaxed text-neutral-500">{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ 4. CTA ============ --}}
    <section class="bg-navy-950">
        <div class="mx-auto max-w-2xl px-4 py-16 text-center sm:px-6 sm:py-20">
            <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-balance text-white sm:text-4xl">Siap membicarakan<br> proyek Anda?</h2>
            <p class="mx-auto mt-5 max-w-md text-[15px] leading-relaxed text-neutral-300">Mulai dengan percakapan sederhana. Kami akan membantu menentukan langkah berikutnya.</p>
            <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                <a href="mailto:halo@nusakode.id?subject=Diskusi%20Kebutuhan%20TI" class="rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-700">Kirim Email</a>
                <a href="https://wa.me/622150001234?text=Halo%20Nusakode%2C%20saya%20ingin%20bertanya." target="_blank" rel="noopener" class="rounded-full border border-white/30 px-8 py-3.5 text-sm font-bold text-white transition hover:border-white">WhatsApp</a>
            </div>
        </div>
    </section>
@endsection
