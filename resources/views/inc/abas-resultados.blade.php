<div class="topo-resultados">
    <div class="abas-resultados">
        <a href="#" data-type="resultado" class="active">RESULTADO</a>
        <a href="{{ route('msg-pessoal') }}" data-type="pessoal">PESSOAL
            @if($new_messages_pessoal)
                <span>{{ $new_messages_pessoal }}</span>
            @endif
            </a>
        </a>
        <a href="{{ route('msg-trabalho') }}" data-type="trabalho">TRABALHO
            @if($new_messages_trabalho)
                <span>{{ $new_messages_trabalho }}</span>
            @endif
        </a>
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
