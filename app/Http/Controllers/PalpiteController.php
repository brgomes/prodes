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
    	$data      = $request->all();
    	$rodada    = $this->rodada($rodada_id);

    	if (!$rodada) {
    		return redirect()->back();
    	}

        $liga = $rodada->liga;

    	if (count($request->partidas) === 0) {
    		return redirect()->back();
    	}

    	foreach ($request->partidas as $partida_id) {
            $partida = Partida::with('rodada')->find($partida_id);

            if (!$partida->aberta()) {
                continue;
            }

            $jogador = $this->jogador($partida->liga_id);

    		if (!$jogador) {
    			continue;
    		}

            $where = [
                'usuario_id'    => $jogador->usuario_id,
                'jogador_id'    => $jogador->id,
                'rodada_id'     => $partida->rodada_id,
                'partida_id'    => $partida->id,
            ];

            $coringa = false;

            if ($liga->temcoringa) {
                if (array_key_exists('coringa', $data)) {
                    $coringa = ($data['coringa'] == $partida->id);
                }
            }

            if ($liga->tipo == 'P') {
                $golsm = 'palpitem-' . $partida_id;
                $golsv = 'palpitev-' . $partida_id;

                if (array_key_exists($golsm, $data) && array_key_exists($golsv, $data)) {
                    if ((trim($data[$golsm]) == '') && (trim($data[$golsv]) == '')) {
                        $this->palpite->where('jogador_id', $jogador->id)->where('partida_id', $partida->id)->delete();
                    } elseif (((int) $data[$golsm] >= 0) && ((int) $data[$golsv] >= 0)) {
                        $values = [
                            'palpitegolsm'  => $data[$golsm],
                            'palpitegolsv'  => $data[$golsv],
                            'pontos'        => null,
                            'coringa'       => $coringa,
                        ];

                        $this->palpite->updateOrCreate($where, $values);
                    }
                }
            } elseif ($liga->tipo == 'V') {
                $key = 'palpite-' . $partida_id;

    		  if (array_key_exists($key, $data)) {
                    $values = [
                        'palpite'	=> $data[$key],
	    			    'pontos'	=> null,
                        'coringa'   => $coringa,
                    ];

                    $this->palpite->updateOrCreate($where, $values);
                } else {
                    $this->palpite->where('jogador_id', $jogador->id)->where('partida_id', $partida->id)->delete();
                }
            }
        }

    	return redirect()->route('ligas.show', [$rodada->liga_id, $rodada->id]);
    }
}
