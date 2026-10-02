<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    public const WAITING_REPLY_MESSAGE = 'Terima kasih telah menghubungi KIT Konsultan IT. Pesan Anda sudah kami terima. Mohon menunggu balasan dari tim kami melalui percakapan ini pada jam kerja.';

    protected $fillable = [
        'chat_participant_id',
        'guest_token',
        'sender',
        'name',
        'email',
        'body',
        'is_auto',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_auto' => 'boolean',
            'is_read' => 'boolean',
        ];
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(ChatParticipant::class, 'chat_participant_id');
    }

    public function isGuest(): bool
    {
        return $this->sender === 'guest';
    }

    public function timestampWib(string $format = 'H:i'): string
    {
        return $this->created_at->copy()->timezone('Asia/Jakarta')->locale('id')->translatedFormat($format).' WIB';
    }
}
