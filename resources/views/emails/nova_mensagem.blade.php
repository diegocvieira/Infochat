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
            <table id="Tabela_01" width="600" height="auto" border="0" cellpadding="0" cellspacing="0" align="center">
                <tr>
                    <td>
                        <img style="display: block; margin: auto auto 40px auto; width: 50px;" src="{{ asset('img/icon-logo.png') }}" />
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #fff; padding: 40px 50px 0 50px;">
                        <span style="display: block; cursor: default; font-size: 25px; color: rgb(49, 49, 49); font-weight: 700">Tem mensagem para você no infochat</span>
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #fff; padding: 0 50px;">
                        <span style="display: block; cursor: default; margin: 10px 0; font-size: 16.6; color: rgb(100, 100, 100);">Para visualizar clique no botão abaixo ou acesse sua conta em <a href="{{ url('/') }}" style="color: rgb(118, 145, 198);">infochat.com.br</a></span>
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #fff; padding: 0 50px 40px 50px;">
                        <a href="{{ url('/') }}" style="float: left; font-weight: 700; border-radius: 20px; cursor: pointer; font-size: 14.5; margin-top: 10px; background-color: rgb(241, 240, 240); color: rgb(100, 100, 100); padding: 13px 40px;">VER MENSAGEM</a>
                    </td>
                </tr>
            </table>
        </span>
    </body>
</html>