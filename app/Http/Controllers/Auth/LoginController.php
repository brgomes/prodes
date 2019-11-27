<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    /*public function showLoginForm()
    {

    }*/

    public function authenticated(Request $request, $user)
    {
        if (!$user->email_verified_at) {
            auth()->logout();

            return back()->with('warning', __('auth.need_verify'));
        }

        return redirect()->intended($this->redirectPath());
    }

    protected function credentials(Request $request) {
        return array_merge($request->only($this->username(), 'password'), ['ativo' => 1]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => __('auth.failed')];
        $user   = Usuario::where($this->username(), $request->{$this->username()})->first();

        if (!$user) {
            $errors = [$this->username() => __('auth.email')];
        } elseif (!Hash::check($request->password, $user->password)) {
            $errors = ['password' => __('auth.password')];
        } elseif (Hash::check($request->password, $user->password) && $user->ativo != 1) {
            $errors = [$this->username() => __('auth.disabled')];
        }

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }
}
