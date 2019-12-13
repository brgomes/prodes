<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerguntaValidationRequest;
use App\Models\BonusPergunta;
use App\Models\Liga;
use App\Models\Jogador;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    protected $pergunta;

    public function __construct(BonusPergunta $pergunta)
    {
        $this->pergunta = $pergunta;
    }

    public function liga($id, $admin = true)
    {
        $liga = Liga::with('perguntas')->find($id);

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

        if ($jogador->admin) {
            $perguntas = $liga->perguntas;
        } else {
            $perguntas = $liga->perguntas->where('ativa', 1);
        }

    	return view('bonus.index', compact('liga', 'jogador', 'perguntas'));
    }

    public function novaPergunta($liga_id)
    {
        return view('bonus.nova-pergunta');
    }

    public function inserirPergunta(PerguntaValidationRequest $request, $liga_id)
    {
        $liga = $this->liga($liga_id);

        if (!$liga) {
            return redirect()->route('ligas.index');
        }

        $jogador = $this->jogador($liga->id);

        if (!$liga) {
            return redirect()->route('ligas.index');
        }

        $data = $request->all();

        $data['liga_id'] = $liga->id;

        if ($this->pergunta->create($data)) {
            return redirect()->route('bonus.index', $liga->id);
        }

        return redirect()->back()->with('error', __('message.erro'));
    }
}
