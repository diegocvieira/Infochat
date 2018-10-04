<?php
    $body_class = 'pagina-recuperar-senha';
    $top_simple = true;
?>

@extends('base')

@section('content')
    <div class="container">
        {!! Form::open(['method' => 'post', 'id' => 'form-recuperar-senha', 'action' => 'ClaimedController@claimedAccount']) !!}
            {!! Form::hidden('email', $email) !!}

            <h1>Reivindicar perfil</h1>

            <p>Cadastre uma nova senha abaixo</p>

            <span class="email">{{ $email }}</span>

            {!! Form::input('password', 'password', null, ['required', 'placeholder' => 'Senha']) !!}

            {!! Form::input('password', 'password_confirmation', null, ['required', 'placeholder' => 'Repetir senha']) !!}

            {!! Form::submit('Reivindicar perfil') !!}

            @if($errors)
                <div class="errors">
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif
        {!! Form::close() !!}
    </div>
@endsection
