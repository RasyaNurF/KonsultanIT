<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SolutionCategoryRequest;
use App\Models\SolutionCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SolutionCategoryController extends Controller
{
    use HandlesImageUploads;

    public function index(Request $request): View
    {
        $categories = SolutionCategory::query()
            ->search($request->string('q')->toString())
            ->withStatus($request->string('status')->toString())
            ->withCount('solutions')
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();

        return view('admin.solution-categories.index', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Kategori Solusi'],
            ],
            'searchPlaceholder' => 'Cari nama atau deskripsi kategori…',
            'searchAction' => route('admin.solution-categories.index'),
            'categories' => $categories,
            'statuses' => PublishStatus::cases(),
        ]);
    }

    public function create(): View
    {
        return view('admin.solution-categories.form', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Kategori Solusi', 'url' => route('admin.solution-categories.index')],
                ['label' => 'Tambah'],
            ],
            'category' => new SolutionCategory,
            'statuses' => PublishStatus::cases(),
        ]);
    }

    public function store(SolutionCategoryRequest $request): RedirectResponse
    {
        SolutionCategory::create($this->applyImageUploads($request, $request->validated(), ['image_path'], 'solution-categories'));

        return redirect()->route('admin.solution-categories.index')->with('success', 'Kategori solusi berhasil ditambahkan.');
    }

    public function edit(SolutionCategory $solutionCategory): View
    {
        return view('admin.solution-categories.form', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Kategori Solusi', 'url' => route('admin.solution-categories.index')],
                ['label' => $solutionCategory->name],
            ],
            'category' => $solutionCategory,
            'statuses' => PublishStatus::cases(),
        ]);
    }

    public function update(SolutionCategoryRequest $request, SolutionCategory $solutionCategory): RedirectResponse
    {
        $solutionCategory->update($this->applyImageUploads($request, $request->validated(), ['image_path'], 'solution-categories', $solutionCategory));

        return redirect()->route('admin.solution-categories.index')->with('success', 'Kategori solusi berhasil diperbarui.');
    }

    public function destroy(SolutionCategory $solutionCategory): RedirectResponse
    {
        $this->deleteImageFiles($solutionCategory, ['image_path']);

        foreach ($solutionCategory->solutions as $solution) {
            $this->deleteImageFiles($solution, ['logo_path', 'cover_image_path']);
        }

        $solutionCategory->delete();

        return redirect()->route('admin.solution-categories.index')->with('success', 'Kategori solusi berhasil dihapus.');
    }
}
