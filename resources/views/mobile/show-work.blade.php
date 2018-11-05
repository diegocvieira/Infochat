@extends('mobile.base')

@section('content')
    <div class="show-work">
        <div class="top-page">
            <a href="javascript:history.back()" class="back-arrow"></a>

            <h3 class="title {{ $work->user->online ? 'margin' : '' }}">{{ $work->nome }}</h3>

            @if($work->user->online)
                <span class="online">online</span>
            @endif
        </div>

        <div class="image">
            @if($work->imagem)
                <img src="{{ asset('uploads/' . $work->user_id . '/' . _getOriginalImage($work->imagem)) }}" alt="Foto de perfil de {{ $work->nome }}" />
            @else
                <img src="{{ asset('img/paisagem.png') }}" class="no-image" alt="Foto de perfil de {{ $work->nome }}" />
            @endif
        </div>

        <div class="infos">
            <h2 class="work-name">{{ $work->nome }}</h2>

            <div class="work-tags">
                @foreach($work->tags as $tag)
                    <span>{{ $tag->tag }}</span>
                @endforeach
            </div>

            <p class="work-city">{{ $work->cidade->title }}/{{ $work->cidade->estado->letter }}</p>

            <div class="work-statistics">
                @if($work->calc_avaliacao($work->id))
                    <div class="avaliacao">
                        <span>{{ $work->calc_avaliacao($work->id) }}%</span>

                        <p>Avaliação dos usuários</p>
                    </div>
                @endif

                @if($work->calc_atendimento($work->id))
                    <div class="atendimento">
                        <span>{{ $work->calc_atendimento($work->id) }}%</span>

                        <p>Avaliação dos atendimentos</p>
                    </div>
                @endif
            </div>

            <p class="work-description">
                @if($work->descricao)
                    {{ $work->descricao }}
                @else
                    Este usuário ainda não adicionou uma descrição
                @endif
            </p>

            <a href="{{ route('chat', [$work->id, 'trabalho']) }}" class="work-slug">www.infochat.com.br/{{ $work->slug }}</a>

            {!! Form::open(['method' => 'post', 'id' => 'form-avaliar-trabalho', 'route' => 'avaliar-trabalho']) !!}
                {!! Form::hidden('trabalho_id', $work->id) !!}

                <div class="nota">
                    <p>Avalie este usuário</p>

                    @for($q = 1; $q <= 5; $q++)
                        {!! Form::radio('nota', $q, @!empty($q == $avaliacao_usuario->nota), ['id' => 'nota' . $q, 'required']) !!}

                        {!! Form::label('nota' . $q, ' ', ['class' => @!empty($q <= $avaliacao_usuario->nota) ? 'star-full' : '']) !!}
                    @endfor
                </div>
            {!! Form::close() !!}

            <a href="{{ route('chat', [$work->id, 'trabalho']) }}" class="link-chat">IR PARA O CHAT</a>
        </div>
    </div>
@endsection
