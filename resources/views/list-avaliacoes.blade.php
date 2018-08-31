@forelse($avaliacoes as $avaliacao)
    <div class="comentario">
        <div class="imagem-user">
            @if($avaliacao->user->imagem)
                <img src="{{ asset('uploads/perfil/' . $avaliacao->user->imagem) }}" />
            @else
                <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" />
            @endif
        </div>

        <div class="header-comentario">
            <h4>{{ $avaliacao->user->nome }}</h4>
            <span class="nota">{{ $avaliacao->nota }}.0</span>
            <span class="data">{{ date('d/m/Y', strtotime($avaliacao->created_at)) }}</span>
        </div>

        <div class="descricao-comentario">
            <p>
                {{ $avaliacao->descricao }}
            </p>
        </div>
    </div>
@empty
    <div class="sem-resultados">
        <p>Este {{ $trabalho->tipoNome($trabalho->tipo) }} ainda não recebeu um comentário...</p>
    </div>
@endforelse

@if($avaliacoes->currentPage() < $avaliacoes->lastPage())
    <button class="load-more-avaliacoes" data-page="{{ $avaliacoes->currentPage() + 1 }}">ver mais comentários</button>
@endif
