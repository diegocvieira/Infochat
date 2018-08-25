{!! Form::hidden('user_id', $tipo == 'trabalho' ? $destinatario->user_id : $destinatario->id, ['id' => 'user_id']) !!}

<div class="top-modal">
    <a href="#" data-dismiss="modal" class="close-modal-arrow"></a>

    <div class="imagem {{ !$destinatario->imagem ? 'border' : '' }}">
        @if($destinatario->imagem)
            <img src="{{ asset('uploads/perfil/' . $destinatario->imagem) }}" alt="Foto de perfil de {{ $destinatario->nome }}" />
        @else
            <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $destinatario->nome }}" />
        @endif
    </div>

    <div class="title-status">
        <h3 class="title {{ ($tipo == 'trabalho' && $destinatario->user->online || $tipo == 'pessoal' && $destinatario->online) ? 'margin' : '' }}">{{ $destinatario->nome }}</h3>

        @if($tipo == 'trabalho' && $destinatario->user->online || $tipo == 'pessoal' && $destinatario->online)
            <span class="online">online</span>
        @endif
    </div>

    @if(Auth::guard('web')->check() && $tipo == 'trabalho')
        {!! Form::open(['method' => 'post', 'id' => 'form-avaliar', 'route' => 'avaliar-atendimento']) !!}
            {!! Form::hidden('trabalho_id', $destinatario->id) !!}

            {!! Form::radio('like', '1', (Session::has('atendimento') && session('atendimento_' . $destinatario->id) == '1') ? true : false, ['id' => 'like']) !!}
            {!! Form::label('like', ' ') !!}

            {!! Form::radio('like', '0', (Session::has('atendimento') && session('atendimento_' . $destinatario->id) == '0') ? true : false, ['id' => 'dislike']) !!}
            {!! Form::label('dislike', ' ') !!}
        {!! Form::close() !!}
    @endif
</div>

<div class="mensagens">
    @if(isset($chat) && count($chat->messages) > 0)
        @include('inc.list-mensagens-chat')
    @else
        <div class="sem-mensagens">
            <img src="{{ asset('img/icon-logo.png') }}" />
            <p>Escreva uma mensagem<br>para iniciar o atendimento</p>
        </div>
    @endif
</div>

@if(Auth::guard('web')->check())
    {!! Form::open(['method' => 'post', 'action' => 'MessageController@send', 'id' => 'form-enviar-msg']) !!}
        {!! Form::text('message', null, ['autofocus', 'placeholder' => 'Digite aqui...', 'id' => 'teste']) !!}

        {!! Form::hidden('chat_id', $chat->id) !!}

        {!! Form::submit('', ['class' => 'button']) !!}
    {!! Form::close() !!}
@else
    <div id="form-enviar-msg">
        {!! Form::text('mensagem', null, ['autofocus', 'placeholder' => 'Acesse sua conta para liberar o infochat']) !!}

        <button type="button" class="button"></button>
    </div>
@endif
