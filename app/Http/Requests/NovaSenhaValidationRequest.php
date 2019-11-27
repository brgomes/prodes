<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckUserPassword;

class NovaSenhaValidationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'senhaatual' => ['required', new CheckUserPassword()],
            'novasenha' => 'required|min:8|different:senhaatual',
            'confsenha' => 'same:novasenha'
        ];
    }
}
