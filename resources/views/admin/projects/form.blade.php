@extends('admin.layouts.app')

@section('title', ($project->exists ? 'Ubah Proyek' : 'Tambah Proyek').' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header
    :title="$project->exists ? 'Ubah Proyek' : 'Tambah Proyek'"
    eyebrow="Proyek"
    :description="$project->exists ? 'Perbarui data proyek '.$project->name.'.' : 'Tambahkan proyek baru KIT Konsultan IT.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.projects.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" enctype="multipart/form-data"
    action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if ($project->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Nama proyek" name="name" :required="true">
            <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" required maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Klien" name="client_id">
            <select name="client_id" id="client_id" class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="">Tanpa klien</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @selected((string) old('client_id', $project->client_id) === (string) $client->id)>{{ $client->name }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Kategori" name="category">
            <input type="text" name="category" id="category" value="{{ old('category', $project->category) }}" maxlength="100"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Status" name="status" :required="true">
            <select name="status" id="status" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('status', $project->status?->value) === null)>Pilih status…</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $project->status?->value) === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Tanggal mulai" name="started_at">
            <input type="date" name="started_at" id="started_at" value="{{ old('started_at', $project->started_at?->format('Y-m-d')) }}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Tanggal selesai" name="finished_at">
            <input type="date" name="finished_at" id="finished_at" value="{{ old('finished_at', $project->finished_at?->format('Y-m-d')) }}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Teknologi" name="technologies" hint="Pisahkan dengan koma. Contoh: Laravel, MySQL, Redis.">
            <input type="text" name="technologies" id="technologies" value="{{ old('technologies', $project->technologies) }}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="URL" name="url">
            <input type="url" name="url" id="url" value="{{ old('url', $project->url) }}" maxlength="255" placeholder="https://"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Deskripsi" name="description" class="sm:col-span-2">
            <textarea name="description" id="description" rows="5"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('description', $project->description) }}</textarea>
        </x-admin.field>

        <x-admin.image-input name="image_path" :value="$project->image_path" label="Gambar proyek" shape="rect" class="sm:col-span-2" />
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.projects.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
