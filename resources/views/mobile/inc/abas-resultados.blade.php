<div class="abas-resultados">
    <a href="#" data-type="resultado" class="active">PESQUISA</a>

    <a href="{{ route('msg-pessoal') }}" data-type="pessoal">CONVERSAS
        @if($new_messages_pessoal)
            <span>{{ $new_messages_pessoal }}</span>
        @endif
        </a>
    </a>

    @if(Auth::guard('web')->check() && Auth::guard('web')->user()->trabalho && Auth::guard('web')->user()->trabalho->status)
        <a href="{{ route('msg-trabalho') }}" data-type="trabalho">CLIENTES
            @if($new_messages_trabalho)
                <span>{{ $new_messages_trabalho }}</span>
            @endif
        </a>
    @endif
</div>

<div class="info-results" style="{{ (!isset($trabalhos) || isset($trabalhos) && count($trabalhos) == 0) ? 'display: none;' : '' }}">
    <p class="show-info-description">Melhores resultados</p>
</div>

<div id="form-search-results">
    @if(isset($trabalhos) && count($trabalhos) > 0)
        @include('mobile.inc.list-resultados')
    @elseif(!isset($trabalhos))
        <div class="sem-resultados">
            <p>Pesquise um profissional ou estabelecimento<br>para pedir informações ou tirar dúvidas</p>
        </div>
    @else
        <div class="sem-resultados">
            <p>Sua pesquisa não encontrou resultado.<br>Verifique se todas as palavras estão corretas ou tente palavras-chave diferentes.</p>
        </div>
    @endif
</div>
