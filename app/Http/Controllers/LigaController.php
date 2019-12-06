<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LigaValidationRequest;
use App\Models\Classificacao;
use App\Models\Jogador;
use App\Models\Liga;
use App\Models\Palpite;
use App\Models\Rodada;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class LigaController extends Controller
{
    protected $liga;
    protected $jogador;

    public function __construct(Liga $liga, Jogador $jogador)
    {
        $this->liga     = $liga;
        $this->jogador  = $jogador;
    }

    public function index()
    {
        $ligas = Jogador::addSelect(['datafim' => Liga::select('datafim')->whereColumn('id', 'jogador.liga_id')])
                ->where('usuario_id', auth()->user()->id)
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
                'created_at'        => Carbon::now()->setTimezone(config('app.timezone')),
            ];

            if ($this->jogador->create($data)) {
                DB::commit();

                return redirect()->route('ligas.index');
            }
        }

        DB::rollback();

        return redirect()->back()->with('error', __('message.erro'));
    }

    public function show($id, $rodada_id = null)
    {
        $jogador = Jogador::where('usuario_id', auth()->user()->id)
                    ->where('liga_id', $id)
                    ->with('liga')
                    ->first();

        if ($jogador) {
            $liga = $jogador->liga;

            if (isset($rodada_id)) {
                $rodada = $liga->rodada($rodada_id);
            } else {
                $rodada = $liga->rodada();
            }

            if (isset($rodada)) {
                if ($liga->id != $rodada->liga_id) {
                    return redirect()->back();
                }
            }

            $classificacao = $liga->jogadores()->orderBy('posicao')->get();

            return view('ligas.show', compact('jogador', 'liga', 'rodada', 'rodada_id', 'classificacao'));
        }

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $temp = Jogador::where('usuario_id', auth()->user()->id)
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

    public function destroy($id)
    {
        $temp = Jogador::where('usuario_id', auth()->user()->id)
                ->where('liga_id', $id)
                ->where('admin', 1)
                ->with('liga')
                ->first();

        if (!$temp) {
            return redirect()->back();
        }

        $liga       = $temp->liga;
        $rodadas    = $liga->rodadas;
        
        if ($rodadas->count() > 0) {
            foreach ($rodadas as $rodada) {
                $rodada->palpites()->delete();
                $rodada->partidas()->delete();
                $rodada->classificacao()->delete();
                $rodada->delete();
            }
        }

        $liga->jogadores()->delete();
        $liga->delete();

        return redirect()->route('ligas.index')->with('success', __('message.liga-excluida'));
    }

    public function consolidar($liga_id, $rodada_id)
    {
        $temp = Jogador::where('usuario_id', auth()->user()->id)
                ->where('liga_id', $liga_id)
                ->where('admin', 1)
                ->with('liga')
                ->first();

        if (!$temp) {
            return redirect()->back();
        }

        $liga       = $temp->liga;
        $jogadores  = $liga->jogadores;
        $rodadas    = $liga->rodadas;

        foreach ($jogadores as $jogador) {
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
    }

    public function setarAdmin($liga_id, $usuario_id)
    {
        $temp = Jogador::where('usuario_id', auth()->user()->id)
                ->where('liga_id', $liga_id)
                ->where('admin', 1)
                ->with('liga')
                ->first();

        if (!$temp) {
            return redirect()->back();
        }

        $jogador = Jogador::where('usuario_id', $usuario_id)
                    ->where('liga_id', $liga_id)
                    ->where('admin', 0)
                    ->first();

        if ($jogador) {
            $jogador->update(['admin' => 1]);
        }

        return redirect()->back()->with('success', __('message.admin-setado'));
    }

    public function removerAdmin($liga_id, $usuario_id)
    {
        $user = auth()->user();
        $temp = Jogador::where('usuario_id', $user->id)
                ->where('liga_id', $liga_id)
                ->where('admin', 1)
                ->with('liga')
                ->first();

        if (!$temp) {
            return redirect()->back();
        }

        $outrosAdmin = [];

        foreach ($temp->liga->administradores as $admin) {
            if ($admin->usuario_id != $user->id) {
                $outrosAdmin[] = $admin;
            }
        }

        if (count($outrosAdmin) == 0) {
            return redirect()->back()->with('warning', __('message.nao-pode-excluir-admin'));
        }

        $jogador = Jogador::where('usuario_id', $usuario_id)
                    ->where('liga_id', $liga_id)
                    ->where('admin', 1)
                    ->first();

        if ($jogador) {
            $jogador->update(['admin' => 0]);
        }

        return redirect()->back()->with('success', __('message.admin-removido'));
    }

    public function pesquisar(Request $request)
    {
        $liga = $this->liga->where('codigo', (int) $request->q)->first();

        if ($liga) {
            $jogador = $this->jogador->where('liga_id', $liga->id)->where('usuario_id', auth()->user()->id)->first();
        } else {
            $jogador = null;
        }
        
        return view('ligas.pesquisa', compact('liga', 'jogador'));
    }

    public function entrar($liga_id)
    {
        $liga = $this->liga->find($liga_id);

        if (!$liga) {
            return redirect()->back();
        }

        $user       = auth()->user();
        $jogador    = $this->jogador->where('liga_id', $liga_id)->where('usuario_id', $user->id)->first();

        if ($jogador) {
            return redirect()->route('ligas.show', $liga->id);
        }

        $data = [
            'liga_id'           => $liga->id,
            'usuario_id'        => $user->id,
            'admin'             => false,
            'rodadasjogadas'    => 0,
            'rodadasvencidas'   => 0,
            'pontosdisputados'  => 0,
            'pontosganhos'      => 0,
            'created_at'        => Carbon::now()->setTimezone(config('app.timezone')),
        ];

        try {
            $this->jogador->create($data);

            return redirect()->route('ligas.show', $liga->id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('message.acao-nao-pode-ser-executada'));
        }
    }
}
