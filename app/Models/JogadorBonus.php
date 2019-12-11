<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JogadorBonus extends Model
{
    protected $table 	= 'jogador_bonus';
    protected $fillable = ['liga_id', 'jogador_id', 'pergunta_id', 'resposta_id', 'consolidado', 'pontosdisputados', 'pontosganhos'];

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function jogador()
    {
        return $this->belongsTo(Jogador::class)->with('usuario');
    }

    public function pergunta()
    {
        return $this->belongsTo(BonusPergunta::class);
    }

    public function resposta()
    {
        return $this->belongsTo(BonusResposta::class);
    }
}
