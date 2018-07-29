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


Route::get('aside/categorias/{slug}', 'GlobalController@asideCategorias');
Route::get('aside/subcategorias/{slug}', 'GlobalController@asideSubcategorias');
Route::get('aside/areas/{tipo}', 'GlobalController@asideAreas');




Route::post('trabalhos/busca', 'TrabalhoController@formBusca');
Route::any('busca/{tipo?}/{palavra_chave?}/{area?}/{tag?}', 'TrabalhoController@busca');



Route::post('mensagem/save', 'MensagemController@save');
Route::get('mensagens/paginate/{id}/{offset}', 'MensagemController@pagination');


Route::group(['prefix' => 'trabalho'], function() {
    Route::get('show/{id}', 'TrabalhoController@show');

    Route::group(['middleware' => 'auth:web'], function() {
        Route::get('config', 'TrabalhoController@getConfig');
        Route::post('config', 'TrabalhoController@setConfig');
        Route::post('config/status', 'TrabalhoController@setStatus');

        Route::post('avaliar', 'TrabalhoController@avaliar');
    });
});

Route::group(['prefix' => 'cliente'], function() {
    Route::post('cadastro', 'UserController@create');
    Route::post('login', 'UserController@login');

    Route::group(['middleware' => 'auth:web'], function() {
        Route::get('logout', 'UserController@logout')->name('usuario-logout');
    });
});
