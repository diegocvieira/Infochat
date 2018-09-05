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
                <div class="nome-tags">
                    <h3>{{ $chat->user_from->nome }}</h3>
                </div>

                <div class="status">
                    <span class="date">{{ diaSemana($chat->created_at) }}</span>

                    @if($chat->close)
                        <span class="status-close"></span>
                    @endif

                    @if($chat->user_from->blocked)
                        <span class="status-block"></span>
                    @endif

                    @if($chat->count_new_messages() > 0)
                        <span class="new-messages">{{ $chat->count_new_messages() }}</span>
                    @endif
                </div>
            </div>

            <div class="manage-options">
                <div class="options">
                    @if(!$chat->close || $chat->close && $chat->close == Auth::guard('web')->user()->id)
                        @if($chat->close)
                            <a href="{{ route('open-chat', $chat->id) }}" id="open-chat"></a>
                        @else
                            <a href="{{ route('close-chat', $chat->id) }}" id="close-chat"></a>
                        @endif
                    @endif

                    @if($chat->user_from->blocked)
                        <a href="{{ route('unblock-user', $chat->from_id) }}" id="unblock-user"></a>
                    @else
                        <a href="{{ route('block-user', $chat->from_id) }}" id="block-user"></a>
                    @endif

                    <a href="{{ route('delete-chat', $chat->id) }}" id="delete-chat"></a>
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
