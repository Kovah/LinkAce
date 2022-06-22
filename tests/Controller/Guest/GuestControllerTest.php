<?php

namespace Tests\Controller\Guest;

use App\Settings\SettingsAudit;
use App\Settings\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestModeEnabled(): void
    {
        SystemSettings::fake([
            'guest_access_enabled' => true,
            'setup_completed' => true,
        ]);

        $response = $this->get('/');

        $response->assertRedirect('guest/links');
    }

    public function testGuestModeDisabledWithSplashpage(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('login');
    }

    public function testGuestModeDisabledWithLogin(): void
    {
        $response = $this->get('links');

        $response->assertRedirect('login');
    }
}
