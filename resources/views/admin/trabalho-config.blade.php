{!! Form::model($trabalho, ['method' => 'post', 'action' => 'TrabalhoController@setConfig', 'id' => 'form-trabalho-config', 'files' => 'true']) !!}
    {!! Form::text('cpf_cnpj', null, ['class' => 'cpf-cnpj']) !!}

    <div class="row">
        <div class="col-xs-4 imagem">
            @if(isset($trabalho) && $trabalho->imagem)
            <div class="bg" style="background-image: url({{ asset('uploads/perfil/' . $trabalho->imagem) }}); background-size: cover;">
            @else
            <div class="bg sem-imagem">
            @endif

                {!! Form::label('imagem', 'trocar imagem', ['class' => 'trocar-imagem']) !!}
            </div>

            {!! Form::file('img', ['id' => 'imagem']) !!}
        </div>

        <div class="col-xs-8 infos">
            <div class="tipo-nome">
                {!! Form::select('tipo', $tipos, null, ['class' => 'selectpicker tipo', 'title' => 'Tipo', 'required']) !!}

                {!! Form::text('nome', null, ['placeholder' => 'Nome', 'class' => 'nome', 'required']) !!}
            </div>

            <div class="categorias">
                {!! Form::select('area_id', $areas, null, ['class' => 'selectpicker area', 'title' => 'Área', 'required']) !!}

                {!! Form::select('categoria', [], null, ['class' => 'selectpicker categoria', 'title' => 'Categoria']) !!}

                {!! Form::select('subcategoria', [], null, ['class' => 'selectpicker subcategoria', 'title' => 'Subcategoria']) !!}
            </div>

            <div class="tags">
                <div class="top-tags">
                    <span class="label">Palavras-chave</span>

                    <div class="info-tag">
                        <a href="#" title="Informações sobre as tags"></a>

                        <div class="infos">
                            <p>
                                <span>1. São palavras que os usuários podem usar na busca por seus serviços, produtos ou empresa</span>
                                <span>2. As primeiras palavras-chave irão aparecer embaixo do seu nome de usuário</span>
                                <span>3. Selecione as palavras-chave nos campos acima ou escreva e pressione enter para inserir cada uma</span>
                                <span>4. Priorize as caixas de seleção para aparecer melhor nos resultados</span>
                            </p>
                        </div>
                    </div>

                    <span class="count-tag">10</span>
                </div>

                <label for="insert-tag">
                    <span class="placeholder">ex.: fotógrafo, padaria, capinha celular, advogada, bar, bicicleta...</span>

                    {!! Form::text('insert_tag', '', ['id' => 'insert-tag']) !!}
                </label>
            </div>
        </div>
    </div>


    <div class="row" style="padding-left: 15px;">
        <div class="abas col-xs-9">
            <a href="#" class="active" data-type="sobre">Sobre</a>
            <a href="#" data-type="informacoes">Mais informações</a>
            <a href="#" data-type="dados">Dados</a>
        </div>

        <div class="btn-ativar col-xs-3">
            <div class="status">
                <label class="switch">
                    {!! Form::checkbox('status') !!}
                    <span class="slider"></span>
                </label>

                <div class="title-status">{{ (isset($trabalho) && $trabalho->status) ? 'Desativar perfil' : 'Ativar perfil' }}</div>
            </div>

            {!! Form::submit('Salvar') !!}
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 sobre aba-aberta">
            {!! Form::textarea('descricao', null, ['placeholder' => 'Escreva uma descrição dos seus serviços ou empresa']) !!}
        </div>

        <div class="informacoes aba-aberta">
            <div class="form-group endereco">
                {!! Form::hidden('estado', null, ['id' => 'estado']) !!}
                {!! Form::hidden('cidade', null, ['id' => 'cidade']) !!}

                <div class="row">
                    {!! Form::text('cep', null, ['placeholder' => 'Cep', 'class' => 'cep', 'id' => 'cep']) !!}

                    {!! Form::text('bairro', null, ['placeholder' => 'Bairro', 'class' => 'bairro', 'id' => 'bairro']) !!}
                </div>

                <div class="row">
                    {!! Form::text('logradouro', null, ['placeholder' => 'Endereço', 'class' => 'logradouro', 'id' => 'logradouro']) !!}

                    {!! Form::text('numero', null, ['placeholder' => 'Nº', 'class' => 'numero']) !!}

                    {!! Form::text('complemento', null, ['placeholder' => 'Comp.', 'class' => 'complemento']) !!}
                </div>
            </div>

            <div class="form-group fones">
                @if(isset($trabalho) && count($trabalho->telefones) > 0)
                    @foreach($trabalho->telefones as $key_fone => $fone)
                        <div class="row fone">
                            {!! Form::text('fone[]', $fone->fone, ['placeholder' => 'Telefone', 'class' => 'fone-mask']) !!}

                            @if($key_fone > 0)
                                <a href="#" class="remove-item"></a>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="row fone">
                        {!! Form::text('fone[]', null, ['placeholder' => 'Telefone', 'class' => 'fone-mask']) !!}
                    </div>
                @endif

                <div class="row add">
                    <a href="#" class="add-fone">+ adicionar</a>
                </div>
            </div>

            <div class="form-group email">
                <div class="row">
                    {!! Form::email('email', null, ['placeholder' => 'E-mail']) !!}
                </div>
            </div>

            <div class="form-group redes-sociais">
                <div class="row social slug">
                    {!! Form::text('slug', null, ['id' => 'slug', 'required']) !!}
                </div>

                @if(isset($trabalho))
                    @foreach($trabalho->redes as $key_rede => $rede)
                        <div class="row social">
                            {!! Form::text('social[]', $rede->url, ['placeholder' => 'Link']) !!}

                            @if($key_rede > 1)
                                <a href="#" class="remove-item"></a>
                            @endif
                        </div>
                    @endforeach

                    @if(count($trabalho->redes) == 0)
                        <div class="row social">
                            {!! Form::text('social[]', null, ['placeholder' => 'Site']) !!}
                        </div>
                    @endif

                    @if(count($trabalho->redes) <= 1)
                        <div class="row social">
                            {!! Form::text('social[]', null, ['placeholder' => 'Facebook']) !!}
                        </div>
                    @endif
                @else
                    <div class="row social">
                        {!! Form::text('social[]', null, ['placeholder' => 'Site']) !!}
                    </div>

                    <div class="row social">
                        {!! Form::text('social[]', null, ['placeholder' => 'Facebook']) !!}
                    </div>
                @endif

                <div class="row add">
                    <a href="#" class="add-social">+ adicionar</a>
                </div>
            </div>

            <div class="form-group atendimento">
                @if(isset($trabalho) && count($trabalho->horarios) > 0)
                    @foreach($trabalho->horarios as $key_horario => $horario)
                        <div class="row semana">
                            {!! Form::select('dia[]', $dias_semana, $horario->dia, ['class' => 'selectpicker dia', 'title' => 'Dia']) !!}
                            {!! Form::select('de_manha[]', $horarios, substr($horario->de_manha, 0, 5), ['class' => 'selectpicker', 'title' => 'Hora']) !!}
                            {!! Form::select('ate_tarde[]', $horarios, substr($horario->ate_tarde, 0, 5), ['class' => 'selectpicker', 'title' => 'Hora']) !!}
                            {!! Form::select('de_tarde[]', $horarios, substr($horario->de_tarde, 0, 5), ['class' => 'selectpicker', 'title' => 'Hora']) !!}
                            {!! Form::select('ate_noite[]', $horarios, substr($horario->ate_noite, 0, 5), ['class' => 'selectpicker', 'title' => 'Hora']) !!}

                            @if($key_horario > 0)
                                <a href="#" class="remove-item"></a>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="row semana">
                        {!! Form::select('dia[]', $dias_semana, null, ['class' => 'selectpicker dia', 'title' => 'Dia']) !!}
                        {!! Form::select('de_manha[]', $horarios, null, ['class' => 'selectpicker', 'title' => 'Hora']) !!}
                        {!! Form::select('ate_tarde[]', $horarios, null, ['class' => 'selectpicker', 'title' => 'Hora']) !!}
                        {!! Form::select('de_tarde[]', $horarios, null, ['class' => 'selectpicker', 'title' => 'Hora']) !!}
                        {!! Form::select('ate_noite[]', $horarios, null, ['class' => 'selectpicker', 'title' => 'Hora']) !!}
                    </div>
                @endif

                <div class="row add">
                    <a href="#" class="add-atendimento">+ adicionar</a>
                </div>
            </div>
        </div>
    </div>
{!! Form::close() !!}
