<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RodadaClassificacao extends Model
{
    public $timestamps = false;

    protected $table 	= 'rodada_classificacao';
    protected $fillable = ['rodada_id', 'usuario_id', 'posicao', 'pontosdisputados', 'pontosganhos', 'aproveitamento'];

    public function rodada()
    {
        return $this->belongsTo(LigaRodada::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
