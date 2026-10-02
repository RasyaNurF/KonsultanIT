@extends('admin.layouts.app')

@section('title', ($seo->exists ? 'Ubah SEO' : 'Tambah SEO').' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header
    :title="$seo->exists ? 'Ubah SEO' : 'Tambah SEO'"
    eyebrow="Website"
    :description="$seo->exists ? 'Perbarui pengaturan SEO '.$seo->path.'.' : 'Tambahkan pengaturan SEO untuk sebuah halaman.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.seo.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" enctype="multipart/form-data"
    action="{{ $seo->exists ? route('admin.seo.update', $seo) : route('admin.seo.store') }}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if ($seo->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Path" name="path" hint="Contoh: / atau /kontak" :required="true">
            <input type="text" name="path" id="path" value="{{ old('path', $seo->path) }}" required maxlength="255" placeholder="/"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Label" name="label" :required="true">
            <input type="text" name="label" id="label" value="{{ old('label', $seo->label) }}" required maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="SEO title" name="title">
            <input type="text" name="title" id="title" value="{{ old('title', $seo->title) }}" maxlength="200"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Canonical URL" name="canonical_url">
            <input type="url" name="canonical_url" id="canonical_url" value="{{ old('canonical_url', $seo->canonical_url) }}" maxlength="255" placeholder="https://"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Meta description" name="description" class="sm:col-span-2">
            <textarea name="description" id="description" rows="3" maxlength="300"
                class="h-auto w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('description', $seo->description) }}</textarea>
        </x-admin.field>

        <x-admin.image-input name="og_image_path" :value="$seo->og_image_path" label="OG image" shape="rect" class="sm:col-span-2" />

        <label class="flex items-center gap-2.5 sm:col-span-2">
            <input type="checkbox" name="is_indexable" value="1" @checked(old('is_indexable', $seo->exists ? $seo->is_indexable : true)) class="h-4 w-4 accent-[#2563EB]">
            <span class="text-[13px] font-semibold text-neutral-800">Index halaman ini</span>
        </label>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.seo.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
