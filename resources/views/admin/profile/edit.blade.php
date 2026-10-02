@extends('admin.layouts.app')

@section('title', 'Profil — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Profil" description="Kelola data akun dan kata sandi Anda." eyebrow="Admin" />

<div class="mt-8 grid gap-8 lg:grid-cols-[1fr_1fr]">
    <x-admin.card title="Informasi akun" description="Data yang tampil di panel dan website.">
        <form method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('put')

            <div class="grid gap-6 sm:grid-cols-2">
                <x-admin.field label="Nama" name="name" :required="true">
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required maxlength="150"
                        class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                </x-admin.field>

                <x-admin.field label="Email" name="email" :required="true">
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required maxlength="255"
                        class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                </x-admin.field>

                <x-admin.field label="Jabatan" name="job_title" class="sm:col-span-2">
                    <input type="text" name="job_title" id="job_title" value="{{ old('job_title', $user->job_title) }}" maxlength="150"
                        class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                </x-admin.field>

                <x-admin.image-input name="avatar_path" :value="$user->avatar_path" label="Avatar" shape="circle" class="sm:col-span-2" />
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
                <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
            </div>
        </form>
    </x-admin.card>

    <x-admin.card title="Ubah kata sandi" description="Gunakan kata sandi yang kuat dan unik.">
        <form method="post" action="{{ route('admin.profile.password') }}" class="p-6">
            @csrf
            @method('put')

            <div class="grid gap-6">
                <x-admin.field label="Kata sandi saat ini" name="current_password" :required="true">
                    <input type="password" name="current_password" id="current_password" required autocomplete="current-password"
                        class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                </x-admin.field>

                <x-admin.field label="Kata sandi baru" name="password" :required="true">
                    <input type="password" name="password" id="password" required autocomplete="new-password"
                        class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                </x-admin.field>

                <x-admin.field label="Konfirmasi kata sandi" name="password_confirmation" :required="true">
                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                        class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                </x-admin.field>
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
                <x-admin.partials.button type="submit" variant="primary">Perbarui Kata Sandi</x-admin.partials.button>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection
