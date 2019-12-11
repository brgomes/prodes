<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusPergunta extends Model
{
    protected $table 	= 'bonus_pergunta';
    protected $fillable = ['liga_id', 'gruporesposta_id', 'datalimiteresposta', 'pergunta', 'respostacerta_id', 'pontuacao'];

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function grupoResposta()
    {
        return $this->belongsTo(GrupoResposta::class);
    }

    public function respostaCerta()
    {
        return $this->belongsTo(BonusResposta::class);
    }
}
