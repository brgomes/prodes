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
        $data['temcoringa'] = array_key_exists('temcoringa', $data);
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

            $habilita_coringa = $liga->temcoringa;

            if (isset($rodada)) {
                if ($liga->id != $rodada->liga_id) {
                    return redirect()->back();
                }

                $palpites = $rodada->palpites->where('jogador_id', $jogador->id);

                if ($habilita_coringa) {
                    if ($palpites->count() > 0) {
                        foreach ($palpites as $palpite) {
                            if ($palpite->coringa) {
                                if (!$palpite->partida->aberta()) {
                                    $habilita_coringa = false;
                                    break;
                                }
                            }
                        }
                    }
                }
            }

            $classificacao = $liga->jogadores()->orderBy(DB::raw('ISNULL(posicao), posicao'), 'ASC')->get();

            return view('ligas.show', compact('jogador', 'liga', 'rodada', 'rodada_id', 'classificacao', 'habilita_coringa'));
        }

        return redirect()->back();
    }

    public function edit($id)
    {
        $liga = $this->liga($id);

        if (!$liga) {
            return response()->json(['message' => 'Liga não encontrada.'], 404);
        }

        return view('ligas.edit', compact('liga'));
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

            $data['temcoringa'] = array_key_exists('temcoringa', $data);
            $data['updated_by'] = auth()->user()->id;

            $liga->update($data);

            return redirect()->route('ligas.show', $liga->id);
        }

        return redirect()->route('ligas.index');
    }

    public function delete($id)
    {
        $liga = $this->liga($id);

        if (!$liga) {
            return response()->json(['message' => 'Liga não encontrada.'], 404);
        }

        return view('ligas.delete', compact('liga'));
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

        $liga = $temp->liga;

        $liga->consolidar();

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

        // Verificar se há perguntas a serem respondidas

        $data = [
            'liga_id'           => $liga->id,
            'usuario_id'        => $user->id,
            'admin'             => false,
            'rodadasjogadas'    => 0,
            'rodadasvencidas'   => 0,
            'pontosdisputados'  => 0,
            'pontosganhos'      => 0,
            'bonusdisputados'   => 0,
            'bonusganhos'       => 0,
            'totalpontos'       => 0,
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
