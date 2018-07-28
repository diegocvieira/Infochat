<?php
    $top_nav = true;
?>

@extends('base')

@section('content')
    <div class="container-fluid pagina-inicial full-height">
        <div class="row" style="height: 100%;">
            @include('inc.aside-categorias')

            <div class="col-xs-3 resultados">
                @include('inc.abas-resultados')

                <div id="form-search-results"></div>
            </div>

            <div class="col-xs-5 chat"></div>

            <div class="col-xs-2">
                adsense
            </div>
        </div>
    </div>
@endsection
