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

    public function rodada($id)
    {
        $rodada = LigaRodada::with('liga')->find($id);

        if (!$rodada) {
            return null;
        }

        if (!auth()->user()->podeAdministrarLiga($rodada->liga_id)) {
            return null;
        }

        return $rodada;
    }

    public function create()
    {
        //
    }

    public function store(RodadaValidationRequest $request)
    {
        $data = $request->all();
        $user = auth()->user();

        $data['created_by'] = $user->id;
        $data['updated_by'] = $user->id;

        if ($rodada = $this->ligaRodada->create($data)) {
            return redirect()->route('ligas.show', $request->liga_id);
        }

        return redirect()->back()->with('error', __('message.erro'));
    }

    public function show($id)
    {
        $rodada = $this->rodada($id);

        if (!$rodada) {
            return redirect()->back();
        }

        $classificacao = LigaClassificacao::where('usuario_id', auth()->user()->id)
                            ->where('liga_id', $rodada->liga_id)
                            ->first();

        return view('rodadas.show', compact('rodada', 'classificacao'));
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
