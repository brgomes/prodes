<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RodadaValidationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'numero'        => 'required|numeric',
            'datainicial'   => 'required|date_format:Y-m-d',
            'horainicial'   => 'required|date_format:H:i',
            'datafinal'     => 'required|date_format:Y-m-d|after_or_equal:datainicial',
            'horafinal'     => 'required|date_format:H:i',
        ];
    }
}
