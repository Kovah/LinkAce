<?php

namespace Tests\Controller\App;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testValidDashboardResponse(): void
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->get('dashboard');

        $response->assertOk()
            ->assertSee('Hello ' . $user->name . '!');
    }
}
