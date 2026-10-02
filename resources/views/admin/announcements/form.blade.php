@extends('admin.layouts.app')

@section('title', ($announcement->exists ? 'Ubah Pengumuman' : 'Tambah Pengumuman').' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header
    :title="$announcement->exists ? 'Ubah Pengumuman' : 'Tambah Pengumuman'"
    eyebrow="Website"
    :description="$announcement->exists ? 'Perbarui pengumuman yang tampil di website.' : 'Atur bilah atas, pop-up masuk, atau banner cookie.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.announcements.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" enctype="multipart/form-data"
    action="{{ $announcement->exists ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if ($announcement->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Jenis" name="placement" :required="true" hint="Bilah atas tampil di semua halaman; pop-up tampil saat pengunjung masuk; cookie untuk persetujuan.">
            <select name="placement" id="placement" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                @foreach ($placements as $placement)
                    <option value="{{ $placement->value }}" @selected(old('placement', $announcement->placement?->value) === $placement->value)>{{ $placement->label() }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Judul" name="title" hint="Opsional. Dipakai sebagai judul pop-up atau banner cookie.">
            <input type="text" name="title" id="title" value="{{ old('title', $announcement->title) }}" maxlength="200"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Isi Pesan" name="message" :required="true">
            <textarea name="message" id="message" rows="4" required maxlength="2000"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('message', $announcement->message) }}</textarea>
        </x-admin.field>

        <x-admin.field label="Label Tautan" name="link_label" hint="Opsional, mis. “Konsultasi gratis”.">
            <input type="text" name="link_label" id="link_label" value="{{ old('link_label', $announcement->link_label) }}" maxlength="100"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="URL Tautan" name="link_url" hint="Boleh relatif, mis. /kontak.">
            <input type="text" name="link_url" id="link_url" value="{{ old('link_url', $announcement->link_url) }}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Frekuensi" name="frequency" :required="true" hint="Berlaku untuk pop-up. “Setiap kunjungan” memakai penanda per tab, jadi tidak berulang saat berpindah halaman.">
            <select name="frequency" id="frequency" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                @foreach (\App\Enums\AnnouncementFrequency::cases() as $frequency)
                    <option value="{{ $frequency->value }}" @selected(old('frequency', $announcement->frequency?->value ?? \App\Enums\AnnouncementFrequency::Always->value) === $frequency->value)>{{ $frequency->label() }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Jumlah Hari" name="frequency_days" hint="Wajib bila frekuensi “Sekali per beberapa hari”.">
            <input type="number" name="frequency_days" id="frequency_days" min="1" max="365" value="{{ old('frequency_days', $announcement->frequency_days) }}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Mulai Tampil" name="starts_at" hint="Kosongkan agar langsung tampil.">
            <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $announcement->starts_at?->format('Y-m-d\TH:i')) }}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Berakhir" name="ends_at" hint="Kosongkan agar tampil terus.">
            <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', $announcement->ends_at?->format('Y-m-d\TH:i')) }}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Urutan" name="sort_order" hint="Pengumuman dengan urutan terkecil tampil lebih dulu.">
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $announcement->sort_order ?? 0) }}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.image-input
            name="image_path"
            :value="$announcement->image_path"
            label="Gambar"
            hint="Opsional, dipakai pada pop-up. Rasio lanskap, mis. 1200×600."
            shape="rect"
            class="sm:col-span-2" />

        <label class="flex items-center gap-2.5">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $announcement->is_active ?? true)) class="h-4 w-4 accent-[#2563EB]">
            <span class="text-[13px] font-semibold text-neutral-800">Aktifkan pengumuman</span>
        </label>

        <label class="flex items-center gap-2.5">
            <input type="checkbox" name="is_dismissible" value="1" @checked(old('is_dismissible', $announcement->is_dismissible ?? true)) class="h-4 w-4 accent-[#2563EB]">
            <span class="text-[13px] font-semibold text-neutral-800">Boleh ditutup pengunjung</span>
        </label>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.announcements.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
