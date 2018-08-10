<?php
    $body_class = 'pagina-recuperar-senha';
?>

@extends('base')

@section('content')
    <div class="container">
        <div class="top-senha">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo-infochat.png') }}" />
            </a>
        </div>

        {!! Form::open(['method' => 'post', 'id' => 'form-recuperar-senha', 'action' => 'RecuperarSenhaController@alterar']) !!}
            {!! Form::hidden('email', $email) !!}

            <p>Cadastre uma senha nova.</p>

            {!! Form::input('password', 'password', null, ['required', 'placeholder' => 'senha']) !!}

            {!! Form::input('password', 'password_confirmation', null, ['required', 'placeholder' => 'confirmar senha']) !!}

            {!! Form::submit('Enviar') !!}

            @if($errors)
                <div class="errors">
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif
        {!! Form::close() !!}
    </div>
@endsection
