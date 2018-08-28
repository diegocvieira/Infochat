<?php
    $top_nav = true;
?>

@extends('mobile.base')

@section('content')
    <div class="container pagina-inicial">
        <div class="resultados">
            @include('mobile.inc.abas-resultados')
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            $('.result:first').trigger('tap');
        });
    </script>
@endsection
