<?php
    $header_title = 'Como funciona | Infochat';
    $top_simple = true;
    $top_nav_title = 'Como funciona';
?>

@extends('mobile.base')

@section('content')
    <div class="container pagina-como-funciona">

        <div class="slider" id="slider">
            <div class="holder">
                <div class="slide-wrapper banner1">
                    <p>O infochat é o atendimento<br>online dos profissionais<br>e estabelecimentos<br>da sua cidade</p>

                    <img class="slide-dimage" src="{{ asset('img/mobile-como-funciona/1.png') }}" />

                    <div class="deslizar"><span class="text">Deslize</span><span class="arrow right"></span></div>
                </div>

                <div class="slide-wrapper banner2">
                    <p>Você pode pedir<br>informações e tirar suas<br>dúvidas de um jeito rápido e fácil</p>

                    <img class="slide-dimage" src="{{ asset('img/mobile-como-funciona/2.png') }}" />

                    <div class="deslizar"><span class="arrow left"></span><span class="text">Deslize</span><span class="arrow right"></span></div>
                </div>

                <div class="slide-wrapper banner3">
                    <p>1. Encontre o estabelecimento ou<br>o profissional que você procura<br><br>2. Envie uma mensagem e<br>aguarde o atendimento</p>

                    <img class="slide-dimage" src="{{ asset('img/mobile-como-funciona/3.png') }}" />

                    <div class="deslizar"><span class="arrow left"></span><span class="text">Deslize</span><span class="arrow right"></span></div>
                </div>

                <div class="slide-wrapper banner4">
                    <p>Você também pode usar o<br>infochat no seu computador</p>

                    <img class="slide-dimage" src="{{ asset('img/mobile-como-funciona/4.png') }}" />

                    <div class="deslizar"><span class="arrow left"></span><span class="text">Deslize</span><span class="arrow right"></span></div>
                </div>

                <div class="slide-wrapper banner5">
                    <p>E ainda pode ativar seu perfil<br>de trabalho para atender<br>seus clientes online<br><br><i>É totalmente grátis!</i></p>

                    <img class="slide-dimage" src="{{ asset('img/mobile-como-funciona/5.png') }}" />

                    <div class="deslizar"><span class="arrow left"></span><span class="text">Deslize</span></div>
                </div>
            </div>
        </div>

    </div>
@endsection
