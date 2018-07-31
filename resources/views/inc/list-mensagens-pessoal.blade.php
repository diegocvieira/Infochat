@if(isset($mensagens))
    @foreach($mensagens as $mensagem)
        <div class="result">
            <a href="#" class="open-chat" data-id="{{ $mensagem->user_destinatario->trabalho->id }}">
                <div class="col-xs-2">
                    <div class="imagem">
                        @if($mensagem->user_destinatario->trabalho->imagem)
                            <img src="{{ asset('uploads/perfil/' . $mensagem->user_destinatario->trabalho->imagem) }}" alt="Foto de perfil de {{ $mensagem->user_destinatario->trabalho->nome }}" />
                        @else
                            <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $mensagem->user_destinatario->trabalho->nome }}" />
                        @endif
                    </div>
                </div>

                <div class="col-xs-10">
                    <h1>{{ $mensagem->user_destinatario->trabalho->nome }}</h1>

                    <div class="tags">
                        @foreach($mensagem->user_destinatario->trabalho->tags as $t)
                            <p><span>-</span> {{ $t->tag }}</p>
                        @endforeach
                    </div>

                    <a href="#" class="ver-perfil" data-id="{{ $mensagem->user_destinatario->trabalho->id }}">ver perfil</a>
                </div>
            </a>
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
