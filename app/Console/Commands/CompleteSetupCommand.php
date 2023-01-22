<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CompleteSetupCommand extends Command
{
    protected $signature = 'setup:complete';

    protected $description = 'Use this command in case the setup cannot be completed automatically.';

    public function handle()
    {
        try {
            Setting::updateOrCreate(
                ['key' => 'system_setup_completed'],
                ['key' => 'system_setup_completed', 'value' => true]
            );
            Cache::forget('systemsettings');
        } catch (\Exception $e) {
            $this->error('Could not complete the setup because of an error: ' . $e->getMessage());
            $this->error('Check the log files for further details.');
            Log::warning($e);
            return;
        }

        $this->info('Successfully marked the setup as completed. You can now visit the web interface.');
        $this->line('Hint: If you install LinkAce with PostgreSQL or SQLite, use the <options=reverse>registeruser</> command now.');
    }
}
