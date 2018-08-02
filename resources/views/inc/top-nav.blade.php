<header class="container-fluid top-nav">
    <div class="col-xs-2">
        <a href="{{ url('/') }}">
            <img src="{{ asset('img/logo-infochat.png') }}" id="logo-infochat" class="img-responsive" />
        </a>
    </div>

    <div class="col-xs-3">
        {!! Form::open(['method' => 'post', 'id' => 'form-search', 'action' => 'TrabalhoController@formBusca']) !!}
            {!! Form::text('palavra_chave', (isset($palavra_chave) && $palavra_chave != 'area') ? $palavra_chave : '', ['class' => 'form-control', 'id' => 'form-search-palavra-chave', 'placeholder' => 'Pesquise aqui']) !!}

            {!! Form::hidden('area', isset($area) ? $area : '', ['id' => 'form-search-area']) !!}
            {!! Form::hidden('tag', isset($tag) ? $tag : '', ['id' => 'form-search-tag']) !!}
            {!! Form::hidden('tipo', isset($tipo) ? $tipo : 'todos', ['id' => 'form-search-tipo']) !!}

            {!! Form::hidden('ordem', isset($ordem) ? $ordem : '', ['id' => 'form-search-ordem']) !!}

            {!! Form::hidden('offset', '', ['id' => 'form-search-offset']) !!}

            {!! Form::submit('') !!}
        {!! Form::close() !!}
    </div>

    <div class="col-xs-7">
        <nav class="nav navbar-nav nav-menu">
            <ul>
                @if(Auth::guard('web')->check())
                    <li>
                        <a href="#" class="open-nav" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ strstr(Auth::guard('web')->user()->nome, ' ', true) }}</a>

                        <ul class="dropdown-menu">
                            <li><a href="{{ action('TrabalhoController@getConfig') }}" class="icon-perfil-trabalho" id="open-trabalho-config">Perfil de trabalho</a></li>

                            <li><a href="#" class="icon-conta">Configurações</a></li>

                            <li><a href="{{ route('usuario-logout') }}" class="icon-logout">Sair :(</a></li>
                        </ul>
                    </li>
                @else
                    <li class="button">
                        <a href="#" data-toggle="modal" data-target="#modal-como-funciona">Como funciona</a>
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
