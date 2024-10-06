<?php

namespace App\Console;

use App\Console\Commands\CheckLinksCommand;
use App\Console\Commands\CompleteSetupCommand;
use App\Console\Commands\ImportCommand;
use App\Console\Commands\ListUsersCommand;
use App\Console\Commands\RegisterUserCommand;
use App\Console\Commands\ResetPasswordCommand;
use App\Console\Commands\UpdateLinkThumbnails;
use App\Console\Commands\ViewRecoveryCodesCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('queue:work --queue=default,import')->withoutOverlapping();

        $schedule->command('links:check')->hourly();

        if (config('backup.backup.enabled')) {
            $schedule->command('backup:clean')->daily()->at('01:00');
            $schedule->command('backup:run')->daily()->at('02:00');
        }
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
