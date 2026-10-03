<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'path',
        'label',
        'title',
        'description',
        'og_image_path',
        'canonical_url',
        'is_indexable',
    ];

    protected function casts(): array
    {
        return [
            'is_indexable' => 'boolean',
        ];
    }

    /**
     * @return list<string>
     */
    public static function defaultPaths(): array
    {
        return ['/', '/tentang', '/solusi', '/portfolio', '/blog', '/karier', '/kontak'];
    }

    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, fn (Builder $q) => $q->where(function (Builder $q) use ($term) {
            $q->where('path', 'like', "%{$term}%")
                ->orWhere('label', 'like', "%{$term}%")
                ->orWhere('title', 'like', "%{$term}%");
        }));
    }
}
