@foreach($trabalhos as $trabalho)
    <div class="result">
        <a href="#" class="{{ Auth::guard('web')->check() ? 'open-chat' : '' }}" data-id="{{ $trabalho->id }}">
            <div class="col-xs-2">
                <div class="imagem">
                    @if($trabalho->imagem)
                        <img src="{{ asset('uploads/perfil/' . $trabalho->imagem) }}" alt="Foto de perfil de {{ $trabalho->nome }}" />
                    @else
                        <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" alt="Foto de perfil de {{ $trabalho->nome }}" />
                    @endif
                </div>
            </div>

            <div class="col-xs-10">
                <h1>{{ $trabalho->nome }}</h1>

                <div class="tags">
                    @foreach($trabalho->tags as $t)
                        <p><span>-</span> {{ $t->tag }}</p>
                    @endforeach
                </div>

                <a href="#" class="ver-perfil" data-id="{{ $trabalho->id }}">ver perfil</a>
            </div>
        </a>
    </div>
@endforeach
