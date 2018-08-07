{!! Form::model($usuario, ['method' => 'post', 'route' => 'set-usuario-config', 'id' => 'form-usuario-config', 'files' => 'true']) !!}
    <div class="row">
        <div class="imagem">
            @if($usuario->imagem)
                <img src="{{ asset('uploads/perfil/' . $usuario->imagem) }}" />
            @else
                <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" />
            @endif

            {!! Form::file('img', ['id' => 'imagem', 'accept' => 'image/*']) !!}

            {!! Form::label('imagem', 'trocar imagem', ['class' => 'trocar-imagem']) !!}
        </div>
    </div>

    <div class="row">
        {!! Form::text('nome', null, ['class' => 'col-xs-12', 'placeholder' => 'Nome']) !!}
    </div>

    <div class="row">
        {!! Form::email('email', null, ['class' => 'col-xs-12', 'placeholder' => 'E-mail']) !!}
    </div>

    <div class="row">
        {!! Form::input('password', 'password', null, ['class' => 'col-xs-6', 'placeholder' => 'Nova senha', 'id' => 'senha-usuario']) !!}

        {!! Form::input('password', 'password_confirmation', null, ['class' => 'col-xs-6', 'placeholder' => 'Confirmar nova senha']) !!}
    </div>

    <div class="row">
        {!! Form::submit('Salvar', ['class' => 'col-xs-12']) !!}
    </div>

    <div class="row text-center">
        <a href="#" id="deletar-conta">Deletar conta</a>
    </div>
{!! Form::close() !!}
