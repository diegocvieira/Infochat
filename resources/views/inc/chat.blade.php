<div class="topo-chat">
    {!! Form::hidden('user_id', $tipo == 'trabalho' ? $chat->user_id : $chat->id, ['id' => 'user_id']) !!}

    <div class="imagem">
        @if($chat->imagem)
            <img src="{{ asset('uploads/perfil/' . $chat->imagem) }}" alt="Foto de perfil de {{ $chat->nome }}" />
        @else
            <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $chat->nome }}" />
        @endif
    </div>

    @if($tipo == 'trabalho')
        <a href="#" class="ver-perfil" data-id="{{ $chat->id }}">{{ $chat->nome }}</a>
    @else
        <a href="#">{{ $chat->nome }}</a>
    @endif

    @if(Auth::guard('web')->check() && $tipo == 'trabalho')
        {!! Form::open(['method' => 'post', 'id' => 'form-avaliar', 'route' => 'avaliar-atendimento']) !!}
            <span>avalie este atendimento</span>

            {!! Form::hidden('trabalho_id', $chat->id) !!}

            {!! Form::radio('like', '1', (Session::has('atendimento') && session('atendimento') == '1') ? true : false, ['id' => 'like']) !!}
            {!! Form::label('like', ' ') !!}

            {!! Form::radio('like', '0', (Session::has('atendimento') && session('atendimento') == '0') ? true : false, ['id' => 'dislike']) !!}
            {!! Form::label('dislike', ' ') !!}
        {!! Form::close() !!}
    @endif
</div>

<div class="mensagens">
    @if(isset($mensagens) && count($mensagens) > 0)
        @include('inc.list-mensagens-chat')
    @else
        <div class="sem-mensagens">
            <img src="{{ asset('img/icon-logo.png') }}" />
            <p>Escreva uma mensagem e pressione<br>enter para iniciar o atendimento</p>
        </div>
    @endif
</div>

@if(Auth::guard('web')->check())
    {!! Form::open(['method' => 'post', 'action' => 'MensagemController@send', 'id' => 'form-enviar-msg']) !!}
        {!! Form::text('mensagem', null, ['placeholder' => 'Digite uma mensagem']) !!}

        {!! Form::hidden('destinatario_id', $tipo == 'trabalho' ? $chat->user->id : $chat->id, ['class' => 'trabalho-id']) !!}

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
