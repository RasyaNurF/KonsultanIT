<?php

namespace App\Http\Controllers\Public;

use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\Industry;
use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::query()
            ->where('status', PublishStatus::Published->value)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $industries = Industry::query()
            ->where('status', PublishStatus::Published->value)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('services.index', [
            'services' => $services,
            'industries' => $industries,
        ]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->status === PublishStatus::Published, 404);

        $service->load(['industries' => fn ($query) => $query
            ->where('status', PublishStatus::Published->value)
            ->orderBy('sort_order')
            ->orderBy('name')]);

        $related = Service::query()
            ->where('status', PublishStatus::Published->value)
            ->whereKeyNot($service->id)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('services.show', [
            'service' => $service,
            'related' => $related,
        ]);
    }
}
