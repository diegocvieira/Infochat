<?php
    $header_title = 'Cadastrar | Infochat';
    $top_simple = true;
?>

@extends('base')

@section('content')
    <div class="container page-user-register">
        {!! Form::open(['method' => 'post', 'action' => 'UserController@create', 'id' => 'form-user-register']) !!}
            <h1>Bem-vindo!</h1>

            <h2>Cadastre-se totalmente grátis</h2>

            {!! Form::text('nome', '', ['placeholder' => 'Nome', 'class' => 'form-control', 'required', 'maxlength' => '62']) !!}

            {!! Form::email('email', '', ['placeholder' => 'E-mail', 'class' => 'form-control', 'required', 'maxlength' => '62']) !!}

            <div class="passwords">
                {!! Form::input('password', 'password', '', ['placeholder' => 'Senha', 'class' => 'form-control', 'id' => 'senha-usuario', 'required']) !!}

                {!! Form::input('password', 'password_confirmation', '', ['placeholder' => 'Confirmar senha', 'class' => 'form-control', 'required']) !!}
            </div>

            {!! Form::submit('Cadastrar', ['class' => 'form-control btn btn-primary']) !!}

            <p class="termos">Ao se cadastrar você concorda com os <a href="{{ route('termos-uso') }}" target="_blank">termos de uso</a><br>e a <a href="{{ route('termos-privacidade') }}" target="_blank">política de privacidade</a> do infochat.com.br</p>
        {!! Form::close() !!}
    </div>
@endsection
