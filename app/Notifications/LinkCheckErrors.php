<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LinkCheckErrors extends Notification
{
    use Queueable;

    /** @var array */
    public $moved_links = [];

    /** @var array */
    public $broken_links = [];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($moved_links, $broken_links)
    {
        $this->moved_links = $moved_links;
        $this->broken_links = $broken_links;
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
                'moved_links' => $this->moved_links,
                'broken_links' => $this->broken_links,
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
            'moved_links' => $this->moved_links,
            'broken_links' => $this->broken_links,
        ];
    }
}
