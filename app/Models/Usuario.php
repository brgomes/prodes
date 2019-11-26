<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword;

class Usuario extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $table = 'usuario';

    protected $fillable = [
        'primeironome', 'sobrenome', 'sexo', 'timezone', 'pais_id', 'email',
        'password', 'datasenha', 'ativo', 'admin', 'ultimoacesso', 'foto',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*public function getAuthPassword()
    {
        return $this->senha;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['senha'] = $value;
    }*/

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }
}
