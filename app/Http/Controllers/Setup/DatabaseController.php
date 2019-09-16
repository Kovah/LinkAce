<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetupDatabaseRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

/**
 * Class DatabaseController
 *
 * @package App\Http\Controllers\Setup
 */
class DatabaseController extends Controller
{
    protected $dbConfig;

    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('setup.database');
    }

    /**
     * @param SetupDatabaseRequest $request
     * @return Factory|View
     */
    public function configure(SetupDatabaseRequest $request)
    {
        $this->createTempDatabaseConnection($request->all());

        $migrationResult = $this->migrateDatabase();

        if ($migrationResult === false) {
            return redirect()->back()->withInput();
        }

        $this->storeConfigurationInEnv();

        return redirect()->route('setup.account');
    }

    /**
     * @param array $credentials
     */
    protected function createTempDatabaseConnection($credentials): void
    {
        $this->dbConfig = config('database.connections.mysql');

        $this->dbConfig['host'] = $credentials['db_host'];
        $this->dbConfig['port'] = $credentials['db_port'];
        $this->dbConfig['database'] = $credentials['db_name'];
        $this->dbConfig['username'] = $credentials['db_user'];
        $this->dbConfig['password'] = $credentials['db_password'];

        Config::set('database.connections.setup', $this->dbConfig);
    }

    /**
     * @return bool
     */
    protected function migrateDatabase(): bool
    {
        try {
            Artisan::call('migrate:fresh', [
                '--database' => 'setup', // Specify the correct connection
                '--force' => true, // Needed for production
                '--no-interaction' => true,
            ]);
        } catch (\Exception $e) {
            $alert = trans('setup.database.config_error') . ' ' . $e->getMessage();
            alert($alert, 'danger');
            return false;
        }

        return true;
    }

    protected function storeConfigurationInEnv(): void
    {
        $envContent = File::get(base_path('.env'));

        $envContent = str_replace([
            'DB_HOST=127.0.0.1',
            'DB_PORT=3306',
            'DB_DATABASE=linkace',
            'DB_USERNAME=linkace',
            'DB_PASSWORD=changeThisPassword',
        ], [
            'DB_HOST=' . $this->dbConfig['host'],
            'DB_PORT=' . $this->dbConfig['port'],
            'DB_DATABASE=' . $this->dbConfig['database'],
            'DB_USERNAME=' . $this->dbConfig['username'],
            'DB_PASSWORD=' . $this->dbConfig['password'],
        ], $envContent);

        if ($envContent !== null) {
            File::put(base_path('.env'), $envContent);
        }
    }
}
