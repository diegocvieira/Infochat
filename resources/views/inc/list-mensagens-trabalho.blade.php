@if(isset($chats) && count($chats) > 0)
    @foreach($chats as $chat)
        <div class="result open-chat" data-type="pessoal" data-id="{{ $chat->user_from->id }}" data-identificador="{{ $chat->id }}">
            <div class="imagem">
                @if($chat->user_from->imagem)
                    <img src="{{ asset('uploads/perfil/' . $chat->user_from->imagem) }}" alt="Foto de perfil de {{ $chat->user_from->nome }}" />
                @else
                    <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $chat->user_from->nome }}" />
                @endif
            </div>

            <div class="infos">
                <h2 class="usuario">{{ $chat->user_from->nome }}</h2>

                @if($chat->close)
                    <span class="status-chat">CHAT FINALIZADO</span>
                @endif

                @if($chat->user_from->blocked)
                    asdsad
                @endif

                <ul class="options">
                    <li>
                        <a href="#" class="open-options" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></a>

                        <ul class="dropdown-menu">
                            @if(!$chat->close || $chat->close && $chat->close == Auth::guard('web')->user()->id)
                                <li>
                                    @if($chat->close)
                                        <a href="{{ route('open-chat', $chat->id) }}" class="option-chat" data-type="open">Retomar chat</a>
                                    @else
                                        <a href="{{ route('close-chat', $chat->id) }}" class="option-chat" data-type="close">Finalizar chat</a>
                                    @endif
                                </li>
                            @endif

                            <li>
                                <a href="{{ route('block-user', $chat->to_id) }}" class="option-chat" data-type="block">Bloquear usuário</a>
                            </li>

                            <li>
                                <a href="{{ route('delete-chat', $chat->id) }}" class="option-chat" data-type="delete">Apagar chat</a>
                            </li>
                        </ul>
                    </li>
                </ul>
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
