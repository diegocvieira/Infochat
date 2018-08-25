<!DOCTYPE html>
<html lang="pt-br">
    <head>
    	<meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

    	<base href="{{ url('/') }}">

    	<title>{{ $header_title or 'Infochat - O atendimento online da sua cidade' }}</title>

    	<link rel="shortcut icon" href="img/favicon.ico">

    	<meta name="theme-color" content="#1a276d">

    	<!-- SEO META TAGS -->
        <meta name="google-site-verification" content="QmIWiV6myEkzAONZs45zJROviJCSlA-6kSCE4bQjTNY" />
    	<meta name="csrf-token" content="{!! csrf_token() !!}">

    	@if(isset($header_keywords))
    		<meta name="keywords" content="{{ $header_keywords }}" />
    	@else
    		<meta name="keywords" content="infochat, estabelecimentos, profissionais, atendimento" />
    	@endif

    	<link rel='canonical' href='{{ $header_canonical or url()->current() }}' />

    	<meta name='description' content='{{ $header_desc or 'Infochat - O atendimento online da sua cidade' }}' />
    	<meta itemprop='name' content='{{ $header_title or 'Infochat' }}'>
    	<meta itemprop='description' content='{{ $header_desc or "Infochat - O atendimento online da sua cidade" }}'>
    	<meta itemprop='image' content='{{ $header_image or asset('img/banner.png') }}'>

    	<meta name='twitter:card' content='summary_large_image'>
    	<meta name='twitter:title' content='{{ $header_title or 'Infochat' }}'>
    	<meta name='twitter:description' content='{{ $header_desc or 'v' }}'>
    	<!-- imagens largas para o Twitter Summary Card precisam ter pelo menos 280x150px  -->
    	<meta name='twitter:image' content='{{ $header_image or asset('img/banner.png')}}'>

    	<meta property='og:title' content='{{ $header_title or 'Infochat' }}' />
    	<meta property='og:type' content='website' />
    	<meta property='og:url' content='{{ url()->current() }}' />
    	<meta property='og:image' content='{{ $header_image or asset('img/banner.png')}}' />
    	<meta property='og:description' content='{{ $header_desc or 'Infochat - O atendimento online da sua cidade' }}' />
    	<meta property='og:site_name' content='Infochat' />

        <style>
            body { opacity: 0; }
        </style>

    	<link href="//fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

        @if($app->environment('local'))
            <link rel="stylesheet" href="{{ asset('offline-developer/bootstrap.min.css') }}">
            <link rel="stylesheet" href="{{ asset('offline-developer/bootstrap-select.min.css') }}">
        @else
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>
        @endif

        <link rel="stylesheet" type="text/css" href="{{ mix('/css/mobile-global.css') }}"/>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-86559553-2"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-86559553-2');
        </script>
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>
          (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-7367829499171206",
            enable_page_level_ads: true
          });
        </script>
    </head>
    <body class="<?php echo isset($body_class) ? $body_class : '' ?>">
        @if(session('session_flash'))
        	<div class="session-flash">
    			{!! session('session_flash') !!}
        	</div>
        @endif
