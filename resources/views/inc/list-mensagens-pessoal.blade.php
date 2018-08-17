@if(isset($chats) && count($chats) > 0)
    @foreach($chats as $chat)
        <div class="result open-chat" data-type="trabalho" data-id="{{ $chat->user_to->trabalho->id }}" data-identificador="{{ $chat->id }}">
            <div class="imagem">
                @if($chat->user_to->trabalho->imagem)
                    <img src="{{ asset('uploads/perfil/' . $chat->user_to->trabalho->imagem) }}" alt="Foto de perfil de {{ $chat->user_to->trabalho->nome }}" />
                @else
                    <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $chat->user_to->trabalho->nome }}" />
                @endif
            </div>

            <div class="infos">
                <h2>{{ $chat->user_to->trabalho->nome }}</h2>

                <div class="tags">
                    @foreach($chat->user_to->trabalho->tags as $t)
                        <p><span>-</span> {{ $t->tag }}</p>
                    @endforeach
                </div>

                @if($chat->count_new_messages() > 0)
                    <div class="new-messages">
                        <span>{{ $chat->count_new_messages() }}</span>
                    </div>
                @endif

                <div class="result-bottom">
                    <a href="#" class="ver-perfil" data-id="{{ $chat->user_to->trabalho->id }}">ver perfil</a>

                    @if($chat->close)
                        <span class="status-chat">CHAT FINALIZADO</span>
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
    @if($section == 'pessoal')
        <div class="sem-resultados">
            @if(Auth::guard('web')->check())
                <p>Você ainda não enviou nenhuma mensagem.</p>
            @else
                <p>É necessário estar logado para poder visualizar suas mensagens.</p>
            @endif
        </div>
    @endif
@endif
