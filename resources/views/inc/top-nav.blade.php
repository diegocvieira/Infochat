<header class="top-nav">
    <a href="{{ url('/') }}" id="logo-infochat">
        <img src="{{ asset('img/logo-infochat.png') }}" class="img-responsive" alt="Logo infochat" />
    </a>

    <div class="cidades">
        <div class="cidade-atual">Você está em <span>{{ Cookie::get('sessao_cidade_title') . '/' . Cookie::get('sessao_estado_letter') }}</span></div>

        {!! Form::open(['action' => 'GlobalController@getCidade', 'method' => 'post', 'id' => 'form-busca-cidade']) !!}
            {!! Form::text('nome_cidade', '', ['placeholder' => 'Digite sua cidade', 'autocomplete' => 'off']) !!}

            {!! Form::submit('') !!}
        {!! Form::close() !!}
    </div>

    <nav class="nav navbar-nav nav-menu">
        <ul>
            @if(Auth::guard('web')->check())
                <li>
                    <a href="#" class="open-nav" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
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
                            <a href="{{ route('get-usuario-config') }}" id="open-usuario-config" class="icon-conta">Perfil de usuário</a>
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
                    </ul>
                </li>
            @else
                <li>
                    <a href="https://play.google.com/store/apps/details?id=com.infochat" target="_blank">Baixe nosso app</a>
                </li>

                <li>
                    <a href="#" data-toggle="modal" data-target="#modal-como-funciona">Como funciona</a>
                </li>

                <li class="button">
                    <a href="{{ route('user-register') }}">Cadastrar</a>
                </li>

                <li>
                    <a href="{{ route('user-login') }}">Entrar</a>
                </li>
            @endif
        </ul>
    </nav>
</header>

@if(session('session_flash_como_funciona'))
    @section('script')
        <script>
            $(function() {
                $('#modal-como-funciona').modal('show');
            });
        </script>
    @endsection
@endif
