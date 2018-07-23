<!DOCTYPE html>
<html lang="pt-br">
    <head>
    	<meta charset="UTF-8">

    	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    	<base href="{{url('/')}}">

    	<title>{{ $header_title or 'SeuSuper - Encomende online dos melhores supermercados de Pelotas' }}</title>

    	<link rel="shortcut icon" href="img/icon-seusuper.png">

    	<meta name="theme-color" content="#43b02a">

    	<!-- SEO META TAGS -->
    	<meta name="csrf-token" content="{!! csrf_token() !!}">

    	@if(isset($header_keywords))
    		<meta name="keywords" content="{{ $header_keywords }}" />
    	@else
    		<meta name="keywords" content="seusuper, supermercados, pelotas" />
    	@endif

    	<link rel='canonical' href='{{ $header_canonical or url()->current() }}' />

    	<meta name='description' content='{{ $header_desc or 'SeuSuper - Encomende online dos melhores supermercados de Pelotas' }}' />
    	<meta itemprop='name' content='{{ $header_title or 'SeuSuper' }}'>
    	<meta itemprop='description' content='{{ $header_desc or "SeuSuper - Encomende online dos melhores supermercados de Pelotas" }}'>
    	<meta itemprop='image' content='{{ $header_image or asset('img/banner-redes-sociais.jpg') }}'>

    	<meta name='twitter:card' content='summary_large_image'>
    	<meta name='twitter:title' content='{{ $header_title or 'SeuSuper' }}'>
    	<meta name='twitter:description' content='{{ $header_desc or 'SeuSuper - Encomende online dos melhores supermercados de Pelotas' }}'>
    	<!-- imagens largas para o Twitter Summary Card precisam ter pelo menos 280x150px  -->
    	<meta name='twitter:image' content='{{ $header_image or asset('img/banner-redes-sociais.jpg')}}'>

    	<meta property='og:title' content='{{ $header_title or 'SeuSuper' }}' />
    	<meta property='og:type' content='website' />
    	<meta property='og:url' content='{{ url()->current() }}' />
    	<meta property='og:image' content='{{ $header_image or asset('img/banner-redes-sociais.jpg')}}' />
    	<meta property='og:description' content='{{ $header_desc or 'SeuSuper - Encomende online dos melhores supermercados de Pelotas' }}' />
    	<meta property='og:site_name' content='SeuSuper' />

        <style>
            body {
                opacity: 0;
            }
        </style>

    	<link href="//fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>

        <link rel="stylesheet" type="text/css" href="{{ url('css/global.css') }}"/>
    </head>
    <body class="<?php echo isset($body_class) ? $body_class : '' ?>">
        @if(session('session_flash'))
        	<div class="session-flash">
    			{!! session('session_flash') !!}
        	</div>
        @endif
