<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'nik' => '3201012345678901',
            'dob' => '1995-05-15',
            'gender' => 'male',
            'bpjs_number' => '0009876543210',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('patients', [
            'nik' => '3201012345678901',
            'full_name' => 'Test User',
            'bpjs_number' => '0009876543210',
            'bpjs_status' => 'pending',
        ]);
    }
}
