<?php

namespace Tests\Feature\Controller\App;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    public function testValidLoginResponse(): void
    {
        $response = $this->get('login');

        $response->assertStatus(200)
            ->assertSee('Login');
    }

    public function testValidLoginRedirect(): void
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->get('login');

        $response->assertStatus(302)
            ->assertRedirect('dashboard');
    }

    public function testValidLoginSubmit(): void
    {
        $user = factory(User::class)->create();

        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'secretpassword',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('dashboard');
    }

    public function testInvalidLoginSubmit(): void
    {
        $user = factory(User::class)->create();

        $response = $this->post('login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['email']);
    }
}
