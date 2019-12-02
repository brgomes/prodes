<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function rodada($id = null)
    {
        if (isset($id)) {
            return LigaRodada::where('liga_id', $this->id)->find($id);
        }

        $liga = LigaRodada::where('liga_id', $this->id)
                ->where('datainicio', '>=', Carbon::now())
                ->orderBy('datafim')
                ->first();

        if ($liga) {
            return $liga;
        }

        return LigaRodada::where('liga_id', $this->id)
                ->where('datainicio', '<=', Carbon::now())
                ->orderBy('datafim', 'DESC')
                ->first();
    }
}
