<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ApostaController extends Controller
{
    public function index()
    {
    	$partidas = Partida::select('liga_partida.*')
    				->with('rodada')
					->join('liga_rodada as r', 'r.id', '=', 'liga_partida.rodada_id')
					->join('liga_classificacao as c', 'c.liga_id', '=', 'r.liga_id')
					->where('liga_partida.datapartida', '>=', Carbon::now())
					->orderBy('liga_partida.datapartida')
					->orderBy('liga_partida.id')
					->get();

    	return view('apostas.index', compact('partidas'));
    }
}
