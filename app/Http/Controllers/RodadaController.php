<?php

namespace App\Http\Controllers;

use App\Http\Requests\RodadaValidationRequest;
use App\Models\Liga;
use App\Models\LigaClassificacao;
use App\Models\LigaRodada;
use Illuminate\Http\Request;

class RodadaController extends Controller
{
    protected $ligaRodada;

    public function __construct(LigaRodada $ligaRodada)
    {
        $this->ligaRodada = $ligaRodada;
    }

    public function liga($id)
    {
        $liga = Liga::find($id);

        if (!$liga) {
            return null;
        }

        if (!auth()->user()->adminLiga($liga->id)) {
            return null;
        }

        return $liga;
    }

    public function rodada($id)
    {
        $rodada = LigaRodada::with(['liga', 'partidas'])->find($id);

        if (!$rodada) {
            return null;
        }

        if (!auth()->user()->adminLiga($rodada->liga_id)) {
            return null;
        }

        return $rodada;
    }

    public function store(RodadaValidationRequest $request, $liga)
    {
        $data = $request->all();
        $user = auth()->user();

        $data['datainicio'] = $request->datainicial . ' ' . $request->horainicial . ':00';
        $data['datafim']    = $request->datafinal . ' ' . $request->horafinal . ':00';
        $data['created_by'] = $user->id;
        $data['updated_by'] = $user->id;

        $liga = $this->liga($liga);

        if (!$liga) {
            return redirect()->back();
        }

        if ($data['datainicio'] < $liga->datainicio . ' 00:00:00') {
            return redirect()->back()->with('warning', __('message.datainicio-rodada-liga'))->withInput();
        }

        if ($data['datafim'] > $liga->datafim . ' 23:59:59') {
            return redirect()->back()->with('warning', __('message.datafim-rodada-liga'))->withInput();
        }

        if ($rodada = $this->ligaRodada->create($data)) {
            return redirect()->route('ligas.show', $request->liga_id);
        }

        return redirect()->back()->with('error', __('message.erro'));
    }

    public function update(RodadaValidationRequest $request, $id)
    {
        $rodada = $this->rodada($id);

        if (!$rodada) {
            return redirect()->back();
        }

        $data = $request->all();

        $data['datainicio'] = $request->datainicial . ' ' . $request->horainicial . ':00';
        $data['datafim']    = $request->datafinal . ' ' . $request->horafinal . ':00';
        $data['updated_by'] = auth()->user()->id;

        if ($data['datainicio'] < $rodada->liga->datainicio . ' 00:00:00') {
            return redirect()->back()->with('warning', __('message.datainicio-rodada-liga'))->withInput();
        }

        if ($data['datafim'] > $rodada->liga->datafim . ' 23:59:59') {
            return redirect()->back()->with('warning', __('message.datafim-rodada-liga'))->withInput();
        }

        if ($rodada->update($data)) {
            return redirect()->route('ligas.show', [$rodada->liga_id, $id]);
        }

        return redirect()->back()->with('error', __('message.erro'));
    }

    public function destroy($id)
    {
        //
    }

    /*public function consolidar($rodada_id)
    {
        $rodada = $this->rodada($rodada_id);

        if (!$rodada) {
            return redirect()->back();
        }

        // Pesquisa todos os usuários que participam da liga
        $usuarios = $rodada->liga->classificacao;
        
        if ($usuarios->count() > 0) {
            $rodadas
            foreach ($usuarios as $usuario) {

            }
        }

        // Para cada usuário pesquisa todas as apostas dele ao longo do tempo, ordenado por rodada

        // 
    }*/
}
