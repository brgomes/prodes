<?php

namespace App\Notifications;

use App\Mail\ResetPassword as Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    use Queueable;

    private $_token;

    public function __construct($token)
    {
        $this->_token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = __('content.redefinir-senha');

        return (new Mailable($this->_token, $notifiable))->from(config('mail.username'), config('app.name'))->subject($subject)->to($notifiable->email);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
