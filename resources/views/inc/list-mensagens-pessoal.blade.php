@if(isset($chats) && count($chats) > 0)
    @foreach($chats as $chat)
        @if($chat->user_to->trabalho)
            <div class="result open-chat" data-type="trabalho" data-id="{{ $chat->user_to->trabalho->id }}" data-chatid="{{ $chat->id }}">
                <div class="imagem">
                    @if($chat->user_to->trabalho->imagem)
                        <img src="{{ asset('uploads/' . $chat->user_to->id . '/' . $chat->user_to->trabalho->imagem) }}" alt="Foto de perfil de {{ $chat->user_to->trabalho->nome }}" />
                    @else
                        <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $chat->user_to->trabalho->nome }}" />
                    @endif
                </div>

                <div class="infos">
                    <div class="top">
                        <h3>{{ $chat->user_to->trabalho->nome }}</h3>

                        @if($chat->count_new_messages() > 0)
                            <div class="new-messages">
                                <span>{{ $chat->count_new_messages() }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="bottom">
                        <div class="tags">
                            @foreach($chat->user_to->trabalho->tags as $t)
                                <p><span>-</span> {{ $t->tag }}</p>
                            @endforeach
                        </div>

                        <?php /*<a href="#" class="ver-perfil" data-id="{{ $chat->user_to->trabalho->id }}">ver perfil</a>*/ ?>

                        <div class="status-geral">
                            @if($chat->close)
                                <span class="status-chat status-close" title="Chat finalizado"></span>
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
            </div>
        @else
            <div class="result">
                <div class="imagem">
                    <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil" />
                </div>

                <div class="infos">
                    <h3 class="usuario">Perfil removido</h3>
                </div>
            </div>
        @endif
    @endforeach
@else
    @if($section == 'pessoal')
        <div class="sem-resultados">
            <p>Dê início a um chat para<br>visualizar suas conversas aqui.</p>
        </div>
    @endif
@endif
