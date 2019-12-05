<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartidaValidationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'rodada_id'     => 'required|exists:rodada,id',
            'data'          => 'required|date_format:Y-m-d',
            'hora'          => 'required|date_format:H:i',
            'mandante'      => 'required|max:50',
            'visitante'     => 'required|max:50',
            'sigla'         => 'required|max:7',
        ];
    }
}
