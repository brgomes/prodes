<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Rodada extends Model
{
    protected $table 	= 'rodada';
    protected $fillable = ['liga_id', 'numero', 'datainicio', 'datafim', 'consolidar', 'dataconsolidacao', 'created_by', 'updated_by'];

    public function liga()
    {
    	return $this->belongsTo(Liga::class)->with('jogadores');
    }

    public function partidas()
    {
        return $this->hasMany(Partida::class, 'rodada_id')->with('palpite')->orderBy('datapartida');
    }

    public function classificacao()
    {
        return $this->hasMany(Classificacao::class, 'rodada_id')->orderBy(DB::raw('ISNULL(posicao), posicao'), 'ASC');
    }

    public function palpites()
    {
        return $this->hasMany(Palpite::class, 'rodada_id')->with('jogador');
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
        $itens = Classificacao::addSelect(['primeironome' => Usuario::select('primeironome')->whereColumn('usuario.id', 'jogador.usuario_id')])
                    ->join('jogador', 'jogador.id', '=', 'classificacao.jogador_id')
                    ->where('rodada_id', $this->id)
                    ->orderBy('pontosganhos', 'DESC')
                    ->orderBy('aproveitamento', 'DESC')
                    ->orderBy('primeironome')
                    ->get();

        if ($itens->count() == 0) {
            return true;
        }

        $i                      = 1;
        $pontosLider            = $itens[0]->pontosganhos;
        $qtdePartidasAbertas    = Partida::where('rodada_id', $this->id)->where('temresultado', false)->get()->count();

        foreach ($itens as $item) {
            if ($qtdePartidasAbertas == 0) {
                $lider = ($item->pontosganhos == $pontosLider);

                $item->update(['posicao' => $i, 'lider' => $lider]);
            } else {
                $item->update(['posicao' => $i, 'lider' => false]);
            }

            $i++;
        }

        return true;
    }
}
