@extends('admin.layouts.app')

@section('title', ($hero->exists ? 'Ubah Hero' : 'Tambah Hero').' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header
    :title="$hero->exists ? 'Ubah Hero' : 'Tambah Hero'"
    eyebrow="Konten"
    :description="$hero->exists ? 'Perbarui hero homepage.' : 'Tambahkan hero baru untuk homepage.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.heroes.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" enctype="multipart/form-data"
    action="{{ $hero->exists ? route('admin.heroes.update', $hero) : route('admin.heroes.store') }}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if ($hero->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Judul" name="title" :required="true" class="sm:col-span-2">
            <input type="text" name="title" id="title" value="{{ old('title', $hero->title) }}" required maxlength="200"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Sorotan (highlight)" name="highlight" hint="Kata yang diberi warna aksen di judul.">
            <input type="text" name="highlight" id="highlight" value="{{ old('highlight', $hero->highlight) }}" maxlength="200"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Urutan" name="sort_order">
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $hero->sort_order ?? 0) }}" min="0"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Status" name="status" :required="true">
            <select name="status" id="status" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('status', $hero->status?->value) === null)>Pilih status…</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $hero->status?->value) === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Label CTA utama" name="cta_label">
            <input type="text" name="cta_label" id="cta_label" value="{{ old('cta_label', $hero->cta_label) }}" maxlength="100"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="URL CTA utama" name="cta_url">
            <input type="text" name="cta_url" id="cta_url" value="{{ old('cta_url', $hero->cta_url) }}" maxlength="255" placeholder="/kontak"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Label CTA kedua" name="secondary_cta_label">
            <input type="text" name="secondary_cta_label" id="secondary_cta_label" value="{{ old('secondary_cta_label', $hero->secondary_cta_label) }}" maxlength="100"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="URL CTA kedua" name="secondary_cta_url">
            <input type="text" name="secondary_cta_url" id="secondary_cta_url" value="{{ old('secondary_cta_url', $hero->secondary_cta_url) }}" maxlength="255" placeholder="/portfolio"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.image-input name="image_path" :value="$hero->image_path" label="Gambar latar hero" hint="Gambar lebar penuh, mis. 1920×1080." shape="rect" class="sm:col-span-2" />

        <x-admin.field label="Deskripsi" name="description" class="sm:col-span-2">
            <textarea name="description" id="description" rows="4"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('description', $hero->description) }}</textarea>
        </x-admin.field>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.heroes.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
