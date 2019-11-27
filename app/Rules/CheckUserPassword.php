<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class CheckUserPassword implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        $user = auth()->user();

        return Hash::check($value, $user->password);
    }

    public function message()
    {
        return __('message.senha-atual-errada');
    }
}
