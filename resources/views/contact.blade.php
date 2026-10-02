@extends('layouts.site')

@section('title', 'Kontak — KIT Konsultan IT')
@section('meta-description', 'Ceritakan kebutuhan proyek digital Anda kepada tim KIT Konsultan IT. Mulai diskusi pengembangan website, aplikasi, dan sistem informasi melalui formulir konsultasi.')

@section('content')
@php
    $faqs = [
        ['q' => 'Berapa biaya pengerjaan sebuah proyek?', 'a' => 'Biaya bergantung pada ruang lingkup dan kebutuhan proyek. Setelah diskusi awal, kami akan menyusun penawaran tertulis agar biaya dan pekerjaan yang termasuk di dalamnya jelas sejak awal.'],
        ['q' => 'Berapa lama waktu pengerjaannya?', 'a' => 'Jadwal ditentukan setelah kebutuhan dan ruang lingkup disepakati. Tahapan pengerjaan dan target penyelesaian akan dijelaskan dalam proposal proyek.'],
        ['q' => 'Apakah kode sumber menjadi milik kami?', 'a' => 'Ketentuan mengenai kepemilikan kode sumber, dokumentasi, dan serah terima akan dicantumkan secara jelas dalam kesepakatan proyek.'],
        ['q' => 'Bagaimana dukungan setelah proyek selesai?', 'a' => 'Kami dapat mendiskusikan kebutuhan pemeliharaan dan dukungan lanjutan sesuai sistem yang dibangun. Lingkup serta masa dukungan akan disepakati sebelum proyek dimulai.'],
    ];
@endphp

    <section id="proyek" class="scroll-mt-20 border-b border-slate-200 bg-[#f7f9fc]">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 pb-20 pt-16 sm:px-6 sm:pb-24 sm:pt-20 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] lg:gap-x-20 lg:gap-y-0 lg:py-24">
            <div class="lg:col-start-1 lg:row-start-1 lg:pt-8">
                <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.2em] text-brand-700"><span class="h-px w-8 bg-brand-600" aria-hidden="true"></span>Kontak &amp; Kolaborasi</p>
                <h1 class="mt-7 max-w-xl text-4xl font-extrabold leading-[1.12] tracking-tight text-balance text-navy-950 sm:text-5xl lg:text-[3.4rem]">Mari bicarakan proyek digital Anda.</h1>
                <p class="mt-6 max-w-lg text-base leading-8 text-slate-600">Ceritakan tujuan dan tantangan bisnis Anda. Kami akan membantu menyusun langkah pengembangan yang sesuai dengan kebutuhan proyek.</p>
            </div>

            <div class="lg:col-start-2 lg:row-span-2 lg:row-start-1">
                <form method="post" action="{{ route('kontak.permintaan') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-[0_24px_70px_-48px_rgba(5,24,41,0.45)] sm:p-9 lg:p-10">
                    @csrf
                    <div class="border-b border-slate-100 pb-7">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-700">Formulir Konsultasi</p>
                        <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-navy-950 sm:text-[1.75rem]">Ceritakan kebutuhan Anda</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Isi informasi singkat berikut agar kami dapat memahami proyek Anda.</p>
                    </div>

                    @if (session('success'))
                        <div class="mt-7 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm leading-6 text-emerald-900" role="status">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-7 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm leading-6 text-red-800" role="alert">Periksa kembali kolom yang ditandai di bawah.</div>
                    @endif

                    <div class="mt-7 grid gap-x-5 gap-y-5 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-navy-950">Nama lengkap <span class="text-brand-700" aria-hidden="true">*</span></label>
                            <input id="name" name="name" type="text" required autocomplete="name" maxlength="100" value="{{ old('name') }}" placeholder="Nama Anda" aria-invalid="@error('name') true @else false @enderror" @error('name') aria-describedby="name-error" @enderror class="mt-2 w-full rounded-lg border bg-white px-4 py-3 text-sm text-navy-950 placeholder:text-slate-400 focus:border-brand-600 focus:outline-none focus:ring-3 focus:ring-brand-100 @error('name') border-red-500 @else border-slate-300 @enderror">
                            @error('name') <p id="name-error" class="mt-1.5 text-xs text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-navy-950">Email kerja <span class="text-brand-700" aria-hidden="true">*</span></label>
                            <input id="email" name="email" type="email" required autocomplete="email" maxlength="255" value="{{ old('email') }}" placeholder="nama@perusahaan.co.id" aria-invalid="@error('email') true @else false @enderror" @error('email') aria-describedby="email-error" @enderror class="mt-2 w-full rounded-lg border bg-white px-4 py-3 text-sm text-navy-950 placeholder:text-slate-400 focus:border-brand-600 focus:outline-none focus:ring-3 focus:ring-brand-100 @error('email') border-red-500 @else border-slate-300 @enderror">
                            @error('email') <p id="email-error" class="mt-1.5 text-xs text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="company" class="block text-sm font-semibold text-navy-950">Perusahaan <span class="font-normal text-slate-500">(opsional)</span></label>
                            <input id="company" name="company" type="text" autocomplete="organization" maxlength="150" value="{{ old('company') }}" placeholder="Nama perusahaan" aria-invalid="@error('company') true @else false @enderror" @error('company') aria-describedby="company-error" @enderror class="mt-2 w-full rounded-lg border bg-white px-4 py-3 text-sm text-navy-950 placeholder:text-slate-400 focus:border-brand-600 focus:outline-none focus:ring-3 focus:ring-brand-100 @error('company') border-red-500 @else border-slate-300 @enderror">
                            @error('company') <p id="company-error" class="mt-1.5 text-xs text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="project-type" class="block text-sm font-semibold text-navy-950">Jenis proyek <span class="text-brand-700" aria-hidden="true">*</span></label>
                            <select id="project-type" name="project_type" required aria-invalid="@error('project_type') true @else false @enderror" @error('project_type') aria-describedby="project-type-error" @enderror class="mt-2 w-full rounded-lg border bg-white px-4 py-3 text-sm text-navy-950 focus:border-brand-600 focus:outline-none focus:ring-3 focus:ring-brand-100 @error('project_type') border-red-500 @else border-slate-300 @enderror">
                                <option value="" disabled @selected(! old('project_type'))>Pilih jenis proyek</option>
                                @foreach (\App\Models\ProjectInquiry::PROJECT_TYPES as $type)
                                    <option value="{{ $type }}" @selected(old('project_type') === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('project_type') <p id="project-type-error" class="mt-1.5 text-xs text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="budget-range" class="block text-sm font-semibold text-navy-950">Perkiraan anggaran <span class="font-normal text-slate-500">(opsional)</span></label>
                            <select id="budget-range" name="budget_range" aria-invalid="@error('budget_range') true @else false @enderror" @error('budget_range') aria-describedby="budget-range-error" @enderror class="mt-2 w-full rounded-lg border bg-white px-4 py-3 text-sm text-navy-950 focus:border-brand-600 focus:outline-none focus:ring-3 focus:ring-brand-100 @error('budget_range') border-red-500 @else border-slate-300 @enderror">
                                <option value="" @selected(! old('budget_range'))>Belum ditentukan</option>
                                @foreach (\App\Models\ProjectInquiry::BUDGET_RANGES as $range)
                                    <option value="{{ $range }}" @selected(old('budget_range') === $range)>{{ $range }}</option>
                                @endforeach
                            </select>
                            @error('budget_range') <p id="budget-range-error" class="mt-1.5 text-xs text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="project-detail" class="block text-sm font-semibold text-navy-950">Gambaran proyek <span class="text-brand-700" aria-hidden="true">*</span></label>
                            <textarea id="project-detail" name="project_detail" rows="5" required maxlength="5000" placeholder="Ceritakan tujuan, tantangan, atau fitur yang Anda butuhkan…" aria-invalid="@error('project_detail') true @else false @enderror" @error('project_detail') aria-describedby="project-detail-error" @enderror class="mt-2 w-full resize-y rounded-lg border bg-white px-4 py-3 text-sm leading-6 text-navy-950 placeholder:text-slate-400 focus:border-brand-600 focus:outline-none focus:ring-3 focus:ring-brand-100 @error('project_detail') border-red-500 @else border-slate-300 @enderror">{{ old('project_detail') }}</textarea>
                            @error('project_detail') <p id="project-detail-error" class="mt-1.5 text-xs text-red-700">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <button type="submit" class="group mt-7 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-6 py-3.5 text-sm font-bold text-white transition hover:bg-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">Kirim Permintaan <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                    <p class="mt-4 text-center text-xs leading-5 text-slate-500">Dengan mengirim formulir ini, Anda mengizinkan kami menghubungi Anda terkait permintaan proyek.</p>
                </form>
            </div>
            <div class="lg:col-start-1 lg:row-start-2 lg:pt-12">
                <div class="max-w-lg border-t border-slate-200 pt-8">
                    <h2 class="text-sm font-bold text-navy-950">Apa yang terjadi setelah Anda menghubungi kami?</h2>
                    <ol class="mt-6 space-y-6">
                        <li class="flex gap-4">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-brand-200 bg-white text-xs font-bold text-brand-700">01</span>
                            <div><h3 class="text-sm font-bold text-navy-950">Kami membaca kebutuhan Anda</h3><p class="mt-1 text-sm leading-6 text-slate-600">Informasi yang Anda kirim menjadi dasar untuk memahami tujuan proyek.</p></div>
                        </li>
                        <li class="flex gap-4">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-brand-200 bg-white text-xs font-bold text-brand-700">02</span>
                            <div><h3 class="text-sm font-bold text-navy-950">Tim kami menghubungi Anda</h3><p class="mt-1 text-sm leading-6 text-slate-600">Kami akan merespons untuk menggali kebutuhan dan menjawab pertanyaan awal.</p></div>
                        </li>
                        <li class="flex gap-4">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-brand-200 bg-white text-xs font-bold text-brand-700">03</span>
                            <div><h3 class="text-sm font-bold text-navy-950">Diskusikan langkah berikutnya</h3><p class="mt-1 text-sm leading-6 text-slate-600">Ruang lingkup dan pendekatan proyek dibicarakan sebelum pekerjaan dimulai.</p></div>
                        </li>
                    </ol>
                </div>
                <div class="mt-10 flex max-w-lg items-start gap-3 rounded-xl border border-slate-200 bg-white px-5 py-4">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-brand-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" stroke-linecap="round" stroke-linejoin="round"/><path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <p class="text-sm leading-6 text-slate-600">Informasi Anda digunakan untuk menindaklanjuti konsultasi ini. Kami akan menghubungi Anda maksimal 1×24 jam kerja.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-20 sm:py-24">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[minmax(0,0.7fr)_minmax(0,1.3fr)] lg:gap-20">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-700">Pertanyaan Umum</p>
                <h2 class="mt-4 max-w-sm text-3xl font-extrabold leading-tight tracking-tight text-navy-950 sm:text-4xl">Hal yang sering ditanyakan.</h2>
                <p class="mt-4 max-w-sm text-sm leading-7 text-slate-600">Informasi awal sebelum kita membahas kebutuhan proyek Anda lebih jauh.</p>
            </div>
            <div class="border-t border-slate-200">
                @foreach ($faqs as $faq)
                    <details class="group border-b border-slate-200">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-6 py-5 text-left text-sm font-bold text-navy-950 transition hover:text-brand-700 sm:text-base [&::-webkit-details-marker]:hidden">
                            {{ $faq['q'] }}
                            <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform duration-200 group-open:rotate-45 group-open:text-brand-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                        </summary>
                        <p class="max-w-2xl pb-6 pr-9 text-sm leading-7 text-slate-600">{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-t border-slate-200 bg-[#f7f9fc]">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-10 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-700">Lihat pengalaman kami</p>
                <h2 class="mt-2 text-xl font-extrabold text-navy-950 sm:text-2xl">Kenali proyek yang telah kami kerjakan.</h2>
            </div>
            <a href="{{ route('portfolio.index') }}" class="inline-flex w-fit items-center gap-2 rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-navy-950 transition hover:border-brand-400 hover:text-brand-700">Lihat Portofolio <span aria-hidden="true">→</span></a>
        </div>
    </section>
@endsection
