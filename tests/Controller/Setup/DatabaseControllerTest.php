<?php

namespace Tests\Controller\Setup;

use App\Settings\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_form_does_not_expose_configured_password(): void
    {
        SystemSettings::fake([
            'setup_completed' => false,
        ]);

        $originalServer = $_SERVER['DB_PASSWORD'] ?? null;
        $originalEnv = $_ENV['DB_PASSWORD'] ?? null;
        $_SERVER['DB_PASSWORD'] = $_ENV['DB_PASSWORD'] = 'configured-secret-password';

        try {
            $this->get('setup/database')
                ->assertOk()
                ->assertDontSee('configured-secret-password');
        } finally {
            $_SERVER['DB_PASSWORD'] = $originalServer;
            $_ENV['DB_PASSWORD'] = $originalEnv;
        }
    }
}
