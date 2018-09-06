{!! Form::hidden('user_id', $destinatario_id, ['id' => 'user_id']) !!}

<div class="top-modal">
    <a href="#" data-dismiss="modal" class="close-modal-arrow"></a>

    <div class="imagem {{ !$destinatario->imagem ? 'border' : '' }}">
        @if($destinatario->imagem)
            <img src="{{ asset('uploads/' . $destinatario_id . '/' . $destinatario->imagem) }}" alt="Foto de perfil de {{ $destinatario->nome }}" />
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
            <img src="{{ asset('img/icon-logo.png') }}" alt="Escreva uma mensagem" />
            <p>Escreva uma mensagem<br>para iniciar o atendimento</p>
        </div>
    @endif
</div>

{!! Form::open(['method' => 'post', 'action' => 'MessageController@send', 'id' => 'form-enviar-msg']) !!}
    {!! Form::text('message', null, ['autofocus', 'placeholder' => 'Digite aqui...', 'class' => !Auth::guard('web')->check() ? 'lock' : '']) !!}

    {!! Form::hidden('chat_id', isset($chat) ? $chat->id : '') !!}

    {!! Form::submit('', ['class' => 'button']) !!}
{!! Form::close() !!}
