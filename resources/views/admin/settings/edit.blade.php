@extends('admin.layouts.app')

@section('title', 'Pengaturan — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Pengaturan" description="Identitas perusahaan dan konfigurasi website." eyebrow="Website">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.settings.edit')" variant="secondary">
            <x-admin.icon name="external" class="h-4 w-4" /> Lihat website
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @method('put')

    <div class="grid gap-6 sm:grid-cols-2">
        <h2 class="mt-1 border-t border-neutral-200 pt-5 text-[13px] font-semibold text-neutral-900 sm:col-span-2">Identitas</h2>

        <x-admin.field label="Nama perusahaan" name="company_name">
            <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $settings['company_name'] ?? '') }}" maxlength="150"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Tagline" name="tagline">
            <input type="text" name="tagline" id="tagline" value="{{ old('tagline', $settings['tagline'] ?? '') }}" maxlength="200"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.image-input name="logo_path" :value="$settings['logo_path'] ?? null" label="Logo" shape="square" />

        <x-admin.image-input name="favicon_path" :value="$settings['favicon_path'] ?? null" label="Favicon" shape="square" />

        <x-admin.field label="Copyright" name="copyright" class="sm:col-span-2">
            <input type="text" name="copyright" id="copyright" value="{{ old('copyright', $settings['copyright'] ?? '') }}" maxlength="200"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <h2 class="mt-1 border-t border-neutral-200 pt-5 text-[13px] font-semibold text-neutral-900 sm:col-span-2">Halaman utama</h2>

        <x-admin.image-input name="hero_image_path" :value="$settings['hero_image_path'] ?? null" label="Gambar hero" hint="Latar bagian paling atas. Rasio lanskap lebar, mis. 1920×1080." shape="rect" />

        <x-admin.image-input name="about_image_path" :value="$settings['about_image_path'] ?? null" label="Gambar bagian tentang" hint="Tampil di bagian “Bekerja seperti divisi internal Anda”." shape="rect" />

        <x-admin.field label="Statistik: tahun pengalaman" name="stat_experience" hint="Contoh: 10+">
            <input type="text" name="stat_experience" id="stat_experience" value="{{ old('stat_experience', $settings['stat_experience'] ?? '') }}" maxlength="20"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Statistik: proyek selesai" name="stat_projects" hint="Contoh: 120+">
            <input type="text" name="stat_projects" id="stat_projects" value="{{ old('stat_projects', $settings['stat_projects'] ?? '') }}" maxlength="20"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Statistik: retensi klien" name="stat_retention" hint="Contoh: 98%" class="sm:col-span-2">
            <input type="text" name="stat_retention" id="stat_retention" value="{{ old('stat_retention', $settings['stat_retention'] ?? '') }}" maxlength="20"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <h2 class="mt-1 border-t border-neutral-200 pt-5 text-[13px] font-semibold text-neutral-900 sm:col-span-2">Kontak</h2>

        <x-admin.field label="Email" name="email">
            <input type="email" name="email" id="email" value="{{ old('email', $settings['email'] ?? '') }}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Telepon" name="phone">
            <input type="text" name="phone" id="phone" value="{{ old('phone', $settings['phone'] ?? '') }}" maxlength="30"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="WhatsApp" name="whatsapp" hint="Format: 62xxxxxxxxxx tanpa + atau spasi." class="sm:col-span-2">
            <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}" maxlength="30"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Alamat" name="address" class="sm:col-span-2">
            <textarea name="address" id="address" rows="3" maxlength="500"
                class="h-auto w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('address', $settings['address'] ?? '') }}</textarea>
        </x-admin.field>

        <h2 class="mt-1 border-t border-neutral-200 pt-5 text-[13px] font-semibold text-neutral-900 sm:col-span-2">Media sosial</h2>

        <x-admin.field label="Facebook" name="facebook">
            <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $settings['facebook'] ?? '') }}" maxlength="255" placeholder="https://"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Instagram" name="instagram">
            <input type="url" name="instagram" id="instagram" value="{{ old('instagram', $settings['instagram'] ?? '') }}" maxlength="255" placeholder="https://"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="LinkedIn" name="linkedin">
            <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin', $settings['linkedin'] ?? '') }}" maxlength="255" placeholder="https://"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Twitter/X" name="twitter">
            <input type="url" name="twitter" id="twitter" value="{{ old('twitter', $settings['twitter'] ?? '') }}" maxlength="255" placeholder="https://"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="YouTube" name="youtube" class="sm:col-span-2">
            <input type="url" name="youtube" id="youtube" value="{{ old('youtube', $settings['youtube'] ?? '') }}" maxlength="255" placeholder="https://"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <h2 class="mt-1 border-t border-neutral-200 pt-5 text-[13px] font-semibold text-neutral-900 sm:col-span-2">Lokasi</h2>

        <x-admin.field label="Google Maps" name="google_maps" hint="URL share Google Maps." class="sm:col-span-2">
            <input type="text" name="google_maps" id="google_maps" value="{{ old('google_maps', $settings['google_maps'] ?? '') }}" maxlength="500"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Google Maps embed" name="google_maps_embed" hint="Kode iframe atau src embed." class="sm:col-span-2">
            <textarea name="google_maps_embed" id="google_maps_embed" rows="3"
                class="h-auto w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('google_maps_embed', $settings['google_maps_embed'] ?? '') }}</textarea>
        </x-admin.field>

        <h2 class="mt-1 border-t border-neutral-200 pt-5 text-[13px] font-semibold text-neutral-900 sm:col-span-2">Mode</h2>

        <div class="sm:col-span-2">
            <label class="flex items-center gap-2.5">
                <input type="checkbox" name="maintenance_mode" value="1" @checked(old('maintenance_mode', ($settings['maintenance_mode'] ?? '0') === '1')) class="h-4 w-4 accent-[#2563EB]">
                <span class="text-[13px] font-semibold text-neutral-800">Maintenance mode</span>
            </label>
            <p class="mt-1.5 text-xs text-neutral-500">Saat aktif, website publik menampilkan halaman pemeliharaan. Admin tetap dapat mengakses panel.</p>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button type="submit" variant="primary">Simpan Pengaturan</x-admin.partials.button>
    </div>
</form>
@endsection
