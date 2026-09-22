<?php

namespace App\View\Components\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NavLink extends Component
{
    public function __construct(
        public string $href,
        public string $icon,
        public bool $active = false,
        public int|string|null $badge = null,
        public ?string $badgeTarget = null,
    ) {}

    public function render(): View
    {
        return view('components.admin.nav-link');
    }
}
