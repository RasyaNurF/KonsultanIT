<?php

namespace Tests\Feature;

use App\Models\ProjectInquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_panel(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_admin_sees_the_dashboard_with_stats(): void
    {
        $admin = User::factory()->create();

        ProjectInquiry::create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'project_type' => 'Aplikasi Web',
            'project_detail' => 'Butuh aplikasi kasir.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Berikut ringkasan aktivitas KIT Konsultan IT.', false)
            ->assertSee('Total Leads');
    }
}
