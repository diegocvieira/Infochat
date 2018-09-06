<header class="container-fluid top-nav">
    <div class="col-xs-2">
        <a href="{{ url('/') }}">
            <img src="{{ asset('img/logo-infochat.png') }}" id="logo-infochat" class="img-responsive" alt="Logo infochat" />
        </a>
    </div>

    <div class="col-xs-3">
        {!! Form::open(['method' => 'post', 'id' => 'form-search', 'action' => 'TrabalhoController@formBusca']) !!}
            {!! Form::text('palavra_chave', (isset($palavra_chave) && $palavra_chave != 'area') ? $palavra_chave : '', ['class' => 'form-control', 'id' => 'form-search-palavra-chave', 'placeholder' => 'Pesquise aqui', 'autocomplete' => 'off']) !!}

            {!! Form::hidden('area', isset($area) ? $area : '', ['id' => 'form-search-area']) !!}
            {!! Form::hidden('tag', isset($tag) ? $tag : '', ['id' => 'form-search-tag']) !!}
            {!! Form::hidden('tipo', isset($tipo) ? $tipo : 'todos', ['id' => 'form-search-tipo']) !!}

            {!! Form::hidden('ordem', isset($ordem) ? $ordem : '', ['id' => 'form-search-ordem']) !!}

            {!! Form::hidden('page', '', ['id' => 'form-search-page']) !!}

            {!! Form::submit('') !!}
        {!! Form::close() !!}
    </div>

    <div class="col-xs-5">
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
                                <a href="{{ action('TrabalhoController@getConfig') }}" class="icon-perfil-trabalho" id="open-trabalho-config">Perfil de trabalho</a>
                            </li>

                            <li>
                                <a href="{{ route('get-usuario-config') }}" id="open-usuario-config" class="icon-conta">Configurações</a>
                            </li>

                            <li>
                                <a href="{{ route('usuario-logout') }}" class="icon-logout">Sair</a>
                            </li>

                            <li class="termos">
                                <a href="#" id="open-contato">Contato</a>
                                <span>-</span>
                                <a href="{{ route('termos-uso') }}" target="_blank">Termos</a>
                                <span>-</span>
                                <a href="{{ route('termos-privacidade') }}" target="_blank">Privacidade</a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="button">
                        <a href="#" data-toggle="modal" data-target="#modal-como-funciona">Como funciona</a>
                    </li>

                    <li>
                        <a href="{{ route('user-register') }}">Cadastrar</a>
                    </li>

                    <li>
                        <a href="{{ route('user-login') }}">Entrar</a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>

    <div class="col-xs-2">
        <div class="social-links">
            <a href="https://www.facebook.com/infochatapp" target="_blank" class="social-facebook"></a>
            <a href="https://www.instagram.com/infochatapp" target="_blank" class="social-instagram"></a>
            <a href="https://twitter.com/infochatapp" target="_blank" class="social-twitter"></a>
        </div>
    </div>
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
