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
            'numero' => 'required|numeric',
        ];
    }
}
