<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LinkCheckNotification extends Notification
{
    use Queueable;

    /** @var array */
    public $movedLinks = [];

    /** @var array */
    public $brokenLinks = [];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($moved_links, $broken_links)
    {
        $this->movedLinks = $moved_links;
        $this->brokenLinks = $broken_links;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
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
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'subject' => trans('link.notifications.linkcheck.errors'),
            'moved_links' => $this->movedLinks,
            'broken_links' => $this->brokenLinks,
        ];
    }
}
