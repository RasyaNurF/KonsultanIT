@extends('admin.layouts.app')

@section('title', ($resource->exists ? 'Ubah Resource' : 'Tambah Resource').' — Admin KIT Konsultan IT')

@php
    $startsAt = old('starts_at', $resource->starts_at?->format('Y-m-d\TH:i'));
    $endsAt = old('ends_at', $resource->ends_at?->format('Y-m-d\TH:i'));
    $publishedAt = old('published_at', $resource->published_at?->format('Y-m-d\TH:i'));
    $isEvent = old('type', $resource->type?->value) === 'event';
@endphp

@section('content')
<x-admin.page-header
    :title="$resource->exists ? 'Ubah Resource' : 'Tambah Resource'"
    eyebrow="Konten"
    :description="$resource->exists ? 'Perbarui resource '.$resource->title.'.' : 'Tambahkan resource baru untuk KIT Konsultan IT.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.resources.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" enctype="multipart/form-data"
    action="{{$resource->exists ? route('admin.resources.update',$resource) : route('admin.resources.store')}}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if($resource->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Tipe" name="type" :required="true">
            <select name="type" id="type" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('type',$resource->type?->value)===null)>Pilih tipe…</option>
                @foreach($types as $type)
                    <option value="{{$type->value}}" @selected(old('type',$resource->type?->value)===$type->value)>{{$type->label()}}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Status" name="status" :required="true">
            <select name="status" id="status" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('status',$resource->status?->value)===null)>Pilih status…</option>
                @foreach($statuses as $status)
                    <option value="{{$status->value}}" @selected(old('status',$resource->status?->value)===$status->value)>{{$status->label()}}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Judul" name="title" :required="true" class="sm:col-span-2">
            <input type="text" name="title" id="title" value="{{old('title',$resource->title)}}" required maxlength="200"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Slug" name="slug" hint="Kosongkan untuk otomatis dari judul.">
            <input type="text" name="slug" id="slug" value="{{old('slug',$resource->slug)}}" maxlength="220"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Urutan" name="sort_order">
            <input type="number" name="sort_order" id="sort_order" value="{{old('sort_order',$resource->sort_order ?? 0)}}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Ringkasan" name="excerpt" class="sm:col-span-2">
            <textarea name="excerpt" id="excerpt" rows="3"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{old('excerpt',$resource->excerpt)}}</textarea>
        </x-admin.field>

        <x-admin.image-input name="cover_image_path" :value="$resource->cover_image_path" label="Gambar sampul" shape="rect" class="sm:col-span-2" />

        <x-admin.field label="Lokasi" name="location" hint="Untuk event.">
            <input type="text" name="location" id="location" value="{{old('location',$resource->location)}}" maxlength="180"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Penyelenggara" name="organizer" hint="Untuk event atau webinar.">
            <input type="text" name="organizer" id="organizer" value="{{old('organizer',$resource->organizer)}}" maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Mulai" name="starts_at" hint="Untuk event.">
            <input type="datetime-local" name="starts_at" id="starts_at" value="{{$startsAt}}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Selesai" name="ends_at" hint="Untuk event.">
            <input type="datetime-local" name="ends_at" id="ends_at" value="{{$endsAt}}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Link eksternal" name="external_url" hint="Untuk pendaftaran event atau tautan unduhan.">
            <input type="text" name="external_url" id="external_url" value="{{old('external_url',$resource->external_url)}}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Label CTA" name="cta_label">
            <input type="text" name="cta_label" id="cta_label" value="{{old('cta_label',$resource->cta_label)}}" maxlength="100"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="URL CTA" name="cta_url">
            <input type="text" name="cta_url" id="cta_url" value="{{old('cta_url',$resource->cta_url)}}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Tanggal publikasi" name="published_at" hint="Kosongkan agar otomatis saat published.">
            <input type="datetime-local" name="published_at" id="published_at" value="{{$publishedAt}}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Unggulan" name="is_featured">
            <label class="flex h-10 items-center gap-2.5 text-[13px] text-neutral-700">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured',$resource->is_featured)) class="h-4 w-4 accent-[#2563EB]">
                Tandai sebagai unggulan
            </label>
        </x-admin.field>

                <x-admin.field label="Industri" name="industry" hint="Untuk go-live: sektor klien.">
            <input type="text" name="industry" id="industry" value="{{old('industry',$resource->industry)}}" maxlength="120"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Jumlah halaman" name="page_count" hint="Untuk whitepaper/e-book.">
            <input type="number" name="page_count" id="page_count" min="1" value="{{old('page_count',$resource->page_count)}}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="URL pendaftaran" name="register_url" hint="Untuk event.">
            <input type="text" name="register_url" id="register_url" value="{{old('register_url',$resource->register_url)}}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="URL rekaman" name="recording_url" hint="Untuk event yang sudah berlalu.">
            <input type="text" name="recording_url" id="recording_url" value="{{old('recording_url',$resource->recording_url)}}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Agenda" name="agenda_text" hint="Satu baris: Waktu | Judul | Deskripsi" class="sm:col-span-2">
            <textarea name="agenda_text" id="agenda_text" rows="4"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{old('agenda_text',collect($resource->agenda??[])->map(fn($r)=>($r['time']??'').' | '.($r['title']??'').' | '.($r['description']??''))->implode("\n"))}}</textarea>
        </x-admin.field>

        <x-admin.field label="Pembicara" name="speakers_text" hint="Satu baris: Nama | Jabatan | URL foto" class="sm:col-span-2">
            <textarea name="speakers_text" id="speakers_text" rows="4"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{old('speakers_text',collect($resource->speakers??[])->map(fn($r)=>($r['name']??'').' | '.($r['position']??'').' | '.($r['photo']??''))->implode("\n"))}}</textarea>
        </x-admin.field>

        <x-admin.field label="Galeri" name="gallery_text" hint="Satu URL gambar per baris." class="sm:col-span-2">
            <textarea name="gallery_text" id="gallery_text" rows="3"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{old('gallery_text',implode("\n",$resource->gallery??[]))}}</textarea>
        </x-admin.field>

        <x-admin.field label="Metrik hasil" name="metrics_text" hint="Satu baris: Label | Nilai. Untuk go-live." class="sm:col-span-2">
            <textarea name="metrics_text" id="metrics_text" rows="3"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{old('metrics_text',collect($resource->metrics??[])->map(fn($r)=>($r['label']??'').' | '.($r['value']??''))->implode("\n"))}}</textarea>
        </x-admin.field>

        <x-admin.field label="Daftar isi" name="toc_text" hint="Satu baris per entri. Untuk whitepaper/e-book." class="sm:col-span-2">
            <textarea name="toc_text" id="toc_text" rows="4"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{old('toc_text',implode("\n",$resource->toc??[]))}}</textarea>
        </x-admin.field>

        <x-admin.field label="Bab" name="chapters_text" hint="Satu baris: Judul | Deskripsi. Untuk e-book." class="sm:col-span-2">
            <textarea name="chapters_text" id="chapters_text" rows="4"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{old('chapters_text',collect($resource->chapters??[])->map(fn($r)=>($r['title']??'').' | '.($r['description']??''))->implode("\n"))}}</textarea>
        </x-admin.field>

        <x-admin.field label="Isi" name="body" class="sm:col-span-2">

            <textarea name="body" id="body" rows="8"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{old('body',$resource->body)}}</textarea>
        </x-admin.field>

        <x-admin.file-input name="file_path" :value="$resource->file_path" label="Berkas unduhan (PDF/Dokumen)" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip" class="sm:col-span-2" />

    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.resources.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
