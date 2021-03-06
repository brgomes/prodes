<?php

Route::group(['middleware' => ['auth', 'verified']], function() {
	Route::get('perfil', 'IndexController@perfil')->name('perfil');
	Route::post('perfil/salvar', 'IndexController@salvarPerfil')->name('salvar-perfil');
	Route::post('perfil/senha', 'IndexController@salvarSenha')->name('salvar-senha');

	Route::get('ligas', 'LigaController@index')->name('ligas.index');
	Route::get('ligas/{liga}/show/{rodada?}', 'LigaController@show')->name('ligas.show');
	Route::get('ligas/{liga}/edit', 'LigaController@edit')->name('ligas.edit');
	Route::post('ligas', 'LigaController@store')->name('ligas.store');
	Route::put('ligas/{liga}', 'LigaController@update')->name('ligas.update');
	Route::get('ligas/{liga}/delete', 'LigaController@delete')->name('ligas.delete');
	Route::delete('ligas/{liga}', 'LigaController@destroy')->name('ligas.destroy');
	Route::post('ligas/consolidar/{liga}/{rodada}', 'LigaController@consolidar')->name('ligas.consolidar');
	Route::get('ligas/setar-admin/{liga}/{usuario}', 'LigaController@setarAdmin')->name('ligas.setar-admin');
	Route::get('ligas/remover-admin/{liga}/{usuario}', 'LigaController@removerAdmin')->name('ligas.remover-admin');
	Route::get('pesquisar-liga', 'LigaController@pesquisar')->name('ligas.pesquisar');
	Route::post('ligas/entrar/{liga}', 'LigaController@entrar')->name('ligas.entrar');

	Route::get('rodadas/{liga}/create', 'RodadaController@create')->name('rodadas.create');
	Route::post('rodadas/{liga}', 'RodadaController@store')->name('rodadas.store');
	Route::get('rodadas/{rodada}/edit', 'RodadaController@edit')->name('rodadas.edit');
	Route::put('rodadas/{rodada}', 'RodadaController@update')->name('rodadas.update');
	//Route::post('rodadas/consolidar/{rodada}', 'RodadaController@consolidar')->name('rodadas.consolidar');
	Route::get('rodadas/{rodada}/delete', 'RodadaController@delete')->name('rodadas.delete');
	Route::delete('rodadas/{rodada}', 'RodadaController@destroy')->name('rodadas.destroy');
	Route::get('rodadas/tabela-resultado/{rodada}', 'RodadaController@tabela')->name('rodadas.tabela-resultado');
	Route::get('rodadas/{rodada}/classificacao', 'RodadaController@classificacao')->name('rodadas.classificacao');

	Route::post('palpitar/{rodada}', 'PalpiteController@salvar')->name('palpitar');
	Route::get('palpites/{usuario}/{rodada}', 'PalpiteController@show')->name('palpites.show');

	Route::resource('partidas', 'PartidaController');
	Route::get('partidas/{rodada}/create', 'PartidaController@create')->name('partidas.create');
	Route::get('partidas/{partida}/delete', 'PartidaController@delete')->name('partidas.delete');

	Route::get('ligas/{liga}/bonus', 'BonusController@index')->name('bonus.index');
	Route::post('ligas/{liga}/bonus/store-pergunta', 'BonusController@storePergunta')->name('bonus.store-pergunta');
	Route::get('ligas/bonus/{pergunta}/edit-pergunta', 'BonusController@editPergunta')->name('bonus.edit-pergunta');
	Route::put('ligas/bonus/update-pergunta', 'BonusController@updatePergunta')->name('bonus.update-pergunta');

	Route::get('ligas/bonus/{pergunta}/create-opcao', 'BonusController@createOpcao')->name('bonus.create-opcao');	
	Route::post('ligas/bonus/store-opcao', 'BonusController@storeOpcao')->name('bonus.store-opcao');

	Route::get('ligas/bonus/{pergunta}/delete-opcoes', 'BonusController@deleteOpcoes')->name('bonus.delete-opcoes');
	Route::post('ligas/bonus/destroy-opcoes', 'BonusController@destroyOpcoes')->name('bonus.destroy-opcoes');

	Route::post('ligas/bonus/salvar-respostas', 'BonusController@salvarRespostas')->name('bonus.salvar-respostas');
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
