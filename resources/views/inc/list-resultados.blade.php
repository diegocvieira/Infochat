@foreach($trabalhos as $trabalho)
    <div class="result open-chat" data-type="trabalho" data-id="{{ $trabalho->id }}" data-identificador="">
        <div class="imagem">
            @if($trabalho->imagem)
                <img src="{{ asset('uploads/perfil/' . $trabalho->imagem) }}" alt="Foto de perfil de {{ $trabalho->nome }}" />
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
