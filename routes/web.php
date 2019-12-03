<?php

Route::group(['middleware' => ['auth', 'verified']], function() {
	Route::get('perfil', 'IndexController@perfil')->name('perfil');
	Route::post('perfil/salvar', 'IndexController@salvarPerfil')->name('salvar-perfil');
	Route::post('perfil/senha', 'IndexController@salvarSenha')->name('salvar-senha');

	Route::get('ligas', 'LigaController@index')->name('ligas.index');
	Route::get('ligas/{liga}/{rodada?}', 'LigaController@show')->name('ligas.show');
	Route::post('ligas', 'LigaController@store')->name('ligas.store');
	Route::put('ligas/{liga}', 'LigaController@update')->name('ligas.update');
	Route::delete('ligas/{liga}', 'LigaController@destroy')->name('ligas.destroy');
	Route::post('ligas/consolidar/{liga}', 'LigaController@consolidar')->name('ligas.consolidar');

	Route::post('rodadas/{liga}', 'RodadaController@store')->name('rodadas.store');
	Route::put('rodadas/{rodada}', 'RodadaController@update')->name('rodadas.update');
	Route::post('rodadas/consolidar/{rodada}', 'RodadaController@consolidar')->name('rodadas.consolidar');

	Route::post('palpitar/{rodada}', 'PalpiteController@salvar')->name('palpitar');

	Route::resource('partidas', 'PartidaController');

	//Route::get('apostas', 'ApostaController@index')->name('apostas.index');
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
