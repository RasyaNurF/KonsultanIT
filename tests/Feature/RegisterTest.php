<?php

namespace Tests\Feature;

use App\Enums\AdminRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_is_accessible_to_guests(): void
    {
        $this->get(route('register'))->assertOk();
    }

    public function test_user_can_register_and_is_logged_in(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Sinta Dewi',
            'email' => 'sinta@example.com',
            'password' => 'rahasia-aman-123',
            'password_confirmation' => 'rahasia-aman-123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'sinta@example.com', 'role' => AdminRole::User->value]);
    }

    public function test_registered_user_cannot_access_admin_panel(): void
    {
        $this->post(route('register'), [
            'name' => 'Sinta Dewi',
            'email' => 'sinta@example.com',
            'password' => 'rahasia-aman-123',
            'password_confirmation' => 'rahasia-aman-123',
        ]);

        $this->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_registered_user_can_open_their_dashboard_and_inbox(): void
    {
        $this->post(route('register'), [
            'name' => 'Sinta Dewi',
            'email' => 'sinta@example.com',
            'password' => 'rahasia-aman-123',
            'password_confirmation' => 'rahasia-aman-123',
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Total Pesan');

        $this->get(route('pesan.index'))
            ->assertOk()
            ->assertJsonStructure(['messages']);
    }

    public function test_registration_rejects_mismatched_password_confirmation(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Sinta Dewi',
            'email' => 'sinta@example.com',
            'password' => 'rahasia-aman-123',
            'password_confirmation' => 'berbeda-123',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('register'));
        $this->assertDatabaseMissing('users', ['email' => 'sinta@example.com']);
    }

    public function test_registration_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'sinta@example.com']);

        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Sinta Lain',
            'email' => 'sinta@example.com',
            'password' => 'rahasia-aman-123',
            'password_confirmation' => 'rahasia-aman-123',
        ]);

        $this->assertGuest();
        $response->assertInvalid('email');
    }
}
