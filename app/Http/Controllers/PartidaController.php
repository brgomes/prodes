<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartidaValidationRequest;
use App\Models\Partida;
use Illuminate\Http\Request;

class PartidaController extends Controller
{
    protected $partida;
    
    public function __construct(Partida $partida)
    {
        $this->partida = $partida;
    }

    public function store(PartidaValidationRequest $request)
    {
        $data = $request->all();
        $user = auth()->user();

        $data['datapartida']    = $request->data . ' ' . $request->hora . ':00';
        $data['created_by']     = $user->id;
        $data['updated_by']     = $user->id;

        if ($partida = $this->partida->create($data)) {
            return redirect()->route('rodadas.show', $request->rodada_id);
        }

        return redirect()->back()->with('error', __('message.erro'));
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
