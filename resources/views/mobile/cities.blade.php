<?php
    $header_title = 'Trocar cidade | Infochat';
?>

@extends('mobile.base')

@section('content')
    <div class="page-cities">
        {!! Form::open(['action' => 'GlobalController@getCidade', 'id' => 'form-search-city', 'method' => 'POST']) !!}
            <a href="{{ url('/') }}" class="back-link"></a>

            {!! Form::text('nome_cidade', '', ['placeholder' => 'Pesquisar cidade', 'autofocus']) !!}
        {!! Form::close() !!}
    </div>
@endsection
