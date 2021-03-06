<header class="top-nav">
    <a href="{{ url('/') }}" id="logo-infochat">
        <img src="{{ asset('img/logo-infochat.png') }}" class="img-responsive" alt="Logo infochat" />
    </a>

    <div class="cidades">
        <div class="cidade-atual">Você está em <span>{{ Cookie::get('sessao_cidade_slug') ? Cookie::get('sessao_cidade_title') . '/' . Cookie::get('sessao_estado_letter') : 'Porto Alegre/RS' }}</span></div>

        {!! Form::open(['action' => 'GlobalController@getCidade', 'method' => 'post', 'id' => 'form-busca-cidade']) !!}
            {!! Form::text('nome_cidade', '', ['placeholder' => 'Digite sua cidade', 'autocomplete' => 'off']) !!}

            {!! Form::submit('') !!}
        {!! Form::close() !!}
    </div>

    <nav class="nav navbar-nav nav-menu">
        <ul>
            @if(Auth::guard('web')->check() && !_temporaryAccount())
                <li>
                    <a href="#" class="open-nav-logged" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span>{{ firstName(Auth::guard('web')->user()->nome) }}</span>

                        <div class="img">
                            @if(Auth::guard('web')->user()->imagem)
                                <img src="{{ asset('uploads/' . Auth::guard('web')->user()->id . '/' . Auth::guard('web')->user()->imagem) }}" alt="Foto de perfil de {{ Auth::guard('web')->user()->nome }}" />
                            @else
                                <img src="{{ asset('img/icon-profile2.png') }}" class="sem-imagem" alt="Foto de perfil de {{ Auth::guard('web')->user()->nome }}" />
                            @endif
                        </div>
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ route('get-usuario-config') }}" id="open-usuario-config" class="icon-conta">Minha conta</a>
                        </li>

                        <li>
                            <a href="{{ action('TrabalhoController@getConfig') }}" class="icon-perfil-trabalho" id="open-trabalho-config">Perfil de trabalho</a>
                        </li>

                        @if(Auth::guard('web')->user()->trabalho && Auth::guard('web')->user()->trabalho->status)
                            <li>
                                <a href="{{ route('material-preview') }}" class="icon-material" id="open-material">Divulgar perfil</a>
                            </li>
                        @endif

                        <li>
                            <a href="{{ route('usuario-logout') }}" class="icon-logout">Sair</a>
                        </li>

                        <li>
                            <a href="#" id="open-contato">Contato</a>

                            <a href="{{ route('termos-uso') }}" target="_blank">Termos</a>

                            <a href="{{ route('termos-privacidade') }}" target="_blank">Privacidade</a>
                        </li>
                    </ul>
                </li>
            @else
                <li>
                    <a href="#" data-type="about" class="open-modal-slider">Sobre</a>
                </li>

                <li>
                    <a href="#" data-toggle="dropdown" class="open-how-works" role="button" aria-haspopup="true" aria-expanded="false">Como funciona</a>

                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" data-type="user" class="open-modal-slider">Para o usuário</a>
                        </li>

                        <li>
                            <a href="#" data-type="work" class="open-modal-slider">Para o profissional</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="https://play.google.com/store/apps/details?id=com.infochat" target="_blank">Baixe nosso app</a>
                </li>

                <li class="button">
                    <a href="{{ route('user-register') }}">Cadastrar</a>
                </li>

                @if(Auth::guard('web')->check() && _temporaryAccount())
                    <li>
                        <a href="{{ route('usuario-logout') }}" class="icon-logout">Sair</a>
                    </li>
                @else
                    <li data-type="login">
                        <a href="{{ route('user-login') }}">Entrar</a>
                    </li>
                @endif

                <li>
                    <a href="#" class="open-nav" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset('img/menu.png') }}" alt="Menu" />
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" id="open-contato">Contato</a>
                        </li>

                        <li>
                            <a href="{{ route('termos-uso') }}" target="_blank">Termos</a>
                        </li>

                        <li>
                            <a href="{{ route('termos-privacidade') }}" target="_blank">Privacidade</a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </nav>
</header>

@if(session('session_flash_slider'))
    @section('script')
        <script>
            $(function() {
                $(".open-modal-slider[data-type={{ session('session_flash_slider') }}]").trigger('click');
            });
        </script>
    @endsection
@endif
