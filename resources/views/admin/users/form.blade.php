@extends('admin.layouts.app')

@section('title', ($user->exists ? 'Ubah Pengguna' : 'Tambah Pengguna').' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header
    :title="$user->exists ? 'Ubah Pengguna' : 'Tambah Pengguna'"
    eyebrow="Admin"
    :description="$user->exists ? 'Perbarui data pengguna '.$user->name.'.' : 'Tambahkan akun admin baru.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.users.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post"
    action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if ($user->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Nama" name="name" :required="true">
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Email" name="email" :required="true">
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Peran" name="role" :required="true">
            <select name="role" id="role" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('role', $user->role?->value) === null)>Pilih peran…</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->value }}" @selected(old('role', $user->role?->value) === $role->value)>{{ $role->label() }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Jabatan" name="job_title">
            <input type="text" name="job_title" id="job_title" value="{{ old('job_title', $user->job_title) }}" maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field
            :label="$user->exists ? 'Kata sandi' : 'Kata sandi'"
            name="password"
            :required="! $user->exists"
            :hint="$user->exists ? 'Biarkan kosong bila tidak diubah.' : null">
            <input type="password" name="password" id="password" @required(! $user->exists) maxlength="255" autocomplete="new-password"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <div class="flex items-end">
            <label class="flex items-center gap-2.5 pb-2.5">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->exists ? $user->is_active : true)) class="h-4 w-4 accent-[#2563EB]">
                <span class="text-[13px] font-semibold text-neutral-800">Aktif</span>
            </label>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.users.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
