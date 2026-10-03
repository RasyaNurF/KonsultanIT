<?php

namespace App\Http\Controllers\Public;

use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function index(): View
    {
        $careers = Career::query()
            ->where('status', PublishStatus::Published->value)
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12);

        return view('careers.index', [
            'careers' => $careers,
        ]);
    }

    public function show(Career $career): View
    {
        abort_unless($career->status === PublishStatus::Published, 404);

        $career->loadCount('applications');

        $others = Career::query()
            ->where('status', PublishStatus::Published->value)
            ->whereKeyNot($career->id)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('careers.show', [
            'career' => $career,
            'others' => $others,
        ]);
    }

    public function apply(Request $request, Career $career): RedirectResponse
    {
        abort_unless($career->status === PublishStatus::Published, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'cover_letter' => ['nullable', 'string', 'max:5000'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('career-cvs', 'local');
        }

        $career->applications()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'cv_path' => $cvPath,
            'status' => 'new',
        ]);

        return back()->with('success', 'Lamaran Anda berhasil dikirim. Tim kami akan menghubungi Anda bila sesuai kualifikasi.');
    }
}
