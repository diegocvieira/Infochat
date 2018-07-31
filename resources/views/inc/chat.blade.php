<div class="topo-chat">
    {!! Form::hidden('user_id', $chat_trabalho->user_id, ['id' => 'user_id']) !!}

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
    @include('pagination-mensagens')
</div>

@if(Auth::guard('web')->check())
    {!! Form::open(['method' => 'post', 'action' => 'MensagemController@save', 'id' => 'form-enviar-msg']) !!}
        {!! Form::text('mensagem', null, ['placeholder' => 'Digite uma mensagem']) !!}

        {!! Form::hidden('destinatario_id', $chat_trabalho->user->id, ['class' => 'trabalho-id']) !!}

        {!! Form::submit('', ['class' => 'button']) !!}
    {!! Form::close() !!}
@else
    <div id="form-enviar-msg">
        <div class="lock">
            <div class="balao">
                <p>Acesse sua conta ou cadastre-se para liberar o infochat</p>
            </div>
        </div>

        {!! Form::text('mensagem', null, ['placeholder' => 'Digite uma mensagem']) !!}

        <button type="button" class="button"></button>
    </div>
@endif
