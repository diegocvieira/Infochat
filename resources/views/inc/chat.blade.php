<div class="topo-chat">
    {!! Form::hidden('user_id', $destinatario_id, ['id' => 'user_id']) !!}

    <div class="imagem">
        @if($destinatario->imagem)
            <img src="{{ asset('uploads/' . $destinatario_id . '/' . $destinatario->imagem) }}" alt="Foto de perfil de {{ $destinatario->nome }}" />
        @else
            <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $destinatario->nome }}" />
        @endif
    </div>

    @if($tipo == 'trabalho')
        <a href="#" class="ver-perfil" data-id="{{ $destinatario->id }}">{{ $destinatario->nome }}</a>
    @else
        <a href="#">{{ $destinatario->nome }}</a>
    @endif

    @if(Auth::guard('web')->check() && $tipo == 'trabalho')
        {!! Form::open(['method' => 'post', 'id' => 'form-avaliar', 'route' => 'avaliar-atendimento']) !!}
            <span>avalie este atendimento</span>

            {!! Form::hidden('trabalho_id', $destinatario->id) !!}

            {!! Form::radio('like', '1', (Session::has('atendimento') && session('atendimento_' . $destinatario->id) == '1') ? true : false, ['id' => 'like']) !!}
            {!! Form::label('like', ' ') !!}

            {!! Form::radio('like', '0', (Session::has('atendimento') && session('atendimento_' . $destinatario->id) == '0') ? true : false, ['id' => 'dislike']) !!}
            {!! Form::label('dislike', ' ') !!}
        {!! Form::close() !!}
    @endif
</div>

<div class="mensagens">
    @if(isset($messages) && count($messages) > 0)
        @include('inc.list-mensagens-chat')
    @else
        <div class="sem-mensagens">
            <img src="{{ asset('img/icon-logo.png') }}" />
            <p>Escreva uma mensagem e pressione<br>enter para iniciar o atendimento</p>
        </div>
    @endif
</div>

{!! Form::open(['method' => 'post', 'action' => 'MessageController@send', 'id' => 'form-enviar-msg']) !!}
    @if(!Auth::guard('web')->check())
        <div class="lock">
            <div class="balao">
                <p>Acesse sua conta ou cadastre-se para liberar o infochat</p>
            </div>
        </div>
    @endif

    {!! Form::text('message', null, ['placeholder' => 'Digite uma mensagem']) !!}

    {!! Form::hidden('chat_id', isset($messages) ? $messages->first()->chat_id : '') !!}

    {!! Form::submit('', ['class' => 'button']) !!}
{!! Form::close() !!}
