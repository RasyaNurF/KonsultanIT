@extends('admin.layouts.app')

@section('title', ($team->exists ? 'Ubah Anggota Tim' : 'Tambah Anggota Tim').' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header
    :title="$team->exists ? 'Ubah Anggota Tim' : 'Tambah Anggota Tim'"
    eyebrow="Konten"
    :description="$team->exists ? 'Perbarui data '.$team->name.'.' : 'Tambahkan anggota tim baru.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.teams.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" enctype="multipart/form-data"
    action="{{ $team->exists ? route('admin.teams.update', $team) : route('admin.teams.store') }}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if ($team->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Nama" name="name" :required="true">
            <input type="text" name="name" id="name" value="{{ old('name', $team->name) }}" required maxlength="120"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Posisi" name="position">
            <input type="text" name="position" id="position" value="{{ old('position', $team->position) }}" maxlength="120"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Urutan" name="sort_order">
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $team->sort_order ?? 0) }}" min="0"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Status" name="status" :required="true">
            <select name="status" id="status" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('status', $team->status?->value) === null)>Pilih status…</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $team->status?->value) === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.image-input name="photo_path" :value="$team->photo_path" label="Foto" hint="Rasio persegi, mis. 800×800." shape="circle" class="sm:col-span-2" />

        <x-admin.field label="Bio" name="bio" class="sm:col-span-2">
            <textarea name="bio" id="bio" rows="4"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('bio', $team->bio) }}</textarea>
        </x-admin.field>

        <x-admin.field label="LinkedIn URL" name="linkedin_url">
            <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $team->linkedin_url) }}" maxlength="255" placeholder="https://"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Instagram URL" name="instagram_url">
            <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $team->instagram_url) }}" maxlength="255" placeholder="https://"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="GitHub URL" name="github_url" class="sm:col-span-2">
            <input type="url" name="github_url" id="github_url" value="{{ old('github_url', $team->github_url) }}" maxlength="255" placeholder="https://"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.teams.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
