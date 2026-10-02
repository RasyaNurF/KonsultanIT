@extends('admin.layouts.app')

@section('title', ($service->exists ? 'Ubah Layanan' : 'Tambah Layanan').' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header
    :title="$service->exists ? 'Ubah Layanan' : 'Tambah Layanan'"
    eyebrow="Konten"
    :description="$service->exists ? 'Perbarui data layanan '.$service->title.'.' : 'Tambahkan layanan baru KIT Konsultan IT.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.services.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" enctype="multipart/form-data"
    action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if ($service->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Judul" name="title" :required="true">
            <input type="text" name="title" id="title" value="{{ old('title', $service->title) }}" required maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Slug" name="slug" hint="Kosongkan untuk otomatis dari judul.">
            <input type="text" name="slug" id="slug" value="{{ old('slug', $service->slug) }}" maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Ikon" name="icon" hint="Nama ikon, mis. layers">
            <input type="text" name="icon" id="icon" value="{{ old('icon', $service->icon) }}" maxlength="100"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Urutan" name="sort_order">
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $service->sort_order ?? 0) }}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Status" name="status" :required="true">
            <select name="status" id="status" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('status', $service->status?->value) === null)>Pilih status…</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $service->status?->value) === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Teknologi" name="technologies" hint="Pisahkan dengan koma. Contoh: Laravel, MySQL, Redis.">
            <input type="text" name="technologies" id="technologies" value="{{ old('technologies', $service->technologies) }}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.image-input
            name="image_path"
            :value="$service->image_path"
            label="Gambar layanan"
            hint="Tampil di halaman utama bagian layanan. Rasio lanskap, mis. 1200×900."
            shape="rect"
            class="sm:col-span-2" />

        <x-admin.field label="Deskripsi" name="description" class="sm:col-span-2">
            <textarea name="description" id="description" rows="5"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('description', $service->description) }}</textarea>
        </x-admin.field>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.services.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
