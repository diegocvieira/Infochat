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
Route::get('{slug}', 'TrabalhoController@show')->name('show-work');

// Show perfil desktop
Route::get('{slug}/desktop', 'TrabalhoController@showDesktop')->name('show-work-desktop');

// Cities
Route::group(['prefix' => 'cidades'], function() {
    Route::post('get', 'GlobalController@getCidade');
    Route::get('set/{id}', 'GlobalController@setCidade');
    Route::get('list/{state}', 'GlobalController@listCities');

    Route::get('trocar', function() {
        if(Agent::isMobile()) {
            return view('mobile.cities');
        } else {
            return redirect()->route('inicial');
        }
    })->name('cities');
});

// Listar categorias admin
/*Route::get('subcategorias/get/{categoria}', 'GlobalController@getSubcategorias');
Route::get('categorias/get/{area}', 'GlobalController@getCategorias');
Route::get('areas/get/{tipo}', 'GlobalController@getAreas');*/

// Aside categorias
/*Route::get('aside/categorias/{slug}/{type}', 'GlobalController@asideCategorias');
Route::get('aside/subcategorias/{slug}', 'GlobalController@asideSubcategorias');
Route::get('aside/areas/{tipo}', 'GlobalController@asideAreas');
Route::post('categorias/busca', 'GlobalController@buscaCategorias');
Route::get('aside/result/{type}/{title}', 'GlobalController@searchResult');*/

// Busca
Route::get('trabalhos/busca', 'TrabalhoController@formBusca');
Route::any('busca/{city}/{state}/{palavra_chave?}', 'TrabalhoController@busca');

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

Route::get('como-funciona/usuario', function() {
    if(Agent::isDesktop()) {
        session()->flash('session_flash_slider', 'user');

        return redirect()->route('inicial');
    } else {
        return view('mobile.how-works-user');
    }
})->name('how-works-user');

Route::get('como-funciona/profissional', function() {
    if(Agent::isDesktop()) {
        session()->flash('session_flash_slider', 'work');

        return redirect()->route('inicial');
    } else {
        return view('mobile.how-works-work');
    }
})->name('how-works-user');

Route::get('site/sobre', function() {
    if(Agent::isDesktop()) {
        session()->flash('session_flash_slider', 'about');

        return redirect()->route('inicial');
    } else {
        return view('mobile.about');
    }
})->name('about');

Route::group(['prefix' => 'mensagem'], function() {
    // List personal chats
    Route::get('list/pessoal', 'ChatController@pessoal')->name('msg-pessoal');
    // List work chats
    Route::get('list/trabalho', 'ChatController@trabalho')->name('msg-trabalho');
    // Show chat
    Route::get('chat/show/{id}/{tipo}/{chat_id?}', 'ChatController@show')->name('chat');
    // Send message
    Route::post('send', 'MessageController@send');

    Route::group(['middleware' => 'auth:web'], function() {
        // List messages from chat
        Route::get('list/{id}/{page}/{new_messages?}', 'MessageController@list');
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
    //Route::get('avaliar/list/{id}/{page}', 'AvaliarController@list')->name('listar-avaliacoes');

    Route::group(['middleware' => 'auth:web'], function() {
        Route::get('config', 'TrabalhoController@getConfig');
        Route::post('config', 'TrabalhoController@setConfig');
        Route::post('config/status', 'TrabalhoController@setStatus');

        Route::post('avaliar-atendimento', 'AvaliarController@avaliarAtendimento')->name('avaliar-atendimento');
        Route::post('avaliar', 'AvaliarController@avaliar')->name('avaliar-trabalho');

        //Route::get('favoritar/{id}', 'TrabalhoController@favoritar');

        Route::get('material/preview', 'MaterialController@preview')->name('material-preview');
        Route::get('material/create/{folder}', 'MaterialController@create');
    });
});

Route::group(['prefix' => 'usuario'], function() {
    Route::get('login', function() {
        if(!Auth::guard('web')->check()) {
            if(Agent::isMobile()) {
                return view('mobile.user-login');
            } else {
                return view('user-login');
            }
        } else {
            return redirect()->route('inicial');
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

Route::group(['prefix' => 'reivindicar-conta'], function() {
    Route::get('check/{token}', 'ClaimedController@checkToken');
    Route::post('claimed-account', 'ClaimedController@claimedAccount');
    Route::post('claimed-account-phone', 'ClaimedController@claimedAccountPhone')->name('claimed-phone');

    Route::get('fone', function() {
        if(Agent::isDesktop()) {
            return view('claimed-account-phone');
        } else {
            return view('mobile.claimed-account-phone');
        }
    });
});

// Set OneSignal token
Route::post('app/onesignal/token', 'UserController@tokenOnesignal');

// Remove after execute all methods
Route::get('adm/automatic', function() {
    return view('automatic');
});
Route::post('adm/automatic-register', 'GlobalController@automaticRegister')->name('automatic-register');
Route::post('adm/automatic-emails', 'GlobalController@automaticEmails')->name('automatic-emails');
Route::post('adm/automatic-images', 'GlobalController@automaticImages')->name('automatic-images');
