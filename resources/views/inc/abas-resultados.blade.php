<div class="topo-resultados">
    <div class="abas-resultados">
        <a href="#" data-type="resultado" class="active">RESULTADO</a>
        <a href="{{ action('MensagemController@pessoal') }}" data-type="pessoal">PESSOAL</a>
        <a href="{{ action('MensagemController@trabalho') }}" data-type="trabalho">TRABALHO</a>
    </div>

    @if(isset($filtro_ordem))
        {!! Form::select('ordem', $filtro_ordem, null, ['class' => 'selectpicker filtro-ordem', 'title' => 'filtrar por']) !!}
    @endif
</div>

<div id="form-search-results">
    @if(count($trabalhos) > 0)
        @include('pagination')
    @else
        <div class="sem-resultados">
            <p>Sua pesquisa não encontrou resultado.<br>Verifique se todas as palavras estão corretas ou tente palavras-chave diferentes.</p>
        </div>
    @endif
</div>
