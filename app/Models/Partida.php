<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $table 	= 'liga_partida';
    protected $fillable = ['rodada_id', 'datapartida', 'mandante', 'golsmandante', 'visitante', 'golsvisitante', 'sigla', 'created_by', 'updated_by'];

    public function rodada()
    {
        return $this->belongsTo(LigaRodada::class, 'rodada_id');
    }

    public function getDataAttribute()
    {
        return datetime($this->datapartida, 'Y-m-d');
    }

    public function getHoraAttribute()
    {
        return datetime($this->datapartida, 'H:i');
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
}
