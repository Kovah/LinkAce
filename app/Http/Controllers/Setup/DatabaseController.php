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
    protected string $connection;
    protected array $dbConfig;

    public function index(): View
    {
        return view('setup.database', [
            'pageTitle' => trans('setup.database_configure'),
        ]);
    }

    public function configure(SetupDatabaseRequest $request): RedirectResponse
    {
        $this->createTempDatabaseConnection($request->validated());

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

    protected function createTempDatabaseConnection(array $configuration): void
    {
        $this->connection = $configuration['connection'];
        $this->dbConfig = config('database.connections.' . $this->connection);

        if ($this->connection === 'sqlite') {
            $this->dbConfig['database'] = $configuration['db_path'];
        } else {
            $this->dbConfig['host'] = $configuration['db_host'];
            $this->dbConfig['port'] = $configuration['db_port'];
            $this->dbConfig['database'] = $configuration['db_name'];
            $this->dbConfig['username'] = $configuration['db_user'];
            $this->dbConfig['password'] = $configuration['db_password'];
        }

        Config::set('database.connections.' . $this->connection, $this->dbConfig);
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
                '--database' => $this->connection, // Specify the correct connection
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
     * the presence and write-ability of the file in the previous setup step.
     */
    protected function storeConfigurationInEnv(): void
    {
        $envContent = File::get(base_path('.env'));

        $envContent = preg_replace([
            '/DB_CONNECTION=(.*)\S/',
            '/DB_HOST=(.*)\S/',
            '/DB_PORT=(.*)\S/',
            '/DB_DATABASE=(.*)\S/',
            '/DB_USERNAME=(.*)\S/',
            '/DB_PASSWORD=(.*)\S/',
        ], [
            'DB_CONNECTION=' . $this->connection,
            'DB_HOST=' . ($this->dbConfig['host'] ?? ''),
            'DB_PORT=' . ($this->dbConfig['port'] ?? ''),
            'DB_DATABASE=' . ($this->dbConfig['database'] ?? ''),
            'DB_USERNAME=' . ($this->dbConfig['username'] ?? ''),
            'DB_PASSWORD=' . ($this->dbConfig['password'] ?? ''),
        ], $envContent);

        if ($envContent !== null) {
            File::put(base_path('.env'), $envContent);
        }
    }

    /**
     * To prevent unwanted data loss we check for data in the database. It does
     * not matter which data, because users may accidentally enter the
     * credentials for a wrong database.
     *
     * @return bool
     */
    protected function databaseHasData(): bool
    {
        try {
            $presentTables = DB::connection($this->connection)
                ->getDoctrineSchemaManager()
                ->listTableNames();
        } catch (PDOException|\Doctrine\DBAL\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

        return count($presentTables) > 0;
    }
}
