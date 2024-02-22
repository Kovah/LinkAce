<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LinkCheckNotification extends Notification
{
    use Queueable;

    public function __construct(public array $movedLinks, public array $brokenLinks)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject(trans('link.notifications.linkcheck.errors'))
            ->markdown('mail.notifications.linkcheck', [
                'moved_links' => $this->movedLinks,
                'broken_links' => $this->brokenLinks,
                'linkace_url' => route('dashboard'),
            ]);
    }

    public function toArray(): array
    {
        return [
            'subject' => trans('link.notifications.linkcheck.errors'),
            'moved_links' => $this->movedLinks,
            'broken_links' => $this->brokenLinks,
        ];
    }
}
