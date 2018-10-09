<div class="aside-categorias">
    <div class="cidades">
        <div class="cidade-atual">Você está em <span>{{ Cookie::get('sessao_cidade_title') . '/' . Cookie::get('sessao_estado_letter') }}</span></div>

        {!! Form::open(['action' => 'GlobalController@getCidade', 'method' => 'post', 'id' => 'form-busca-cidade']) !!}
            {!! Form::text('nome_cidade', '', ['placeholder' => 'Digite sua cidade']) !!}

            {!! Form::submit('') !!}
        {!! Form::close() !!}
    </div>

    <ul id="categorias">
        <li>
            <a href="#" class="cor-destaque tipo {{ (!isset($tipo) || $tipo == 'todos') ? 'active' : '' }}" data-search="todos">TODOS</a>
        </li>

        <li>
            <a href="#" class="cor-destaque tipo {{ (isset($tipo) && $tipo == 'profissionais') ? 'active' : '' }}" data-search="profissionais">PROFISSIONAIS</a>
        </li>

        <li>
            <a href="#" class="cor-destaque tipo {{ (isset($tipo) && $tipo == 'estabelecimentos') ? 'active' : '' }}" data-search="estabelecimentos">ESTABELECIMENTOS</a>
        </li>

        <li>
            <a href="#" class="cor-destaque tipo" data-search="favoritos">FAVORITOS</a>
        </li>

        <div class="busca-categorias">
            <a href="#" class="open-busca-categoria"></a>

            {!! Form::open(['action' => 'GlobalController@buscaCategorias', 'method' => 'post', 'id' => 'form-busca-categoria']) !!}
                {!! Form::text('nome_categoria', '', ['placeholder' => 'Pesquisar categoria']) !!}

                {!! Form::submit('') !!}
            {!! Form::close() !!}
        </div>

        @foreach($areas as $a)
            <li>
                <a href="#" class="area cat-search {{ (isset($area) && $area == $a->id) ? 'active' : '' }}" data-search="{{ $a->slug }}" style="background-image: url({{ asset('img/categorias/' . $a->slug . '.png') }})">{{ $a->titulo }}</a>
            </li>
        @endforeach
    </ul>

    <div class="aside-bottom">
        <div class="support">
            <a href="http://www.pelotas.com.br/" target="_blank"><b>APOIO</b> Prefeitura de <img src="{{ asset('img/logo-pel.png') }}" /></a>
        </div>

        <div class="social-links">
            <a href="https://www.facebook.com/infochatapp" target="_blank" class="social-facebook"></a>
            <a href="https://www.instagram.com/infochatapp" target="_blank" class="social-instagram"></a>
            <a href="https://twitter.com/infochatapp" target="_blank" class="social-twitter"></a>
        </div>

        <div class="termos">
            <a href="{{ route('termos-uso') }}" target="_blank">Termos</a>
            <a href="{{ route('termos-privacidade') }}" target="_blank">Privacidade</a>
            <a href="#" id="open-contato">Contato</a>
        </div>
    </div>
</div>
