@extends('admin.layouts.app')

@section('title', ($client->exists ? 'Ubah Klien' : 'Tambah Klien').' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header
    :title="$client->exists ? 'Ubah Klien' : 'Tambah Klien'"
    eyebrow="CRM"
    :description="$client->exists ? 'Perbarui data klien '.$client->name.'.' : 'Tambahkan perusahaan atau organisasi baru.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.clients.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" enctype="multipart/form-data"
    action="{{ $client->exists ? route('admin.clients.update', $client) : route('admin.clients.store') }}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if ($client->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Nama perusahaan" name="name" :required="true">
            <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" required maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Industri" name="industry">
            <input type="text" name="industry" id="industry" value="{{ old('industry', $client->industry) }}" maxlength="100"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Website" name="website" hint="Sertakan http:// atau https://">
            <input type="url" name="website" id="website" value="{{ old('website', $client->website) }}" maxlength="255" placeholder="https://"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Status" name="status" :required="true">
            <select name="status" id="status" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('status', $client->status?->value) === null)>Pilih status…</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $client->status?->value) === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <h2 class="mt-1 border-t border-neutral-200 pt-5 text-[13px] font-semibold text-neutral-900 sm:col-span-2">Kontak</h2>

        <x-admin.field label="Nama kontak" name="contact_name">
            <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name', $client->contact_name) }}" maxlength="100"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Email kontak" name="contact_email">
            <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $client->contact_email) }}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Telepon kontak" name="contact_phone">
            <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $client->contact_phone) }}" maxlength="30"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.image-input name="logo_path" :value="$client->logo_path" label="Logo" shape="square" hint="PNG, JPG, WebP, atau SVG." class="sm:col-span-2" />

        <x-admin.field label="Catatan" name="notes" class="sm:col-span-2">
            <textarea name="notes" id="notes" rows="4" maxlength="5000"
                class="h-auto w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('notes', $client->notes) }}</textarea>
        </x-admin.field>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.clients.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
