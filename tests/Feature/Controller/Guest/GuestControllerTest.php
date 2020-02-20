<?php

namespace Tests\Feature\Controller\Guest;

use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GuestControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    public function testGuestModeEnabled(): void
    {
        Setting::create([
            'key' => 'system_guest_access',
            'value' => '1',
        ]);

        $response = $this->get('/');

        $response->assertRedirect('guest/links');
    }

    public function testGuestModeDisabledWithSplashpage(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Login');
    }

    public function testGuestModeDisabledWithLogin(): void
    {
        $response = $this->get('links');

        $response->assertRedirect('login');
    }
}
