<?php

namespace App\Console\Commands\Setup;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

/**
 * Trait SetupDatabaseViaCLI
 *
 * @package App\Console\Commands\Setup
 */
trait SetupDatabaseViaCLI
{
    /** @var array */
    private $credentials = [
        'db_host' => null,
        'db_port' => null,
        'db_database' => null,
        'db_username' => null,
        'db_password' => null,
    ];

    private $credentialNames = [
        'db_host' => 'Hostname',
        'db_port' => 'Port',
        'db_database' => 'Database',
        'db_username' => 'Username',
        'db_password' => 'Password',
    ];

    /** @var array */
    private $dbConfig;

    protected function runStepDatabase()
    {
        $this->line('Step 1: Database');
        $this->line('========================');

        if ($this->databaseIsConfigured()) {

            if (!$this->confirm('It seems you already configured your database via the .env file. Do you want to proceed anyway, which will overwrite your current settings for the database?')) {
                return;
            }
        }

        $this->askForValidCredentials();

        $this->storeConfigurationInEnv();

        $this->success('Your database was configured correctly.');
    }

    protected function databaseIsConfigured()
    {
        return env('DB_HOST') && env('DB_DATABASE');
    }

    protected function askForValidCredentials()
    {
        do {
            $this->line('Provide the correct credentials for your database now.');

            foreach ($this->credentials as $key => $value) {
                $field = $this->credentialNames[$key];
                $this->credentials[$key] = $this->ask('Please enter a value for the ' . $field, env(strtoupper($key)));
            }

            $testResult = $this->validateCredentials();

            if (is_string($testResult)) {
                $this->warn('LinkAce could not connect to your database. The following error was returned: ');
                $this->warn($testResult . "\n");
            }

        } while ($testResult !== true);
    }

    protected function validateCredentials()
    {
        $this->createTempDatabaseConnection();

        return $this->testDatabaseConnection();
    }

    /**
     * @param array $credentials
     */
    protected function createTempDatabaseConnection(): void
    {
        $this->dbConfig = Config::get('database.connections.mysql');
        $this->dbConfig['host'] = $this->credentials['db_host'];
        $this->dbConfig['port'] = $this->credentials['db_port'];
        $this->dbConfig['database'] = $this->credentials['db_database'];
        $this->dbConfig['username'] = $this->credentials['db_username'];
        $this->dbConfig['password'] = $this->credentials['db_password'];

        Config::set('database.connections.setup', $this->dbConfig);
    }

    /**
     * @return bool|\Exception
     */
    protected function testDatabaseConnection()
    {
        try {
            // @TODO For some unknown reason I cannot setup the database anymore after entering the wrong credentials once, even if the credentials are correct on the second try [KW 2019-10-22]

            Artisan::call('migrate:fresh', [
                '--database' => 'setup', // Specify the correct connection
                '--force' => true, // Needed for production
                '--no-interaction' => true,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    protected function storeConfigurationInEnv(): void
    {
        $envContent = File::get(base_path('.env'));

        $envContent = preg_replace([
            '/DB_HOST=([\S]+)?/',
            '/DB_PORT=([0-9]+)?/',
            '/DB_DATABASE=([\S]+)?/',
            '/DB_USERNAME=([\S]+)?/',
            '/DB_PASSWORD=([\S]+)?/',
        ], [
            'DB_HOST=' . $this->dbConfig['host'],
            'DB_PORT=' . $this->dbConfig['port'],
            'DB_DATABASE=' . $this->dbConfig['database'],
            'DB_USERNAME=' . $this->dbConfig['username'],
            'DB_PASSWORD=' . $this->dbConfig['password'],
        ], $envContent);

        if ($envContent === null) {
            $this->warn('Error while saving your .env file. Please add your credentials manually before your proceed');
            return;
        }

        File::put(base_path('.env'), $envContent);
    }
}
