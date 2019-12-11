<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusResposta extends Model
{
    public $timestamps = false;

    protected $table 	= 'bonus_resposta';
    protected $fillable = ['gruporesposta_id', 'resposta'];

    public function grupoResposta()
    {
        return $this->belongsTo(GrupoResposta::class);
    }
}
