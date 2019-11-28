<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LigaValidationRequest;
use App\Models\Liga;
use App\Models\LigaClassificacao;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
