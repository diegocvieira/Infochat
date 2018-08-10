<?php
    $top_nav = true;
?>

@extends('base')

@section('content')
    <div class="container-fluid pagina-inicial full-height">
        <div class="row" style="height: 100%;">
            <div class="col-xs-2 aside-categorias">
                @include('inc.aside-categorias')
            </div>

            <div class="col-xs-3 resultados">
                @include('inc.abas-resultados')
            </div>

            <div class="col-xs-5 chat">
                <div class="sem-selecao">
                    <img src="{{ asset('img/icon-logo.png') }}" />

                    @if(Auth::guard('web')->check())
                        <p>Selecione um profissional ou estabelecimento<br>para pedir informações ou tirar dúvidas</p>
                    @else
                        <p>Acesse sua conta e selecione um profissional<br>ou estabelecimento para ser atendido</p>
                    @endif
                </div>
            </div>

            <div class="col-xs-2">
                
            </div>
        </div>
    </div>
@endsection
