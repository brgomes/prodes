<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LigaValidationRequest;
use App\Models\Liga;
use App\Models\LigaClassificacao;
use App\Models\LigaRodada;
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
                $rodada = rodadas($id, $rodada_id);
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
}
