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

Route::get('/', 'GlobalController@inicial')->name('inicial');

Route::post('cidades/get', 'GlobalController@getCidade');
Route::get('cidades/set/{id}', 'GlobalController@setCidade');

Route::post('categorias/busca', 'GlobalController@buscaCategorias');

Route::get('subcategorias/get/{categoria}', 'GlobalController@getSubcategorias');
Route::get('categorias/get/{area}', 'GlobalController@getCategorias');
Route::get('areas/get/{tipo}', 'GlobalController@getAreas');


Route::get('teste/teste', 'TrabalhoController@teste');



Route::group(['prefix' => 'trabalho'], function() {
    Route::group(['middleware' => 'auth:web'], function() {
        Route::get('config', 'TrabalhoController@getConfig');
        Route::post('config', 'TrabalhoController@setConfig');
        Route::post('config/status', 'TrabalhoController@setStatus');
    });
});


Route::group(['prefix' => 'cliente'], function() {
    Route::post('cadastro', 'UserController@create');
    Route::post('login', 'UserController@login');

    Route::group(['middleware' => 'auth:web'], function() {
        Route::get('logout', 'UserController@logout')->name('usuario-logout');
    });
});
