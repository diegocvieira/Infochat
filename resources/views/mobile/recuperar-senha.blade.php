<?php
    $top_simple = true;
    $top_nav_title = 'Recuperar senha';
?>

@extends('mobile.base')

@section('content')
    <div class="container pagina-recuperar-senha">
        {!! Form::open(['method' => 'post', 'id' => 'form-recuperar-senha', 'action' => 'RecuperarSenhaController@alterar']) !!}
            {!! Form::hidden('email', $email) !!}

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
