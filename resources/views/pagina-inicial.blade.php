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

            <div class="col-xs-5 chat"></div>

            <div class="col-xs-2">
                adsense
            </div>
        </div>
    </div>
@endsection
