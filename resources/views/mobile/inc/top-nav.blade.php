<header class="top-nav">
    <a href="{{ url('/') }}" id="logo-infochat">
        <img src="{{ asset('img/icon-logo.png') }}" alt="Logo infochat" />
    </a>

    <nav>
        <a href="#" id="open-menu">

        @if(Auth::guard('web')->check() && !_temporaryAccount())
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
                <a href="{{ route('cities') }}" class="icon-cidades">{{ Cookie::get('sessao_cidade_slug') ? Cookie::get('sessao_cidade_title') . '/' . Cookie::get('sessao_estado_letter') : 'Porto Alegre/RS' }}</a>
            </li>

            <li>
                <a href="https://play.google.com/store/apps/details?id=com.infochat" class="icon-app">Baixe nosso app</a>
            </li>

            @if(Auth::guard('web')->check() && !_temporaryAccount())
                <li>
                    <a href="{{ route('get-usuario-config') }}" class="icon-conta">Minha conta</a>
                </li>

                <li>
                    <a href="{{ action('TrabalhoController@getConfig') }}" class="icon-perfil-trabalho">Perfil de trabalho</a>
                </li>
            @else
                <li>
                    <a href="{{ route('about') }}" class="icon-about">Sobre</a>
                </li>

                <li>
                    <a href="#" class="icon-how-works open-how-works">Como funciona</a>
                </li>

                <li>
                    <a href="{{ route('user-register') }}" class="icon-cadastro">Cadastrar</a>
                </li>

                @if(!Auth::guard('web')->check())
                    <li data-type="login">
                        <a href="{{ route('user-login') }}" class="icon-login">Entrar</a>
                    </li>
                @endif
            @endif

            @if(Auth::guard('web')->check())
                <li>
                    <a href="{{ route('usuario-logout') }}" class="icon-logout">Sair</a>
                </li>
            @endif

            <li>
                <a href="#" id="open-contato">Contato</a>

                <a href="{{ route('termos-uso') }}" target="_blank">Termos</a>

                <a href="{{ route('termos-privacidade') }}" target="_blank">Privacidade</a>
            </li>
        </ul>
    </nav>

    <?php /*<a href="#" id="open-search"></a>*/ ?>

    {!! Form::open(['method' => 'post', 'id' => 'form-search', 'action' => 'TrabalhoController@formBusca']) !!}
        <?php /*<a href="#" class="close-form-search"></a>*/ ?>

        {!! Form::text('palavra_chave', isset($palavra_chave) ? $palavra_chave : '', ['class' => 'form-control', 'id' => 'form-search-palavra-chave', 'placeholder' => 'Pesquise aqui', 'autocomplete' => 'off']) !!}

        <?php /*{!! Form::hidden('area', isset($area) ? $area : '', ['id' => 'form-search-area']) !!}
        {!! Form::hidden('tag', isset($tag) ? $tag : '', ['id' => 'form-search-tag']) !!}
        {!! Form::hidden('tipo', isset($tipo) ? $tipo : 'todos', ['id' => 'form-search-tipo']) !!}

        {!! Form::hidden('ordem', isset($ordem) ? $ordem : '', ['id' => 'form-search-ordem']) !!}

        {!! Form::hidden('page', '', ['id' => 'form-search-page']) !!}*/ ?>
    {!! Form::close() !!}
</header>
