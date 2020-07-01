<?php

namespace Tests\Controller\Guest;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestControllerTest extends TestCase
{
    use RefreshDatabase;

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

        $response->assertOk()
            ->assertSee('Login');
    }

    public function testGuestModeDisabledWithLogin(): void
    {
        $response = $this->get('links');

        $response->assertRedirect('login');
    }
}
