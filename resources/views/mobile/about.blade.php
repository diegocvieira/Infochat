<?php
    $header_title = 'Sobre | Infochat';
    $top_simple = true;
    $top_nav_title = 'Sobre';
?>

@extends('mobile.base')

@section('content')
    <div class="container page-slider page-about">
        <div class="slider" id="slider">
            <div class="holder">
                @for($i = 1; $i <= 5; $i++)
                    <div class="slide-wrapper">
                        <img src="{{ asset('img/about-mobile/' . $i . '.png') }}" alt="{{ $i }}º imagem do sobre nós" />
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection
