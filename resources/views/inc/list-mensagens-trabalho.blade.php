@if(isset($chats) && count($chats) > 0)
    @foreach($chats as $chat)
        <div class="result open-chat work-tab" data-type="pessoal" data-id="{{ $chat->user_from->id }}" data-chatid="{{ $chat->id }}">
            <div class="imagem">
                @if($chat->user_from->imagem)
                    <img src="{{ asset('uploads/' . $chat->user_from->id . '/' . $chat->user_from->imagem) }}" alt="Foto de perfil de {{ $chat->user_from->nome }}" />
                @else
                    <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $chat->user_from->nome }}" />
                @endif
            </div>

            <div class="infos">
                @if($chat->count_new_messages() > 0)
                    <div class="new-messages">
                        <span>{{ $chat->count_new_messages() }}</span>
                    </div>
                @endif

                <h3 class="usuario">{{ $chat->user_from->nome }}</h3>

                <div class="result-bottom">
                    @if($chat->close)
                        <span class="status-chat status-close">CHAT FINALIZADO</span>
                    @endif

                    @if($chat->user_from->blocked)
                        <span class="status-chat status-block">USUÁRIO BLOQUEADO</span>
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
                                    @if($chat->user_from->blocked)
                                        <a href="{{ route('unblock-user', $chat->from_id) }}" class="option-chat" data-type="unblock">Desbloquear usuário</a>
                                    @else
                                        <a href="{{ route('block-user', $chat->from_id) }}" class="option-chat" data-type="block">Bloquear usuário</a>
                                    @endif
                                </li>

                                <li>
                                    <a href="{{ route('delete-chat', $chat->id) }}" class="option-chat" data-type="delete">Apagar chat</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    @endforeach
@else
    @if($section == 'trabalho')
        <div class="sem-resultados">
            <img src="{{ asset('img/icon-work.png') }}" />

            @if(Auth::guard('web')->check() && Auth::guard('web')->user()->trabalho)
                <p>Você ainda não recebeu nenhuma mensagem.</p>
            @else
                <p>Atenda seus clientes online e<br>permita que novos clientes encontrem você</p>

                @if(Auth::guard('web')->check())
                    <a href="{{ action('TrabalhoController@getConfig') }}" id="open-trabalho-config">Ativar perfil de trabalho</a>
                @else
                    <a href="#" data-toggle="modal" data-target="#modal-login-usuario">Ativar perfil de trabalho</a>
                @endif
            @endif
        </div>
    @endif
@endif
