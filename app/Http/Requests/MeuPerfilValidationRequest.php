<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeuPerfilValidationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = auth()->user()->id;

        return [
            'primeironome'  => 'required|max:20',
            'sobrenome'     => 'required|max:130',
            'sexo'          => 'required|in:M,F',
            'pais_id'       => 'required|exists:pais,id',
            'timezone'      => 'required',
            'locale'        => 'required|in:pt-BR,en,es',
            'email'         => 'required|email|max:255|unique:usuario,id,{id}',
        ];
    }
}
