        @if(!Auth::check())
            <div id="modal-cadastro-usuario" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1>Bem-vindo!</h1>
                            <p>Cadastre-se totalmente grátis</p>
                        </div>

                        <div class="modal-body">
                            {!! Form::open(['method' => 'post', 'action' => 'UserController@create', 'id' => 'form-cadastro-usuario']) !!}
                                <div class="row">
                                    <div class="col-xs-12">
                                        {!! Form::text('nome', '', ['placeholder' => 'Nome', 'class' => 'form-control', 'required', 'maxlength' => '62']) !!}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        {!! Form::email('email', '', ['placeholder' => 'E-mail', 'class' => 'form-control', 'required', 'maxlength' => '62']) !!}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6">
                                        {!! Form::input('password', 'password', '', ['placeholder' => 'Senha', 'class' => 'form-control', 'id' => 'senha-usuario', 'required']) !!}
                                    </div>

                                    <div class="col-xs-6">
                                        {!! Form::input('password', 'password_confirmation', '', ['placeholder' => 'Confirmar senha', 'class' => 'form-control', 'required']) !!}
                                    </div>
                                </div>

                                {!! Form::submit('Cadastrar', ['class' => 'form-control btn btn-primary']) !!}
                            {!! Form::close() !!}
                        </div>

                        <div class="modal-footer">
                            <p>Ao se cadastrar você concorda com os <a href="#">termos<br>e condições</a> do infochat.com.br</p>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div id="modal-login-usuario" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1>Bem-vindo de volta!</h1>
                            <p>Acesse seu perfil para começar</p>
                        </div>

                        <div class="modal-body">
                            {!! Form::open(['method' => 'post', 'action' => 'UserController@login', 'id' => 'form-login-usuario']) !!}
                                <div class="row">
                                    <div class="col-xs-12">
                                        {!! Form::email('email', '', ['placeholder' => 'E-mail', 'class' => 'form-control', 'required']) !!}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        {!! Form::input('password', 'password', '', ['placeholder' => 'Senha', 'class' => 'form-control', 'required']) !!}
                                    </div>
                                </div>

                                {!! Form::submit('Entrar', ['class' => 'form-control btn btn-primary']) !!}
                            {!! Form::close() !!}
                        </div>

                        <div class="modal-footer">
                            <p><a href="#">Recuperar senha</a></p>
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

        <div class="modal fade" id="modal-como-funciona" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <a href="#" class="arrow prev" data-position="1"></a>

                    <img src="{{ asset('img/como-funciona/1.png') }}" class="img-responsive" />

                    <a href="#" class="arrow next" data-position="1"></a>

                    <div class="position">
                        @for($i = 1; $i <= 5; $i++)
                            <a href="#" data-position="{{ $i }}" class="advance {{ $i == 1 ? 'active' : '' }}"></a>
                        @endfor
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div><!-- /.modal -->

        @if($app->environment('development'))
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

        <script>
            @if(Auth::guard('web')->check())
                var logged = true;
            @else
                var logged = false;
            @endif
        </script>

        <script src="{{ mix('/js/global.js') }}"></script>
    </body>
</html>
