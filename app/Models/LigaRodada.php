<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigaRodada extends Model
{
    protected $table 	= 'liga_rodada';
    protected $fillable = ['liga_id', 'numero', 'dataconsolidacao', 'created_by', 'updated_by'];

    public function liga()
    {
    	return $this->belongsTo(Liga::class);
    }

    public function partidas()
    {
        return $this->hasMany(Partida::class, 'rodada_id')->orderBy('datapartida');
    }
}
