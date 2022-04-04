<?php

namespace Tests\Controller\Setup;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MetaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSetupCheckRedirect(): void
    {
        Setting::where('key', 'system_setup_completed')->delete();

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
        Setting::where('key', 'system_setup_completed')->delete();

        $response = $this->get('setup/start');

        $response->assertOk()
            ->assertSee('Welcome to the LinkAce setup');
    }
}
