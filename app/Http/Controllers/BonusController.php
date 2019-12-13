<?php

namespace App\Http\Controllers;

use App\Models\Liga;
use App\Models\Jogador;
use Illuminate\Http\Request;

class BonusController extends Controller
{
	public function liga($id, $admin = true)
    {
        $liga = Liga::find($id);

        if (!$liga) {
            return null;
        }

        if ($admin) {
            if (!auth()->user()->adminLiga($liga->id)) {
                return null;
            }
        }

        return $liga;
    }

    public function jogador($liga_id)
    {
        return Jogador::where('liga_id', $liga_id)
                ->where('usuario_id', auth()->user()->id)
                ->first();
    }

    public function index($liga_id)
    {
    	$liga = $this->liga($liga_id);

    	if (!$liga) {
    		return redirect()->route('ligas.index');
    	}

    	$jogador = $this->jogador($liga->id);

        

    	return view('bonus.index', compact('liga', 'jogador'));
    }
}
