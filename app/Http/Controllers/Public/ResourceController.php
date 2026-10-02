<?php

namespace App\Http\Controllers\Public;

use App\Enums\PublishStatus;
use App\Enums\ResourceType;
use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function type(string $type): View
    {
        $resourceType = ResourceType::tryFrom($type);
        abort_if($resourceType === null, 404);

        $resources = Resource::query()
            ->where('status', PublishStatus::Published->value)
            ->where('type', $resourceType->value)
            ->orderByDesc('published_at')
            ->latest('id')
            ->paginate(9);

        $view = match ($resourceType) {
            ResourceType::Event => 'resources.event-index',
            ResourceType::News => 'resources.news-index',
            ResourceType::GoLive => 'resources.go-live-index',
            ResourceType::Ebook => 'resources.ebook-index',
            ResourceType::Whitepaper => 'resources.whitepaper-index',
            default => 'resources.type',
        };

        return view($view, [
            'resourceType' => $resourceType,
            'resources' => $resources,
            'types' => ResourceType::cases(),
        ]);
    }

    public function show(string $type, Resource $resource): View
    {
        $resourceType = ResourceType::tryFrom($type);
        abort_if($resourceType === null || $resource->type !== $resourceType, 404);
        abort_unless($resource->status === PublishStatus::Published, 404);

        $related = Resource::query()
            ->where('status', PublishStatus::Published->value)
            ->where('type', $resourceType->value)
            ->whereKeyNot($resource->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $view = match ($resourceType) {
            ResourceType::Event => 'resources.event-show',
            ResourceType::News => 'resources.news-show',
            ResourceType::GoLive => 'resources.go-live-show',
            ResourceType::Ebook => 'resources.ebook-show',
            ResourceType::Whitepaper => 'resources.whitepaper-show',
            default => 'resources.show',
        };

        return view($view, [
            'resource' => $resource,
            'resourceType' => $resourceType,
            'related' => $related,
        ]);
    }
}
