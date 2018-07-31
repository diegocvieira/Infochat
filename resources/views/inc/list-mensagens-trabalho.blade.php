@if(isset($mensagens) && count($mensagens) > 0)
    @foreach($mensagens as $mensagem)
        <div class="result">
            <a href="#" class="open-chat" data-id="{{ $mensagem->user_remetente->trabalho->id }}">
                <div class="col-xs-2">
                    <div class="imagem">
                        @if($mensagem->user_remetente->trabalho->imagem)
                            <img src="{{ asset('uploads/perfil/' . $mensagem->user_remetente->trabalho->imagem) }}" alt="Foto de perfil de {{ $mensagem->user_remetente->trabalho->nome }}" />
                        @else
                            <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $mensagem->user_remetente->trabalho->nome }}" />
                        @endif
                    </div>
                </div>

                <div class="col-xs-10">
                    <h1>{{ $mensagem->user_remetente->trabalho->nome }}</h1>

                    <div class="tags">
                        @foreach($mensagem->user_remetente->trabalho->tags as $t)
                            <p><span>-</span> {{ $t->tag }}</p>
                        @endforeach
                    </div>

                    <a href="#" class="ver-perfil" data-id="{{ $mensagem->user_remetente->trabalho->id }}">ver perfil</a>
                </div>
            </a>
        </div>
    @endforeach
@else
    @if($section == 'trabalho')
        <div class="sem-perfil-trabalho">
            <img src="{{ asset('img/icon-work.png') }}" />
            <p>Atenda seus clientes online e<br>permitir que novos clientes encontrem você</p>
            @if(Auth::guard('web')->check())
                <a href="{{ action('TrabalhoController@getConfig') }}" id="open-trabalho-config">Ativar perfil de trabalho</a>
            @else
                <a href="#" data-toggle="modal" data-target="#modal-login-usuario">Ativar perfil de trabalho</a>
            @endif
        </div>
    @endif
@endif
