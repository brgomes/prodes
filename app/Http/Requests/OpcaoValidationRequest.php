<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpcaoValidationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pergunta_id'   => 'required|exists:bonus_pergunta,id',
            'opcao'         => 'required|max:150',
        ];
    }
}
