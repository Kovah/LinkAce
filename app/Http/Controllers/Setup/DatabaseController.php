<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetupDatabaseRequest;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use PDOException;

class DatabaseController extends Controller
{
    protected $dbConfig;

    /**
     * Display the form for configuration of the database.
     *
     * @return View
     */
    public function index(): View
    {
        return view('setup.database');
    }

    /**
     * Handle the test and configuration of a new database connection.
     *
     * @param SetupDatabaseRequest $request
     * @return RedirectResponse
     */
    public function configure(SetupDatabaseRequest $request): RedirectResponse
    {
        $this->createTempDatabaseConnection($request->all());

        if ($this->databaseHasData() && !$request->has('overwrite_data')) {
            flash(trans('setup.database.data_present'), 'danger');
            return redirect()->back()->with('data_present', true)->withInput();
        }

        $migrationResult = $this->migrateDatabase();

        if ($migrationResult === false) {
            return redirect()->back()->withInput();
        }

        $this->storeConfigurationInEnv();

        return redirect()->route('setup.account');
    }

    /**
     * Accepts new credentials for a database and sets them accordingly.
     *
     * @param array $credentials
     */
    protected function createTempDatabaseConnection(array $credentials): void
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
     * Instead of trying to manually detect if the database connection is
     * working we try to run the migration of the database scheme. If it fails
     * we get the exact error we can display to the user, e.g. SQLSTATE[HY000]
     * [2002] Connection refused which implies wrong credentials.
     *
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
        } catch (Exception $e) {
            $alert = trans('setup.database.config_error') . ' ' . $e->getMessage();
            flash($alert, 'danger');
            return false;
        }

        return true;
    }

    /**
     * At this point we write the database credentials to the .env file.
     * We can ignore the FileNotFoundException exception as we already checked
     * the presence and writability of the file in the previous setup step.
     */
    protected function storeConfigurationInEnv(): void
    {
        $envContent = File::get(base_path('.env'));

        $envContent = preg_replace([
            '/DB_HOST=(.*)\S/',
            '/DB_PORT=(.*)\S/',
            '/DB_DATABASE=(.*)\S/',
            '/DB_USERNAME=(.*)\S/',
            '/DB_PASSWORD=(.*)\S/',
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

    /**
     * To prevent unwanted data loss we check for data in the database. It does
     * not matter which data, because users may accidentially enter the
     * credentials for a wrong database.
     *
     * @return bool
     */
    protected function databaseHasData(): bool
    {
        try {
            $present_tables = DB::connection('setup')
                ->getDoctrineSchemaManager()
                ->listTableNames();
        } catch (PDOException $e) {
            Log::error($e->getMessage());
            return false;
        }

        return count($present_tables) > 0;
    }
}
