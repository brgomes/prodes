<?php

namespace App\Http\Controllers;

use App\Http\Requests\RodadaValidationRequest;
use App\Models\Jogador;
use App\Models\Liga;
use App\Models\Rodada;
use App\Models\Usuario;
use Illuminate\Http\Request;

class RodadaController extends Controller
{
    protected $rodada;

    public function __construct(Rodada $rodada)
    {
        $this->rodada = $rodada;
    }

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

    public function rodada($id, $admin = true)
    {
        $rodada = Rodada::with(['liga', 'partidas'])->find($id);

        if (!$rodada) {
            return null;
        }

        if ($admin) {
            if (!auth()->user()->adminLiga($rodada->liga_id)) {
                return null;
            }
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

        if ($this->rodada->create($data)) {
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
        $rodada = $this->rodada($id);

        if (!$rodada) {
            return redirect()->back();
        }

        $liga = $rodada->liga;

        $rodada->palpites()->delete();
        $rodada->partidas()->delete();
        $rodada->classificacao()->delete();
        $rodada->delete();

        $liga->update(['consolidar' => true]);

        return redirect()->route('ligas.show', $liga->id)->with('success', __('message.rodada-excluida'));
    }

    public function tabela($id)
    {
        $rodada = $this->rodada($id, false);

        $jogadores = $rodada->liga->jogadores()
                        ->addSelect(['primeironome' => Usuario::select('primeironome')->whereColumn('usuario.id', 'jogador.usuario_id')])
                        ->addSelect(['sobrenome' => Usuario::select('sobrenome')->whereColumn('usuario.id', 'jogador.usuario_id')])
                        ->orderBy('primeironome')
                        ->orderBy('sobrenome')
                        ->get();

        return view('rodadas.tabela', compact('rodada', 'jogadores'));
    }
}
