<?php

namespace App\Http\Controllers;

use App\Models\LigaRodada;
use App\Models\Palpite;
use App\Models\Partida;
use Illuminate\Http\Request;

class PalpiteController extends Controller
{
	protected $palpite;

	public function __construct(Palpite $palpite)
	{
		$this->palpite = $palpite;
	}

	public function rodada($id)
    {
        $rodada = LigaRodada::with('liga')->find($id);

        if (!$rodada) {
            return null;
        }

        return $rodada;
    }

    public function salvar(Request $request, $rodada_id)
    {
    	$data 		= $request->all();
    	$rodada 	= $this->rodada($rodada_id);
    	$usuario 	= auth()->user();

    	if (!$rodada) {
    		return redirect()->back();
    	}

    	if (count($request->partidas) === 0) {
    		return redirect()->back();
    	}

    	foreach ($request->partidas as $partida_id) {
    		$key = 'palpite-' . $partida_id;

    		$partida = Partida::with('rodada')->find($partida_id);

    		if (!$usuario->participaDaLiga($partida->rodada->liga_id)) {
    			continue;
    		}

    		if (!$partida->aberta()) {
    			continue;
    		}

    		if (array_key_exists($key, $data)) {
    			$where = [
	    			'usuario_id' 	=> $usuario->id,
                    'rodada_id'     => $partida->rodada_id,
	    			'partida_id'	=> $partida->id,
	    		];

	    		$values = [
	    			'palpite'		=> $data['palpite-' . $partida->id],
	    			'pontos'		=> null,
	    		];

	    		$this->palpite->updateOrCreate($where, $values);
	    	} else {
	    		$this->palpite->where('usuario_id', $usuario->id)->where('partida_id', $partida->id)->delete();
	    	}
    	}

    	return redirect()->route('ligas.show', [$rodada->liga_id, $rodada->id]);
    }
}
