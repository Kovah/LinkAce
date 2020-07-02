<?php

namespace Tests\Controller\Setup;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MetaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.setup_completed' => false]);
    }

    public function testSetupCheckRedirect(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('setup/start');
    }

    public function testSetupCheckWithoutRedirect(): void
    {
        config(['app.setup_completed' => true]);

        $response = $this->get('/');

        $response->assertOk();
    }

    public function testRedirectIfSetupCompleted(): void
    {
        config(['app.setup_completed' => true]);

        $response = $this->get('setup/start');

        $response->assertRedirect('/');
    }

    public function testSetupWelcomeView(): void
    {
        $response = $this->get('setup/start');

        $response->assertOk()
            ->assertSee('Welcome to the LinkAce setup');
    }
}
