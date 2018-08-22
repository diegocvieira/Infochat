        @if(!Auth::check())
            <div id="modal-cadastro-usuario" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <a href="#" class="close-modal" data-dismiss="modal"></a>

                        <div class="modal-header">
                            <h1>Bem-vindo!</h1>
                            <p>Cadastre-se totalmente grátis</p>
                        </div>

                        <div class="modal-body">
                            {!! Form::open(['method' => 'post', 'action' => 'UserController@create', 'id' => 'form-cadastro-usuario']) !!}
                                {!! Form::text('nome', '', ['placeholder' => 'Nome', 'class' => 'form-control', 'required', 'maxlength' => '62']) !!}

                                {!! Form::email('email', '', ['placeholder' => 'E-mail', 'class' => 'form-control', 'required', 'maxlength' => '62']) !!}

                                {!! Form::input('password', 'password', '', ['placeholder' => 'Senha', 'class' => 'form-control', 'id' => 'senha-usuario', 'required']) !!}

                                {!! Form::input('password', 'password_confirmation', '', ['placeholder' => 'Confirmar senha', 'class' => 'form-control', 'required']) !!}

                                {!! Form::submit('Cadastrar') !!}
                            {!! Form::close() !!}
                        </div>

                        <div class="modal-footer">
                            <p>Ao se cadastrar você concorda com os <a href="{{ route('termos-uso') }}" target="_blank">termos de uso</a><br>e a <a href="{{ route('termos-privacidade') }}" target="_blank">política de privacidade</a> do infochat.com.br</p>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div id="modal-login-usuario" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <a href="#" class="close-modal" data-dismiss="modal"></a>

                        <div class="modal-header">
                            <h1>Bem-vindo de volta!</h1>
                            <p>Acesse seu perfil para começar</p>
                        </div>

                        <div class="modal-body">
                            {!! Form::open(['method' => 'post', 'action' => 'UserController@login', 'id' => 'form-login-usuario']) !!}
                                {!! Form::email('email', '', ['placeholder' => 'E-mail', 'class' => 'form-control', 'required']) !!}

                                {!! Form::input('password', 'password', '', ['placeholder' => 'Senha', 'class' => 'form-control', 'required']) !!}

                                {!! Form::submit('Entrar', ['class' => 'form-control btn btn-primary']) !!}
                            {!! Form::close() !!}
                        </div>

                        <div class="modal-footer">
                            <p><a href="#" id="recuperar-senha">Recuperar senha</a></p>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        @endif

        <div class="modal fade" id="modal-alert" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body"></div>

                    <div class="modal-footer">
                        <button type="button" class="btn-back" data-dismiss="modal">VOLTAR</button>

                        <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" id="modal-default" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body"></div>
                </div><!-- /.modal-content -->
            </div>
        </div><!-- /.modal -->

        @if($app->environment('local'))
            <script type="text/javascript" src="{{ asset('offline-developer/jquery.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('offline-developer/bootstrap.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('offline-developer/jquery.validate.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('offline-developer/bootstrap-select.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('offline-developer/jquery.mask.min.js') }}"></script>
        @else
            <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js'></script>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.8/jquery.mask.min.js"></script>
        @endif

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.finger/0.1.6/jquery.finger.min.js"></script>

        <audio src="{{ asset('img/sound.mp3') }}" id="alert-new-message"></audio>

        <script>
            @if(Auth::guard('web')->check())
                var logged = true;
            @else
                var logged = false;
            @endif
        </script>

        <script src="{{ mix('/js/mobile-global.js') }}"></script>
    </body>
</html>