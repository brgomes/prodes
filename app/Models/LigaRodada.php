<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigaRodada extends Model
{
    protected $table 	= 'liga_rodada';
    protected $fillable = ['liga_id', 'numero', 'datainicio', 'datafim', 'dataconsolidacao', 'created_by', 'updated_by'];

    public function liga()
    {
    	return $this->belongsTo(Liga::class);
    }

    public function partidas()
    {
        return $this->hasMany(Partida::class, 'rodada_id')->with('palpite')->orderBy('datapartida');
    }

    public function classificacao()
    {
        return $this->hasMany(RodadaClassificacao::class, 'rodada_id')->orderBy('posicao');
    }

    public function getDatainicialAttribute()
    {
        return datetime($this->datainicio, 'Y-m-d');
    }

    public function getHorainicialAttribute()
    {
        return datetime($this->datainicio, 'H:i');
    }

    public function getDatafinalAttribute()
    {
        return datetime($this->datafim, 'Y-m-d');
    }

    public function getHorafinalAttribute()
    {
        return datetime($this->datafim, 'H:i');
    }

    public function rankear()
    {
        $itens = RodadaClassificacao::where('rodada_id', $this->id)
                    ->orderBy('pontosganhos', 'DESC')
                    ->orderBy('aproveitamento', 'DESC')
                    ->get();

        if ($itens->count() == 0) {
            return true;
        }

        $i              = 1;
        $pontosLider    = $itens[0]->pontosganhos;

        foreach ($itens as $item) {
            $lider = ($item->pontosganhos == $pontosLider);

            $item->update(['posicao' => $i, 'lider' => $lider]);

            $i++;
        }

        return true;
    }
}
