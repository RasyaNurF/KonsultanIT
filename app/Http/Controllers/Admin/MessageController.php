<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MessageReplyRequest;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.messages.index', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Pesan'],
            ],
            'searchPlaceholder' => 'Cari nama, email, atau subjek…',
            'searchAction' => route('admin.messages.index'),
            'participants' => $this->participants($request),
            'statuses' => MessageStatus::cases(),
        ]);
    }

    /**
     * Jumlah percakapan belum dibaca untuk badge sidebar/topbar (polling).
     */
    public function unread(): JsonResponse
    {
        return response()->json(['unread' => $this->unreadCount()]);
    }

    /**
     * Render ulang daftar percakapan sebagai HTML agar halaman indeks tetap segar.
     */
    public function live(Request $request): JsonResponse
    {
        return response()->json([
            'html' => view('admin.messages.partials.list', [
                'participants' => $this->participants($request),
            ])->render(),
            'unread' => $this->unreadCount(),
        ]);
    }

    /**
     * Pesan baru pada satu percakapan; dipanggil berkala saat admin membuka thread.
     */
    public function poll(Request $request, ChatParticipant $participant): JsonResponse
    {
        $after = $request->integer('after');

        $messages = $participant->messages()
            ->when($after > 0, fn ($query) => $query->where('id', '>', $after))
            ->oldest()
            ->get();

        if ($participant->status === MessageStatus::Unread->value) {
            $participant->update(['status' => MessageStatus::Read->value]);
            $participant->messages()->update(['is_read' => true]);
        }

        $status = MessageStatus::from($participant->status);

        return response()->json([
            'messages' => $messages->map(fn (ChatMessage $message): array => [
                'id' => $message->id,
                'sender' => $message->sender,
                'name' => $message->name,
                'body' => $message->body,
                'time' => $message->timestampWib('H:i d M'),
            ])->values(),
            'status' => $status->value,
            'status_label' => $status->label(),
            'unread' => $this->unreadCount(),
        ]);
    }

    public function show(ChatParticipant $participant): View
    {
        $messages = $participant->messages()->oldest()->get();

        if ($participant->status === MessageStatus::Unread->value) {
            $participant->update(['status' => MessageStatus::Read->value]);
            $participant->messages()->update(['is_read' => true]);
        }

        return view('admin.messages.show', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Pesan', 'url' => route('admin.messages.index')],
                ['label' => $participant->name],
            ],
            'participant' => $participant,
            'messages' => $messages,
            'statuses' => MessageStatus::cases(),
        ]);
    }

    public function typing(Request $request, ChatParticipant $participant): JsonResponse
    {
        $validated = $request->validate([
            'typing' => ['required', 'boolean'],
        ]);

        $participant->setAdminTyping((bool) $validated['typing']);

        return response()->json(['typing' => $participant->adminIsTyping()]);
    }

    public function reply(MessageReplyRequest $request, ChatParticipant $participant): RedirectResponse
    {
        $participant->setAdminTyping(false);

        ChatMessage::create([
            'chat_participant_id' => $participant->id,
            'guest_token' => $participant->guest_token,
            'sender' => 'admin',
            'name' => auth()->user()->name,
            'body' => $request->validated('body'),
            'is_read' => true,
        ]);

        $participant->update([
            'status' => MessageStatus::Replied->value,
            'last_message_at' => now(),
        ]);

        return redirect()->route('admin.messages.show', $participant)->with('success', 'Balasan berhasil dikirim.');
    }

    public function update(Request $request, ChatParticipant $participant): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(MessageStatus::class)],
        ]);

        $participant->update(['status' => $validated['status']]);

        return redirect()->route('admin.messages.show', $participant)->with('success', 'Status percakapan berhasil diperbarui.');
    }

    public function destroy(ChatParticipant $participant): RedirectResponse
    {
        $participant->setAdminTyping(false);

        $participant->messages()->delete();
        $participant->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Percakapan berhasil dihapus.');
    }

    private function participants(Request $request): LengthAwarePaginator
    {
        return ChatParticipant::query()
            ->search($request->string('q')->toString())
            ->withStatus($request->string('status')->toString())
            ->withCount('messages')
            ->with('messages')
            ->orderByDesc('last_message_at')
            ->paginate(15)
            ->withQueryString();
    }

    private function unreadCount(): int
    {
        return ChatParticipant::query()
            ->where('status', MessageStatus::Unread->value)
            ->count();
    }
}
