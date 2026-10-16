<?php

namespace Tests\Middleware;

use App\Settings\SystemSettings;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SetupCheckMiddlewareTest extends TestCase
{
    public function test_setup_is_locked_if_database_is_unreachable(): void
    {
        $this->useDatabaseConnection('mysql', [
            'host' => '127.0.0.1',
            'port' => 1,
        ]);
        config(['app.debug' => false]);

        $this->get('setup/start')
            ->assertServerError()
            ->assertSee('An internal server error occured')
            ->assertDontSee('Welcome to the LinkAce setup')
            ->assertDontSee('SQLite database file');

        $this->get('setup/database')
            ->assertServerError();

        $this->post('setup/database')
            ->assertServerError();

        $this->post('setup/account')
            ->assertServerError();
    }

    public function test_setup_is_available_if_settings_table_is_missing(): void
    {
        $this->useDatabaseConnection('sqlite', [
            'database' => ':memory:',
        ]);

        $this->get('setup/start')
            ->assertOk()
            ->assertSee('Welcome to the LinkAce setup');
    }

    public function test_error_page_shows_hint_if_sqlite_database_file_is_missing(): void
    {
        $this->useDatabaseConnection('sqlite', [
            'database' => '/tmp/linkace-missing-database.sqlite',
        ]);
        config(['app.debug' => false]);

        $this->get('/')
            ->assertServerError()
            ->assertSee('An internal server error occured')
            ->assertSee('please make sure that your SQLite database file is present')
            ->assertDontSee('linkace-missing-database.sqlite');
    }

    public function test_health_check_works_if_database_is_unreachable(): void
    {
        $this->useDatabaseConnection('mysql', [
            'host' => '127.0.0.1',
            'port' => 1,
        ]);

        $this->get('_health')->assertOk();
    }

    protected function useDatabaseConnection(string $connection, array $config): void
    {
        foreach ($config as $key => $value) {
            config(["database.connections.$connection.$key" => $value]);
        }
        config(['database.default' => $connection]);
        DB::purge($connection);

        // Drop the faked settings so they are loaded from the database again
        app()->forgetInstance(SystemSettings::class);
    }
}
