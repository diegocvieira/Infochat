<header class="top-nav">
    <a href="{{ url('/') }}">
        <img src="{{ asset('img/logo-infochat.png') }}" id="logo-infochat" />
    </a>

    <nav>
        @if(Auth::guard('web')->check())
            <a href="#" id="open-menu" class="logged"></a>
        @else
            <a href="#" id="open-menu"></a>
        @endif

        <ul>
            <li>
                <a href="#" id="open-cidades" class="icon-cidades">Trocar cidade</a>
            </li>

            <li>
                <a href="#" id="open-aside" class="icon-categorias">Categorias</a>
            </li>

            @if(Auth::guard('web')->check())
                <li>
                    <a href="{{ action('TrabalhoController@getConfig') }}" class="icon-perfil-trabalho" id="open-trabalho-config">Perfil de trabalho</a>
                </li>

                <li>
                    <a href="{{ route('get-usuario-config') }}" id="open-usuario-config" class="icon-conta">Configurações</a>
                </li>

                <li>
                    <a href="{{ route('usuario-logout') }}" class="icon-logout">Sair :(</a>
                </li>
            @else
                <li>
                    <a href="{{ route('como-funciona') }}" class="icon-como-funciona">Como funciona</a>
                </li>

                <li>
                    <a href="#" data-toggle="modal" data-target="#modal-cadastro-usuario" class="icon-cadastro">Cadastrar</a>
                </li>

                <li>
                    <a href="#" data-toggle="modal" data-target="#modal-login-usuario" class="icon-login">Entrar</a>
                </li>
            @endif

            <li class="termos">
                <a href="#" id="open-contato">Contato</a>
                <span>-</span>
                <a href="{{ route('termos-uso') }}" target="_blank">Termos</a>
                <span>-</span>
                <a href="{{ route('termos-privacidade') }}" target="_blank">Privacidade</a>
            </li>
        </ul>
    </nav>

    <a href="#" id="open-search"></a>

    {!! Form::open(['method' => 'post', 'id' => 'form-search', 'action' => 'TrabalhoController@formBusca']) !!}
        <a href="#" class="close-form-search"></a>

        {!! Form::text('palavra_chave', (isset($palavra_chave) && $palavra_chave != 'area') ? $palavra_chave : '', ['class' => 'form-control', 'id' => 'form-search-palavra-chave', 'placeholder' => 'Pesquisar...']) !!}

        {!! Form::hidden('area', isset($area) ? $area : '', ['id' => 'form-search-area']) !!}
        {!! Form::hidden('tag', isset($tag) ? $tag : '', ['id' => 'form-search-tag']) !!}
        {!! Form::hidden('tipo', isset($tipo) ? $tipo : 'todos', ['id' => 'form-search-tipo']) !!}

        {!! Form::hidden('ordem', isset($ordem) ? $ordem : '', ['id' => 'form-search-ordem']) !!}

        {!! Form::hidden('offset', '', ['id' => 'form-search-offset']) !!}
    {!! Form::close() !!}

    @include('mobile.inc.aside-categorias')
</header>
