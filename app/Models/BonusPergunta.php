<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusOpcao extends Model
{
    protected $table 	= 'bonus_pergunta';
    protected $fillable = ['liga_id', 'ativa', 'datalimiteresposta', 'pergunta', 'qtderespostas', 'opcaocorreta1_id',
        'pontos1', 'opcaocorreta2_id', 'pontos2', 'opcaocorreta3_id', 'pontos3', 'opcaocorreta4_id', 'pontos4'];

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function opcaoCorreta1()
    {
        return $this->belongsTo(GrupoOpcao::class, 'opcaocorreta1_id');
    }

    public function opcaoCorreta2()
    {
        return $this->belongsTo(GrupoOpcao::class, 'opcaocorreta2_id');
    }

    public function opcaoCorreta3()
    {
        return $this->belongsTo(GrupoOpcao::class, 'opcaocorreta3_id');
    }

    public function opcaoCorreta4()
    {
        return $this->belongsTo(GrupoOpcao::class, 'opcaocorreta4_id');
    }
}
