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
                    <img src="{{ asset('img/icon-logo.png') }}" alt="Selecione um profissional ou estabelecimento" />

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

@if(session('session_flash_cidade_fechada'))
    @section('script')
        <script>
            $(function() {
                var modal = $('#modal-alert');
                modal.find('.modal-body').html('Ainda não estamos operando nesta cidade.' + "<br>" + 'Volte outro dia, estamos trabalhando para levar o infochat para o mundo todo.');
                modal.find('.modal-footer .btn').text('OK');
                modal.modal('show');
            });
        </script>
    @endsection
@endif
