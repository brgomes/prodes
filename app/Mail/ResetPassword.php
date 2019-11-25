<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $_token;
    private $_notifiable;

    public function __construct($token, $notifiable)
    {
        $this->_token      = $token;
        $this->_notifiable = $notifiable;
    }

    public function build()
    {
        return $this->markdown('auth.passwords.message', [
            'url'     => url(config('app.url') . route('password.reset', $this->_token, false)),
            'usuario' => $this->_notifiable
        ]);
    }
}
