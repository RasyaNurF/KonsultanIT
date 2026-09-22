<?php

namespace App\Http\Controllers;

use App\Enums\PublishStatus;
use App\Models\Article;
use App\Models\ChatParticipant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Dashboard akun pengguna: ringkasan percakapan, statistik pesan, dan
     * pintasan tugas (Baymard: halaman akun bersifat task-oriented).
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasAbility('dashboard.view')) {
            return redirect()->route('admin.dashboard');
        }

        $participant = ChatParticipant::query()
            ->where('user_id', $user->id)
            ->first();

        $messages = $participant
            ? $participant->messages()->latest('id')->limit(4)->get()->reverse()->values()
            : collect();

        $insights = Article::query()
            ->where('status', PublishStatus::Published->value)
            ->with('blogCategory')
            ->orderByDesc('published_at')
            ->latest('id')
            ->limit(3)
            ->get();

        return view('dashboard', [
            'participant' => $participant,
            'messages' => $messages,
            'unread' => $participant?->unreadForUser() ?? 0,
            'messageCount' => $participant?->messages()->count() ?? 0,
            'insights' => $insights,
        ]);
    }
}
