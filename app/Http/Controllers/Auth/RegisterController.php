<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyMail;
use App\Models\Pais;
use App\Models\Usuario;
use App\Models\VerifyUser;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        if (app()->getLocale() == 'pt-BR') {
            $paises = Pais::orderBy('nomePT')->pluck('nomePT', 'id')->prepend('-- SELECIONE --', '');
        } elseif (app()->getLocale() == 'en') {
            $paises = Pais::orderBy('nomeEN')->pluck('nomeEN', 'id')->prepend('-- SELECT --', '');
        } else {
            $paises = Pais::orderBy('nomeES')->pluck('nomeES', 'id')->prepend('-- SELECCIONE --', '');
        }

        return view('auth.register', compact('paises'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'primeironome'  => ['required', 'string', 'max:30'],
            'sobrenome'     => ['required', 'string', 'max:120'],
            'sexo'          => ['required', 'in:M,F'],
            'pais_id'       => ['required', 'exists:pais,id'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:usuario'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $data['password']   = Hash::make($data['password']);
        $data['ativo']      = 1;
        $data['admin']      = 0;

        $user = Usuario::create($data);

        $verifyUser = VerifyUser::create([
            'user_id'   => $user->id,
            'token'     => str_random(40)
        ]);

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();

        return redirect('/')->with('status', 'We sent you an activation code. Check your email and click on the link to verify.');
    }

    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::with('usuario')->where('token', $token)->first();

        if (isset($verifyUser)) {
            $usuario = $verifyUser->usuario;

            if (!$usuario->email_verified_at) {
                $usuario->email_verified_at = Carbon::now();
                $usuario->save();

                $status = 'Your e-mail is verified. You can now login.';
            }else{
                $status = 'Your e-mail is already verified. You can now login.';
            }
        } else {
            return redirect('/')->with('warning', 'Sorry your email cannot be identified.');
        }

        return redirect('/')->with('status', $status);
    }
}
