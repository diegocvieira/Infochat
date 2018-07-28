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

                <div id="form-search-results">
                    @if(count($trabalhos) > 0)
                        @foreach($trabalhos as $trabalho)
                            <div class="result">
                                <div class="col-xs-2">
                                    <div class="imagem">
                                        @if($trabalho->imagem)
                                            <img src="{{ asset('uploads/perfil/' . $trabalho->imagem) }}" alt="Foto de perfil de {{ $trabalho->nome }}" />
                                        @else
                                            <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $trabalho->nome }}" />
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-10">
                                    <h1>{{ $trabalho->nome }}</h1>

                                    <div class="tags">
                                        @foreach($trabalho->tags as $t)
                                            <p><span>-</span> {{ $t->tag }}</p>
                                        @endforeach
                                    </div>

                                    <a href="#" class="ver-perfil" data-id="{{ $trabalho->id }}">ver perfil</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="sem-resultados">
                            <p>Sua pesquisa não encontrou resultado.<br>Verifique se todas as palavras estão corretas ou tente palavras-chave diferentes.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-xs-5 chat"></div>

            <div class="col-xs-2">
                adsense
            </div>
        </div>
    </div>
@endsection
