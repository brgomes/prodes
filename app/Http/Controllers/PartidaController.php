<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartidaValidationRequest;
use App\Models\LigaClassificacao;
use App\Models\LigaRodada;
use App\Models\Partida;
use Illuminate\Http\Request;

class PartidaController extends Controller
{
    protected $partida;
    
    public function __construct(Partida $partida)
    {
        $this->partida = $partida;
    }

    public function rodada($id)
    {
        $rodada = LigaRodada::find($id);

        if (!$rodada) {
            return null;
        }

        if (!auth()->user()->podeAdministrarLiga($rodada->liga_id)) {
            return null;
        }

        return $rodada;
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

        $rodada = $this->rodada($request->rodada_id);

        if (!$rodada) {
            return redirect()->back();
        }

        if ($data['datapartida'] < $rodada->datainicio) {
            return redirect()->back()->with('warning', __('message.datainicio-partida-rodada'))->withInput();
        }

        if ($data['datapartida'] > $rodada->datafim) {
            return redirect()->back()->with('warning', __('message.datafim-partida-rodada'))->withInput();
        }

        if ($partida = $this->partida->create($data)) {
            return redirect()->route('ligas.show', [$partida->rodada->liga_id, $request->rodada_id]);
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

        $rodada = $partida->rodada;

        if (!$rodada) {
            return redirect()->back();
        }

        if ($data['datapartida'] < $rodada->datainicio) {
            return redirect()->back()->with('warning', __('message.datainicio-partida-rodada'))->withInput();
        }

        if ($data['datapartida'] > $rodada->datafim) {
            return redirect()->back()->with('warning', __('message.datafim-partida-rodada'))->withInput();
        }

        if ($partida->update($data)) {
            return redirect()->route('ligas.show', [$partida->rodada->liga_id, $partida->rodada_id]);
        }

        return redirect()->back()->with('error', __('message.erro'));
    }

    public function destroy($id)
    {
        $partida = $this->partida($id);

        if (!$partida) {
            return redirect()->back();
        }

        $liga_id    = $partida->rodada->liga_id;
        $rodada_id  = $partida->rodada_id;

        if ($partida->delete()) {
            return redirect()->route('ligas.show', [$liga_id, $rodada_id]);
        }

        return redirect()->back()->with('error', __('message.erro'));
    }
}
