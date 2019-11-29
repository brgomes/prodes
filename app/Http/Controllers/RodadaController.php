<?php

namespace App\Http\Controllers;

use App\Http\Requests\RodadaValidationRequest;
use App\Models\LigaRodada;
use Illuminate\Http\Request;

class RodadaController extends Controller
{
    protected $ligaRodada;

    public function __construct(LigaRodada $ligaRodada)
    {
        $this->ligaRodada = $ligaRodada;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
