<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigaClassificacao extends Model
{
    public $timestamps = false;

    protected $table 	= 'liga_classificacao';
    protected $fillable = ['liga_id', 'usuario_id', 'admin', 'rodadasjogadas', 'rodadasvencidas', 'pontosdisputados', 'pontosganhos', 'posicao', 'aproveitamento'];

    public function liga()
    {
        return $this->belongsTo(Liga::class)->with(['rodadas', 'classificacao']);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
