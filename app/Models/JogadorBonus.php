<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JogadorBonus extends Model
{
    protected $table 	= 'jogador_bonus';
    protected $fillable = ['liga_id', 'jogador_id', 'pergunta_id', 'opcao1_id', 'opcao2_id', 'opcao3_id', 'opcao4_id',
        'consolidado', 'pontos1', 'pontos2', 'pontos3', 'pontos4', 'pontosdisputados', 'pontosganhos'];

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

    public function opcao1()
    {
        return $this->belongsTo(BonusOpcao::class, 'opcao1_id');
    }

    public function opcao2()
    {
        return $this->belongsTo(BonusOpcao::class, 'opcao2_id');
    }

    public function opcao3()
    {
        return $this->belongsTo(BonusOpcao::class, 'opcao3_id');
    }

    public function opcao4()
    {
        return $this->belongsTo(BonusOpcao::class, 'opcao4_id');
    }
}
