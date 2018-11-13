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
                    <div class="slide-wrapper banner{{ $i }}">
                        <p>
                            @if($i == 1)
                                O infochat é o atendimento<br>online dos profissionais<br>e estabelecimentos<br>da sua cidade
                            @elseif($i == 2)
                                Você pode pedir<br>informações e tirar suas<br>dúvidas de um jeito rápido e fácil
                            @elseif($i == 3)
                                1. Encontre o estabelecimento ou<br>o profissional que você procura<br><br>2. Envie uma mensagem e<br>aguarde o atendimento
                            @elseif($i == 4)
                                Você também pode usar o<br>infochat no seu computador
                            @else
                                E ainda pode ativar seu perfil<br>de trabalho, para atender<br>seus clientes online<br><br><i>É totalmente grátis!</i>
                            @endif
                        </p>

                        <img src="{{ asset('img/about-mobile/' . $i . '.png') }}" alt="{{ $i }}º imagem do sobre nós" />

                        @if($i == 4)
                            <img class="note" src="{{ asset('img/about-mobile/note.png') }}" alt="{{ $i }}º imagem do sobre nós" />
                        @endif

                        <div class="deslizar"><span class="text">Deslize</span><span class="arrow right"></span></div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection
