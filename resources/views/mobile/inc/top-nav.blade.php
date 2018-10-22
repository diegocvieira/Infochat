<header class="top-nav">
    <a href="{{ url('/') }}">
        <img src="{{ asset('img/logo-infochat.png') }}" id="logo-infochat" alt="Logo infochat" />
    </a>

    <nav>
        <a href="#" id="open-menu">

        @if(Auth::guard('web')->check())
            @if(Auth::guard('web')->user()->imagem)
                <img src="{{ asset('uploads/' . Auth::guard('web')->user()->id . '/' . Auth::guard('web')->user()->imagem) }}" class="logged" />
            @else
                <img src="{{ asset('img/icon-profile2.png') }}" class="logged" />
            @endif
        @else
            <img src="{{ asset('img/menu.png') }}" />
        @endif

        </a>

        <ul>
            <li>
                <a href="#" id="open-cidades" class="icon-cidades">{{ Cookie::get('sessao_cidade_title') . '/' . Cookie::get('sessao_estado_letter') }}</a>
            </li>

            <li>
                <a href="https://play.google.com/store/apps/details?id=com.infochat" class="icon-app">Baixe nosso app</a>
            </li>

            @if(Auth::guard('web')->check())
                <li>
                    <a href="{{ route('get-usuario-config') }}" id="open-usuario-config" class="icon-conta">Perfil de usuário</a>
                </li>

                <li>
                    <a href="{{ action('TrabalhoController@getConfig') }}" class="icon-perfil-trabalho" id="open-trabalho-config">Perfil de trabalho</a>
                </li>

                <li>
                    <a href="{{ route('usuario-logout') }}" class="icon-logout">Sair</a>
                </li>
            @else
                <li>
                    <a href="{{ route('como-funciona') }}" class="icon-como-funciona">Como funciona</a>
                </li>

                <li>
                    <a href="{{ route('user-register') }}" class="icon-cadastro">Cadastrar</a>
                </li>

                <li>
                    <a href="{{ route('user-login') }}" class="icon-login">Entrar</a>
                </li>
            @endif
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

        {!! Form::hidden('page', '', ['id' => 'form-search-page']) !!}
    {!! Form::close() !!}

    @include('mobile.inc.aside-categorias')
</header>
