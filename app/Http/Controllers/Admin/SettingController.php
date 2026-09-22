<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\SiteSetting;
use App\Support\SvgSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Pengaturan'],
            ],
            'settings' => SiteSetting::allAsArray(),
        ]);
    }

    public function update(SettingRequest $request): RedirectResponse
    {
        $data = $request->validated();

        foreach (['logo_path', 'favicon_path', 'hero_image_path', 'about_image_path'] as $field) {
            unset($data[$field.'_file'], $data[$field.'_remove']);

            if ($request->boolean($field.'_remove')) {
                $this->deleteStoredFile(SiteSetting::value($field));
                $data[$field] = null;

                continue;
            }

            if (! $request->hasFile($field.'_file')) {
                unset($data[$field]);

                continue;
            }

            $path = $request->file($field.'_file')->store('settings', 'public');

            if (! is_string($path)) {
                unset($data[$field]);

                continue;
            }

            SvgSanitizer::sanitizeFile(Storage::disk('public')->path($path));

            $this->deleteStoredFile(SiteSetting::value($field));
            $data[$field] = $path;
        }

        SiteSetting::put('maintenance_mode', $request->boolean('maintenance_mode') ? '1' : '0');
        SiteSetting::putMany($data, 'general');

        return redirect()->route('admin.settings.edit')->with('success', 'Pengaturan berhasil disimpan.');
    }

    private function deleteStoredFile(?string $path): void
    {
        if (filled($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
