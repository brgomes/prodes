<?php

namespace App\Http\Controllers;

use App\Models\Jogador;
use App\Models\Palpite;
use App\Models\Partida;
use App\Models\Rodada;
use Illuminate\Http\Request;

class PalpiteController extends Controller
{
	protected $palpite;
    protected $jogador;

	public function __construct(Palpite $palpite, Jogador $jogador)
	{
		$this->palpite = $palpite;
        $this->jogador = $jogador;
	}

	public function rodada($id)
    {
        $rodada = Rodada::with('liga')->find($id);

        if (!$rodada) {
            return null;
        }

        return $rodada;
    }

    public function jogador($liga_id)
    {
        return Jogador::where('liga_id', $liga_id)
                ->where('usuario_id', auth()->user()->id)
                ->first();
    }

    public function salvar(Request $request, $rodada_id)
    {
    	$data 		= $request->all();
    	$rodada 	= $this->rodada($rodada_id);

    	if (!$rodada) {
    		return redirect()->back();
    	}

    	if (count($request->partidas) === 0) {
    		return redirect()->back();
    	}

    	foreach ($request->partidas as $partida_id) {
            $key        = 'palpite-' . $partida_id;
            $partida    = Partida::with('rodada')->find($partida_id);
            $jogador    = $this->jogador($partida->liga_id);

    		if (!$jogador) {
    			continue;
    		}

    		if (!$partida->aberta()) {
    			continue;
    		}

    		if (array_key_exists($key, $data)) {
    			$where = [
	    			'usuario_id' 	=> $jogador->usuario_id,
                    'jogador_id'    => $jogador->id,
                    'rodada_id'     => $partida->rodada_id,
	    			'partida_id'	=> $partida->id,
	    		];

	    		$values = [
	    			'palpite'		=> $data['palpite-' . $partida->id],
	    			'pontos'		=> null,
	    		];

	    		$this->palpite->updateOrCreate($where, $values);
	    	} else {
	    		$this->palpite->where('jogador_id', $jogador->id)->where('partida_id', $partida->id)->delete();
	    	}
    	}

    	return redirect()->route('ligas.show', [$rodada->liga_id, $rodada->id]);
    }
}
