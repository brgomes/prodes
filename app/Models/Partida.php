<?php

namespace App\Models;

use App\Helpers\Registry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $table 	= 'partida';
    protected $fillable = ['liga_id', 'rodada_id', 'datapartida', 'mandante', 'golsmandante', 'visitante',
        'golsvisitante', 'sigla', 'cancelada', 'temresultado', 'created_by', 'updated_by'];

    public function liga()
    {
        return $this->belongsTo(Liga::class, 'liga_id');
    }

    public function rodada()
    {
        return $this->belongsTo(Rodada::class, 'rodada_id')->with('liga');
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
            return $this->mandante . ' ' . $this->golsmandante . '-' . $this->golsvisitante . ' ' . $this->visitante;
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

    public function percentualMandante()
    {
        $var = 'palpites-partida' . $this->id;

        if (Registry::isRegistered($var)) {
            $palpites = Registry::get($var);
        } else {
            $palpites = Palpite::where('partida_id', $this->id)->get();

            Registry::set($var, $palpites);
        }

        if ($palpites->count() == 0) {
            return 0;
        }

        $count = 0;

        foreach ($palpites as $palpite) {
            if ($palpite->palpite == 'M') {
                $count++;
            }
        }

        return round(($count * 100) / $palpites->count(), 0);
    }

    public function percentualVisitante()
    {
        $var = 'palpites-partida' . $this->id;

        if (Registry::isRegistered($var)) {
            $palpites = Registry::get($var);
        } else {
            $palpites = Palpite::where('partida_id', $this->id)->get();

            Registry::set($var, $palpites);
        }

        if ($palpites->count() == 0) {
            return 0;
        }

        $count = 0;

        foreach ($palpites as $palpite) {
            if ($palpite->palpite == 'V') {
                $count++;
            }
        }

        return round(($count * 100) / $palpites->count(), 0);
    }
}
