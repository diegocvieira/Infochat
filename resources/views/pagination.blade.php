    @foreach($trabalhos as $trabalho)
        <div class="result">
            <div class="col-xs-2">
                @if($trabalho->imagem)
                    <img class="img" src="{{ asset('uploads/perfil/' . $trabalho->imagem) }}" alt="Foto de perfil de {{ $trabalho->nome }}" />
                @else
                    <img src="{{ asset('img/paisagem.png') }}" class="img sem-imagem" alt="Foto de perfil de {{ $trabalho->nome }}" />
                @endif
            </div>

            <div class="col-xs-10" style="padding-left: 30px;">
                <h1>{{ $trabalho->nome }}</h1>

                <div class="tags">
                    @foreach($trabalho->tags as $t)
                        <p><span>-</span> {{ $t->tag }}</p>
                    @endforeach
                </div>

                <a href="#" class="ver-perfil" data-id="{{ $trabalho->id }}">ver perfil</a>
            </div>
        </div>
    @endforeach

    <!--<div class="sem-resultados">
        <p>Sua pesquisa não encontrou resultado.<br>Verifique se todas as palavras estão corretas ou tente palavras-chave diferentes.</p>
    </div>-->
