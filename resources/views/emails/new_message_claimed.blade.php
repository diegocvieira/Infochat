<html>
    <head>
        <style type="text/css">
            /**This is to overwrite Outlook.com’s Embedded CSS************/
            table {border-collapse:separate;}
            .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td {line-height: 100%}
            .ExternalClass {width: 100%;}
            .ExternalClass {display:inline-block; line-height: 100% !important;}
            /**This is to center your email in Outlook.com************/

            .yshortcuts {color: #3f51b5;}

            p {margin: 0; padding: 0; margin-bottom: 0;} /*optional*/
            a, a:link, a:visited {color: rgb(66, 133, 244); text-decoration: none;} /*optional*/
            a, a:hover {text-decoration: none;}
            img:hover {cursor: default;}
        </style>
    </head>
    <body style="background: rgb(241, 240, 240);" alink="#3f51b5" link="#3f51b5" bgcolor="rgb(241, 240, 240)" text="#FFFFFF">
        <span id="body_style" style="padding: 0; display: block">
            <table id="Tabela_01" style="margin-bottom: 30px;" width="600" height="auto" border="0" cellpadding="0" cellspacing="0" align="center">
                <tr>
                    <td>
                        <img style="display: block; margin: 30px auto 30px auto; width: 50px;" src="{{ asset('img/icon-logo.png') }}" />
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #fff; padding: 60px 50px 0 50px;">
                        <span style="display: block; cursor: default; font-size: 16.6px; color: rgb(220, 115, 110);">Esse e-mail não é spam e nem mesmo propaganda</span>
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #fff; padding: 20px 50px 0 50px;">
                        <span style="display: block; cursor: default; font-size: 25px; color: rgb(49, 49, 49); font-weight: 700">Você ou o seu estabelecimento está cadastrado no infochat e um cliente real entrou em contato</span>
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #fff; padding: 20px 50px 0 50px;">
                        <span style="display: block; cursor: default; font-size: 14.5px; color: rgb(49, 49, 49);">Isso pode ter acontecido porque estamos sempre nas primeiras posições do Google quando alguém pesquisa pelo seu serviço na sua cidade.</span>
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #fff; padding: 40px 50px 0 50px;">
                        @if($client['image'])
                            <img src="{{ asset('uploads/' . $client['id'] . '/' . _getOriginalImage($client['image'])) }}" style="float: left; width: 38px; height: 38px; object-fit: cover;" />
                        @else
                            <span style="float: left; width: 38px; text-align: center; height: 38px; border: 1px solid rgb(230, 230, 230);">
                                <img src="{{ asset('img/paisagem.png') }}" style="width: 18px; height: 18px; position: relative; top: 50%; margin-top: -9px;" />
                            </span>
                        @endif

                        <span style="float: left; margin: 10px 0 0 10px; cursor: default; font-weight: 700; font-size: 16.6; color: rgb(100, 100, 100);">
                            {{ $client['name'] }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #fff; padding: 0 50px 30px 50px;">
                        <span style="display: block; border-bottom: 1px solid rgb(235, 235, 235); padding: 0 0 50px 0; cursor: default; margin: 10px 0; font-size: 14.5; color: rgb(150, 150, 150);">{{ $client['message'] }}</span>
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #fff; padding: 0 50px;">
                        <span style="display: block; cursor: default; margin: 10px 0; font-size: 16.6; color: rgb(100, 100, 100);">Para responder seu cliente clique no botão abaixo</span>
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #fff; padding: 0 50px 40px 50px;">
                        <a href="{{ $claimed_url }}" style="float: left; font-weight: 700; border-radius: 25px; cursor: pointer; font-size: 14.5; margin-top: 10px; background-color: rgb(241, 240, 240); color: rgb(100, 100, 100); padding: 13px 25px;">REIVINDICAR PERFIL</a>

                        <span style="float: left; margin: 23px 0 0 10px; font-size: 14.5px; color: rgb(150, 150, 150); cursor: default;">ou <a href="{{ $work_url }}" style="color: rgb(118, 145, 198); cursor: pointer;">clique aqui</a> para primeiro ver o perfil</span>
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #fff; padding: 30px 50px 0 50px;">
                        <span style="display: block; cursor: default; font-size: 25px; color: rgb(49, 49, 49); font-weight: 700">O QUE É INFOCHAT?</span>
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #fff; padding: 20px 50px 0 50px;">
                        <span style="display: block; cursor: default; font-size: 14.5px; color: rgb(150, 150, 150);">Em resumo, é um whatsapp para encontrar e falar com profissionais e estabelecimentos.</span>
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #fff; padding: 20px 50px 50px 50px;">
                        <span style="display: block; cursor: default; font-size: 14.5px; color: rgb(150, 150, 150);">O infochat é um serviço <b>gratuito</b> de atendimento online ao cliente, onde você pode ser encontrado de forma rápida e fácil e também atender clientes interessandos nos seus serviços através de mensagens de texto.</span>
                    </td>
                </tr>
            </table>
        </span>
    </body>
</html>
