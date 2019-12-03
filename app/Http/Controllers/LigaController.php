<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LigaValidationRequest;
use App\Models\Liga;
use App\Models\LigaClassificacao;
use App\Models\LigaRodada;
use App\Models\Palpite;
use App\Models\RodadaClassificacao;
use DB;
use Illuminate\Http\Request;

class LigaController extends Controller
{
    protected $liga;
    protected $ligaClassificacao;

    public function __construct(Liga $liga, LigaClassificacao $ligaClassificacao)
    {
        $this->liga                 = $liga;
        $this->ligaClassificacao    = $ligaClassificacao;
    }

    public function index()
    {
        $ligas = LigaClassificacao::addSelect(['datafim' => Liga::select('datafim')
                    ->where('id', 'liga_classificacao.id')
                ])
                ->with('liga')->orderBy('datafim', 'DESC')->get();

        return view('ligas.index', compact('ligas'));
    }

    public function store(LigaValidationRequest $request)
    {
        $data = $request->all();
        $user = auth()->user();

        $data['codigo']     = mt_rand(100000, 999999);
        $data['created_by'] = $user->id;
        $data['updated_by'] = $user->id;

        DB::beginTransaction();

        if ($liga = $this->liga->create($data)) {
            // Insere o usuário na classificação da liga como admin
            $data = [
                'liga_id'           => $liga->id,
                'usuario_id'        => $user->id,
                'admin'             => 1,
                'rodadasjogadas'    => 0,
            ];

            if ($this->ligaClassificacao->create($data)) {
                DB::commit();

                return redirect()->route('ligas.index');
            }
        }

        DB::rollback();

        return redirect()->back()->with('error', __('message.erro'));
    }

    public function show($id, $rodada_id = null)
    {
        $classificacao = LigaClassificacao::where('usuario_id', auth()->user()->id)
                        ->where('id', $id)
                        ->with('liga')
                        ->first();

        if ($classificacao) {
            $liga = $classificacao->liga;

            if ($rodada_id) {
                $rodada = $liga->rodada($rodada_id);
            } else {
                $rodada = $liga->rodada();
            }

            if ($liga->id != $rodada->liga_id) {
                return redirect()->back();
            }

            return view('ligas.show', compact('classificacao', 'liga', 'rodada', 'rodada_id'));
        }

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $temp = LigaClassificacao::where('usuario_id', auth()->user()->id)
                                ->where('liga_id', $id)
                                ->where('admin', 1)
                                ->with('liga')
                                ->first();

        if ($temp) {
            $liga = $temp->liga;
            $data = $request->all();

            $data['updated_by'] = auth()->user()->id;

            $liga->update($data);
        }

        return redirect()->route('ligas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function consolidar($id)
    {
        $temp = LigaClassificacao::where('usuario_id', auth()->user()->id)
                ->where('liga_id', $id)
                ->where('admin', 1)
                ->with('liga')
                ->first();

        if (!$temp) {
            return redirect()->back();
        }

        $liga       = $temp->liga;
        $usuarios   = $liga->classificacao;
        $rodadas    = $liga->rodadas;

        foreach ($usuarios as $usuario) {
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
                            ->where('usuario_id', $usuario->id)
                            ->with('partida')
                            ->get();

                if ($palpites->count() == 0) {
                    $aproveitamentoRodada = null;
                } else {
                    $rodadasJogadas++;

                    foreach ($palpites as $palpite) {
                        if ($palpite->partida->resultado()) {
                            $pontosDisputadosLiga++;
                            $pontosDisputadosRodada++;

                            if ($palpite->palpite == $palpite->partida->vencedor) {
                                $pontosGanhosLiga++;
                                $pontosGanhosRodada++;

                                $pontuacao = 1;
                            } else {
                                $pontuacao = 0;
                            }

                            $palpite->update(['pontos' => $pontuacao]);
                        }
                    }

                    if ($pontosDisputadosRodada == 0) {
                        $aproveitamentoRodada = 0;
                    } else {
                        $aproveitamentoRodada = round((($pontosGanhosRodada * 100) / $pontosDisputadosRodada), 2);
                    }
                }

                $where = [
                    'rodada_id'     => $rodada->id,
                    'usuario_id'    => $usuario->id,
                ];

                $values = [
                    'pontosdisputados'  => $pontosDisputadosRodada,
                    'pontosganhos'      => $pontosGanhosRodada,
                    'aproveitamento'    => $aproveitamentoRodada,
                ];

                RodadaClassificacao::updateOrCreate($where, $values);
            }

            if ($pontosDisputadosLiga == 0) {
                $aproveitamentoLiga = null;
            } else {
                $aproveitamentoLiga = round((($pontosGanhosLiga * 100) / $pontosDisputadosLiga), 2);
            }

            $usuario->update([
                'rodadasjogadas'    => $rodadasJogadas,
                'pontosdisputados'  => $pontosDisputadosLiga,
                'pontosganhos'      => $pontosGanhosLiga,
                'aproveitamento'    => $aproveitamentoLiga,
            ]);
        }

        echo 'Falta calcular as posições';
    }
}
