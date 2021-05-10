<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ViewRecoveryCodesCommand extends Command
{
    use AsksForUser;

    protected $signature = '2fa:view-recovery-codes';

    protected $description = 'View the recovery codes for a given user, in case the user has no access to the dashboard anymore.';

    public function handle(): void
    {
        $this->line('This tool allows you to view the 2FA recovery codes for any user.');

        $this->askForUser();
        $this->viewBackupCodes();
    }

    protected function viewBackupCodes(): void
    {
        if (empty($this->user->two_factor_recovery_codes)) {
            $this->warn('Two Factor Authentication is not enabled for this user.');
            return;
        }

        $this->info('Recovery Codes for user ' . $this->user->name . ':');

        $recoveryCodes = json_decode(decrypt($this->user->two_factor_recovery_codes), true);
        foreach ($recoveryCodes as $code) {
            $this->line($code);
        }
    }
}
