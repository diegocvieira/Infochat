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
            <h2>{{ $trabalho->nome }}</h2>

            <div class="tags">
                @foreach($trabalho->tags as $t)
                    <p><span>-</span> {{ $t->tag }}</p>
                @endforeach
            </div>

            <div class="result-bottom">
                <a href="#" class="ver-perfil" data-id="{{ $trabalho->id }}">ver perfil</a>
            </div>
        </div>
    </div>
@endforeach

@if($trabalhos->currentPage() < $trabalhos->lastPage())
    <button class="load-more-results" data-page="{{ $trabalhos->currentPage() + 1 }}">+</button>
@endif
