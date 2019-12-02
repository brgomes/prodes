<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigaRodada extends Model
{
    protected $table 	= 'liga_rodada';
    protected $fillable = ['liga_id', 'numero', 'datainicio', 'datafim', 'dataconsolidacao', 'created_by', 'updated_by'];

    public function liga()
    {
    	return $this->belongsTo(Liga::class);
    }

    public function partidas()
    {
        return $this->hasMany(Partida::class, 'rodada_id')->orderBy('datapartida');
    }

    public function getDatainicialAttribute()
    {
        return datetime($this->datainicio, 'Y-m-d');
    }

    public function getHorainicialAttribute()
    {
        return datetime($this->datainicio, 'H:i');
    }

    public function getDatafinalAttribute()
    {
        return datetime($this->datafim, 'Y-m-d');
    }

    public function getHorafinalAttribute()
    {
        return datetime($this->datafim, 'H:i');
    }
}
