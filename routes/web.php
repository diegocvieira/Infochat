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

// Acessar o chat pela url
Route::get('{slug}', 'ChatController@showChatUrl');

// Listar e setar cidade
Route::post('cidades/get', 'GlobalController@getCidade');
Route::get('cidades/set/{id}', 'GlobalController@setCidade');

// Listar categorias admin
Route::get('subcategorias/get/{categoria}', 'GlobalController@getSubcategorias');
Route::get('categorias/get/{area}', 'GlobalController@getCategorias');
Route::get('areas/get/{tipo}', 'GlobalController@getAreas');

// Aside categorias
Route::get('aside/categorias/{slug}', 'GlobalController@asideCategorias');
Route::get('aside/subcategorias/{slug}', 'GlobalController@asideSubcategorias');
Route::get('aside/areas/{tipo}', 'GlobalController@asideAreas');
Route::post('categorias/busca', 'GlobalController@buscaCategorias');

// Busca
Route::get('trabalhos/busca', 'TrabalhoController@formBusca');
Route::any('busca/{city}/{state}/{tipo?}/{palavra_chave?}/{area?}/{tag?}', 'TrabalhoController@busca');

Route::get('termos/uso', function() {
    if(Agent::isMobile()) {
        return view('mobile.termos-uso');
    } else {
        return view('termos-uso');
    }
})->name('termos-uso');
Route::get('politica/privacidade', function() {
    if(Agent::isMobile()) {
        return view('mobile.termos-uso');
    } else {
        return view('termos-privacidade');
    }
})->name('termos-privacidade');

Route::get('como/funciona', function() {
    if(Agent::isMobile()) {
        return view('mobile.como-funciona');
    }
})->name('como-funciona');

Route::group(['prefix' => 'mensagem'], function() {
    // Listar mensagens pessoais
    Route::get('list/pessoal', 'ChatController@pessoal')->name('msg-pessoal');
    // Listar mensagens de trabalho
    Route::get('list/trabalho', 'ChatController@trabalho')->name('msg-trabalho');
    // Exibir chat
    Route::get('chat/show/{id}/{tipo}/{chat_id?}', 'ChatController@show');

    Route::group(['middleware' => 'auth:web'], function() {
        // Enviar
        Route::post('send', 'MessageController@send');
        // Listar mensagens do chat
        Route::get('list/{id}/{offset}', 'MessageController@list');
        // Finalizar chat
        Route::get('chat/close/{id}', 'ChatController@close')->name('close-chat');
        // Retomar chat
        Route::get('chat/open/{id}', 'ChatController@open')->name('open-chat');
        // Apagar chat
        Route::get('chat/delete/{id}', 'ChatController@delete')->name('delete-chat');
        // Bloquear Usuario
        Route::get('chat/block/{id}', 'ChatController@blockUser')->name('block-user');
        // Desbloquear Usuario
        Route::get('chat/unblock/{id}', 'ChatController@unblockUser')->name('unblock-user');
        // Count novas mensagens trabalho/pessoal
        Route::post('new-messages', 'MessageController@newMessages');
    });
});

Route::group(['prefix' => 'trabalho'], function() {
    Route::get('show/{id}', 'TrabalhoController@show')->name('show-trabalho');

    Route::group(['middleware' => 'auth:web'], function() {
        Route::get('config', 'TrabalhoController@getConfig');
        Route::post('config', 'TrabalhoController@setConfig');
        Route::post('config/status', 'TrabalhoController@setStatus');

        Route::post('avaliar-atendimento', 'AvaliarController@avaliarAtendimento')->name('avaliar-atendimento');
        Route::post('avaliar', 'AvaliarController@avaliar')->name('avaliar-trabalho');
        Route::get('avaliar/list/{id}/{offset}', 'AvaliarController@list')->name('listar-avaliacoes');

        Route::get('favoritar/{id}', 'TrabalhoController@favoritar');
    });
});

Route::group(['prefix' => 'usuario'], function() {
    Route::get('login', function() {
        if(Agent::isMobile()) {
            return view('mobile.user-login');
        } else {
            return view('user-login');
        }
    })->name('user-login');

    Route::get('cadastro', function() {
        if(Agent::isMobile()) {
            return view('mobile.user-register');
        } else {
            return view('user-register');
        }
    })->name('user-register');

    Route::post('cadastro', 'UserController@create');
    Route::post('login', 'UserController@login');

    Route::group(['middleware' => 'auth:web'], function() {
        Route::get('logout', 'UserController@logout')->name('usuario-logout');

        Route::get('config', 'UserController@getConfig')->name('get-usuario-config');
        Route::post('config', 'UserController@setConfig')->name('set-usuario-config');

        Route::post('excluir-conta', 'UserController@excluirConta');
    });
});

Route::group(['prefix' => 'recuperar-senha'], function() {
    Route::post('solicitar', 'RecuperarSenhaController@solicitar');
    Route::get('check/{token}', 'RecuperarSenhaController@check');
    Route::post('alterar', 'RecuperarSenhaController@alterar');
});





Route::get('teste/teste', 'TrabalhoController@teste');
