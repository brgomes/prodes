<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Palpite extends Model
{
    protected $table        = 'palpite';
    protected $fillable     = ['usuario_id', 'jogador_id', 'rodada_id', 'partida_id', 'palpite', 'consolidado', 'pontos'];

    public function usuario()
    {
    	return $this->belongsTo(Usuario::class);
    }

    public function jogador()
    {
        return $this->belongsTo(Jogador::class);
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
