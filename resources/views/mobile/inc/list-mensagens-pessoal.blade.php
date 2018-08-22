@if(isset($chats) && count($chats) > 0)
    @foreach($chats as $chat)
        <div class="result open-chat" data-type="trabalho" data-id="{{ $chat->user_to->trabalho->id }}" data-chatid="{{ $chat->id }}">
            <div class="imagem">
                @if($chat->user_to->trabalho->imagem)
                    <img src="{{ asset('uploads/perfil/' . $chat->user_to->trabalho->imagem) }}" alt="Foto de perfil de {{ $chat->user_to->trabalho->nome }}" />
                @else
                    <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $chat->user_to->trabalho->nome }}" />
                @endif
            </div>

            <div class="infos">
                <div class="nome-tags">
                    <h2>{{ $chat->user_to->trabalho->nome }}</h2>

                    <div class="tags">
                        @foreach($chat->user_to->trabalho->tags as $t)
                            <p><span>-</span> {{ $t->tag }}</p>
                        @endforeach
                    </div>
                </div>

                <div class="status">
                    <span class="date">{{ diaSemana($chat->created_at) }}</span>

                    @if($chat->close)
                        <span class="status-close"></span>
                    @endif

                    @if($chat->count_new_messages() > 0)
                        <span class="new-messages">{{ $chat->count_new_messages() }}</span>
                    @endif
                </div>
            </div>

            <div class="manage-options">
                <div class="options">
                    <a href="{{ route('show-trabalho', $chat->user_to->trabalho->id) }}" id="work-details"></a>

                    @if(!$chat->close || $chat->close && $chat->close == Auth::guard('web')->user()->id)
                        @if($chat->close)
                            <a href="{{ route('open-chat', $chat->id) }}" id="open-chat"></a>
                        @else
                            <a href="{{ route('close-chat', $chat->id) }}" id="close-chat"></a>
                        @endif
                    @endif

                    <a href="{{ route('delete-chat', $chat->id) }}" id="delete-chat"></a>
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
