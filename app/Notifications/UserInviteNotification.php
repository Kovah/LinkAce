<?php

namespace App\Notifications;

use App\Models\UserInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class UserInviteNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail(UserInvitation $invitation): MailMessage
    {
        return (new MailMessage)
            ->subject(trans('admin.user_management.invite_notification_title'))
            ->line(trans('admin.user_management.invite_notification'))
            ->action(trans('admin.user_management.invite_accept'), $this->inviteUrl($invitation))
            ->line(trans('admin.user_management.invite_valid_until_info', ['datetime' => $invitation->valid_until]));
    }

    public function toArray(UserInvitation $invitation): array
    {
        return [
            'invitation' => $invitation,
            'inviteUrl' => $this->inviteUrl($invitation),
        ];
    }

    protected function inviteUrl(UserInvitation $invitation): string
    {
        return URL::temporarySignedRoute(
            'user-management-accept-invite',
            $invitation->valid_until,
            ['token' => $invitation->token]
        );
    }
}
