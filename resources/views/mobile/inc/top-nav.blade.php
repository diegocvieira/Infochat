<header class="top-nav">
    <a href="{{ url('/') }}">
        <img src="{{ asset('img/logo-infochat.png') }}" id="logo-infochat" />
    </a>

    <nav>
        <a href="#" id="open-menu"></a>

        <ul>
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
</header>
