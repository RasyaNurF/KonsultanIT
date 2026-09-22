<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Support\SvgSanitizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HandlesImageUploads
{
    /**
     * Sinkronkan kolom path gambar dari berkas yang diunggah.
     *
     * Untuk tiap field: `{field}_file` menggantikan gambar, `{field}_remove` menghapusnya.
     * Bila tidak ada keduanya, nilai lama dipertahankan.
     *
     * @param  list<string>  $fields
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function applyImageUploads(Request $request, array $data, array $fields, string $folder, ?Model $model = null): array
    {
        foreach ($fields as $field) {
            $current = $model?->getAttribute($field) ?? ($data[$field] ?? null);

            if ($request->boolean($field.'_remove')) {
                $this->deleteImageFile($current);
                $data[$field] = null;

                continue;
            }

            if (! $request->hasFile($field.'_file')) {
                $data[$field] = $current;

                continue;
            }

            $path = $request->file($field.'_file')->store($folder, 'public');

            if (! is_string($path)) {
                $data[$field] = $current;

                continue;
            }

            SvgSanitizer::sanitizeFile(Storage::disk('public')->path($path));

            $this->deleteImageFile($current);
            $data[$field] = $path;
        }

        return $data;
    }

    /**
     * Hapus semua berkas gambar terkait model.
     *
     * @param  list<string>  $fields
     */
    protected function deleteImageFiles(Model $model, array $fields): void
    {
        foreach ($fields as $field) {
            $this->deleteImageFile($model->getAttribute($field));
        }
    }

    private function deleteImageFile(?string $path): void
    {
        if (filled($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
