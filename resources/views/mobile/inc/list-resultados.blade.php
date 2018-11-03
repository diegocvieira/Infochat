@foreach($trabalhos as $trabalho)
    <div class="result result-tab">
        <a href="{{ route('chat', [$trabalho->id, 'trabalho']) }}">
            <div class="imagem">
                @if($trabalho->imagem)
                    <img src="{{ asset('uploads/' . $trabalho->user_id . '/' . $trabalho->imagem) }}" alt="Foto de perfil de {{ $trabalho->nome }}" />
                @else
                    <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $trabalho->nome }}" />
                @endif
            </div>

            <div class="infos">
                <div class="nome-tags">
                    <h3>{{ $trabalho->nome }}</h3>

                    <div class="tags">
                        @foreach($trabalho->tags as $t)
                            <p><span>-</span> {{ $t->tag }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </a>

        <div class="manage-options">
            <div class="options">
                <a href="{{ route('show-work', $trabalho->slug) }}" id="work-details"></a>
            </div>
        </div>
    </div>
@endforeach

@if($trabalhos->currentPage() < $trabalhos->lastPage())
    <button class="load-more-results" data-page="{{ $trabalhos->currentPage() + 1 }}">+</button>
@endif
