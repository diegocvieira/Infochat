<div class="row">
    <div class="col-xs-4 imagem">
        @if($trabalho->imagem)
        <div class="bg" style="background-image: url({{ asset('uploads/' . $trabalho->user_id . '/' . $trabalho->imagem) }}); background-size: cover;">
        @else
        <div class="bg sem-imagem">
        @endif
        </div>
    </div>

    <div class="col-xs-8 infos">
        <div class="header-info">
            <div class="nome">
                <h1>{{ $trabalho->nome }}</h1>
            </div>

            @if($trabalho->user->online)
                <span class="online">online</span>
            @endif
        </div>

        <div class="tags">
            @foreach($trabalho->tags->take(3) as $tag)
                <p>{{ $tag->tag }} <span>-</span></p>
            @endforeach
        </div>

        <div class="info atendimento">
            <p>{{ $trabalho->calc_atendimento($trabalho->id) }}% atendimento infochat</p>
        </div>

        <div class="info avaliacao">
            <p>{{ $trabalho->calc_avaliacao($trabalho->id) }} avaliação da empresa</p>
        </div>

        <div class="add-favoritos">
            <a href="#" class="favoritar {{ (Auth::guard('web')->check() && Auth::guard('web')->user()->favorito($trabalho->id)) ? 'favorito' : '' }}" data-id="{{ $trabalho->id }}"></a>
        </div>
    </div>
</div>

<div class="row" style="padding-left: 15px;">
    <div class="abas col-xs-12">
        <a href="#" class="active" data-type="sobre">Sobre</a>
        <a href="#" data-type="informacoes">Mais informações</a>
        <a href="#" data-type="comentarios">Comentários</a>
        <a href="#" data-type="avaliar">Avaliar</a>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 sobre aba-aberta">
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

    <div class="col-xs-12 informacoes aba-aberta">
        <div class="group endereco">
            <p>
                {{ $trabalho->logradouro }}, {{ $trabalho->numero }}

                @if($trabalho->complemento)
                    - {{ $trabalho->complemento }}
                @endif
            </p>

            <p>{{ $trabalho->bairro }} - {{ $trabalho->cidade->title }}/{{ $trabalho->cidade->estado->letter }} - Brasil</p>

            <p>{{ $trabalho->cep }}</p>

            <a class="ver-no-mapa" href="//maps.google.com/?q={{ $trabalho->logradouro }}, {{ $trabalho->numero }}, {{ $trabalho->bairro }}, {{ $trabalho->cidade->title }}, {{ $trabalho->cidade->estado->letter }}" target="_blanck">ver no mapa</a>
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

    <div class="col-xs-12 comentarios aba-aberta">
        @include('list-avaliacoes')
    </div>

    <div class="col-xs-12 avaliar aba-aberta">
        {!! Form::open(['method' => 'post', 'id' => 'form-avaliar-trabalho', 'route' => 'avaliar-trabalho']) !!}
            {!! Form::hidden('trabalho_id', $trabalho->id) !!}

            <div class="nota">
                <p>Avalie este usuário</p>

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
</div>
