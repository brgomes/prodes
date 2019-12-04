<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Palpite extends Model
{
    protected $table        = 'usuario_palpite';
    protected $fillable     = ['usuario_id', 'rodada_id', 'partida_id', 'palpite', 'consolidado', 'pontos'];

    public function usuario()
    {
    	return $this->belongsTo(Usuario::class);
    }

    public function rodada()
    {
    	return $this->belongsTo(LigaRodada::class);
    }

    public function partida()
    {
        return $this->belongsTo(Partida::class);
    }
}
