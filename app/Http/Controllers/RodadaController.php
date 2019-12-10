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

    public function create($liga_id)
    {
        $liga = $this->liga($liga_id);

        if (!$liga) {
            return response()->json(['message' => 'Liga não encontrada.'], 404);
        }

        return view('rodadas.create', compact('liga'));
    }

    public function store(RodadaValidationRequest $request, $liga_id)
    {
        $data = $request->all();
        $user = auth()->user();

        $liga = $this->liga($liga_id);

        if (!$liga) {
            return redirect()->back();
        }

        $data['liga_id']    = $liga->id;
        $data['datainicio'] = $request->datainicial . ' ' . $request->horainicial . ':00';
        $data['datafim']    = $request->datafinal . ' ' . $request->horafinal . ':00';
        $data['created_by'] = $user->id;
        $data['updated_by'] = $user->id;

        if ($data['datainicio'] < $liga->datainicio . ' 00:00:00') {
            return redirect()->back()->with('warning', __('message.datainicio-rodada-liga'))->withInput();
        }

        if ($data['datafim'] > $liga->datafim . ' 23:59:59') {
            return redirect()->back()->with('warning', __('message.datafim-rodada-liga'))->withInput();
        }

        if ($this->rodada->create($data)) {
            return redirect()->route('ligas.show', $liga->id);
        }

        return redirect()->back()->with('error', __('message.erro'));
    }

    public function edit($id)
    {
        $rodada = $this->rodada($id);

        if (!$rodada) {
            return response()->json(['message' => 'Rodada não encontrada.'], 404);
        }

        return view('rodadas.edit', compact('rodada'));
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

    public function delete($id)
    {
        $rodada = $this->rodada($id);

        if (!$rodada) {
            return response()->json(['message' => 'Rodada não encontrada.'], 404);
        }

        return view('rodadas.delete', compact('rodada'));
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
        $admin  = auth()->user()->adminLiga($rodada->liga_id);

        $jogadores = $rodada->liga->jogadores()
                        ->addSelect(['primeironome' => Usuario::select('primeironome')->whereColumn('usuario.id', 'jogador.usuario_id')])
                        ->addSelect(['sobrenome' => Usuario::select('sobrenome')->whereColumn('usuario.id', 'jogador.usuario_id')])
                        ->orderBy('primeironome')
                        ->orderBy('sobrenome')
                        ->get();

        return view('rodadas.tabela', compact('rodada', 'jogadores', 'admin'));
    }

    /*public function consolidar($rodada_id)
    {
        $rodada = $this->rodada($rodada_id);

        if (!$rodada) {
            return redirect()->back();
        }

        $palpites = $rodada->palpites;

        foreach ($palpites as $palpite) {
            $pontosDisputadosLiga   = 0;
            $pontosGanhosLiga       = 0;
            $rodadasJogadas         = 0;
            $totalPartidasLiga      = 0;

            foreach ($rodadas as $rodada) {
                $pontosDisputadosRodada = 0;
                $pontosGanhosRodada     = 0;
                $totalPartidasRodada    = 0;

                $totalPartidasRodada    += $rodada->partidas->count();
                $totalPartidasLiga      += $totalPartidasRodada;

                $palpites = Palpite::where('rodada_id', $rodada->id)
                            ->where('jogador_id', $jogador->id)
                            ->with('partida')
                            ->get();

                if ($palpites->count() == 0) {
                    $aproveitamentoRodada = null;
                } else {
                    foreach ($palpites as $palpite) {
                        if ($palpite->partida->temresultado) {
                            $pontosDisputadosLiga++;
                            $pontosDisputadosRodada++;

                            if ($palpite->palpite == $palpite->partida->vencedor) {
                                $pontosGanhosLiga++;
                                $pontosGanhosRodada++;

                                $pontuacao = 1;
                            } else {
                                $pontuacao = 0;
                            }

                            $palpite->update(['consolidado' => true, 'pontos' => $pontuacao]);
                        } else {
                            $palpite->update(['consolidado' => false, 'pontos' => null]);
                        }
                    }

                    if ($pontosDisputadosRodada > 0) {
                        $rodadasJogadas++;

                        $aproveitamentoRodada = round((($pontosGanhosRodada * 100) / $pontosDisputadosRodada), 2);
                    }
                }

                if ($pontosDisputadosRodada > 0) {
                    $where = [
                        'liga_id'       => $rodada->liga_id,
                        'rodada_id'     => $rodada->id,
                        'jogador_id'    => $jogador->id,
                    ];

                    $values = [
                        'pontosdisputados'  => $pontosDisputadosRodada,
                        'pontosganhos'      => $pontosGanhosRodada,
                        'aproveitamento'    => $aproveitamentoRodada,
                    ];

                    Classificacao::updateOrCreate($where, $values);
                }
            }

            if ($pontosDisputadosLiga == 0) {
                $aproveitamentoLiga = 0;
            } else {
                $aproveitamentoLiga = round((($pontosGanhosLiga * 100) / $pontosDisputadosLiga), 2);
            }

            $jogador->update([
                'rodadasjogadas'    => $rodadasJogadas,
                'pontosdisputados'  => $pontosDisputadosLiga,
                'pontosganhos'      => $pontosGanhosLiga,
                'aproveitamento'    => $aproveitamentoLiga,
            ]);
        }

        foreach ($rodadas as $rodada) {
            $rodada->rankear();
        }

        // Se a rodada já não tiver partidas abertas, calcula a quantidade de vencedores
        foreach ($jogadores as $jogador) {
            $liderancas = Classificacao::where('liga_id', $liga->id)
                            ->where('jogador_id', $jogador->id)
                            ->where('lider', 1)
                            ->get();

            $jogador->update(['rodadasvencidas' => $liderancas->count()]);
        }

        $liga->rankear();

        $liga->update(['consolidar' => false, 'dataconsolidacao' => Carbon::now()->setTimezone(config('app.timezone'))]);

        return redirect()->route('ligas.show', [$liga->id, $rodada_id]);
    }*/
}
