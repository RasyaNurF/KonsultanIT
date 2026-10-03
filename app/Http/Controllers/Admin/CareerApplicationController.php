<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CareerApplicationRequest;
use App\Models\CareerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CareerApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $applications = CareerApplication::query()
            ->search($request->string('q')->toString())
            ->withStatus($request->string('status')->toString())
            ->with('career')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.career-applications.index', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Lamaran Masuk'],
            ],
            'searchPlaceholder' => 'Cari nama atau email pelamar…',
            'searchAction' => route('admin.career-applications.index'),
            'applications' => $applications,
            'statuses' => CareerApplication::STATUSES,
        ]);
    }

    public function show(CareerApplication $careerApplication): View
    {
        return view('admin.career-applications.show', [
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Lamaran Masuk', 'url' => route('admin.career-applications.index')],
                ['label' => $careerApplication->name],
            ],
            'application' => $careerApplication->load('career'),
            'statuses' => CareerApplication::STATUSES,
        ]);
    }

    public function download(CareerApplication $careerApplication): StreamedResponse
    {
        abort_unless(filled($careerApplication->cv_path) && Storage::disk('local')->exists($careerApplication->cv_path), 404);

        return Storage::disk('local')->download($careerApplication->cv_path, null, ['Cache-Control' => 'private, no-store']);
    }

    public function update(CareerApplicationRequest $request, CareerApplication $careerApplication): RedirectResponse
    {
        $careerApplication->update($request->validated());

        return redirect()->route('admin.career-applications.show', $careerApplication)->with('success', 'Status lamaran berhasil diperbarui.');
    }

    public function destroy(CareerApplication $careerApplication): RedirectResponse
    {
        if (filled($careerApplication->cv_path) && Storage::disk('local')->exists($careerApplication->cv_path)) {
            Storage::disk('local')->delete($careerApplication->cv_path);
        }

        $careerApplication->delete();

        return redirect()->route('admin.career-applications.index')->with('success', 'Lamaran berhasil dihapus.');
    }
}
