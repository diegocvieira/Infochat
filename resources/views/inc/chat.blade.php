<div class="topo-chat">
    <div class="imagem">
        @if($chat_trabalho->imagem)
            <img src="{{ asset('uploads/perfil/' . $chat_trabalho->imagem) }}" alt="Foto de perfil de {{ $chat_trabalho->nome }}" />
        @else
            <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $chat_trabalho->nome }}" />
        @endif
    </div>

    <h1>{{ $chat_trabalho->nome }}</h1>

    @if(Auth::guard('web')->check())
        {!! Form::model($avaliacao, ['method' => 'post', 'id' => 'form-avaliar', 'action' => 'TrabalhoController@avaliar']) !!}
            <span>avalie este atendimento</span>

            {!! Form::hidden('trabalho_id', $chat_trabalho->id) !!}

            {!! Form::radio('avaliacao', '1', null, ['id' => 'like']) !!}
            {!! Form::label('like', ' ') !!}

            {!! Form::radio('avaliacao', '0', null, ['id' => 'dislike']) !!}
            {!! Form::label('dislike', ' ') !!}
        {!! Form::close() !!}
    @endif
</div>

<div class="mensagens">

</div>

{!! Form::open(['method' => 'post', 'id' => 'form-enviar-msg']) !!}
    {!! Form::text('msg', null, ['placeholder' => 'Digite uma mensagem']) !!}

    {!! Form::submit('') !!}
{!! Form::close() !!}
