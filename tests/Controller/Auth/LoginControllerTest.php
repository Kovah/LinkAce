<?php

namespace Tests\Controller\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testValidLoginResponse(): void
    {
        $response = $this->get('login');

        $response->assertOk()
            ->assertSee('Login');
    }

    public function testValidLoginRedirect(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('login');

        $response->assertRedirect('dashboard');
    }

    public function testValidLoginSubmit(): void
    {
        $user = User::factory()->create();

        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'secretpassword',
        ]);

        $response->assertRedirect('dashboard');
    }

    public function testInvalidLoginSubmit(): void
    {
        $user = User::factory()->create();

        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}
