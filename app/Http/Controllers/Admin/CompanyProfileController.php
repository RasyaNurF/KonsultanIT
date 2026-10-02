<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyProfileRequest;
use App\Models\CompanyProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyProfileController extends Controller
{
    use HandlesImageUploads;

    public function edit(): View
    {
        $profile = CompanyProfile::active() ?? new CompanyProfile(['name' => 'KIT Konsultan IT']);

        return view('admin.company-profile.edit', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Profil Perusahaan'],
            ],
            'profile' => $profile,
        ]);
    }

    public function update(CompanyProfileRequest $request): RedirectResponse
    {
        $profile = CompanyProfile::active() ?? new CompanyProfile;
        $data = $this->applyImageUploads($request, $request->validated(), ['logo_path', 'cover_image_path'], 'company', $profile->exists ? $profile : null);

        $values = collect(explode("\n", (string) ($data['values'] ?? '')))
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->values()
            ->all();
        $data['values'] = $values ?: null;
        $data['is_active'] = true;

        if ($profile->exists) {
            $profile->update($data);
        } else {
            CompanyProfile::create($data);
        }

        return redirect()->route('admin.company.edit')->with('success', 'Profil perusahaan berhasil disimpan.');
    }
}
