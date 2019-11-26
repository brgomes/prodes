<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    private $_notifiable;

    public function __construct($notifiable)
    {
        $this->_notifiable = $notifiable;
    }

    public function build()
    {
        return $this->markdown('emails.verify-mail', [
            'usuario'   => $this->_notifiable,
            'url'       => route('custom.verify', $this->_notifiable->verifyUser->token),
        ]);
    }
}
