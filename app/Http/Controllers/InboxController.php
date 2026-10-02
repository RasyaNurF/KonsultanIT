<?php

namespace App\Http\Controllers;

use App\Enums\MessageStatus;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InboxController extends Controller
{
    /**
     * Ambil percakapan pengguna sebagai JSON untuk widget chat live.
     * Mendukung ?after={id} agar klien hanya menerima pesan baru saat polling.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $participant = $this->participantFor($user->id, $user->name, $user->email);

        $after = $request->integer('after');

        $messages = $participant->messages()
            ->when($after > 0, fn ($query) => $query->where('id', '>', $after))
            ->oldest()
            ->get();

        $participant->markReadByUser();

        return response()->json([
            'messages' => $messages->map(fn (ChatMessage $message): array => $this->present($message))->values(),
            'admin_typing' => $participant->adminIsTyping(),
        ]);
    }

    /**
     * Simpan pesan pengguna. Membalas JSON untuk widget, atau mengalihkan kembali
     * dengan flash bila dikirim sebagai form biasa (tanpa JavaScript).
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $user = $request->user();
        $participant = $this->participantFor($user->id, $user->name, $user->email);
        $isFirstMessage = ! $participant->messages()->exists();

        $message = $participant->messages()->create([
            'guest_token' => $participant->guest_token,
            'sender' => 'user',
            'name' => $user->name,
            'email' => $user->email,
            'body' => $validated['body'],
        ]);

        $participant->update([
            'name' => $user->name,
            'email' => $user->email,
            'subject' => $participant->subject ?: Str::limit($validated['body'], 120, ''),
            'status' => MessageStatus::Unread->value,
            'last_message_at' => now(),
            'user_last_read_message_id' => $message->id,
        ]);

        $autoReply = $isFirstMessage
            ? $participant->messages()->create([
                'guest_token' => $participant->guest_token,
                'sender' => 'admin',
                'name' => 'Tim KIT Konsultan IT',
                'body' => ChatMessage::WAITING_REPLY_MESSAGE,
                'is_auto' => true,
                'is_read' => true,
            ])
            : null;

        if ($autoReply) {
            $participant->update(['user_last_read_message_id' => $autoReply->id]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->present($message),
                'auto_reply' => $autoReply ? $this->present($autoReply) : null,
            ]);
        }

        return back()->with('success', 'Pesan Anda sudah diterima. Mohon menunggu balasan dari tim kami.');
    }

    private function participantFor(int $userId, string $name, string $email): ChatParticipant
    {
        return ChatParticipant::query()->firstOrCreate(
            ['user_id' => $userId],
            [
                'guest_token' => (string) Str::uuid(),
                'name' => $name,
                'email' => $email,
                'status' => MessageStatus::Unread->value,
            ],
        );
    }

    /**
     * @return array{id: int, sender: string, name: string|null, body: string, is_auto: bool, time: string}
     */
    private function present(ChatMessage $message): array
    {
        return [
            'id' => $message->id,
            'sender' => $message->sender,
            'name' => $message->name,
            'body' => $message->body,
            'is_auto' => $message->is_auto,
            'time' => $message->timestampWib('d M, H:i'),
        ];
    }
}
