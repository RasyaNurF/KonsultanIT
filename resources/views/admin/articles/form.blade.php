@extends('admin.layouts.app')

@section('title', ($article->exists ? 'Ubah Artikel' : 'Tambah Artikel').' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header
    :title="$article->exists ? 'Ubah Artikel' : 'Tambah Artikel'"
    eyebrow="Konten"
    :description="$article->exists ? 'Perbarui artikel '.$article->title.'.' : 'Tulis artikel atau insight baru.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.articles.index')" variant="secondary">Batal</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<form method="post" enctype="multipart/form-data"
    action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}"
    class="mt-8 rounded-lg border border-neutral-200 bg-white p-6">
    @csrf
    @if ($article->exists)
        @method('put')
    @endif

    <div class="grid gap-6 sm:grid-cols-2">
        <x-admin.field label="Judul" name="title" :required="true">
            <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" required maxlength="200"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Slug" name="slug" hint="Kosongkan untuk otomatis dari judul.">
            <input type="text" name="slug" id="slug" value="{{ old('slug', $article->slug) }}" maxlength="200"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Kategori" name="blog_category_id">
            <select name="blog_category_id" id="blog_category_id" class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="">Pilih kategori…</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) old('blog_category_id', $article->blog_category_id) === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <p class="mt-1.5 text-xs text-neutral-500">Kelola daftar kategori di menu Kategori Blog. Kolom teks lama tetap disimpan otomatis.</p>
        </x-admin.field>

        <x-admin.field label="Penulis" name="author_id">
            <select name="author_id" id="author_id" class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="">Saya sendiri</option>
                @foreach ($authors as $author)
                    <option value="{{ $author->id }}" @selected((string) old('author_id', $article->author_id) === (string) $author->id)>{{ $author->name }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Status" name="status" :required="true">
            <select name="status" id="status" required class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                <option value="" disabled @selected(old('status', $article->status?->value) === null)>Pilih status…</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $article->status?->value) === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </x-admin.field>

        <x-admin.field label="Tanggal publish" name="published_at">
            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.image-input name="featured_image_path" :value="$article->featured_image_path" label="Featured image" shape="rect" class="sm:col-span-2" />

        <x-admin.field label="Tags" name="tags" hint="Pisahkan dengan koma.">
            <input type="text" name="tags" id="tags" value="{{ old('tags', $article->tags) }}" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Ringkasan" name="excerpt" class="sm:col-span-2">
            <textarea name="excerpt" id="excerpt" rows="3" maxlength="500"
                class="h-auto w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('excerpt', $article->excerpt) }}</textarea>
        </x-admin.field>

        <x-admin.field label="Isi" name="body" class="sm:col-span-2">
            <textarea name="body" id="body" rows="14"
                class="h-auto w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('body', $article->body) }}</textarea>
        </x-admin.field>

        <h2 class="mt-1 border-t border-neutral-200 pt-5 text-[13px] font-semibold text-neutral-900 sm:col-span-2">SEO</h2>

        <x-admin.field label="SEO title" name="seo_title">
            <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $article->seo_title) }}" maxlength="200"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
        </x-admin.field>

        <x-admin.field label="Meta description" name="meta_description" class="sm:col-span-2">
            <textarea name="meta_description" id="meta_description" rows="3" maxlength="300"
                class="h-auto w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('meta_description', $article->meta_description) }}</textarea>
        </x-admin.field>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-neutral-200 pt-5">
        <x-admin.partials.button :href="route('admin.articles.index')" variant="secondary">Batal</x-admin.partials.button>
        <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
    </div>
</form>
@endsection
