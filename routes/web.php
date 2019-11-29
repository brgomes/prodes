<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth', 'verified']], function() {
	Route::get('perfil', 'IndexController@perfil')->name('perfil');
	Route::post('perfil/salvar', 'IndexController@salvarPerfil')->name('salvar-perfil');
	Route::post('perfil/senha', 'IndexController@salvarSenha')->name('salvar-senha');

	Route::resource('ligas', 'LigaController');
	Route::get('ligas/consolidar/{id}', 'LigaController@consolidar')->name('ligas.consolidar');

	Route::post('rodadas/store/{liga_id}', 'RodadaController@store')->name('rodadas.store');
	Route::get('rodadas/{id}', 'RodadaController@show')->name('rodadas.show');

	Route::resource('partidas', 'PartidaController');
});

Route::group(['middleware' => ['auth', 'verified'], 'namespace' => 'Admin', 'prefix' => 'admin'], function() {
	Route::resource('usuarios', 'UsuarioController', ['as' => 'admin']);
});

Route::group(['prefix' => parseLocale()], function() {
	Auth::routes(['verify' => true]);

	Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

	Route::post('/', 'Auth\LoginController@login')->name('login');

	Route::get('user/verify/{token}', 'Auth\RegisterController@verifyUser')->name('custom.verify');
});

Route::get('logout', 'Auth\LoginController@logout')->name('logout');
