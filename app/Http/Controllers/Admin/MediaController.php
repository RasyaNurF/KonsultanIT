<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaRequest;
use App\Models\Media;
use App\Support\SvgSanitizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(Request $request): View
    {
        $media = Media::query()
            ->search($request->string('q')->toString())
            ->when($request->string('type')->toString() === 'image', fn ($query) => $query->images())
            ->when($request->string('type')->toString() === 'document', fn ($query) => $query->where('mime_type', 'not like', 'image/%'))
            ->latest()
            ->paginate(24)
            ->withQueryString();

        return view('admin.media.index', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Media'],
            ],
            'searchPlaceholder' => 'Cari nama file, judul, atau alt text…',
            'searchAction' => route('admin.media.index'),
            'media' => $media,
        ]);
    }

    public function store(MediaRequest $request): RedirectResponse|JsonResponse
    {
        $file = $request->file('file');
        $path = $file->store('media', 'public');

        if (is_string($path)) {
            SvgSanitizer::sanitizeFile(Storage::disk('public')->path($path));
        }

        $medium = Media::create([
            'disk' => 'public',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'alt_text' => $request->input('alt_text'),
            'title' => $request->input('title'),
            'uploaded_by' => auth()->id(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'media' => [
                    'id' => $medium->id,
                    'url' => $medium->url(),
                ],
            ], 201);
        }

        return redirect()->route('admin.media.index')->with('success', 'Media berhasil diunggah.');
    }

    public function update(Request $request, Media $medium): RedirectResponse
    {
        $validated = $request->validate([
            'alt_text' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $medium->update($validated);

        return redirect()->route('admin.media.index')->with('success', 'Media berhasil diperbarui.');
    }

    public function destroy(Media $medium): RedirectResponse
    {
        Storage::disk($medium->disk)->delete($medium->path);
        $medium->delete();

        return redirect()->route('admin.media.index')->with('success', 'Media berhasil dihapus.');
    }
}
