<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    public function testValidDashboardResponse(): void
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->get('dashboard');

        $response->assertStatus(200)
            ->assertSee('Hello ' . $user->name . '!');
    }

    public function testLoginRedirectForDashboard(): void
    {
        $response = $this->get('dashboard');

        $response->assertStatus(302)
            ->assertRedirect('login');
    }
}
