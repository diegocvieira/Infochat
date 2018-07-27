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
                    <p>teidasodiaiosdhihoi iadhasda sidasids</p>
                    <p>teidasodiaiosdhihoi iadhasda sidasids</p>
                    <p>teidasodiaiosdhihoi iadhasda sidasids</p>

                    <p>teidasodiaiosdhihoi iadhasda sidasids</p>
                    <p>teidasodiaiosdhihoi iadhasda sidasids</p>
                    <p>teidasodiaiosdhihoi iadhasda sidasids</p>
                    <p>teidasodiaiosdhihoi iadhasda sidasids</p>
                    <p>teidasodiaiosdhihoi iadhasda sidasids</p>
                    <p>teidasodiaiosdhihoi iadhasda sidasids</p>

                    
                    @if(count($trabalhos) > 0)
                        @foreach($trabalhos as $trabalho)
                            <div class="result">
                                <div class="col-xs-2">
                                    @if($trabalho->imagem)
                                        <img class="img" src="{{ asset('uploads/perfil/' . $trabalho->imagem) }}" alt="Foto de perfil de {{ $trabalho->nome }}" />
                                    @else
                                        <img src="{{ asset('img/paisagem.png') }}" class="img sem-imagem" alt="Foto de perfil de {{ $trabalho->nome }}" />
                                    @endif
                                </div>

                                <div class="col-xs-10" style="padding-left: 30px;">
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

            <div class="col-xs-5">
                chat
            </div>

            <div class="col-xs-2">
                adsense
            </div>
        </div>
    </div>
@endsection
