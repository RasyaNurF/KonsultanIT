@extends('admin.layouts.app')

@section('title', ($category->exists ? 'Ubah Kategori Solusi' : 'Tambah Kategori Solusi').' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header
    :title="$category->exists ? 'Ubah Kategori Solusi' : 'Tambah Kategori Solusi'"
    eyebrow="Konten"
    :description="$category->exists ? 'Perbarui kategori '.$category->name.'.' : 'Tambahkan kategori solusi baru untuk KIT Konsultan IT.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.solution-categories.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" enctype="multipart/form-data"
    action="{{$category->exists ? route('admin.solution-categories.update',$category) : route('admin.solution-categories.store')}}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if($category->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Nama" name="name" :required="true">
            <input type="text" name="name" id="name" value="{{old('name',$category->name)}}" required maxlength="120"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Slug" name="slug" hint="Kosongkan untuk otomatis dari nama.">
            <input type="text" name="slug" id="slug" value="{{old('slug',$category->slug)}}" maxlength="140"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Tagline" name="tagline" hint="Ringkasan singkat kategori.">
            <input type="text" name="tagline" id="tagline" value="{{old('tagline',$category->tagline)}}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Ikon" name="icon" hint="Nama ikon opsional.">
            <input type="text" name="icon" id="icon" value="{{old('icon',$category->icon)}}" maxlength="100"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Urutan" name="sort_order">
            <input type="number" name="sort_order" id="sort_order" value="{{old('sort_order',$category->sort_order ?? 0)}}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Status" name="status" :required="true">
            <select name="status" id="status" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('status',$category->status?->value)===null)>Pilih status…</option>
                @foreach($statuses as $status)
                    <option value="{{$status->value}}" @selected(old('status',$category->status?->value)===$status->value)>{{$status->label()}}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.image-input name="image_path" :value="$category->image_path" label="Gambar kategori" shape="rect" class="sm:col-span-2" />

        <x-admin.field label="Deskripsi" name="description" class="sm:col-span-2">
            <textarea name="description" id="description" rows="5"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{old('description',$category->description)}}</textarea>
        </x-admin.field>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.solution-categories.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
