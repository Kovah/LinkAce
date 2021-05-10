<?php

namespace App\Console\Commands;

use App\Actions\ImportHtmlBookmarks;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ImportCommand extends Command
{
    use AsksForUser;

    protected $signature = 'links:import
                        {filepath : Bookmarks file to import}
                        {--skip-meta-generation : Whether the automatic generation of titles should be skipped.}
                        {--skip-check : Whether the links checking should be skipped afterwards}';

    protected $description = 'Import links from a bookmarks file which is stored locally in your file system.';

    public function handle(): void
    {
        $lookupMeta = true;

        // Check if option "-skip-lookup" is present
        if ($this->option('skip-meta-generation')) {
            $this->info('Skipping automatic meta generation.');
            $lookupMeta = false;
        }

        $this->info('You will be asked to select a user who will be the owner of the imported bookmarks now.');
        $this->askForUser();

        $this->info('Reading file "' . $this->argument('filepath') . '"...');
        $data = File::get(storage_path($this->argument('filepath')));

        if (empty($data)) {
            $this->warn('The provided file is empty or could not be read!');
            return;
        }

        $importer = new ImportHtmlBookmarks;
        $result = $importer->run($data, $this->user->id, $lookupMeta);

        if ($result === false) {
            $this->error('Error while importing bookmarks. Please check the application logs.');
            return;
        }

        // Check if option "-skip-check" is present
        if ($this->option('skip-check')) {
            $this->info('Skipping link check.');
        } else {
            Artisan::queue('links:check');
        }

        $this->info(trans('import.import_successfully', [
            'imported' => $importer->getImportCount(),
            'skipped' => $importer->getSkippedCount(),
        ]));
    }
}
