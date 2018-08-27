<?php
    $header_title = 'Entrar | Infochat';
    $top_simple = true;
?>

@extends('base')

@section('content')
    <div class="container page-user-login">
        {!! Form::open(['method' => 'post', 'action' => 'UserController@login', 'id' => 'form-user-login']) !!}
            <h1>Bem-vindo de volta!</h1>

            <h2>Acesse seu perfil para começar</h2>

            {!! Form::email('email', '', ['placeholder' => 'E-mail', 'class' => 'form-control', 'required']) !!}

            {!! Form::input('password', 'password', '', ['placeholder' => 'Senha', 'class' => 'form-control', 'required']) !!}

            {!! Form::submit('Entrar', ['class' => 'form-control btn btn-primary']) !!}

            <a href="#" id="recuperar-senha">Recuperar senha</a>
        {!! Form::close() !!}
    </div>
@endsection
