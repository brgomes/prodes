<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusResposta extends Model
{
    public $timestamps = false;

    protected $table 	= 'bonus_opcao';
    protected $fillable = ['pergunta_id', 'opcao'];

    public function pergunta()
    {
        return $this->belongsTo(GrupoPergunta::class);
    }
}
