<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_returns_a_successful_response(): void
    {
        $response = $this->get(route('kontak'));

        $response->assertOk();
        $response->assertSee('Mari bicarakan proyek digital Anda.');
        $response->assertSee('Formulir Konsultasi');
        $response->assertSee('Pertanyaan Umum');
        $response->assertSee('Kirim Permintaan');
    }

    public function test_homepage_links_to_the_contact_page(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('kontak'), false);
    }
}
