<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Liga extends Model
{
    protected $table 	= 'liga';
    protected $fillable = ['nome', 'codigo', 'datainicio', 'datafim', 'regulamento', 'consolidar', 'dataconsolidacao', 'created_by', 'updated_by'];

    public function rodadas()
    {
        return $this->hasMany(LigaRodada::class)->with(['partidas', 'classificacao']);
    }

    public function classificacao()
    {
        return $this->hasMany(LigaClassificacao::class)->orderBy('posicao');
    }

    public function administradores()
    {
        return $this->hasMany(LigaClassificacao::class)->where('admin', true);
    }

    public function rodada($id = null)
    {
        if (isset($id)) {
            return LigaRodada::with(['partidas', 'classificacao'])->where('liga_id', $this->id)->find($id);
        }

        $liga = LigaRodada::where('liga_id', $this->id)
                ->where('datainicio', '>=', Carbon::now())
                ->orderBy('datafim')
                ->with(['partidas', 'classificacao'])
                ->first();

        if ($liga) {
            return $liga;
        }

        return LigaRodada::where('liga_id', $this->id)
                ->where('datainicio', '<=', Carbon::now())
                ->orderBy('datafim', 'DESC')
                ->with(['partidas', 'classificacao'])
                ->first();
    }

    public function rankear()
    {
        $itens = LigaClassificacao::where('liga_id', $this->id)
                    ->orderBy('pontosganhos', 'DESC')
                    ->orderBy('rodadasvencidas', 'DESC')
                    ->orderBy('aproveitamento', 'DESC')
                    ->get();

        $i = 1;

        foreach ($itens as $item) {
            $item->update(['posicao' => $i]);

            $i++;
        }

        return true;
    }
}
