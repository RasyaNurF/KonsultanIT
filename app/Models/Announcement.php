<?php

namespace App\Models;

use App\Enums\AnnouncementFrequency;
use App\Enums\AnnouncementPlacement;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Announcement extends Model
{
    protected $fillable = [
        'placement',
        'title',
        'message',
        'link_label',
        'link_url',
        'image_path',
        'frequency',
        'frequency_days',
        'is_dismissible',
        'is_active',
        'starts_at',
        'ends_at',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'placement' => AnnouncementPlacement::class,
            'frequency' => AnnouncementFrequency::class,
            'frequency_days' => 'integer',
            'is_dismissible' => 'boolean',
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true)
            ->where(fn (Builder $q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn (Builder $q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()));
    }

    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, fn (Builder $q) => $q->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('message', 'like', "%{$term}%");
        }));
    }

    #[Scope]
    protected function forPlacement(Builder $query, ?string $placement): void
    {
        $query->when($placement, fn (Builder $q) => $q->where('placement', $placement));
    }
}
