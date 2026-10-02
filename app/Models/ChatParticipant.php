<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class ChatParticipant extends Model
{
    protected $fillable = [
        'user_id',
        'guest_token',
        'name',
        'email',
        'phone',
        'company',
        'subject',
        'status',
        'last_message_at',
        'user_last_read_message_id',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
            'user_last_read_message_id' => 'integer',
        ];
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isFromRegisteredUser(): bool
    {
        return $this->user_id !== null;
    }

    public function unreadForUser(): int
    {
        return $this->messages()
            ->where('sender', 'admin')
            ->where('id', '>', (int) ($this->user_last_read_message_id ?? 0))
            ->count();
    }

    public function markReadByUser(): void
    {
        $latestId = $this->messages()->max('id');

        if ($latestId !== null && (int) $latestId > (int) ($this->user_last_read_message_id ?? 0)) {
            $this->update(['user_last_read_message_id' => $latestId]);
        }
    }

    public function adminIsTyping(): bool
    {
        return Cache::get($this->adminTypingCacheKey(), false) === true;
    }

    public function setAdminTyping(bool $typing): void
    {
        if ($typing) {
            Cache::put($this->adminTypingCacheKey(), true, 10);

            return;
        }

        Cache::forget($this->adminTypingCacheKey());
    }

    private function adminTypingCacheKey(): string
    {
        return 'chat:admin-typing:'.$this->id;
    }

    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, fn (Builder $q) => $q->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('company', 'like', "%{$term}%")
                ->orWhere('subject', 'like', "%{$term}%");
        }));
    }

    #[Scope]
    protected function withStatus(Builder $query, ?string $status): void
    {
        $query->when($status, fn (Builder $q) => $q->where('status', $status));
    }
}
