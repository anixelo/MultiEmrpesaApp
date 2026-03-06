<?php

namespace Tests\Feature\Auth;

use MultiempresaApp\Plans\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure required role and free plan exist for registration
        Role::firstOrCreate(['name' => 'administrador', 'guard_name' => 'web']);
        Plan::firstOrCreate(
            ['name' => 'Gratuito'],
            ['price_monthly' => 0, 'price_yearly' => 0, 'max_users' => 3, 'max_presupuestos' => 5, 'has_tasks' => false, 'active' => true]
        );
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'company_name' => 'Test Company',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
