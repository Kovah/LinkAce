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
                        {filepath : Bookmarks file to import, use absolute paths if stored outside of LinkAce}
                        {--skip-meta-generation : Whether the automatic generation of titles should be skipped.}
                        {--skip-check : Whether the links checking should be skipped afterwards}';

    protected $description = 'Import links from a bookmarks file which is stored locally in your file system.';

    public function handle(): void
    {
        $lookupMeta = true;

        if ($this->option('skip-meta-generation')) {
            $this->info('Skipping automatic meta generation.');
            $lookupMeta = false;
        }

        $this->info('You will be asked to select a user who will be the owner of the imported bookmarks now.');
        $this->askForUser();

        $this->info('Reading file "' . $this->argument('filepath') . '"...');
        $filepath = $this->argument('filepath');
        $data = File::get(str_starts_with($filepath, '/') ? $filepath : storage_path($filepath));

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

        if ($this->option('skip-check')) {
            $this->info('Skipping link check.');
        } elseif (config('mail.host') !== null) {
            Artisan::queue('links:check');
        } else {
            $this->warn('Links are configured to be checked, but email is not configured!');
        }

        $this->info(trans('import.import_successfully', [
            'imported' => $importer->getImportCount(),
            'skipped' => $importer->getSkippedCount(),
        ]));
    }
}
