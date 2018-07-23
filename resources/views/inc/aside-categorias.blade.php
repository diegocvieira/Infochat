<aside class="aside-categorias col-xs-2">
    <div class="cidades">
        <div class="cidade-atual">Você está em <span>{{ Cookie::get('sessao_cidade_title') }}/{{ Cookie::get('sessao_estado_letter') }}</span></div>

        {!! Form::open(['action' => 'GlobalController@getCidade', 'method' => 'post', 'id' => 'form-busca-cidade']) !!}
            {!! Form::text('nome_cidade', '', ['placeholder' => 'Digite sua cidade']) !!}

            {!! Form::submit('') !!}
        {!! Form::close() !!}
    </div>

    <ul id="categorias">
        <li>
            <a href="#" class="cor-destaque">TODOS</a>
        </li>

        <li>
            <a href="#" class="cor-destaque">PROFISSIONAIS</a>
        </li>

        <li>
            <a href="#" class="cor-destaque">EMPRESAS</a>
        </li>

        <li>
            <a href="#" class="cor-destaque">FAVORITOS</a>
        </li>

        <div class="busca-categorias">
            <a href="#" class="open-hide-busca-categoria open-busca-categoria"></a>

            {!! Form::open(['action' => 'GlobalController@getCategoria', 'method' => 'post', 'id' => 'form-busca-categoria', 'class' => 'open-hide-busca-categoria']) !!}
                {!! Form::text('nome_categoria', '', ['placeholder' => 'Digite sua cidade']) !!}

                {!! Form::submit('') !!}
            {!! Form::close() !!}
        </div>

        <span id="list-categorias">
            @foreach($categorias as $categoria)
                <li>
                    <a href="#" class="open-sub">{{ $categoria->titulo }}</a>
                </li>

                <div class="subs">
                    @foreach($categoria->subcategorias as $subcategoria)
                        <li>
                            <a href="#">{{ $subcategoria->titulo }}</a>
                        </li>
                    @endforeach
                </div>
            @endforeach
        </span>
    </ul>
</aside>
