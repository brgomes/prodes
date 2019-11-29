<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $table 	= 'liga_partida';
    protected $fillable = ['rodada_id', 'datapartida', 'mandante', 'visitante', 'sigla', 'vencedor', 'created_by', 'updated_by'];

    public function rodada()
    {
        return $this->belongsTo(LigaRodada::class, 'rodada_id');
    }
}
