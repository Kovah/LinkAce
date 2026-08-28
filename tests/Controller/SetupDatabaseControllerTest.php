<?php

namespace Tests\Controller;

use App\Settings\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetupDatabaseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_setup_rejects_multiline_passwords(): void
    {
        SystemSettings::fake([
            'setup_completed' => false,
        ]);

        $response = $this->from('/setup/database')->post('/setup/database', [
            'connection' => 'mysql',
            'db_host' => '127.0.0.1',
            'db_port' => 3306,
            'db_name' => 'linkace',
            'db_user' => 'linkace',
            'db_password' => "secret\nMAIL_MAILER=sendmail",
        ]);

        $response
            ->assertRedirect('/setup/database')
            ->assertSessionHasErrors('db_password');
    }

    public function test_database_setup_rejects_multiline_db_path(): void
    {
        SystemSettings::fake([
            'setup_completed' => false,
        ]);

        $response = $this->from('/setup/database')->post('/setup/database', [
            'connection' => 'sqlite',
            'db_path' => "/tmp/linkace.db\nMAIL_MAILER=sendmail",
        ]);

        $response
            ->assertRedirect('/setup/database')
            ->assertSessionHasErrors('db_path');
    }
}
