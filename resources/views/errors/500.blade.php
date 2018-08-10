<?php
    $body_class = 'pagina-error';
    $top_simple = true;
?>

@extends('base')

@section('content')
    <div class="container">
        <img src="{{ asset('img/error.jpg') }}" />
        <h3>Acho que alguém anda comendo as páginas</h3>
        <p>500 - Página não encontrada</p>
        <a href="{{ url('/') }}">Voltar para o site</a>
    </div>
@endsection
