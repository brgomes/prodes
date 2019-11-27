<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeuPerfilValidationRequest;
use App\Http\Requests\NovaSenhaValidationRequest;
use App\Models\Pais;
use App\Models\Timezone;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
    	return view('index');
    }

    public function perfil()
    {
    	if (app()->getLocale() == 'pt-BR') {
            $paises = Pais::orderBy('nomePT')->pluck('nomePT', 'id')->prepend('-- SELECIONE --', '');
        } elseif (app()->getLocale() == 'en') {
            $paises = Pais::orderBy('nomeEN')->pluck('nomeEN', 'id')->prepend('-- SELECT --', '');
        } else {
            $paises = Pais::orderBy('nomeES')->pluck('nomeES', 'id')->prepend('-- SELECCIONE --', '');
        }

        $timezones = Timezone::orderBy('gmt_offset', 'DESC')->orderBy('timezone')->get()->map(function($timezone) {
        	return ['key' => $timezone->timezone, 'value' => '(GMT ' . $timezone->gmt . ') ' . $timezone->timezone];
        })->pluck('value', 'key');

        $idiomas = [
        	'en' 	=> 'English',
        	'es'	=> 'Español',
        	'pt-BR' => 'Português',
        ];

    	return view('perfil', compact('paises', 'timezones', 'idiomas'));
    }

    public function salvarPerfil(MeuPerfilValidationRequest $request)
    {
    	$user 	= auth()->user();
    	$locale = $user->locale;

    	if ($user->update($request->all())) {
    		if ($locale != $request->locale) {
    			app()->setLocale($request->locale);
    		}

    		return redirect()->route('perfil')->with('success', __('message.perfil-atualizado'));
    	}

    	return redirect()->route('perfil')->with('danger', __('message.erro'));
    }

    public function salvarSenha(NovaSenhaValidationRequest $request)
    {
    	$user = auth()->user();

        $data = [
            'password' 	=> bcrypt($request->novasenha),
            'datasenha' => Carbon::now(),
        ];

    	if ($user->update($data)) {
    		return redirect()->route('perfil')->with('success', __('message.senha-alterada'));
    	}

    	return redirect()->route('perfil')->with('danger', __('message.erro'));
    }
}
