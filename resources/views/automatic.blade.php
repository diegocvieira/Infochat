{!! Form::open(['method' => 'post', 'route' => 'automatic-register', 'files' => true]) !!}
    {!! Form::text('tag[]', null, ['placeholder' => 'Tag']) !!}
    {!! Form::text('tag[]', null, ['placeholder' => 'Tag']) !!}
    {!! Form::text('tag[]', null, ['placeholder' => 'Tag']) !!}
    {!! Form::text('tag[]', null, ['placeholder' => 'Tag']) !!}
    {!! Form::text('tag[]', null, ['placeholder' => 'Tag']) !!}

    {!! Form::file('file') !!}

    {!! Form::select('type', ['' => 'tipo de planilha', 'phone' => 'fone', 'email' => 'email']) !!}

    {!! Form::submit('ENVIAR') !!}
{!! Form::close() !!}

{!! Form::open(['method' => 'post', 'route' => 'automatic-emails', 'style' => 'margin-top: 100px;']) !!}
    <p>Precisa estar logado para enviar os e-mails...</p>

    {!! Form::text('message', null, ['placeholder' => 'Mensagem']) !!}

    {!! Form::text('tag', null, ['placeholder' => 'Tag']) !!}

    {!! Form::select('type', ['' => 'tipo de planilha', 'phone' => 'fone', 'email' => 'email']) !!}

    {!! Form::submit('ENVIAR') !!}
{!! Form::close() !!}

{!! Form::open(['method' => 'post', 'route' => 'automatic-images', 'files' => true, 'style' => 'margin-top: 100px;']) !!}
    @for($i = 0; $i <= 9; $i++)
        <div style="display: block; margin-top: 5px;">
            {!! Form::file('images[]') !!}
            {!! Form::text('identifiers[]', null, ['placeholder' => 'E-mail / Fone']) !!}
        </div>
    @endfor

    {!! Form::select('type', ['' => 'tipo de planilha', 'phone' => 'fone', 'email' => 'email']) !!}

    {!! Form::submit('ENVIAR', ['style' => 'margin-top: 50px;']) !!}
{!! Form::close() !!}
