@extends('mobile.base')

@section('content')
    <div class="chat">
        {!! Form::hidden('user_id', $destinatario_id, ['id' => 'user_id']) !!}

        <div class="top-page">
            <a href="javascript:history.back()" class="back-arrow"></a>

            <a href="{{ $tipo == 'trabalho' ? route('show-work', $destinatario->slug) : '#' }}" onclick="{{ $tipo != 'trabalho' ? 'return false;' : '' }}">
                <div class="imagem {{ !$destinatario->imagem ? 'border' : '' }}">
                    @if($destinatario->imagem)
                        <img src="{{ asset('uploads/' . $destinatario_id . '/' . $destinatario->imagem) }}" alt="Foto de perfil de {{ $destinatario->nome }}" />
                    @else
                        <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $destinatario->nome }}" />
                    @endif
                </div>

                <div class="title-status">
                    <h3 class="title {{ ($tipo == 'trabalho' || $tipo == 'pessoal' && $destinatario->online) ? 'margin' : '' }}">{{ $destinatario->nome }}</h3>

                    <span>
                        @if($tipo == 'trabalho')
                            ver perfil
                        @endif

                        @if(($tipo == 'trabalho' && $destinatario->user->online || $tipo == 'pessoal' && $destinatario->online) && $tipo == 'trabalho')
                            -
                        @endif

                        @if($tipo == 'trabalho' && $destinatario->user->online || $tipo == 'pessoal' && $destinatario->online)
                            online
                        @endif
                    </span>
                </div>
            </a>

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
            @if(isset($messages) && count($messages) > 0)
                @include('inc.list-mensagens-chat')
            @else
                <div class="sem-mensagens">
                    <img src="{{ asset('img/icon-logo.png') }}" alt="Escreva uma mensagem" />
                </div>
            @endif
        </div>

        {!! Form::open(['method' => 'post', 'action' => 'MessageController@send', 'id' => 'form-enviar-msg']) !!}
            {!! Form::text('message', null, ['class' => !Auth::guard('web')->check() ? 'unlogged' : '', 'placeholder' => Auth::guard('web')->check() ? 'Envie uma mensagem para começar' : 'Escreva seu nome antes de começar', 'autofocus', 'autocomplete' => 'off']) !!}

            {!! Form::hidden('chat_id', isset($chat_id) ? $chat_id : '') !!}
            {!! Form::hidden('work_user', $tipo == 'trabalho' ? $destinatario->user->id : '') !!}

            {!! Form::submit('', ['class' => 'button']) !!}
        {!! Form::close() !!}
    </div>
@endsection
