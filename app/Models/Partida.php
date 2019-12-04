<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $table 	= 'liga_partida';
    protected $fillable = ['liga_id', 'rodada_id', 'datapartida', 'mandante', 'golsmandante', 'visitante',
                            'golsvisitante', 'sigla', 'cancelada', 'temresultado', 'created_by', 'updated_by'];

    public function liga()
    {
        return $this->belongsTo(Liga::class, 'liga_id');
    }

    public function rodada()
    {
        return $this->belongsTo(LigaRodada::class, 'rodada_id')->with('liga');
    }

    public function palpites()
    {
        return $this->hasMany(Palpite::class, 'partida_id');
    }

    public function palpite()
    {
        $user = auth()->user();

        if (!$user) {
            return null;
        }

        return $this->hasOne(Palpite::class, 'partida_id')->where('usuario_id', $user->id);
    }

    public function getDataAttribute()
    {
        return datetime($this->datapartida, 'Y-m-d');
    }

    public function getHoraAttribute()
    {
        return datetime($this->datapartida, 'H:i');
    }

    public function getResultadoAttribute()
    {
        if ($this->cancelada) {
            return __('content.cancelada');
        } else {
            return $this->golsmandante . '-' . $this->golsvisitante;
        }
    }

    public function getVencedorAttribute()
    {
        if ($this->golsmandante > $this->golsvisitante) {
            return 'M';
        } elseif ($this->golsmandante < $this->golsvisitante) {
            return 'V';
        }

        return 'E';
    }

    public function getDescricaoAttribute()
    {
        if (isset($this->golsmandante) && isset($this->golsvisitante)) {
            if ($this->golsmandante > $this->golsvisitante) {
                return '<strong>' . $this->mandante . '</strong> ' . $this->golsmandante . '-' . $this->golsvisitante . ' ' . $this->visitante;
            } elseif ($this->golsmandante < $this->golsvisitante) {
                return $this->mandante . ' ' . $this->golsmandante . '-' . $this->golsvisitante . ' <strong>' . $this->visitante . '</strong>';
            } else {
                return $this->mandante . ' ' . $this->golsmandante . '-' . $this->golsvisitante . ' ' . $this->visitante;
            }
        } else {
            return $this->mandante . ' - ' . $this->visitante;
        }
    }

    public function aberta()
    {
        if (isset($this->golsmandante) || isset($this->golsvisitante)) {
            return false;
        }

        if ($this->datapartida <= Carbon::now()->setTimezone(config('app.timezone'))) {
            return false;
        }

        if ($this->cancelada) {
            return false;
        }

        return true;
    }
}
