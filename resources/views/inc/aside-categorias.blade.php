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
