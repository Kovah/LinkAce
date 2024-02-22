<?php

namespace Tests\Migrations;

use Illuminate\Support\Facades\Artisan;

trait MigratesUpTo
{
    protected function migrateUpTo(string $migration): void
    {
        $migrator = app('migrator');
        $dbPath = database_path('migrations');

        $migrations = collect($migrator->getMigrationFiles($dbPath))
            ->takeWhile(fn($file) => $file !== $dbPath . '/' . $migration);

        $migrations->prepend(database_path('schema/sqlite-schema.dump'));

        Artisan::call('migrate:fresh', ['--realpath' => 'true', '--path' => $migrations->values()]);
    }
}
