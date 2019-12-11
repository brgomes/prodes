<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusGrupoResposta extends Model
{
    protected $table 	= 'bonus_gruporesposta';
    protected $fillable = ['liga_id', 'nome', 'arquivado_at', 'arquivado_by', 'created_by', 'updated_by'];

    public function liga()
    {
        return $this->belongsTo(Liga::class);
    }

    public function respostas()
    {
        return $this->hasMany(BonusResposta::class);
    }
}
