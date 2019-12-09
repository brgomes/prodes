<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LigaValidationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome'                  => 'required|max:50',
            'datainicio'            => 'required|date',
            'datafim'               => 'required|date|after:datainicio',
            'datalimiteentrada'     => 'date|after:datainicio|before:datafim',
            'pontosacertoplacar'    => 'required|number',
            'pontosacertovencedor'  => 'required|number',
        ];
    }
}
