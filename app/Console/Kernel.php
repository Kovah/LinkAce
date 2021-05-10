<?php

namespace App\Console;

use App\Console\Commands\CheckLinksCommand;
use App\Console\Commands\CleanupLinkHistoriesCommand;
use App\Console\Commands\ImportCommand;
use App\Console\Commands\RegisterUserCommand;
use App\Console\Commands\ResetPasswordCommand;
use App\Console\Commands\UpdateLinkThumbnails;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        RegisterUserCommand::class,
        CheckLinksCommand::class,
        ResetPasswordCommand::class,
        CleanupLinkHistoriesCommand::class,
        ImportCommand::class,
        UpdateLinkThumbnails::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('links:check')->hourly();

        $schedule->command('queue:work --daemon --once')
            ->withoutOverlapping();

        if (config('backup.backup.enabled')) {
            $schedule->command('backup:clean')->daily()->at('01:00');
            $schedule->command('backup:run')->daily()->at('02:00');
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
