<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait GeneratesSlug
{
    protected static function bootGeneratesSlug(): void
    {
        static::creating(function ($model) {
            if (blank($model->slug)) {
                $model->slug = static::uniqueSlug($model->{$model->slugSource()});
            }
        });

        static::updating(function ($model) {
            $source = $model->slugSource();

            // Regenerasi bila slug dikosongkan, atau bila sumber berubah dan slug
            // tidak diisi manual (biar tidak ada slug NULL → error kolom NOT NULL).
            if (blank($model->slug) || ($model->isDirty($source) && ! $model->isDirty('slug'))) {
                $model->slug = static::uniqueSlug($model->{$source}, $model->getKey());
            }
        });
    }

    protected function slugSource(): string
    {
        return 'title';
    }

    protected static function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'item';
        $slug = $base;
        $suffix = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
