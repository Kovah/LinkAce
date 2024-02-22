<?php

namespace Tests\Controller\Setup;

use App\Settings\SettingsAudit;
use App\Settings\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MetaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSetupCheckRedirect(): void
    {
        SystemSettings::fake([
            'setup_completed' => false,
        ]);

        $response = $this->get('/');

        $response->assertRedirect('setup/start');
    }

    public function testSetupCheckWithoutRedirect(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('login');
    }

    public function testRedirectIfSetupCompleted(): void
    {
        $response = $this->get('setup/start');

        $response->assertRedirect('/');
    }

    public function testSetupWelcomeView(): void
    {
        SystemSettings::fake([
            'setup_completed' => false,
        ]);

        $response = $this->get('setup/start');

        $response->assertOk()
            ->assertSee('Welcome to the LinkAce setup');
    }
}
