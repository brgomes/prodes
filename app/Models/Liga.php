<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liga extends Model
{
    protected $table 	= 'liga';
    protected $fillable = ['nome', 'codigo', 'datainicio', 'datafim', 'regulamento', 'dataconsolidacao', 'created_by', 'updated_by'];

    public function rodadas()
    {
        return $this->hasMany(LigaRodada::class)->with('partidas')->orderBy('numero', 'DESC');
    }

    public function classificacao()
    {
        return $this->hasMany(LigaClassificacao::class)->orderBy('posicao');
    }
}
