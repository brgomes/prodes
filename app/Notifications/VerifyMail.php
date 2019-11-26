<?php

namespace App\Notifications;

use App\Mail\VerifyMail as Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyMail extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = __('content.verificar-email');

        return (new Mailable($notifiable))->from(config('mail.username'), config('app.name'))->subject($subject)->to($notifiable->email);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
