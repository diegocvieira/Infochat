@foreach($trabalhos as $trabalho)
    <div class="result open-chat" data-type="trabalho" data-id="{{ $trabalho->id }}">
        <div class="imagem">
            @if($trabalho->imagem)
                <img src="{{ asset('uploads/' . $trabalho->user_id . '/' . $trabalho->imagem) }}" alt="Foto de perfil de {{ $trabalho->nome }}" />
            @else
                <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $trabalho->nome }}" />
            @endif
        </div>

        <div class="infos">
            <div class="top">
                <h3>{{ $trabalho->nome }}</h3>
            </div>

            <div class="bottom">
                <div class="tags">
                    @foreach($trabalho->tags as $t)
                        <p><span>-</span> {{ $t->tag }}</p>
                    @endforeach
                </div>
            </div>

            <?php /*<div class="result-bottom">
                <a href="#" class="ver-perfil" data-id="{{ $trabalho->id }}">ver perfil</a>
            </div>*/ ?>
        </div>
    </div>
@endforeach

@if($trabalhos->lastPage() > 1)
    <div class="pagination">
        @if($trabalhos->currentPage() > 1)
            <a class="load-more-results prev" href="{{ $trabalhos->url($trabalhos->currentPage()-1) }}">anterior</a>
        @else
            <a class="disabled prev">anterior</a>
        @endif

        <span>{{ $trabalhos->currentPage() }}</span>

        @if($trabalhos->currentPage() < $trabalhos->lastPage())
            <a class="load-more-results next" href="{{ $trabalhos->url($trabalhos->currentPage()+1) }}">próximo</a>
        @else
            <a class="disabled next">próximo</a>
        @endif
    </div>
@endif
