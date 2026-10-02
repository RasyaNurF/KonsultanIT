@extends('admin.layouts.app')

@section('title', ($career->exists ? 'Ubah Lowongan' : 'Tambah Lowongan').' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header
    :title="$career->exists ? 'Ubah Lowongan' : 'Tambah Lowongan'"
    eyebrow="Konten"
    :description="$career->exists ? 'Perbarui lowongan '.$career->title.'.' : 'Tambahkan lowongan baru.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.careers.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post"
    action="{{ $career->exists ? route('admin.careers.update', $career) : route('admin.careers.store') }}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if ($career->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Posisi" name="title" :required="true" class="sm:col-span-2">
            <input type="text" name="title" id="title" value="{{ old('title', $career->title) }}" required maxlength="180"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Departemen" name="department">
            <input type="text" name="department" id="department" value="{{ old('department', $career->department) }}" maxlength="100"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Lokasi" name="location">
            <input type="text" name="location" id="location" value="{{ old('location', $career->location) }}" maxlength="120"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Tipe pekerjaan" name="employment_type">
            <input type="text" name="employment_type" id="employment_type" value="{{ old('employment_type', $career->employment_type) }}" maxlength="50" placeholder="Full-time"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Rentang gaji" name="salary_range">
            <input type="text" name="salary_range" id="salary_range" value="{{ old('salary_range', $career->salary_range) }}" maxlength="120"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Deadline" name="deadline">
            <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $career->deadline?->format('Y-m-d')) }}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Urutan" name="sort_order">
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $career->sort_order ?? 0) }}" min="0"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Status" name="status" :required="true">
            <select name="status" id="status" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('status', $career->status?->value) === null)>Pilih status…</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $career->status?->value) === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <div class="sm:col-span-2">
            <label class="flex items-center gap-2.5 text-[13px] font-semibold text-neutral-800">
                <input type="checkbox" name="is_remote" value="1" @checked(old('is_remote', $career->is_remote)) class="h-4 w-4 accent-brand-600">
                Mendukung kerja remote
            </label>
        </div>

        <x-admin.field label="Deskripsi" name="description" class="sm:col-span-2">
            <textarea name="description" id="description" rows="5"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('description', $career->description) }}</textarea>
        </x-admin.field>

        <x-admin.field label="Persyaratan" name="requirements" hint="Satu poin per baris." class="sm:col-span-2">
            <textarea name="requirements" id="requirements" rows="5"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('requirements', $career->requirements) }}</textarea>
        </x-admin.field>

        <x-admin.field label="Tanggung jawab" name="responsibilities" hint="Satu poin per baris." class="sm:col-span-2">
            <textarea name="responsibilities" id="responsibilities" rows="5"
                class="w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('responsibilities', $career->responsibilities) }}</textarea>
        </x-admin.field>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.careers.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
