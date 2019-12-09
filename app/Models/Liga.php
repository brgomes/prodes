<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Liga extends Model
{
    protected $table 	= 'liga';
    protected $fillable = ['nome', 'codigo', 'tipo', 'datainicio', 'datafim', 'regulamento', 'datalimiteentrada',
        'pontosacertoplacar', 'pontosacertovencedor', 'temcoringa', 'consolidar', 'dataconsolidacao', 'created_by', 'updated_by'];

    public function rodadas()
    {
        return $this->hasMany(Rodada::class)->with(['partidas', 'classificacao']);
    }

    public function jogadores()
    {
        return $this->hasMany(Jogador::class);
    }

    public function administradores()
    {
        return $this->hasMany(Jogador::class)->where('admin', true);
    }

    public function rodada($id = null)
    {
        if (isset($id)) {
            return Rodada::with(['partidas', 'classificacao'])->where('liga_id', $this->id)->find($id);
        }

        $liga = Rodada::where('liga_id', $this->id)
                ->where('datainicio', '>=', Carbon::now()->setTimezone(config('app.timezone')))
                ->orderBy('datafim')
                ->with(['partidas', 'classificacao'])
                ->first();

        if ($liga) {
            return $liga;
        }

        return Rodada::where('liga_id', $this->id)
                ->where('datainicio', '<=', Carbon::now()->setTimezone(config('app.timezone')))
                ->orderBy('datafim', 'DESC')
                ->with(['partidas', 'classificacao'])
                ->first();
    }

    public function rankear()
    {
        $itens = Jogador::addSelect(['primeironome' => Usuario::select('primeironome')->whereColumn('usuario.id', 'jogador.usuario_id')])
                    ->where('liga_id', $this->id)
                    ->orderBy('pontosganhos', 'DESC')
                    ->orderBy('rodadasvencidas', 'DESC')
                    ->orderBy('aproveitamento', 'DESC')
                    ->orderBy('primeironome')
                    ->get();

        $i = 1;

        foreach ($itens as $item) {
            $item->update(['posicao' => $i]);

            $i++;
        }

        return true;
    }
}
