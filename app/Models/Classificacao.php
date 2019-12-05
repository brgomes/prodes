<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classificacao extends Model
{
    public $timestamps = false;

    protected $table 	= 'classificacao';
    protected $fillable = ['liga_id', 'rodada_id', 'jogador_id', 'posicao', 'pontosdisputados', 'pontosganhos', 'aproveitamento', 'lider'];

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function rodada()
    {
        return $this->belongsTo(LigaRodada::class);
    }

    public function jogador()
    {
        return $this->belongsTo(Jogador::class);
    }

    public function getAproveitamentofAttribute()
    {
        return number_format($this->aproveitamento, 1);
    }
}
