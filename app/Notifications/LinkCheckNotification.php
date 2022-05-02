<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LinkCheckNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public array $movedLinks, public array $brokenLinks)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
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

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'subject' => trans('link.notifications.linkcheck.errors'),
            'moved_links' => $this->movedLinks,
            'broken_links' => $this->brokenLinks,
        ];
    }
}
