<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartidaValidationRequest;
use App\Models\LigaClassificacao;
use App\Models\Partida;
use Illuminate\Http\Request;

class PartidaController extends Controller
{
    protected $partida;
    
    public function __construct(Partida $partida)
    {
        $this->partida = $partida;
    }

    public function partida($id)
    {
        $partida = Partida::with('rodada')->find($id);

        if (!$partida) {
            return null;
        }

        if (!auth()->user()->podeAdministrarLiga($partida->rodada->liga_id)) {
            return null;
        }

        return $partida;
    }

    public function store(PartidaValidationRequest $request)
    {
        $data = $request->all();
        $user = auth()->user();

        $data['datapartida']    = $request->data . ' ' . $request->hora . ':00';
        $data['created_by']     = $user->id;
        $data['updated_by']     = $user->id;

        if ($partida = $this->partida->create($data)) {
            return redirect()->route('rodadas.show', $request->rodada_id);
        }

        return redirect()->back()->with('error', __('message.erro'));
    }

    public function update(PartidaValidationRequest $request, $id)
    {
        $partida = $this->partida($id);

        if (!$partida) {
            return redirect()->back();
        }

        $data = $request->all();

        $data['datapartida']    = $request->data . ' ' . $request->hora . ':00';
        $data['updated_by']     = auth()->user()->id;

        if ($partida = $partida->update($data)) {
            return redirect()->route('rodadas.show', $request->rodada_id);
        }

        return redirect()->back()->with('error', __('message.erro'));
    }

    public function destroy($id)
    {
        $partida = $this->partida($id);

        if (!$partida) {
            return redirect()->back();
        }

        $rodada_id = $partida->rodada_id;

        if ($partida->delete()) {
            return redirect()->route('rodadas.show', $rodada_id);
        }

        return redirect()->back()->with('error', __('message.erro'));
    }
}
