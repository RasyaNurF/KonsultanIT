@extends('admin.layouts.app')

@section('title', ($portfolio->exists ? 'Ubah Portfolio' : 'Tambah Portfolio').' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header
    :title="$portfolio->exists ? 'Ubah Portfolio' : 'Tambah Portfolio'"
    eyebrow="Portfolio"
    :description="$portfolio->exists ? 'Perbarui data portfolio '.$portfolio->title.'.' : 'Tambahkan karya baru ke portofolio website.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.portfolios.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" enctype="multipart/form-data"
    action="{{ $portfolio->exists ? route('admin.portfolios.update', $portfolio) : route('admin.portfolios.store') }}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if ($portfolio->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Judul" name="title" :required="true">
            <input type="text" name="title" id="title" value="{{ old('title', $portfolio->title) }}" required maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Klien" name="client_id">
            <select name="client_id" id="client_id" class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="">Tanpa klien</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @selected((string) old('client_id', $portfolio->client_id) === (string) $client->id)>{{ $client->name }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Proyek" name="project_id">
            <select name="project_id" id="project_id" class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="">Tanpa proyek</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @selected((string) old('project_id', $portfolio->project_id) === (string) $project->id)>{{ $project->name }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Kategori" name="category">
            <input type="text" name="category" id="category" value="{{ old('category', $portfolio->category) }}" maxlength="100"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Status" name="status" :required="true">
            <select name="status" id="status" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('status', $portfolio->status?->value) === null)>Pilih status…</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $portfolio->status?->value) === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Tahun" name="year">
            <input type="number" name="year" id="year" value="{{ old('year', $portfolio->year) }}" min="1990" max="2100"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="URL" name="url">
            <input type="url" name="url" id="url" value="{{ old('url', $portfolio->url) }}" maxlength="255" placeholder="https://"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Urutan" name="sort_order">
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $portfolio->sort_order ?? 0) }}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.image-input name="thumbnail_path" :value="$portfolio->thumbnail_path" label="Thumbnail" shape="rect" class="sm:col-span-2" />

        <x-admin.field label="Teknologi" name="technologies" hint="Pisahkan dengan koma. Contoh: Laravel, MySQL, Redis." class="sm:col-span-2">
            <input type="text" name="technologies" id="technologies" value="{{ old('technologies', $portfolio->technologies) }}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Deskripsi" name="description" class="sm:col-span-2">
            <textarea name="description" id="description" rows="5"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('description', $portfolio->description) }}</textarea>
        </x-admin.field>

        <x-admin.field label="Tantangan (Problem)" name="challenge" class="sm:col-span-2">
            <textarea name="challenge" id="challenge" rows="4"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('challenge', $portfolio->challenge) }}</textarea>
        </x-admin.field>

        <x-admin.field label="Solusi" name="solution" class="sm:col-span-2">
            <textarea name="solution" id="solution" rows="4"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('solution', $portfolio->solution) }}</textarea>
        </x-admin.field>

        <x-admin.field label="Hasil" name="result" class="sm:col-span-2">
            <textarea name="result" id="result" rows="4"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('result', $portfolio->result) }}</textarea>
        </x-admin.field>

        <x-admin.field label="Gallery (path dari Media)" name="gallery" class="sm:col-span-2">
            <div class="space-y-2">
                @for ($i = 0; $i < 4; $i++)
                    <input type="text" name="gallery[]" value="{{ old('gallery.'.$i, $portfolio->gallery[$i] ?? '') }}" maxlength="255"
                        class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                @endfor
            </div>
        </x-admin.field>

        <div class="sm:col-span-2">
            <label class="flex items-center gap-2.5 text-[13px] font-semibold text-neutral-800">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $portfolio->is_featured)) class="h-4 w-4 accent-brand-600">
                Tampilkan sebagai portfolio unggulan
            </label>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.portfolios.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
