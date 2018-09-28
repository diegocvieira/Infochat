<div class="divulgar">
    <div class="link">
        <h2>Divulgar link para o perfil</h2>

        <span id="material-slug">infochat.com.br/{{ Auth::guard('web')->user()->trabalho->slug }}</span>

        <button type="button" class="copy-link">copiar</button>

        {!! Form::text('slug', 'infochat.com.br/' . Auth::guard('web')->user()->trabalho->slug, ['id' => 'input-material-slug']) !!}
    </div>

    <div class="download">
        <h2>Material de divulgação personalizado</h2>

        <ol>
            <li>Faça o download</li>
            <li>Imprima o material</li>
            <li>Cole no estabelecimento e divulgue</li>
        </ol>

        {!! Form::select('size', $sizes, null, ['class' => 'selectpicker material-size', 'title' => 'selecione aqui']) !!}

        <button type="button" class="material-download">BAIXAR</button>
    </div>
</div>

<div class="images-preview">
    @for($i = 1; $i <= 2; $i++)
        <div class="preview">
            <img src="/img/material-divulgacao/preview/{{ $i == 1 ? 'desktop_blue' : 'desktop_white' }}.jpg" />

            <span>{{ Auth::guard('web')->user()->trabalho->slug }}</span>
        </div>
    @endfor
</div>
