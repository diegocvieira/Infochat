<div class="divulgar">
    <div class="link">
        <h2>Divulgar link para o perfil</h2>
    </div>

    <div class="download">
        <h2>Material de divulgação personalizado</h2>

        <ol>
            <li>Faça o download</li>
            <li>Imprima o material</li>
            <li>Cole no estabelecimento e divulgue</li>
        </ol>

        {!! Form::select('size', ['' => ''] + $sizes, null, ['class' => 'selectpicker', 'title' => 'selecione aqui']) !!}
        <button type="button">BAIXAR</button>
    </div>
</div>

<div class="images-preview">
    <div class="preview">
        <img src="{{ asset('img/material-divulgacao/preview/desktop_blue.jpg') }}" />
        <span>diego</span>
    </div>

    <div class="preview">
        <img src="{{ asset('img/material-divulgacao/preview/desktop_white.jpg') }}" />
        <span>diego</span>
    </div>
</div>
