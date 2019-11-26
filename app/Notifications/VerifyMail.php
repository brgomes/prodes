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
        //$subject = __('content.redefinir-senha');
        $subject = 'Verificar endereço de e-mail';

        return (new Mailable($notifiable))->from('sobgestao@brgomes.com', config('app.name'))->subject($subject)->to($notifiable->email);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
