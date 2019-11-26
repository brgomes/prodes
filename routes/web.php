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

Route::group(['middleware' => ['auth','verified']], function() {
	Route::get('/home', 'IndexController@index')->name('index');
});

Route::group(['prefix' => parseLocale()], function() {
	Auth::routes(['verify' => true]);

	Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

	Route::post('/', 'Auth\LoginController@login')->name('login');

	Route::get('/user/verify/{token}', 'Auth\RegisterController@verifyUser');
});

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

