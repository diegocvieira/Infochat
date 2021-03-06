<div class="topo-chat">
    {!! Form::hidden('user_id', $destinatario_id, ['id' => 'user_id']) !!}

    <a class="show-work" href="{{ $tipo == 'trabalho' ? route('show-work-desktop', $destinatario->slug) : '#' }}" onclick="{{ $tipo != 'trabalho' ? 'return false;' : '' }}">
        <div class="imagem">
            @if($destinatario->imagem)
                <img src="{{ asset('uploads/' . $destinatario_id . '/' . $destinatario->imagem) }}" alt="Foto de perfil de {{ $destinatario->nome }}" />
            @else
                <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $destinatario->nome }}" />
            @endif
        </div>

        <div class="name">
            <h3 class="{{ $tipo == 'trabalho' ? 'margin' : '' }}">{{ $destinatario->nome }}</h3>

            @if($tipo == 'trabalho')
                <span class="perfil">ver perfil</span>
            @endif
        </div>
    </a>

    @if($tipo == 'trabalho' && $destinatario->user->online || $tipo == 'pessoal' && $destinatario->online)
        <span class="online {{ $tipo == 'trabalho' ? 'margin' : '' }}">online</span>
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
        </div>
    @endif
</div>

{!! Form::open(['method' => 'post', 'action' => 'MessageController@send', 'id' => 'form-enviar-msg']) !!}
    <?php /*@if(!Auth::guard('web')->check())
        <div class="lock">
            <div class="balao">
                <p>Acesse sua conta ou cadastre-se para liberar o infochat</p>
            </div>
        </div>
    @endif*/ ?>

    @if(!Auth::guard('web')->check())
        {!! Form::text('name', null, ['placeholder' => 'Nome', 'class' => 'name']) !!}
    @endif

    {!! Form::text('message', null, ['class' => 'message', 'placeholder' => 'Envie sua mensagem']) !!}

    {!! Form::hidden('chat_id', isset($chat_id) ? $chat_id : '') !!}
    {!! Form::hidden('work_user', $tipo == 'trabalho' ? $destinatario->user->id : '') !!}

    {!! Form::submit('', ['class' => 'button']) !!}
{!! Form::close() !!}
