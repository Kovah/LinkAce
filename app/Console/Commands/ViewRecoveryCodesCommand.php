<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/**
 * Class ViewRecoveryCodesCommand
 *
 * @package App\Console\Commands
 */
class ViewRecoveryCodesCommand extends Command
{
    use AsksForUser;

    protected $signature = '2fa:view-recovery-codes';

    public function handle(): void
    {
        $this->line('This tool allows you to view the 2FA recovery codes for any user.');

        $this->askForUserEmail();
        $this->viewBackupCodes();
    }

    protected function viewBackupCodes(): void
    {
        if (empty($this->user->two_factor_recovery_codes)) {
            $this->warn('Two Factor Authentication is not enabled for this user.');
            return;
        }

        $this->info('Recovery Codes for user ' . $this->user->name .':');

        $recoveryCodes = json_decode(decrypt($this->user->two_factor_recovery_codes), true);
        foreach ($recoveryCodes as $code) {
            $this->line($code);
        }
    }
}
