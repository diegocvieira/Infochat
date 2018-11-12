<?php
    $header_title = 'Como funciona do usuário | Infochat';
    $top_simple = true;
    $top_nav_title = 'Usuário';
?>

@extends('mobile.base')

@section('content')
    <div class="container page-slider">
        <div class="slider" id="slider">
            <div class="holder">
                @for($i = 1; $i <= 7; $i++)
                    <div class="slide-wrapper">
                        <img src="{{ asset('img/how-works-mobile/user/' . $i . '.png') }}" alt="{{ $i }}º imagem do como funciona" />
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection
