<?php

namespace App\Http\Controllers;

use App\Enums\MessageStatus;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $token = $this->guestToken($request);

        $messages = ChatMessage::query()
            ->where('guest_token', $token)
            ->oldest()
            ->get();

        return view('contact', ['messages' => $messages]);
    }

    public function store(Request $request): JsonResponse
    {
        $needsIdentity = ! $request->session()->has('chat_name');

        $validated = $request->validate([
            'name' => $needsIdentity
                ? ['required', 'string', 'max:100']
                : ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $token = $this->guestToken($request);
        $name = $validated['name'] ?? $request->session()->get('chat_name');
        $email = $validated['email'] ?? $request->session()->get('chat_email');

        if (filled($validated['name'] ?? null)) {
            $request->session()->put('chat_name', $validated['name']);
        }

        if (filled($validated['email'] ?? null)) {
            $request->session()->put('chat_email', $validated['email']);
        }

        $participant = ChatParticipant::query()->firstOrNew(['guest_token' => $token]);
        $isFirstMessage = ! $participant->exists || ! $participant->messages()->exists();
        $participant->fill([
            'name' => $name,
            'email' => $email,
            'subject' => $participant->subject ?? Str::limit($validated['body'], 120, ''),
            'status' => MessageStatus::Unread->value,
            'last_message_at' => now(),
        ])->save();

        $guestMessage = ChatMessage::create([
            'chat_participant_id' => $participant->id,
            'guest_token' => $token,
            'sender' => 'guest',
            'name' => $name,
            'email' => $email,
            'body' => $validated['body'],
        ]);

        $autoReply = $isFirstMessage
            ? ChatMessage::create([
                'chat_participant_id' => $participant->id,
                'guest_token' => $token,
                'sender' => 'admin',
                'name' => 'Tim KIT Konsultan IT',
                'body' => ChatMessage::WAITING_REPLY_MESSAGE,
                'is_auto' => true,
                'is_read' => true,
            ])
            : null;

        return response()->json([
            'messages' => collect([$guestMessage, $autoReply])
                ->filter()
                ->map(fn (ChatMessage $message): array => $this->present($message))
                ->values(),
        ]);
    }

    private function guestToken(Request $request): string
    {
        return $request->session()->get('chat_token') ?? tap(
            Str::uuid()->toString(),
            fn (string $token) => $request->session()->put('chat_token', $token)
        );
    }

    private function present(ChatMessage $message): array
    {
        return [
            'sender' => $message->sender,
            'name' => $message->name,
            'body' => $message->body,
            'is_auto' => $message->is_auto,
            'time' => $message->timestampWib(),
        ];
    }
}
