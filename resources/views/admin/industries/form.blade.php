@extends('admin.layouts.app')

@section('title', ($industry->exists ? 'Ubah Industri' : 'Tambah Industri').' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header
    :title="$industry->exists ? 'Ubah Industri' : 'Tambah Industri'"
    eyebrow="Konten"
    :description="$industry->exists ? 'Perbarui data industri '.$industry->name.'.' : 'Tambahkan industri baru KIT Konsultan IT.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.industries.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" enctype="multipart/form-data"
    action="{{ $industry->exists ? route('admin.industries.update', $industry) : route('admin.industries.store') }}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if ($industry->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Nama" name="name" :required="true">
            <input type="text" name="name" id="name" value="{{ old('name', $industry->name) }}" required maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Slug" name="slug" hint="Kosongkan untuk otomatis dari nama.">
            <input type="text" name="slug" id="slug" value="{{ old('slug', $industry->slug) }}" maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Urutan" name="sort_order">
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $industry->sort_order ?? 0) }}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Status" name="status" :required="true">
            <select name="status" id="status" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('status', $industry->status?->value) === null)>Pilih status…</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $industry->status?->value) === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.image-input name="image_path" :value="$industry->image_path" label="Gambar industri" shape="rect" class="sm:col-span-2" />

        <x-admin.field label="Teknologi" name="technologies" hint="Pisahkan dengan koma. Contoh: Laravel, MySQL, Redis." class="sm:col-span-2">
            <input type="text" name="technologies" id="technologies" value="{{ old('technologies', $industry->technologies) }}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Deskripsi" name="description" class="sm:col-span-2">
            <textarea name="description" id="description" rows="5"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('description', $industry->description) }}</textarea>
        </x-admin.field>

        <x-admin.field label="Studi kasus" name="case_study" class="sm:col-span-2">
            <textarea name="case_study" id="case_study" rows="5"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('case_study', $industry->case_study) }}</textarea>
        </x-admin.field>

        <div class="sm:col-span-2">
            <p class="text-[13px] font-semibold text-neutral-800">Layanan terkait</p>
            <p class="mt-0.5 text-xs text-neutral-500">Pilih layanan yang relevan dengan industri ini.</p>
            <div class="mt-2">
                @if ($services->isEmpty())
                    <p class="text-[13px] text-neutral-400">Belum ada layanan. Tambahkan layanan terlebih dahulu.</p>
                @else
                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach ($services as $service)
                            <label class="flex items-center gap-2.5 rounded-md border border-neutral-200 px-3 py-2 text-[13px]">
                                <input type="checkbox" name="services[]" value="{{ $service->id }}" @checked(in_array($service->id, old('services', $selectedServices ?? []))) class="h-4 w-4 accent-[#2563EB]">
                                {{ $service->title }}
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>
            @error('services')
                <p class="mt-1.5 flex items-start gap-1 text-xs font-medium text-red-600">
                    <x-admin.icon name="alert" class="mt-px h-3.5 w-3.5 shrink-0" /> {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.industries.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
