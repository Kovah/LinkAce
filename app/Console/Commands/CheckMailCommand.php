<?php

namespace App\Console\Commands;

use App\Mail\TestConfigurationMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CheckMailCommand extends Command
{
    protected $signature = 'mail:check';

    protected $description = 'Test the correct setup of your mail credentials';

    public function handle(): void
    {
        $mailer = config('mail.default');

        $this->info("You are about to check the configuration for sending emails from LinkAce via $mailer.");

        do {
            $recipient = $this->ask('Which email address should receive the test email?');
            $validator = Validator::make(['recipient' => $recipient], ['recipient' => 'email']);
        } while ($recipient === null || $validator->fails());

        $this->info('Sending test email now. Please be patient.');

        try {
            Mail::to($recipient)->send(new TestConfigurationMail());
        } catch (\Exception $exception) {
            $this->error('Error while sending the test email:');
            $this->error($exception->getMessage());
            return;
        }

        $this->info("Successfully sent the test email via $mailer. Check your inbox.");
    }
}
