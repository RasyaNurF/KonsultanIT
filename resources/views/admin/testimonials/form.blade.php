@extends('admin.layouts.app')

@section('title', ($testimonial->exists ? 'Ubah Testimonial' : 'Tambah Testimonial').' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header
    :title="$testimonial->exists ? 'Ubah Testimonial' : 'Tambah Testimonial'"
    eyebrow="Konten"
    :description="$testimonial->exists ? 'Perbarui testimonial '.$testimonial->name.'.' : 'Tambahkan kutipan dari klien.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.testimonials.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" enctype="multipart/form-data"
    action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if ($testimonial->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Nama" name="name" :required="true">
            <input type="text" name="name" id="name" value="{{ old('name', $testimonial->name) }}" required maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Jabatan" name="position">
            <input type="text" name="position" id="position" value="{{ old('position', $testimonial->position) }}" maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Perusahaan" name="company">
            <input type="text" name="company" id="company" value="{{ old('company', $testimonial->company) }}" maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Status" name="status" :required="true">
            <select name="status" id="status" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('status', $testimonial->status?->value) === null)>Pilih status…</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $testimonial->status?->value) === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.image-input name="photo_path" :value="$testimonial->photo_path" label="Foto" shape="circle" class="sm:col-span-2" />

        <x-admin.field label="Quote" name="quote" class="sm:col-span-2">
            <textarea name="quote" id="quote" rows="5" maxlength="2000"
                class="h-auto w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('quote', $testimonial->quote) }}</textarea>
        </x-admin.field>

        <label class="flex items-center gap-2.5 sm:col-span-2">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $testimonial->is_featured)) class="h-4 w-4 accent-[#2563EB]">
            <span class="text-[13px] font-semibold text-neutral-800">Unggulan</span>
        </label>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.testimonials.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
