<div class="top-modal">
    <a href="#" data-dismiss="modal" class="close-modal-arrow"></a>

    <h3 class="title">Configurações</h3>
</div>

{!! Form::model($usuario, ['method' => 'post', 'route' => 'set-usuario-config', 'id' => 'form-usuario-config', 'files' => 'true']) !!}
    {!! Form::hidden('senha_atual') !!}

    <div class="imagem">
        @if($usuario->imagem)
            <img src="{{ asset('uploads/' . $usuario->id . '/' . $usuario->imagem) }}" />
        @else
            <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" />
        @endif

        {!! Form::file('img', ['id' => 'imagem', 'accept' => 'image/*']) !!}

        {!! Form::label('imagem', 'trocar imagem', ['class' => 'trocar-imagem']) !!}
    </div>

    <div class="inputs">
        {!! Form::text('nome', null, ['placeholder' => 'Nome']) !!}

        {!! Form::email('email', null, ['placeholder' => 'E-mail']) !!}

        {!! Form::input('password', 'password', null, ['placeholder' => 'Nova senha', 'id' => 'senha-usuario']) !!}

        {!! Form::input('password', 'password_confirmation', null, ['placeholder' => 'Confirmar nova senha']) !!}

        {!! Form::submit('Salvar') !!}
    </div>

    <div class="text-center">
        <a href="#" id="excluir-conta">Deletar conta</a>
    </div>
{!! Form::close() !!}
