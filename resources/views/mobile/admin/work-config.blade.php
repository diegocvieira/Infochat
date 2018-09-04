{!! Form::model($trabalho, ['method' => 'post', 'action' => 'TrabalhoController@setConfig', 'id' => 'form-trabalho-config', 'files' => 'true']) !!}
    <div class="top-modal">
        <a href="#" data-dismiss="modal" class="close-modal-arrow"></a>

        <h3 class="title">Trabalho</h3>

        {!! Form::submit('Salvar') !!}

        <div class="status">
            <label class="switch">
                {!! Form::checkbox('status') !!}
                <span class="slider"></span>
            </label>
        </div>
    </div>

    <div class="imagem">
        @if($trabalho->imagem)
            <img src="{{ asset('uploads/' . $trabalho->user_id . '/' . $trabalho->imagem) }}" />
        @else
            <img src="{{ asset('img/paisagem.png') }}" class="sem-imagem" />
        @endif

        {!! Form::file('img', ['id' => 'imagem', 'accept' => 'image/*']) !!}

        {!! Form::label('imagem', 'trocar imagem', ['class' => 'trocar-imagem']) !!}
    </div>

    <div class="infos">
        {!! Form::select('tipo', $tipos, null, ['class' => 'selectpicker tipo', 'title' => 'Tipo', 'required']) !!}

        {!! Form::text('nome', null, ['placeholder' => 'Nome', 'class' => 'nome', 'required']) !!}

        {!! Form::select('area_id', isset($trabalho) ? $areas : [], null, ['class' => 'selectpicker area', 'title' => 'Área', 'required']) !!}

        <select name="categoria" title="Categoria" class="selectpicker categoria">
            @if(isset($trabalho))
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" data-title="{{ $categoria->titulo }}">{{ $categoria->titulo }}</option>
                @endforeach
            @endif
        </select>

        {!! Form::select('subcategoria', [], null, ['class' => 'selectpicker subcategoria', 'title' => 'Subcategoria']) !!}

        <div class="tags">
            <div class="top-tags">
                <span class="label">Palavras-chave</span>

                <div class="info-tag">
                    <a href="#"></a>

                    <div class="infos">
                        <p>
                            <span>1. São palavras que os usuários podem usar na busca por seus serviços, produtos ou empresa</span>
                            <span>2. As primeiras palavras-chave irão aparecer embaixo do seu nome de usuário</span>
                            <span>3. Selecione as palavras-chave nos campos acima ou escreva e pressione enter para inserir cada uma</span>
                            <span>4. Priorize as caixas de seleção para aparecer melhor nos resultados</span>
                        </p>
                    </div>
                </div>

                <span class="count-tag">{{ (isset($trabalho) && count($trabalho->tags) > 0) ? 10 - count($trabalho->tags) : 10 }}</span>
            </div>

            <label for="insert-tag">
                @if(isset($trabalho) && count($trabalho->tags) > 0)
                    @foreach($trabalho->tags as $tag)
                        <div class="new-tag">
                            <span>{{ $tag->tag }}</span>
                            <input style="display: none;" type="text" name="tag[]" value="{{ $tag->tag }}" />
                            <a href="#"></a>
                        </div>
                    @endforeach
                @else
                    <span class="placeholder">ex.: fotógrafo, padaria, capinha celular, advogada, bar, bicicleta...</span>
                @endif

                {!! Form::text('insert_tag', '', ['id' => 'insert-tag']) !!}
            </label>
        </div>
    </div>

    <div class="abas">
        <a href="#" class="active" data-type="sobre">Sobre</a>
        <a href="#" data-type="informacoes">Mais informações</a>
    </div>

    <div class="sobre aba-aberta">
        {!! Form::textarea('descricao', null, ['placeholder' => 'Escreva uma descrição dos seus serviços ou empresa']) !!}
    </div>

    <div class="informacoes aba-aberta">
        <div class="form-group endereco">
            {!! Form::text('cep', null, ['placeholder' => 'Cep', 'class' => 'cep', 'id' => 'cep']) !!}

            {!! Form::text('bairro', null, ['placeholder' => 'Bairro', 'class' => 'bairro', 'id' => 'bairro']) !!}

            {!! Form::text('cidade', isset($trabalho) ? $trabalho->cidade->title : null, ['id' => 'cidade', 'placeholder' => 'Cidade', 'class' => 'cidade']) !!}

            {!! Form::text('estado', isset($trabalho) ? $trabalho->cidade->estado->letter : null, ['id' => 'estado', 'placeholder' => 'Estado', 'class' => 'estado']) !!}

            {!! Form::text('logradouro', null, ['placeholder' => 'Endereço', 'class' => 'logradouro', 'id' => 'logradouro']) !!}

            {!! Form::text('numero', null, ['placeholder' => 'Nº', 'class' => 'numero']) !!}

            {!! Form::text('complemento', null, ['placeholder' => 'Comp.', 'class' => 'complemento']) !!}
        </div>

        <div class="form-group fones">
            @if(isset($trabalho) && count($trabalho->telefones) > 0)
                @foreach($trabalho->telefones as $key_fone => $fone)
                    <div class="fone">
                        {!! Form::text('fone[]', $fone->fone, ['placeholder' => 'Telefone', 'class' => 'fone-mask']) !!}

                        @if($key_fone > 0)
                            <a href="#" class="remove-item"></a>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="fone">
                    {!! Form::text('fone[]', null, ['placeholder' => 'Telefone', 'class' => 'fone-mask']) !!}
                </div>
            @endif

            <div class="add">
                <a href="#" class="add-fone">+ adicionar</a>
            </div>
        </div>

        <div class="form-group email">
            {!! Form::email('email', null, ['placeholder' => 'E-mail']) !!}
        </div>

        <div class="form-group redes-sociais">
            <div class="social slug">
                {!! Form::text('slug', null, ['id' => 'slug', 'required']) !!}
            </div>

            @if(isset($trabalho) && count($trabalho->redes) > 0)
                @foreach($trabalho->redes as $key_rede => $rede)
                    <div class="social">
                        {!! Form::text('social[]', $rede->url, ['placeholder' => 'Link']) !!}

                        @if($key_rede > 1)
                            <a href="#" class="remove-item"></a>
                        @endif
                    </div>
                @endforeach

                @if(count($trabalho->redes) <= 1)
                    <div class="social">
                        {!! Form::text('social[]', null, ['placeholder' => 'Facebook']) !!}
                    </div>
                @endif
            @else
                <div class="social">
                    {!! Form::text('social[]', null, ['placeholder' => 'Site']) !!}
                </div>

                <div class="social">
                    {!! Form::text('social[]', null, ['placeholder' => 'Facebook']) !!}
                </div>
            @endif

            <div class="add">
                <a href="#" class="add-social">+ adicionar</a>
            </div>
        </div>

        <div class="form-group atendimento">
            @if(isset($trabalho) && count($trabalho->horarios) > 0)
                @foreach($trabalho->horarios as $key_horario => $horario)
                    <div class="semana">
                        {!! Form::select('dia[]', $dias_semana, $horario->dia, ['class' => 'selectpicker dia', 'title' => 'Dia']) !!}
                        {!! Form::select('ate_tarde[]', $horarios, format_horario($horario->ate_tarde), ['class' => 'selectpicker hora', 'title' => 'Hora']) !!}
                        {!! Form::select('de_manha[]', $horarios, format_horario($horario->de_manha), ['class' => 'selectpicker hora', 'title' => 'Hora']) !!}
                        {!! Form::select('ate_noite[]', $horarios, format_horario($horario->ate_noite), ['class' => 'selectpicker hora', 'title' => 'Hora']) !!}
                        {!! Form::select('de_tarde[]', $horarios, format_horario($horario->de_tarde), ['class' => 'selectpicker hora', 'title' => 'Hora']) !!}

                        @if($key_horario > 0)
                            <a href="#" class="remove-item"></a>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="semana">
                {!! Form::select('dia[]', $dias_semana, null, ['class' => 'selectpicker dia', 'title' => 'Dia']) !!}
                {!! Form::select('ate_tarde[]', $horarios, null, ['class' => 'selectpicker hora', 'title' => 'Hora']) !!}
                {!! Form::select('de_manha[]', $horarios, null, ['class' => 'selectpicker hora', 'title' => 'Hora']) !!}
                {!! Form::select('ate_noite[]', $horarios, null, ['class' => 'selectpicker hora', 'title' => 'Hora']) !!}
                {!! Form::select('de_tarde[]', $horarios, null, ['class' => 'selectpicker hora', 'title' => 'Hora']) !!}
                </div>
            @endif

            <div class="add">
                <a href="#" class="add-atendimento">+ adicionar</a>
            </div>
        </div>
    </div>
{!! Form::close() !!}
