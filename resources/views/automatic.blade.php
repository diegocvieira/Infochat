{!! Form::open(['method' => 'post', 'route' => 'automatic-register', 'files' => true]) !!}
    {!! Form::select('type', $types) !!}
    {!! Form::select('area', $areas) !!}
    {!! Form::select('categorie', $categories) !!}
    {!! Form::file('file') !!}

    {!! Form::submit('ENVIAR') !!}
{!! Form::close() !!}

{!! Form::open(['method' => 'post', 'route' => 'automatic-emails', 'style' => 'margin-top: 100px;']) !!}
    {!! Form::text('message') !!}
    {!! Form::select('categorie', $categories) !!}

    {!! Form::submit('ENVIAR') !!}
{!! Form::close() !!}

{!! Form::open(['method' => 'post', 'route' => 'automatic-images', 'files' => true, 'style' => 'margin-top: 100px;']) !!}
    @for($i = 0; $i <= 9; $i++)
        <div style="display: block; margin-top: 5px;">
            {!! Form::file('images[]') !!}
            {!! Form::text('emails[]') !!}
        </div>
    @endfor

    {!! Form::submit('ENVIAR', ['style' => 'margin-top: 50px;']) !!}
{!! Form::close() !!}
