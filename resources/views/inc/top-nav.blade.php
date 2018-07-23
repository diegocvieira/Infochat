<header class="container-fluid top-nav">
    <!--<div class="row">
        <div class="col-xs-2">
            <h1 class="logo-infochat">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('img/logo-infochat.png') }}" />
                    infochat
                </a>
            </h1>
        </div>

        <div class="col-xs-3">
            {!! Form::open(['method' => 'get', 'id' => 'form-search']) !!}
                {!! Form::text('palavra-chave', '', ['class' => 'form-control', 'placeholder' => 'Pesquise aqui']) !!}
                {!! Form::submit('') !!}
            {!! Form::close() !!}
        </div>

        <div class="col-xs-7" style="border: 1px solid red;">
            <nav class="nav navbar-nav nav-menu">
                <ul>
                    @if(Auth::guard('web')->check())
                        <li>
                            <a href="#" class="open-nav" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::guard('web')->user()->nome }}</a>

                            <ul class="dropdown-menu">
                                <li><a href="#como-funciona" class="icon-conta">Minha conta</a></li>
                                @if(!Auth::guard('web')->user()->trabalho)
                                    <li><a href="{{ action('TrabalhoController@getConfig') }}" class="icon-perfil-trabalho" id="open-trabalho-config">Perfil de trabalho</a></li>
                                @else
                                    <li><a href="#" class="icon-perfil-trabalho">Perfil de trabalhoo</a></li>
                                @endif

                                <li><a href="{{ route('usuario-logout') }}" class="icon-logout">Sair :(</a></li>
                            </ul>
                        </li>
                    @else
                        <li>
                            <a href="#" class="button">Como funciona</a>
                        </li>

                        <li>
                            <a href="#" data-toggle="modal" data-target="#modal-cadastro-usuario">Cadastrar</a>
                        </li>

                        <li>
                            <a href="#" data-toggle="modal" data-target="#modal-login-usuario">Entrar</a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>-->

    <div class="col-xs-2">
        <a href="{{ url('/') }}">
            <img src="{{ asset('img/logo-infochat.png') }}" id="logo-infochat" class="img-responsive" />
        </a>
    </div>

    <div class="col-xs-3">
        {!! Form::open(['method' => 'get', 'id' => 'form-search']) !!}
            {!! Form::text('palavra-chave', '', ['class' => 'form-control', 'placeholder' => 'Pesquise aqui']) !!}
            {!! Form::submit('') !!}
        {!! Form::close() !!}
    </div>

    <div class="col-xs-7">
        <nav class="nav navbar-nav nav-menu">
            <ul>
                @if(Auth::guard('web')->check())
                    <li>
                        <a href="#" class="open-nav" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::guard('web')->user()->nome }}</a>

                        <ul class="dropdown-menu">
                            <li><a href="{{ action('TrabalhoController@getConfig') }}" class="icon-perfil-trabalho" id="open-trabalho-config">Perfil de trabalho</a></li>

                            <li><a href="#como-funciona" class="icon-conta">Configurações</a></li>

                            <li><a href="{{ route('usuario-logout') }}" class="icon-logout">Sair :(</a></li>
                        </ul>
                    </li>
                @else
                    <li class="button">
                        <a href="#">Como funciona</a>
                    </li>

                    <li>
                        <a href="#" data-toggle="modal" data-target="#modal-cadastro-usuario">Cadastrar</a>
                    </li>

                    <li>
                        <a href="#" data-toggle="modal" data-target="#modal-login-usuario">Entrar</a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</header>
