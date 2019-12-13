<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusPergunta extends Model
{
    protected $table 	= 'bonus_pergunta';
    protected $fillable = ['liga_id', 'pergunta', 'datalimiteresposta', 'qtderespostas', 'ativa', 'consolidada', 'opcaocorreta1_id',
        'pontos1', 'opcaocorreta2_id', 'pontos2', 'opcaocorreta3_id', 'pontos3', 'opcaocorreta4_id', 'pontos4'];

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function opcaoCorreta1()
    {
        return $this->belongsTo(BonusOpcao::class, 'opcaocorreta1_id');
    }

    public function opcaoCorreta2()
    {
        return $this->belongsTo(BonusOpcao::class, 'opcaocorreta2_id');
    }

    public function opcaoCorreta3()
    {
        return $this->belongsTo(BonusOpcao::class, 'opcaocorreta3_id');
    }

    public function opcaoCorreta4()
    {
        return $this->belongsTo(BonusOpcao::class, 'opcaocorreta4_id');
    }

    public function opcoes()
    {
        return $this->hasMany(BonusOpcao::class, 'pergunta_id');
    }

    public function pluckOpcoes()
    {
        $opcoes = $this->opcoes()->orderBy('opcao')->pluck('opcao', 'id');

        $opcoes->prepend('-- ' . strtoupper(__('content.selecione')) . ' --', '');

        return $opcoes;
    }

    public function resposta($jogador_id, $index)
    {
        $item = JogadorBonus::where('pergunta_id', $this->id)
                ->where('jogador_id', $jogador_id)
                ->first();

        if (!$item) {
            return null;
        }

        $key = 'opcao' . $index . '_id';

        return $item->$key;
    }
}
