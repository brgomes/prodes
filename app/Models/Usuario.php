<?php

namespace App\Models;

use App\Models\Palpite;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $table = 'usuario';

    protected $fillable = [
        'primeironome', 'sobrenome', 'sexo', 'timezone', 'pais_id', 'email', 'password',
        'locale', 'datasenha', 'ativo', 'admin', 'ultimoacesso', 'foto',
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

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyMail());
    }

    public function verifyUser()
    {
        return $this->hasOne(VerifyUser::class, 'user_id');
    }

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }

    public function getBandeiraAttribute()
    {
        return 'flag-icon flag-icon-' . mb_strtolower($this->pais->iso3);
    }

    public function participaDaLiga($liga_id)
    {
        $jogador = Jogador::where('usuario_id', $this->id)
                        ->where('liga_id', $liga_id)
                        ->first();

        return isset($jogador);
    }

    public function adminLiga($liga_id)
    {
        $jogador = Jogador::where('usuario_id', $this->id)
                    ->where('liga_id', $liga_id)
                    ->where('admin', 1)
                    ->first();

        return isset($jogador);
    }

    public function palpite($partida_id)
    {
        return Palpite::where('usuario_id', $this->id)
                ->where('partida_id', $partida_id)
                ->first();
    }
}
