<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AnnouncementPlacement;
use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AnnouncementRequest;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    use HandlesImageUploads;

    public function index(Request $request): View
    {
        $announcements = Announcement::query()
            ->search($request->string('q')->toString())
            ->forPlacement($request->string('placement')->toString())
            ->when($request->filled('active'), fn ($query) => $query->where('is_active', $request->boolean('active')))
            ->orderBy('placement')
            ->orderBy('sort_order')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.announcements.index', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Pengumuman'],
            ],
            'searchPlaceholder' => 'Cari judul atau isi pengumuman…',
            'searchAction' => route('admin.announcements.index'),
            'announcements' => $announcements,
            'placements' => AnnouncementPlacement::cases(),
        ]);
    }

    public function create(): View
    {
        return view('admin.announcements.form', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Pengumuman', 'url' => route('admin.announcements.index')],
                ['label' => 'Tambah'],
            ],
            'announcement' => new Announcement,
            'placements' => AnnouncementPlacement::cases(),
        ]);
    }

    public function store(AnnouncementRequest $request): RedirectResponse
    {
        Announcement::create($this->prepareData($request));

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.form', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Pengumuman', 'url' => route('admin.announcements.index')],
                ['label' => Str::limit($announcement->message, 40)],
            ],
            'announcement' => $announcement,
            'placements' => AnnouncementPlacement::cases(),
        ]);
    }

    public function update(AnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        $announcement->update($this->prepareData($request, $announcement));

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $this->deleteImageFiles($announcement, ['image_path']);
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function prepareData(AnnouncementRequest $request, ?Announcement $announcement = null): array
    {
        $data = $this->applyImageUploads($request, $request->validated(), ['image_path'], 'announcements', $announcement);

        $data['is_active'] = $request->boolean('is_active');
        $data['is_dismissible'] = $request->boolean('is_dismissible');

        if (($data['frequency'] ?? null) !== 'days') {
            $data['frequency_days'] = null;
        }

        return $data;
    }
}
