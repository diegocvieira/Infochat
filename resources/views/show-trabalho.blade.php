<div class="image">
    @if($work->imagem)
        <img src="{{ asset('uploads/' . $work->user_id . '/' . _getOriginalImage($work->imagem)) }}" alt="Foto de perfil de {{ $work->nome }}" />
    @else
        <img src="{{ asset('img/paisagem.png') }}" class="no-image" alt="Foto de perfil de {{ $work->nome }}" />
    @endif
</div>

<h2 class="work-name">{{ $work->nome }}</h2>

<div class="work-tags">
    @foreach($work->tags as $tag)
        <span>{{ $tag->tag }}</span>
    @endforeach
</div>

<p class="work-city">{{ $work->cidade->title }}/{{ $work->cidade->estado->letter }}</p>

<div class="work-statistics">
    @if($work->nota_avaliacao)
        <div class="stat avaliacao">
            <span>{{ $work->nota_avaliacao }}%</span>

            <p>Avaliação dos usuários</p>
        </div>
    @endif

    @if($work->nota_atendimento)
        <div class="stat atendimento">
            <span>{{ $work->nota_atendimento }}%</span>

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

<a href="{{ route('show-work', $work->slug) }}" class="work-slug">www.infochat.com.br/{{ $work->slug }}</a>

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
