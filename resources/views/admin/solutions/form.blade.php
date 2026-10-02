@extends('admin.layouts.app')

@section('title', ($solution->exists ? 'Ubah Solusi' : 'Tambah Solusi').' — Admin KIT Konsultan IT')

@php
    $featuresText = old('features_text', implode("\n", $solution->features ?? []));
    $benefitsText = old('benefits_text', implode("\n", $solution->benefits ?? []));
@endphp

@section('content')
<x-admin.page-header
    :title="$solution->exists ? 'Ubah Solusi' : 'Tambah Solusi'"
    eyebrow="Konten"
    :description="$solution->exists ? 'Perbarui solusi '.$solution->title.'.' : 'Tambahkan solusi baru di bawah kategori.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.solutions.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" enctype="multipart/form-data"
    action="{{$solution->exists ? route('admin.solutions.update',$solution) : route('admin.solutions.store')}}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if($solution->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Kategori" name="solution_category_id" :required="true">
            <select name="solution_category_id" id="solution_category_id" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('solution_category_id',$solution->solution_category_id)===null)>Pilih kategori…</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}" @selected((int)old('solution_category_id',$solution->solution_category_id)===$category->id)>{{$category->name}}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Partner" name="partner_name" hint="Nama produk atau mitra, mis. SAP SuccessFactors.">
            <input type="text" name="partner_name" id="partner_name" value="{{old('partner_name',$solution->partner_name)}}" maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Judul" name="title" :required="true">
            <input type="text" name="title" id="title" value="{{old('title',$solution->title)}}" required maxlength="180"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Slug" name="slug" hint="Kosongkan untuk otomatis dari judul.">
            <input type="text" name="slug" id="slug" value="{{old('slug',$solution->slug)}}" maxlength="200"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Subjudul" name="subtitle" class="sm:col-span-2">
            <input type="text" name="subtitle" id="subtitle" value="{{old('subtitle',$solution->subtitle)}}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Ringkasan" name="excerpt" class="sm:col-span-2">
            <textarea name="excerpt" id="excerpt" rows="3"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{old('excerpt',$solution->excerpt)}}</textarea>
        </x-admin.field>

        <x-admin.image-input name="logo_path" :value="$solution->logo_path" label="Logo produk" shape="rect" />
        <x-admin.image-input name="cover_image_path" :value="$solution->cover_image_path" label="Gambar sampul" shape="rect" />

        <x-admin.field label="Label CTA" name="cta_label">
            <input type="text" name="cta_label" id="cta_label" value="{{old('cta_label',$solution->cta_label)}}" maxlength="100"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="URL CTA" name="cta_url">
            <input type="text" name="cta_url" id="cta_url" value="{{old('cta_url',$solution->cta_url)}}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Fitur" name="features_text" hint="Satu fitur per baris." class="sm:col-span-2">
            <textarea name="features_text" id="features_text" rows="5"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{$featuresText}}</textarea>
        </x-admin.field>

        <x-admin.field label="Manfaat" name="benefits_text" hint="Satu manfaat per baris." class="sm:col-span-2">
            <textarea name="benefits_text" id="benefits_text" rows="5"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{$benefitsText}}</textarea>
        </x-admin.field>

        <x-admin.field label="Isi" name="body" class="sm:col-span-2">
            <textarea name="body" id="body" rows="6"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{old('body',$solution->body)}}</textarea>
        </x-admin.field>

        <x-admin.field label="Urutan" name="sort_order">
            <input type="number" name="sort_order" id="sort_order" value="{{old('sort_order',$solution->sort_order ?? 0)}}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Status" name="status" :required="true">
            <select name="status" id="status" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('status',$solution->status?->value)===null)>Pilih status…</option>
                @foreach($statuses as $status)
                    <option value="{{$status->value}}" @selected(old('status',$solution->status?->value)===$status->value)>{{$status->label()}}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Unggulan" name="is_featured" class="sm:col-span-2">
            <label class="flex items-center gap-2.5 text-[13px] text-neutral-700">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured',$solution->is_featured)) class="h-4 w-4 accent-[#2563EB]">
                Tandai sebagai solusi unggulan
            </label>
        </x-admin.field>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.solutions.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
