<?php

namespace App\Http\Controllers;

use App\Http\Requests\OpcaoValidationRequest;
use App\Http\Requests\PerguntaValidationRequest;
use App\Models\BonusOpcao;
use App\Models\BonusPergunta;
use App\Models\Liga;
use App\Models\Jogador;
use App\Models\JogadorBonus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    protected $pergunta;
    protected $opcao;

    public function __construct(BonusPergunta $pergunta, BonusOpcao $opcao)
    {
        $this->pergunta = $pergunta;
        $this->opcao    = $opcao;
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
    	$liga = $this->liga($liga_id, false);

    	if (!$liga) return redirect()->route('ligas.index');

    	$jogador   = $this->jogador($liga->id);
        $hoje      = Carbon::now()->setTimezone(config('app.timezone'))->format('Y-m-d');

        if ($jogador->admin) {
            $perguntas = $liga->perguntas;
        } else {
            $perguntas = $liga->perguntas->where('ativa', 1);
        }

    	return view('bonus.index', compact('liga', 'jogador', 'perguntas', 'hoje'));
    }







    public function storePergunta(PerguntaValidationRequest $request, $liga_id)
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

    public function editPergunta($pergunta_id)
    {
        $pergunta = $this->pergunta->find($pergunta_id);

        if (!$pergunta) return redirect()->route('ligas.index');

        $liga = $this->liga($pergunta->liga_id);

        if (!$liga) return redirect()->route('ligas.index');

        return view('bonus.edit-pergunta', compact('pergunta'));
    }

    public function updatePergunta(PerguntaValidationRequest $request)
    {
        $pergunta = $this->pergunta->find($request->pergunta_id);

        if (!$pergunta) return redirect()->route('ligas.index');

        $liga = $this->liga($pergunta->liga_id);

        if (!$liga) return redirect()->route('ligas.index');

        $data = $request->all();

        $data['consolidada'] = false;

        if ($pergunta->update($data)) {
            $pergunta->liga->update(['consolidar' => true]);
        }

        return redirect()->route('bonus.index', $liga->id);
    }

    public function deleteOpcoes($pergunta_id)
    {
        $pergunta = $this->pergunta->find($pergunta_id);

        if (!$pergunta) return redirect()->route('ligas.index');

        $liga = $this->liga($pergunta->liga_id);

        if (!$liga) return redirect()->route('ligas.index');

        return view('bonus.delete-opcoes', compact('pergunta'));
    }

    public function destroyOpcoes(Request $request)
    {
        $pergunta = $this->pergunta->find($request->pergunta_id);

        if (!$pergunta) return redirect()->route('ligas.index');

        $liga = $this->liga($pergunta->liga_id);

        if (!$liga) return redirect()->route('ligas.index');

        if (isset($request->opcoes)) {
            $opcoes = $request->opcoes;

            if (count($opcoes) > 0) {
                foreach ($opcoes as $opcao_id) {
                    $opcao = $this->opcao->find($opcao_id);

                    if ($opcao->pergunta_id == $pergunta->id) {
                        $opcao->delete();
                    }
                }
            }
        }

        return redirect()->route('bonus.index', $liga->id);
    }








    public function createOpcao($pergunta_id)
    {
        $pergunta = $this->pergunta->find($pergunta_id);

        if (!$pergunta) return redirect()->route('ligas.index');

        $liga = $this->liga($pergunta->liga_id);

        if (!$liga) return redirect()->route('ligas.index');

        return view('bonus.create-opcao', compact('pergunta'));
    }

    public function storeOpcao(OpcaoValidationRequest $request)
    {
        $pergunta = $this->pergunta->find($request->pergunta_id);

        if (!$pergunta) return redirect()->route('ligas.index');

        $liga = $this->liga($pergunta->liga_id);

        if (!$liga) return redirect()->route('ligas.index');

        $this->opcao->create($request->all());

        return redirect()->route('bonus.index', $liga->id);
    }







    public function salvarRespostas(Request $request)
    {
        $liga = $this->liga($request->liga_id, false);

        if (!$liga) return redirect()->route('ligas.index');

        $jogador = $this->jogador($liga->id);

        if (!$jogador) return redirect()->route('ligas.index');

        $perguntas = $request->perguntas;

        if (count($perguntas) > 0) {
            $data = $request->all();

            foreach ($perguntas as $pergunta_id) {
                $pergunta = $this->pergunta->find($pergunta_id);

                if (!$pergunta) continue;

                if ($pergunta->liga_id != $liga->id) continue;

                $where = [
                    'liga_id'       => $liga->id,
                    'jogador_id'    => $jogador->id,
                    'pergunta_id'   => $pergunta->id,
                ];

                $values = [];

                for ($i=1; $i<=4; $i++) {
                    $key = 'resposta' . $i . '_' . $pergunta->id;

                    if (array_key_exists($key, $data)) {
                        $values['opcao' . $i . '_id'] = $data[$key];
                    } else {
                        $values['opcao' . $i . '_id'] = null;
                    }
                }

                $values['pontosganhos'] = 0;
                $values['consolidado']  = false;

                JogadorBonus::updateOrCreate($where, $values);
            }
        }

        return redirect()->route('bonus.index', $liga->id);
    }
}
