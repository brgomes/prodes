<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PerguntaValidationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pergunta'  => 'required|max:200',
        ];
    }
}
