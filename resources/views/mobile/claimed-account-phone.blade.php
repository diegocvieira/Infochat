<?php
    $top_simple = true;
    $top_nav_title = 'Reivindicar perfil';
?>

@extends('mobile.base')

@section('content')
    <div class="container pagina-recuperar-senha">
        {!! Form::open(['method' => 'post', 'id' => 'form-recuperar-senha', 'route' => 'claimed-phone']) !!}
            <h1>Reivindicar perfil</h1>

            <p>Para responder o cliente, informe o mesmo celular que recebeu o aviso no whatsapp</p>

            {!! Form::text('phone', null, ['required', 'placeholder' => 'Celular', 'class' => 'fone-mask']) !!}

            {!! Form::email('email', null, ['required', 'placeholder' => 'E-mail']) !!}

            {!! Form::input('password', 'password', null, ['required', 'placeholder' => 'Criar senha', 'class' => 'half']) !!}

            {!! Form::input('password', 'password_confirmation', null, ['required', 'placeholder' => 'Repetir senha', 'class' => 'half']) !!}

            {!! Form::submit('Reivindicar perfil') !!}

            @if($errors)
                <div class="errors">
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif
        {!! Form::close() !!}
    </div>
@endsection
