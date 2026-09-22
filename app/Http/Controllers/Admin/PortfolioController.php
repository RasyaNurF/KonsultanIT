<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PortfolioRequest;
use App\Models\Client;
use App\Models\Portfolio;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    use HandlesImageUploads;

    public function index(Request $request): View
    {
        $portfolios = Portfolio::query()
            ->search($request->string('q')->toString())
            ->withStatus($request->string('status')->toString())
            ->when($request->filled('client_id'), fn ($query) => $query->where('client_id', $request->integer('client_id')))
            ->with(['client', 'project'])
            ->orderBy('sort_order')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.portfolios.index', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Portfolio'],
            ],
            'searchPlaceholder' => 'Cari judul, kategori, atau klien…',
            'searchAction' => route('admin.portfolios.index'),
            'portfolios' => $portfolios,
            'statuses' => PublishStatus::cases(),
            'clients' => Client::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.portfolios.form', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Portfolio', 'url' => route('admin.portfolios.index')],
                ['label' => 'Tambah'],
            ],
            'portfolio' => new Portfolio,
            'clients' => Client::orderBy('name')->get(),
            'projects' => Project::orderBy('name')->get(),
            'statuses' => PublishStatus::cases(),
        ]);
    }

    public function store(PortfolioRequest $request): RedirectResponse
    {
        $portfolio = new Portfolio([
            ...$this->applyImageUploads($request, $request->validated(), ['thumbnail_path'], 'portfolios'),
            'is_featured' => $request->boolean('is_featured'),
        ]);
        $portfolio->save();

        return redirect()->route('admin.portfolios.index')->with('success', 'Portfolio berhasil ditambahkan.');
    }

    public function show(Portfolio $portfolio): View
    {
        return view('admin.portfolios.show', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Portfolio', 'url' => route('admin.portfolios.index')],
                ['label' => $portfolio->title],
            ],
            'portfolio' => $portfolio->load(['client', 'project', 'images']),
            'statuses' => PublishStatus::cases(),
        ]);
    }

    public function edit(Portfolio $portfolio): View
    {
        return view('admin.portfolios.form', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Portfolio', 'url' => route('admin.portfolios.index')],
                ['label' => $portfolio->title],
            ],
            'portfolio' => $portfolio,
            'clients' => Client::orderBy('name')->get(),
            'projects' => Project::orderBy('name')->get(),
            'statuses' => PublishStatus::cases(),
        ]);
    }

    public function update(PortfolioRequest $request, Portfolio $portfolio): RedirectResponse
    {
        $portfolio->update([
            ...$this->applyImageUploads($request, $request->validated(), ['thumbnail_path'], 'portfolios', $portfolio),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.portfolios.index')->with('success', 'Portfolio berhasil diperbarui.');
    }

    public function destroy(Portfolio $portfolio): RedirectResponse
    {
        $this->deleteImageFiles($portfolio, ['thumbnail_path']);

        foreach ($portfolio->images as $image) {
            $this->deleteImageFiles($image, ['image_path']);
        }

        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')->with('success', 'Portfolio berhasil dihapus.');
    }
}
