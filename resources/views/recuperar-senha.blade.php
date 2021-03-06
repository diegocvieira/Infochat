<?php
    $body_class = 'pagina-recuperar-senha';
    $top_simple = true;
?>

@extends('base')

@section('content')
    <div class="container">
        {!! Form::open(['method' => 'post', 'id' => 'form-recuperar-senha', 'action' => 'RecuperarSenhaController@alterar']) !!}
            {!! Form::hidden('email', $email) !!}

            <h1>Recuperar acesso</h1>

            <p>Cadastre uma nova senha abaixo</p>

            {!! Form::input('password', 'password', null, ['required', 'placeholder' => 'Senha']) !!}

            {!! Form::input('password', 'password_confirmation', null, ['required', 'placeholder' => 'Repetir senha']) !!}

            {!! Form::submit('Enviar') !!}

            @if($errors)
                <div class="errors">
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif
        {!! Form::close() !!}
    </div>
@endsection
