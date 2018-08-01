@if(isset($mensagens) && count($mensagens) > 0)
    @foreach($mensagens as $mensagem)
        <div class="result open-chat" data-type="pessoal" data-id="{{ $mensagem->user_remetente->id }}">
            <div class="imagem">
                @if($mensagem->user_remetente->imagem)
                    <img src="{{ asset('uploads/perfil/' . $mensagem->user_remetente->imagem) }}" alt="Foto de perfil de {{ $mensagem->user_remetente->nome }}" />
                @else
                    <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $mensagem->user_remetente->nome }}" />
                @endif
            </div>

            <div class="infos">
                <h2 class="usuario">{{ $mensagem->user_remetente->nome }}</h2>
            </div>
        </div>
    @endforeach
@else
    @if($section == 'trabalho')
        <div class="sem-perfil-trabalho">
            <img src="{{ asset('img/icon-work.png') }}" />

            @if(Auth::guard('web')->check() && Auth::guard('web')->user()->trabalho)
                <p>Você ainda não recebeu nenhuma mensagem.</p>
            @else
                <p>Atenda seus clientes online e<br>permitir que novos clientes encontrem você</p>

                @if(Auth::guard('web')->check())
                    <a href="{{ action('TrabalhoController@getConfig') }}" id="open-trabalho-config">Ativar perfil de trabalho</a>
                @else
                    <a href="#" data-toggle="modal" data-target="#modal-login-usuario">Ativar perfil de trabalho</a>
                @endif
            @endif
        </div>
    @endif
@endif
