<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorCodeNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage())
            ->line(__('global.two_factor.your_code_is', ['code' => $notifiable->two_factor_code]))
            ->action(__('global.two_factor.verify_here'), route('twoFactor.show'))
            ->line(__('global.two_factor.will_expire_in', ['minutes' => 15]))
            ->line(__('global.two_factor.ignore_this'));
    }
}
