<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RodadaClassificacao extends Model
{
    public $timestamps = false;

    protected $table 	= 'rodada_classificacao';
    protected $fillable = ['liga_id', 'rodada_id', 'usuario_id', 'posicao', 'pontosdisputados', 'pontosganhos', 'aproveitamento', 'lider'];

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function rodada()
    {
        return $this->belongsTo(LigaRodada::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
