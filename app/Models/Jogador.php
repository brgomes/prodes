<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jogador extends Model
{
    public $timestamps = false;

    protected $table 	= 'jogador';
    protected $fillable = ['liga_id', 'usuario_id', 'admin', 'rodadasjogadas', 'rodadasvencidas', 'pontosdisputados',
        'pontosganhos', 'bonusdisputados', 'bonusganhos', 'totalpontos', 'posicao', 'aproveitamento', 'created_at'];

    public function liga()
    {
        return $this->belongsTo(Liga::class)->with(['rodadas', 'jogadores']);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class)->with('pais');
    }

    public function getAproveitamentofAttribute()
    {
        return number_format($this->aproveitamento, 1);
    }

    public function palpite($partida_id)
    {
        return Palpite::where('partida_id', $partida_id)
                ->where('jogador_id', $this->id)
                ->first();
    }

    public function pontosNaRodada($rodada_id)
    {
        $item = Classificacao::where('rodada_id', $rodada_id)
                ->where('jogador_id', $this->id)
                ->first();

        if ($item) {
            return $item->pontosganhos;
        }

        return null;
    }

    public function resposta($pergunta_id)
    {
        return JogadorBonus::where('pergunta_id', $pergunta_id)
                ->where('jogador_id', $this->id)
                ->first();
    }
}
