<div class="top-modal">
    <a href="#" data-dismiss="modal" class="close-modal-arrow"></a>

    <h3 class="title {{ $trabalho->user->online ? 'margin' : '' }}">{{ $trabalho->nome }}</h3>

    @if($trabalho->user->online)
        <span class="online">online</span>
    @endif
</div>

<div class="imagem">
    @if($trabalho->imagem)
        <img src="{{ asset('uploads/' . $trabalho->user_id . '/' . _getOriginalImage($trabalho->imagem)) }}" alt="Foto de perfil de {{ $trabalho->nome }}" />
    @else
        <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $trabalho->nome }}" />
    @endif
</div>

<div class="infos">
    <div class="info atendimento">
        <p>
            @if($trabalho->calc_atendimento($trabalho->id))
                {{ $trabalho->calc_atendimento($trabalho->id) }}% atendimento infochat
            @else
                sem avaliação de atendimento
            @endif
        </p>
    </div>

    <div class="info avaliacao">
        <p>
            @if($trabalho->calc_avaliacao($trabalho->id))
                {{ $trabalho->calc_avaliacao($trabalho->id) }} avaliação do {{ $trabalho->tipoNome($trabalho->tipo) }}
            @else
                sem avaliação do {{ $trabalho->tipoNome($trabalho->tipo) }}
            @endif
        </p>
    </div>

    <div class="add-favoritos">
        <a href="#" class="favoritar {{ (Auth::guard('web')->check() && Auth::guard('web')->user()->favorito($trabalho->id)) ? 'favorito' : '' }}" data-id="{{ $trabalho->id }}"></a>
    </div>
</div>

<div class="abas-container">
    <div class="abas">
        <a href="#" class="active" data-type="sobre">Sobre</a>
        <a href="#" data-type="informacoes">Mais informações</a>
        <a href="#" data-type="comentarios">Comentários</a>
        <a href="#" data-type="avaliar">Avaliar</a>
    </div>
</div>

<div class="sobre aba-aberta">
    <div class="descricao">
        <p>{{ $trabalho->descricao }}</p>
    </div>

    <p class="tipo-perfil">Perfil de {{ $trabalho->tipoNome($trabalho->tipo) }}</p>

    <div class="tags">
        @foreach($trabalho->tags as $tag)
            <span>{{ $tag->tag }}</span>
        @endforeach
    </div>
</div>

<div class="informacoes aba-aberta">
    <div class="group endereco">
        @if($trabalho->logradouro || $trabalho->bairro || $trabalho->cep)
            @if($trabalho->logradouro)
                <p>
                    {{ $trabalho->logradouro }}, {{ $trabalho->numero }}

                    @if($trabalho->complemento)
                        - {{ $trabalho->complemento }}
                    @endif
                </p>
            @endif

            @if($trabalho->bairro)
                <p>{{ $trabalho->bairro }}</p>
            @endif

            @if($trabalho->cep)
                <p>{{ $trabalho->cep }}</p>
            @endif

            @if($trabalho->logradouro && $trabalho->numero && $trabalho->bairro)
                <a class="ver-no-mapa" href="//maps.google.com/?q={{ $trabalho->logradouro }}, {{ $trabalho->numero }}, {{ $trabalho->bairro }}, {{ $trabalho->cidade->title }}, {{ $trabalho->cidade->estado->letter }}" target="_blanck">ver no mapa</a>
            @endif
        @else
            <p>O endereço ainda não foi informado...</p>
        @endif
    </div>

    @if(count($trabalho->telefones) > 0)
        <div class="group fones">
            @foreach($trabalho->telefones as $fone)
                <p>{{ $fone->fone }}</p>
            @endforeach
        </div>
    @endif

    @if($trabalho->email)
        <div class="group email">
            <p>{{ $trabalho->email }}</p>
        </div>
    @endif

    @if(count($trabalho->redes) > 0)
        <div class="group redes-sociais">
            @foreach($trabalho->redes as $rede_social)
                <p>{{ $rede_social->url }}</p>
            @endforeach
        </div>
    @endif

    @if(count($trabalho->horarios) > 0)
        <div class="group horarios-atendimento">
            @foreach($trabalho->horarios as $horario)
                <div class="dia">
                    <p>{{ diaHorario($horario->dia) }}</p>
                </div>

                <div class="hora">
                    <p>
                        @if($horario->de_manha)
                            <span>{{ format_horario($horario->de_manha) }}</span>
                        @endif
                        @if($horario->ate_tarde)
                            <span>{{ format_horario($horario->ate_tarde) }}</span>
                        @endif
                        @if($horario->de_tarde)
                            <span>{{ format_horario($horario->de_tarde) }}</span>
                        @endif
                        @if($horario->ate_noite)
                            <span>{{ format_horario($horario->ate_noite) }}</span>
                        @endif
                    </p>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="comentarios aba-aberta">
    @include('list-avaliacoes')
</div>

<div class="avaliar aba-aberta">
    {!! Form::open(['method' => 'post', 'id' => 'form-avaliar-trabalho', 'route' => 'avaliar-trabalho']) !!}
        {!! Form::hidden('trabalho_id', $trabalho->id) !!}

        <div class="nota">
            <p>Avaliação do {{ $trabalho->tipoNome($trabalho->tipo) }}</p>

            @for($q = 1; $q <= 5; $q++)
                {!! Form::radio('nota', $q, @!empty($q == $avaliacao_usuario->nota), ['id' => 'nota' . $q, 'required']) !!}

                {!! Form::label('nota' . $q, ' ', ['class' => @!empty($q <= $avaliacao_usuario->nota) ? 'star-full' : '']) !!}
            @endfor
        </div>

        <div class="comentario">
            {!! Form::label('descricao', 'Deixe seu comentário') !!}

            {!! Form::textarea('descricao', isset($avaliacao_usuario) ? $avaliacao_usuario->descricao : '', ['id' => 'descricao']) !!}
        </div>

        {!! Form::submit('Enviar') !!}
    {!! Form::close() !!}
</div>
