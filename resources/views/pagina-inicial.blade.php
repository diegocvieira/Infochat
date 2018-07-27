<?php
    $top_nav = true;
?>

@extends('base')

@section('content')
    <div class="container-fluid pagina-inicial" style="height: calc(100% - 70px);">
        <div class="row" style="height: 100%;">
            @include('inc.aside-categorias')

            <div class="col-xs-3 resultados">
                <div class="abas-resultados">
                    <a href="#" data-type="resultado" class="active">RESULTADO</a>
                    <a href="#" data-type="pessoal">PESSOAL</a>
                    <a href="#" data-type="trabalho">TRABALHO</a>
                </div>

                <div id="form-search-results" style="border: 1px solid red; height: 200px; overflow: auto">
                </div>
            </div>

            <div class="col-xs-5">
                chat
            </div>

            <div class="col-xs-2">
                adsense
            </div>
        </div>
    </div>
@endsection
