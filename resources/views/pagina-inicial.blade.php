<?php
    $top_nav = true;
?>

@extends('base')

@section('content')
    <div class="container-fluid pagina-inicial full-height">
        <div class="row" style="height: 100%;">
            <?php /*<div class="col-xs-2 aside-categorias">
                @include('inc.aside-categorias')
            </div>*/ ?>

            <div class="resultados">
                @include('inc.abas-resultados')
            </div>

            <div class="chat">
                <div class="sem-mensagens">
                    <img src="{{ asset('img/icon-logo.png') }}" alt="Selecione um profissional ou estabelecimento" />
                </div>
            </div>
        </div>
    </div>
@endsection
