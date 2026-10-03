<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CareerRequest;
use App\Models\Career;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function index(Request $request): View
    {
        $careers = Career::query()
            ->search($request->string('q')->toString())
            ->withStatus($request->string('status')->toString())
            ->withCount('applications')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.careers.index', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Karier'],
            ],
            'searchPlaceholder' => 'Cari posisi, departemen, atau lokasi…',
            'searchAction' => route('admin.careers.index'),
            'careers' => $careers,
            'statuses' => PublishStatus::cases(),
        ]);
    }

    public function create(): View
    {
        return view('admin.careers.form', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Karier', 'url' => route('admin.careers.index')],
                ['label' => 'Tambah'],
            ],
            'career' => new Career,
            'statuses' => PublishStatus::cases(),
        ]);
    }

    public function store(CareerRequest $request): RedirectResponse
    {
        Career::create([
            ...$request->validated(),
            'is_remote' => $request->boolean('is_remote'),
        ]);

        return redirect()->route('admin.careers.index')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function edit(Career $career): View
    {
        return view('admin.careers.form', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Karier', 'url' => route('admin.careers.index')],
                ['label' => $career->title],
            ],
            'career' => $career->loadCount('applications'),
            'statuses' => PublishStatus::cases(),
        ]);
    }

    public function update(CareerRequest $request, Career $career): RedirectResponse
    {
        $career->update([
            ...$request->validated(),
            'is_remote' => $request->boolean('is_remote'),
        ]);

        return redirect()->route('admin.careers.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(Career $career): RedirectResponse
    {
        foreach ($career->applications as $application) {
            if (filled($application->cv_path) && Storage::disk('local')->exists($application->cv_path)) {
                Storage::disk('local')->delete($application->cv_path);
            }
        }

        $career->delete();

        return redirect()->route('admin.careers.index')->with('success', 'Lowongan berhasil dihapus.');
    }
}
