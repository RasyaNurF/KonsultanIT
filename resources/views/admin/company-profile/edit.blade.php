@extends('admin.layouts.app')

@section('title', 'Profil Perusahaan — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Profil Perusahaan" description="Identitas, visi, misi, dan nilai yang tampil di halaman tentang." eyebrow="Konten" />

<form method="post" enctype="multipart/form-data" action="{{ route('admin.company.update') }}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @method('put')

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Nama perusahaan" name="name" :required="true">
            <input type="text" name="name" id="name" value="{{ old('name', $profile->name) }}" required maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Tagline" name="tagline">
            <input type="text" name="tagline" id="tagline" value="{{ old('tagline', $profile->tagline) }}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Email" name="email">
            <input type="email" name="email" id="email" value="{{ old('email', $profile->email) }}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Telepon" name="phone">
            <input type="text" name="phone" id="phone" value="{{ old('phone', $profile->phone) }}" maxlength="50"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Tahun berdiri" name="founded_year">
            <input type="number" name="founded_year" id="founded_year" value="{{ old('founded_year', $profile->founded_year) }}" min="1900" max="2100"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Alamat" name="address">
            <input type="text" name="address" id="address" value="{{ old('address', $profile->address) }}" maxlength="500"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.image-input name="logo_path" :value="$profile->logo_path" label="Logo" shape="rect" />
        <x-admin.image-input name="cover_image_path" :value="$profile->cover_image_path" label="Gambar sampul" hint="Tampil di halaman tentang." shape="rect" />

        <x-admin.field label="Deskripsi singkat" name="description" class="sm:col-span-2">
            <textarea name="description" id="description" rows="3"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('description', $profile->description) }}</textarea>
        </x-admin.field>

        <x-admin.field label="Tentang perusahaan" name="about" class="sm:col-span-2">
            <textarea name="about" id="about" rows="5"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('about', $profile->about) }}</textarea>
        </x-admin.field>

        <x-admin.field label="Visi" name="vision" class="sm:col-span-2">
            <textarea name="vision" id="vision" rows="3"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('vision', $profile->vision) }}</textarea>
        </x-admin.field>

        <x-admin.field label="Misi" name="mission" hint="Satu poin per baris bila berupa daftar." class="sm:col-span-2">
            <textarea name="mission" id="mission" rows="4"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('mission', $profile->mission) }}</textarea>
        </x-admin.field>

        <x-admin.field label="Nilai perusahaan" name="values" hint="Satu nilai per baris." class="sm:col-span-2">
            <textarea name="values" id="values" rows="4"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('values', is_array($profile->values) ? implode("\n", $profile->values) : $profile->values) }}</textarea>
        </x-admin.field>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
