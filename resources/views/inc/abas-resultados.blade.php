<div class="topo-resultados">
    {!! Form::open(['method' => 'post', 'id' => 'form-search', 'action' => 'TrabalhoController@formBusca']) !!}
        {!! Form::text('palavra_chave', (isset($palavra_chave) && $palavra_chave != 'area') ? $palavra_chave : '', ['class' => 'form-control', 'id' => 'form-search-palavra-chave', 'placeholder' => 'Pesquise aqui', 'autocomplete' => 'off']) !!}

        {!! Form::hidden('area', isset($area) ? $area : '', ['id' => 'form-search-area']) !!}
        {!! Form::hidden('tag', isset($tag) ? $tag : '', ['id' => 'form-search-tag']) !!}
        {!! Form::hidden('tipo', isset($tipo) ? $tipo : 'todos', ['id' => 'form-search-tipo']) !!}

        {!! Form::hidden('ordem', isset($ordem) ? $ordem : '', ['id' => 'form-search-ordem']) !!}

        {!! Form::hidden('page', '', ['id' => 'form-search-page']) !!}

        {!! Form::submit('') !!}
    {!! Form::close() !!}

    <div class="abas-resultados">
        <a href="#" data-type="resultado" class="active">PESQUISA</a>

        <a href="{{ route('msg-pessoal') }}" data-type="pessoal">CONVERSAS
            @if($new_messages_pessoal)
                <span>{{ $new_messages_pessoal }}</span>
            @endif
        </a>

        @if(Auth::guard('web')->check() && Auth::guard('web')->user()->trabalho)
            <a href="{{ route('msg-trabalho') }}" data-type="trabalho">CLIENTES
                @if($new_messages_trabalho)
                    <span>{{ $new_messages_trabalho }}</span>
                @endif
            </a>
        @endif
    </div>

    @if(isset($filtro_ordem))
        {!! Form::select('ordem', $filtro_ordem, null, ['class' => 'selectpicker filtro-ordem', 'title' => 'filtrar por']) !!}
    @endif
</div>

<div id="form-search-results">
    @if(isset($trabalhos) && count($trabalhos) > 0)
        @include('inc.list-resultados')
    @else
        <div class="sem-resultados">
            <p>Sua pesquisa não encontrou resultado.<br>Verifique se todas as palavras estão corretas ou tente palavras-chave diferentes.</p>
        </div>
    @endif
</div>
