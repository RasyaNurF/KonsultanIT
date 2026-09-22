<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PortfolioImageRequest;
use App\Models\Portfolio;
use App\Models\PortfolioImage;
use App\Support\SvgSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class PortfolioImageController extends Controller
{
    public function store(PortfolioImageRequest $request, Portfolio $portfolio): RedirectResponse
    {
        $path = $request->file('image_file')->store('portfolios/gallery', 'public');

        if (is_string($path)) {
            SvgSanitizer::sanitizeFile(Storage::disk('public')->path($path));
        }

        $portfolio->images()->create([
            'image_path' => $path,
            'caption' => $request->string('caption')->toString() ?: null,
            'sort_order' => (int) ($portfolio->images()->max('sort_order') ?? 0) + 1,
        ]);

        return back()->with('success', 'Gambar galeri berhasil ditambahkan.');
    }

    public function destroy(Portfolio $portfolio, PortfolioImage $image): RedirectResponse
    {
        abort_unless($image->portfolio_id === $portfolio->id, 404);

        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();

        return back()->with('success', 'Gambar galeri berhasil dihapus.');
    }
}
