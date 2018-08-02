$(document).ready(function() {
    $('body').css('opacity', '1');

    // Aparecer e ocultar mensagens flash session
    setTimeout(function() {
        $('.session-flash').fadeOut();
    }, 5000);

    // Passar imagens do modal nas flechas do teclado
    $('#modal-como-funciona').on('keydown', function(e) {
        var position = parseInt($('#modal-como-funciona').find('.position .active').data('position'));

        if(e.which == 39 && position < 5) {
            $('#modal-como-funciona').find('.next').trigger('click'); // right
        } else if(e.which == 37 && position > 1) {
            $('#modal-como-funciona').find('.prev').trigger('click'); // left
        }
    });
    $('#modal-como-funciona').on('click', '.arrow, .advance', function(e) {
        e.preventDefault();

        var modal = $('#modal-como-funciona'),
            position = parseInt($(this).data('position'));

        // Verifica se o click foi nas flechas
        if($(this).hasClass('arrow')) {
            // Faz o calculo para next ou prev
            position = $(this).hasClass('next') ? position + 1 : position - 1;
        }

        // Adiciona a imagem
        $('#modal-como-funciona').find('img').attr('src', '/img/como-funciona/' + position + '.png');

        // Atualiza a posicao da imagem na flecha
        modal.find('.arrow').data('position', position);
        // Atualiza a class active nos circulos
        modal.find('.advance').removeClass('active');
        modal.find('.advance[data-position=' + position + ']').addClass('active');

        // Oculta a flecha next se estiver na ultima imagem
        position == 5 ? modal.find('.next').hide() : modal.find('.next').show();
        // Oculta a flecha prev se estiver na primeira imagem
        position == 1 ? modal.find('.prev').hide() : modal.find('.prev').show();
    });

    // Modal de alertas
    function modalAlert(body, btn) {
        var modal = $('#modal-alert');

        modal.find('.modal-footer .btn2').remove();

        modal.find('.modal-body').html(body);
        modal.find('.modal-footer .btn').text(btn);
        modal.modal('show');

        $('.modal-backdrop:last').css('z-index', '1080');
    }

    // Mascara de telefone
    var SPMaskBehavior = function (val) {
      return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
    spOptions = {
      onKeyPress: function(val, e, field, options) {
          field.mask(SPMaskBehavior.apply({}, arguments), options);
        }
    };

    // Fazer validate ignorar campos ocultos
    $.validator.setDefaults({ ignore: ":hidden:not(.selectpicker)" });

    // Remover class de erro ao selecionar um valor valido
    $(document).on('change', 'select.selectpicker', function() {
        if($(this).val() != '') {
            $(this).prev().prev().removeClass('error');
            $(this).parent().removeClass('error');
        }
    });

    ////////////////////////////// ASIDE (CATEGORIAS E CIDADE) //////////////////////////////

    // Exibir categorias e subscategorias e pesquisar trabalhos ao seleciona-las
    $(document).on('click', '#categorias .cat-search', function(e) {
        e.preventDefault();

        var next = $(this).parent().next(),
            submit = $(this).hasClass('close-area') ? false : true,
            $this = $(this);

        if($(this).hasClass('area')) {
            var area = $('.aside-categorias').find('.area').parent();

            $('.aside-categorias').find('.cats').remove();

            $.ajax({
                url: 'aside/categorias/' + $(this).data('search'),
                method: 'GET',
                dataType:'json',
                success: function(data) {
                    if(submit) {
                        $this.parent().after("<div class='cats'></div>");

                        $(data.categorias).each(function(index, element) {
                            $this.parent().next().append("<li><a href='#' class='categoria cat-search' data-search='" + element.titulo + "'>" + element.titulo + "</a></li>");
                        });
                    }

                    $this.hasClass('close-area') ? area.show() : area.not($this.parent()).hide();

                    $this.toggleClass('close-area');
                }
            });

            $('#form-search-area').val($(this).hasClass('close-area') ? '' : $this.data('search'));
            $('#form-search-tag').val('');
        } else {
            if($(this).hasClass('categoria')) {
                $.ajax({
                    url: 'aside/subcategorias/' + $(this).data('search'),
                    method: 'GET',
                    dataType:'json',
                    success: function(data) {
                        $this.parent().after("<div class='subs'></div>");

                        $(data.subcategorias).each(function(index, element) {
                            $this.parent().next().append("<li><a href='#' class='cat-search' data-search='" + element.titulo + "'>" + element.titulo + "</a></li>");
                        });

                        $('.aside-categorias').find('.subs').not($this.parent().next()).hide();
                    }
                });
            } else {
                $('.aside-categorias').find('.subs a').removeClass('active');

                $(this).addClass('active');
            }

            $('#form-search-tag').val($(this).data('search'));
        }

        //if(submit) {
            $('#form-search').submit();
        //}
    });

    // Exibir areas e pesquisar trabalhos ao selecionar um novo tipo
    $('#categorias').on('click', '.tipo', function(e) {
        e.preventDefault();

        $('#categorias').find('.tipo').removeClass('active');

        $(this).addClass('active');

        $('#form-search-tipo').val($(this).data('search'));
        $('#form-search').submit();

        $('#categorias').find('.area').remove();

        $.ajax({
            url: 'aside/areas/' + $(this).data('search'),
            method: 'GET',
            dataType:'json',
            success: function(data) {
                $(data.areas).each(function(index, element) {
                    $('#categorias').append("<li><a href='#' class='area cat-search' data-search='" + element.slug + "' style='background-image: url(img/categorias/" + element.slug + ".png);'>" + element.titulo + "</a></li>")
                });
            }
        });
    });

    // Exibir form de busca por cidades
    $('.aside-categorias').on('click', '.cidade-atual', function(e) {
        e.preventDefault();

        $('#form-busca-cidade').show();
        $('#form-busca-cidade').find('input[type=text]').val('').focus();
    });

    //Fechar busca por cidade e categorias
    $(document).click(function(e) {
        if(!$(e.target).closest('.cidades').length) {
            $('.cidades').find('#form-busca-cidade').hide();
            $('.cidades').find('#modal-busca-cidade').remove();
        }

        if(!$(e.target).closest('#categorias').length) {
            $('#categorias').find('#form-busca-categoria').hide();
            $('#categorias').find('#modal-busca-categorias').remove();
            $('.open-busca-categoria').show();
        }
    });

    // Enviar form de busca por cidades
    $('#form-busca-cidade').on('submit', function(e) {
        e.preventDefault();

        if($(this).find('input[type=text]').val()) {
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                dataType:'json',
                data: $(this).serialize(),
                success: function (data) {
                    var modal = $('#modal-busca-cidade');

                    modal.length ? modal.find('li').remove() : $('#form-busca-cidade').append("<div id='modal-busca-cidade'><ul></ul></div>");

                    $(data.cidades).each(function(index, element) {
                        $('#modal-busca-cidade').find('ul').append("<li><a href='/cidades/set/" + element.id + "'>" + element.title + ' - ' + element.estado.letter + "</a></li>");
                    });
                }
            });
        }

        return false;
    });

    // Exibir form de busca por categorias
    $('.aside-categorias').on('click', '.open-busca-categoria', function(e) {
        e.preventDefault();

        $('.open-busca-categoria').hide();
        $('#form-busca-categoria').show();
        $('#form-busca-categoria').find('input[type=text]').val('').focus();
    });

    // Enviar form de busca por categorias
    $('#form-busca-categoria').on('submit', function(e) {
        e.preventDefault();

        if($(this).find('input[type=text]').val()) {
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                dataType:'json',
                data: $(this).serialize(),
                success: function (data) {
                    var modal = $('#modal-busca-categorias');

                    modal.length ? modal.find('li').remove() : $('#form-busca-categoria').append("<div id='modal-busca-categorias'><ul></ul></div>");

                    $(data.categorias).each(function(index, element) {
                        $('#modal-busca-categorias').find('ul').append("<li><a href='#' class='cat-search' data-search='" + element.titulo + "'>" + element.titulo + "</a></li>");
                    });
                }
            });
        }

        return false;
    });





















    ////////////////////////////// RESULTADOS DAS BUSCAS //////////////////////////////

    // Filtrar a ordem dos resultados
    $(document).on('change', 'select.filtro-ordem', function() {
        $('#form-search-ordem').val($(this).val());

        $('#form-search').submit();
    });

    // Submeter form principal de busca
    $(document).on('submit', '#form-search', function() {
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            dataType:'json',
            data: $(this).serialize(),
            success: function(data) {
                window.history.pushState('', '', data.url);

                $('.abas-resultados').find('a').removeClass('active');
                $('.abas-resultados').find('a[data-type=resultado]').addClass('active');

                if(data.trabalhos.length > 0) {
                    $('div.filtro-ordem').show();

                    $('#form-search-results').html(data.trabalhos);
                } else {
                    $('div.filtro-ordem').hide();

                    $('#form-search-results').html("<div class='sem-resultados'><p>Sua pesquisa não encontrou resultado.<br>Verifique se todas as palavras estão corretas ou tente palavras-chave diferentes.</p></div>");
                }
            }
        });

        return false;
    });

    // Alternar entre abas dos resultados e mensagens
    $('.abas-resultados').on('click', 'a', function(e) {
        e.preventDefault();

        $('.abas-resultados').find('a').removeClass('active');

        $(this).addClass('active');

        var type = $(this).data('type');

        if(type == 'resultado') {
            $('#form-search').submit();
        } else {
            $.ajax({
                url: $(this).attr('href'),
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('div.filtro-ordem').hide();

                    window.history.pushState('', '', '/');

                    $('#form-search-results').html(data.mensagens);
                }
            });
        }
    });

    // Scroll infinito nos resultados
    $('#form-search-results').on('scroll', function() {
        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            var form = $('#form-search'),
                form_result = $('#form-search-results');

            form.find('#form-search-offset').val(form_result.find('.result').length);

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                dataType:'json',
                data: form.serialize(),
                success: function(data) {
                    form_result.append(data.trabalhos);
                }
            });
        }
    });



















    ////////////////////////////// CHAT //////////////////////////////

    $(document).on('click', '.ver-perfil', function(e) {
        e.stopPropagation();

        alert('ok');
    });

    $(document).on('click', '.open-chat', function(e) {
        e.preventDefault();

        $('#form-search-results').find('.result').removeClass('active-trabalho');

        $(this).addClass('active-trabalho');

        $.ajax({
            url: '/mensagem/chat/' + $(this).data('id') + '/' + $(this).data('type'),
            method: 'GET',
            dataType:'json',
            success: function(data) {
                $('.chat').html(data.trabalho);

                // Scroll to bottom
                $('.chat').find('.mensagens').scrollTop($('.chat').find('.mensagens')[0].scrollHeight);

                //Scroll custom para funcionar em conteudo carregdao dinamicamente
                listenForScrollEvent($('.chat .mensagens'));

                $('#form-enviar-msg').find('input[type=text]').focus();
            }
        });

        if(logado == true) {
            var interval = null;

            // Limpar setinterval anterior
            clearInterval(interval);

            // Atualizar chat em tempo real
            interval = setInterval(function() {
                $.ajax({
                    url: 'mensagem/list/' + $('.chat').find('#user_id').val() + '/0',
                    method: 'GET',
                    dataType:'json',
                    success: function(data) {
                        var div = $('.chat').find('.mensagens');

                        // Verifica se a ultima mensagem que esta no chat foi a ultima recebida
                        if(div.find('.recebida:last p').text() != data.last_msg) {
                            div.html(data.mensagens);
                        }
                    }
                });
            }, 10000);
        }
    });

    $(document).on('change', '#form-avaliar input[type=radio]', function() {
        $('#form-avaliar').submit();
    });

    $(document).on('submit', '#form-avaliar', function() {
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            dataType:'json',
            data: $(this).serialize(),
            success: function(data) {
            }
        });

        return false;
    });

    $(document).on('submit', '#form-enviar-msg', function() {
        var input = $(this).find('input[type=text]');

        if(input.val()){
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                dataType:'json',
                data: $(this).serialize(),
                success: function(data) {
                    $('.chat').find('.sem-mensagens').remove();

                    var div = $('.chat').find('.mensagens');

                    div.append("<div class='row enviada'><div class='msg'><p>" + data.msg + "</p><span>" + data.hora + "</span></div></div>");

                    div.scrollTop(div[0].scrollHeight);

                    input.val('');
                }
            });
        }

        return false;
    });

    // Scroll infinito nas mensagens do chat
    $(document).on('custom-scroll', '.chat .mensagens', function() {
        if($(this).scrollTop() == 0) {
            var div = $('.chat').find('.mensagens');

            $.ajax({
                url: 'mensagem/list/' + $('.chat').find('.trabalho-id').val() + '/' + div.find('.msg').length,
                method: 'GET',
                dataType:'json',
                success: function(data) {
                    div.prepend(data.mensagens);

                    // Verifica se o nome do dia ja existe e o remove
                    var seen = {};
                    $('.chat .dia h3').each(function() {
                        var txt = $(this).text();

                        seen[txt] ? $(this).parent().remove() : seen[txt] = true;
                    });
                }
            });
        }
    });

    // Scroll custom para funcionar em conteudo carregado dinamicamente
    function listenForScrollEvent(e) {
        e.on('scroll', function() {
            e.trigger('custom-scroll');
        });
    }






































    ////////////////////////////// MODAL LOGIN E CADASTRO //////////////////////////////

    $('#form-login-usuario').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function (data) {
                if(data.status == true) {
                    window.location = '/';
                } else {
                    modalAlert(data.msg);
                }
            }
        });

        return false;
    });

    $('#form-cadastro-usuario').validate({
        rules: {
            nome: {
                required: true,
                minlength: 1,
                maxlength: 100
            },
            email: {
                required: true,
                minlength: 1,
                maxlength: 62,
                email: true
            },
            password: {
                required: true,
                minlength: 8
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: "#senha-usuario"
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass).addClass(validClass);
        },
        errorPlacement: function(error, element) {
        },
        submitHandler: function(form) {
            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
                success: function (data) {
                    if(data.status == true) {
                        window.location = '/';
                    } else {
                        var modal = $('#modal-alert');
                        modal.find('.modal-body').text(data.msg);
                        modal.modal('show');
                    }
                }
            });

            return false;
        }
    });

    ////////////////////////////// MODAL DAS CONFIGURACOES DO TRABALHO //////////////////////////////

    function insertTag(value) {
        $('#insert-tag').before("<div class='new-tag'><span>" + value + "</span><input style='display: none; width:" + ((value.length + 1) * 10) + "px;' type='text' name='tag[]' value='" + value + "' /><a href='#'></a></div>");

        $('#insert-tag').val('');
        $('select.categoria, select.subcategoria').val('').selectpicker('refresh');

        var count = parseInt($('.tags').find('.count-tag').text());

        $('.tags').find('.count-tag').text(count - 1);

        if(count == 1) {
            $(this).remove();
        }
    }

    // Inserir uma nova tag
    $(document).on('keydown', '#insert-tag', function(e) {
        $('.tags').find('.placeholder').hide();

        // Inserir tag ao apertar enter
        if(e.which == 13 && $(this).val()) {
            insertTag($(this).val());

            return false;
        }
    });

    // Inserir tag com a categoria e buscar subcategorias
    $(document).on('change', 'select.categoria, select.subcategoria', function() {
        $('.tags').find('.placeholder').hide();

        // Preencher select das subcategorias
        if($(this).hasClass('categoria')) {
            var select = $('#form-trabalho-config').find('select.subcategoria');

            select.find('option').remove();

            $.ajax({
                url: '/subcategorias/get/' + $(this).val(),
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $(data.subcategorias).each(function(index, element) {
                        select.append("<option value='" + element.id + "' data-title='" + element.titulo + "'>" + element.titulo + "</option>");
                    });

                    select.selectpicker('refresh');
                }
            });
        }

        insertTag($(this).find(':selected').data('title'));
    });

    // Mostrar placeholder novamente
    $(document).on('focusout', '.tags label', function() {
        if(parseInt($('.count-tag').text()) == 10) {
            $('.tags').find('.placeholder').show();
        }
    });

    // Editar tag
    $(document).on('click', '.new-tag span', function(e) {
        e.preventDefault();

        $(this).toggle();
        $(this).parent().find('input').toggle().focus();
    });

    // Setar a edicao da tag
    $(document).on('focusout', '.new-tag input', function(e) {
        e.preventDefault();

        var parent = $(this).parent(),
            value = $(this).val(),
            span = parent.find('span');

        $(this).toggle();
        span.toggle();

        span.text(value);

        if(!value) {
            parent.remove();
        }
    });

    // Remover tag
    $(document).on('click', '.new-tag a', function(e) {
        e.preventDefault();

        var count = $('.tags').find('.count-tag');

        $('.tags').find('.count-tag').text(parseInt(count.text()) + 1);

        $(this).parent().remove();

        $('.tags').find('#insert-tag').focus();
    });

    // Alternar entre abas
    $(document).on('click', '#form-trabalho-config .abas a', function(e) {
        e.preventDefault();

        $('.aba-aberta').hide();
        $('.abas').find('a').removeClass('active');

        $('.' + $(this).data('type')).show();
        $(this).addClass('active');
    });

    // Capturar endereco automaticamente com o cep
    $(document).on('blur', '#cep', function() {
        var cep_original = this.value;
        var cep = this.value.replace(/\D/g,'');
        var url = "https://viacep.com.br/ws/" + cep + "/json/";

        if(cep.length != 8) {
            modalAlert('Não identificamos o CEP que você informou, verifique se digitou corretamente.', 'OK');

            return false;
        }

        $.getJSON(url, function(dadosRetorno) {
            if (dadosRetorno.erro == true) {
                $("#logradouro").val('');
                $("#bairro").val('');
                $("#cidade").val('');
                $("#estado").val('');

                modalAlert('Não identificamos o CEP que você informou, verifique se digitou corretamente.', 'OK');
            } else {
                $("#logradouro").val(dadosRetorno.logradouro);
                $("#bairro").val(dadosRetorno.bairro);
                $("#cidade").val(dadosRetorno.localidade);
                $("#estado").val(dadosRetorno.uf);

                dadosRetorno.bairro != '' ? $("#numero").focus() : $("#bairro").focus();
            }
        }).fail(function() {
            modalAlert('Houve um erro ao identificar o seu CEP. Entre em contato conosco.', 'OK');

            return false;
        });
    });
    $(document).on('keyup', '#cep', function(e) {
        if(this.value.length == 9){
            $('#cep').trigger('blur');
        }
    });

    // Validar slug
    $(document).on('keyup', '#slug', function() {
        $(this).val(convertToSlug($(this).val()));
    });
    function convertToSlug(v) {
        if(!v) {
            return '';
        }

        var map = {
            "2d":"-","20":"-","24":"s","26":"and","30":"0","31":"1","32":"2","33":"3","34":"4","35":"5","36":"6","37":"7","38":"8","39":"9","41":"A","42":"B","43":"C","44":"D","45":"E","46":"F","47":"G","48":"H","49":"I","50":"P","51":"Q","52":"R","53":"S","54":"T","55":"U","56":"V","57":"W","58":"X","59":"Y","61":"a","62":"b","63":"c","64":"d","65":"e","66":"f","67":"g","68":"h","69":"i","70":"p","71":"q","72":"r","73":"s","74":"t","75":"u","76":"v","77":"w","78":"x","79":"y","100":"A","101":"a","102":"A","103":"a","104":"A","105":"a","106":"C","107":"c","108":"C","109":"c","110":"D","111":"d","112":"E","113":"e","114":"E","115":"e","116":"E","117":"e","118":"E","119":"e","120":"G","121":"g","122":"G","123":"g","124":"H","125":"h","126":"H","127":"h","128":"I","129":"i","130":"I","131":"i","132":"IJ","133":"ij","134":"J","135":"j","136":"K","137":"k","138":"k","139":"L","140":"l","141":"L","142":"l","143":"N","144":"n","145":"N","146":"n","147":"N","148":"n","149":"n","150":"O","151":"o","152":"OE","153":"oe","154":"R","155":"r","156":"R","157":"r","158":"R","159":"r","160":"S","161":"s","162":"T","163":"t","164":"T","165":"t","166":"T","167":"t","168":"U","169":"u","170":"U","171":"u","172":"U","173":"u","174":"W","175":"w","176":"Y","177":"y","178":"Y","179":"Z","180":"b","181":"B","182":"b","183":"b","184":"b","185":"b","186":"C","187":"C","188":"c","189":"D","190":"E","191":"F","192":"f","193":"G","194":"Y","195":"h","196":"i","197":"I","198":"K","199":"k","200":"A","201":"a","202":"A","203":"a","204":"E","205":"e","206":"E","207":"e","208":"I","209":"i","210":"R","211":"r","212":"R","213":"r","214":"U","215":"u","216":"U","217":"u","218":"S","219":"s","220":"n","221":"d","222":"8","223":"8","224":"Z","225":"z","226":"A","227":"a","228":"E","229":"e","230":"O","231":"o","232":"Y","233":"y","234":"l","235":"n","236":"t","237":"j","238":"db","239":"qp","240":"<","241":"?","242":"?","243":"B","244":"U","245":"A","246":"E","247":"e","248":"J","249":"j","250":"a","251":"a","252":"a","253":"b","254":"c","255":"e","256":"d","257":"d","258":"e","259":"e","260":"g","261":"g","262":"g","263":"Y","264":"x","265":"u","266":"h","267":"h","268":"i","269":"i","270":"w","271":"m","272":"n","273":"n","274":"N","275":"o","276":"oe","277":"m","278":"o","279":"r","280":"R","281":"R","282":"S","283":"f","284":"f","285":"f","286":"f","287":"t","288":"t","289":"u","290":"Z","291":"Z","292":"3","293":"3","294":"?","295":"?","296":"5","297":"C","298":"O","299":"B","363":"a","364":"e","365":"i","366":"o","367":"u","368":"c","369":"d","386":"A","388":"E","389":"H","390":"i","391":"A","392":"B","393":"r","394":"A","395":"E","396":"Z","397":"H","398":"O","399":"I","400":"E","401":"E","402":"T","403":"r","404":"E","405":"S","406":"I","407":"I","408":"J","409":"jb","410":"A","411":"B","412":"V","413":"G","414":"D","415":"E","416":"ZH","417":"Z","418":"I","419":"Y","420":"R","421":"S","422":"T","423":"U","424":"F","425":"H","426":"TS","427":"CH","428":"SH","429":"SCH","430":"a","431":"b","432":"v","433":"g","434":"d","435":"e","436":"zh","437":"z","438":"i","439":"y","440":"r","441":"s","442":"t","443":"u","444":"f","445":"h","446":"ts","447":"ch","448":"sh","449":"sch","450":"e","451":"e","452":"h","453":"r","454":"e","455":"s","456":"i","457":"i","458":"j","459":"jb","460":"W","461":"w","462":"Tb","463":"tb","464":"IC","465":"ic","466":"A","467":"a","468":"IA","469":"ia","470":"Y","471":"y","472":"O","473":"o","474":"V","475":"v","476":"V","477":"v","478":"Oy","479":"oy","480":"C","481":"c","490":"R","491":"r","492":"F","493":"f","494":"H","495":"h","496":"X","497":"x","498":"3","499":"3","500":"d","501":"d","502":"d","503":"d","504":"R","505":"R","506":"R","507":"R","508":"JT","509":"JT","510":"E","511":"e","512":"JT","513":"jt","514":"JX","515":"JX","531":"U","532":"D","533":"Q","534":"N","535":"T","536":"2","537":"F","538":"r","539":"p","540":"z","541":"2","542":"n","543":"x","544":"U","545":"B","546":"j","547":"t","548":"n","549":"C","550":"R","551":"8","552":"R","553":"O","554":"P","555":"O","556":"S","561":"w","562":"f","563":"q","564":"n","565":"t","566":"q","567":"t","568":"n","569":"p","570":"h","571":"a","572":"n","573":"a","574":"u","575":"j","576":"u","577":"2","578":"n","579":"2","580":"n","581":"g","582":"l","583":"uh","584":"p","585":"o","586":"S","587":"u","4a":"J","4b":"K","4c":"L","4d":"M","4e":"N","4f":"O","5a":"Z","6a":"j","6b":"k","6c":"l","6d":"m","6e":"n","6f":"o","7a":"z","a2":"c","a3":"f","a5":"Y","a7":"s","a9":"c","aa":"a","ae":"r","b2":"2","b3":"3","b5":"u","b6":"p","b9":"1","c0":"A","c1":"A","c2":"A","c3":"A","c4":"A","c5":"A","c6":"AE","c7":"C","c8":"E","c9":"E","ca":"E","cb":"E","cc":"I","cd":"I","ce":"I","cf":"I","d0":"D","d1":"N","d2":"O","d3":"O","d4":"O","d5":"O","d6":"O","d7":"X","d8":"O","d9":"U","da":"U","db":"U","dc":"U","dd":"Y","de":"p","df":"b","e0":"a","e1":"a","e2":"a","e3":"a","e4":"a","e5":"a","e6":"ae","e7":"c","e8":"e","e9":"e","ea":"e","eb":"e","ec":"i","ed":"i","ee":"i","ef":"i","f0":"o","f1":"n","f2":"o","f3":"o","f4":"o","f5":"o","f6":"o","f8":"o","f9":"u","fa":"u","fb":"u","fc":"u","fd":"y","ff":"y","10a":"C","10b":"c","10c":"C","10d":"c","10e":"D","10f":"d","11a":"E","11b":"e","11c":"G","11d":"g","11e":"G","11f":"g","12a":"I","12b":"i","12c":"I","12d":"i","12e":"I","12f":"i","13a":"l","13b":"L","13c":"l","13d":"L","13e":"l","13f":"L","14a":"n","14b":"n","14c":"O","14d":"o","14e":"O","14f":"o","15a":"S","15b":"s","15c":"S","15d":"s","15e":"S","15f":"s","16a":"U","16b":"u","16c":"U","16d":"u","16e":"U","16f":"u","17a":"z","17b":"Z","17c":"z","17d":"Z","17e":"z","17f":"f","18a":"D","18b":"d","18c":"d","18d":"q","18e":"E","18f":"e","19a":"l","19b":"h","19c":"w","19d":"N","19e":"n","19f":"O","1a0":"O","1a1":"o","1a2":"P","1a3":"P","1a4":"P","1a5":"p","1a6":"R","1a7":"S","1a8":"s","1a9":"E","1aa":"l","1ab":"t","1ac":"T","1ad":"t","1ae":"T","1af":"U","1b0":"u","1b1":"U","1b2":"U","1b3":"Y","1b4":"y","1b5":"Z","1b6":"z","1b7":"3","1b8":"3","1b9":"3","1ba":"3","1bb":"2","1bc":"5","1bd":"5","1be":"5","1bf":"p","1c4":"DZ","1c5":"Dz","1c6":"dz","1c7":"Lj","1c8":"Lj","1c9":"lj","1ca":"NJ","1cb":"Nj","1cc":"nj","1cd":"A","1ce":"a","1cf":"I","1d0":"i","1d1":"O","1d2":"o","1d3":"U","1d4":"u","1d5":"U","1d6":"u","1d7":"U","1d8":"u","1d9":"U","1da":"u","1db":"U","1dc":"u","1dd":"e","1de":"A","1df":"a","1e0":"A","1e1":"a","1e2":"AE","1e3":"ae","1e4":"G","1e5":"g","1e6":"G","1e7":"g","1e8":"K","1e9":"k","1ea":"Q","1eb":"q","1ec":"Q","1ed":"q","1ee":"3","1ef":"3","1f0":"J","1f1":"dz","1f2":"dZ","1f3":"DZ","1f4":"g","1f5":"G","1f6":"h","1f7":"p","1f8":"N","1f9":"n","1fa":"A","1fb":"a","1fc":"AE","1fd":"ae","1fe":"O","1ff":"o","20a":"I","20b":"i","20c":"O","20d":"o","20e":"O","20f":"o","21a":"T","21b":"t","21c":"3","21d":"3","21e":"H","21f":"h","22a":"O","22b":"o","22c":"O","22d":"o","22e":"O","22f":"o","23a":"A","23b":"C","23c":"c","23d":"L","23e":"T","23f":"s","24a":"Q","24b":"q","24c":"R","24d":"r","24e":"Y","24f":"y","25a":"e","25b":"3","25c":"3","25d":"3","25e":"3","25f":"j","26a":"i","26b":"I","26c":"I","26d":"I","26e":"h","26f":"w","27a":"R","27b":"r","27c":"R","27d":"R","27e":"r","27f":"r","28a":"u","28b":"v","28c":"A","28d":"M","28e":"Y","28f":"Y","29a":"B","29b":"G","29c":"H","29d":"j","29e":"K","29f":"L","2a0":"q","2a1":"?","2a2":"c","2a3":"dz","2a4":"d3","2a5":"dz","2a6":"ts","2a7":"tf","2a8":"tc","2a9":"fn","2aa":"ls","2ab":"lz","2ac":"ww","2ae":"u","2af":"u","2b0":"h","2b1":"h","2b2":"j","2b3":"r","2b4":"r","2b5":"r","2b6":"R","2b7":"W","2b8":"Y","2df":"x","2e0":"Y","2e1":"1","2e2":"s","2e3":"x","2e4":"c","36a":"h","36b":"m","36c":"r","36d":"t","36e":"v","36f":"x","37b":"c","37c":"c","37d":"c","38a":"I","38c":"O","38e":"Y","38f":"O","39a":"K","39b":"A","39c":"M","39d":"N","39e":"E","39f":"O","3a0":"TT","3a1":"P","3a3":"E","3a4":"T","3a5":"Y","3a6":"O","3a7":"X","3a8":"Y","3a9":"O","3aa":"I","3ab":"Y","3ac":"a","3ad":"e","3ae":"n","3af":"i","3b0":"v","3b1":"a","3b2":"b","3b3":"y","3b4":"d","3b5":"e","3b6":"c","3b7":"n","3b8":"0","3b9":"1","3ba":"k","3bb":"j","3bc":"u","3bd":"v","3be":"c","3bf":"o","3c0":"tt","3c1":"p","3c2":"s","3c3":"o","3c4":"t","3c5":"u","3c6":"q","3c7":"X","3c8":"Y","3c9":"w","3ca":"i","3cb":"u","3cc":"o","3cd":"u","3ce":"w","3d0":"b","3d1":"e","3d2":"Y","3d3":"Y","3d4":"Y","3d5":"O","3d6":"w","3d7":"x","3d8":"Q","3d9":"q","3da":"C","3db":"c","3dc":"F","3dd":"f","3de":"N","3df":"N","3e2":"W","3e3":"w","3e4":"q","3e5":"q","3e6":"h","3e7":"e","3e8":"S","3e9":"s","3ea":"X","3eb":"x","3ec":"6","3ed":"6","3ee":"t","3ef":"t","3f0":"x","3f1":"e","3f2":"c","3f3":"j","3f4":"O","3f5":"E","3f6":"E","3f7":"p","3f8":"p","3f9":"C","3fa":"M","3fb":"M","3fc":"p","3fd":"C","3fe":"C","3ff":"C","40a":"Hb","40b":"Th","40c":"K","40d":"N","40e":"Y","40f":"U","41a":"K","41b":"L","41c":"M","41d":"N","41e":"O","41f":"P","42a":"","42b":"Y","42c":"","42d":"E","42e":"U","42f":"YA","43a":"k","43b":"l","43c":"m","43d":"n","43e":"o","43f":"p","44a":"","44b":"y","44c":"","44d":"e","44e":"u","44f":"ya","45a":"Hb","45b":"h","45c":"k","45d":"n","45e":"y","45f":"u","46a":"mY","46b":"my","46c":"Im","46d":"Im","46e":"3","46f":"3","47a":"O","47b":"o","47c":"W","47d":"w","47e":"W","47f":"W","48a":"H","48b":"H","48c":"B","48d":"b","48e":"P","48f":"p","49a":"K","49b":"k","49c":"K","49d":"k","49e":"K","49f":"k","4a0":"K","4a1":"k","4a2":"H","4a3":"h","4a4":"H","4a5":"h","4a6":"Ih","4a7":"ih","4a8":"O","4a9":"o","4aa":"C","4ab":"c","4ac":"T","4ad":"t","4ae":"Y","4af":"y","4b0":"Y","4b1":"y","4b2":"X","4b3":"x","4b4":"TI","4b5":"ti","4b6":"H","4b7":"h","4b8":"H","4b9":"h","4ba":"H","4bb":"h","4bc":"E","4bd":"e","4be":"E","4bf":"e","4c0":"I","4c1":"X","4c2":"x","4c3":"K","4c4":"k","4c5":"jt","4c6":"jt","4c7":"H","4c8":"h","4c9":"H","4ca":"h","4cb":"H","4cc":"h","4cd":"M","4ce":"m","4cf":"l","4d0":"A","4d1":"a","4d2":"A","4d3":"a","4d4":"AE","4d5":"ae","4d6":"E","4d7":"e","4d8":"e","4d9":"e","4da":"E","4db":"e","4dc":"X","4dd":"X","4de":"3","4df":"3","4e0":"3","4e1":"3","4e2":"N","4e3":"n","4e4":"N","4e5":"n","4e6":"O","4e7":"o","4e8":"O","4e9":"o","4ea":"O","4eb":"o","4ec":"E","4ed":"e","4ee":"Y","4ef":"y","4f0":"Y","4f1":"y","4f2":"Y","4f3":"y","4f4":"H","4f5":"h","4f6":"R","4f7":"r","4f8":"bI","4f9":"bi","4fa":"F","4fb":"f","4fc":"X","4fd":"x","4fe":"X","4ff":"x","50a":"H","50b":"h","50c":"G","50d":"g","50e":"T","50f":"t","51a":"Q","51b":"q","51c":"W","51d":"w","53a":"d","53b":"r","53c":"L","53d":"Iu","53e":"O","53f":"y","54a":"m","54b":"o","54c":"N","54d":"U","54e":"Y","54f":"S","56a":"d","56b":"h","56c":"l","56d":"lu","56e":"d","56f":"y","57a":"w","57b":"2","57c":"n","57d":"u","57e":"y","57f":"un"
        };

        var str = "";
        for(var i = 0; i<v.length; i++){
            str += map[ v.charCodeAt(i).toString(16) ] || "";
        }

        return str.toLowerCase().replace(/-+/g, '').replace(/^-|-$/g, '');
    }

    // Adicionar mais um telefone
    $(document).on('click', '.add-fone', function(e) {
        e.preventDefault();

        $(this).parent().before("<div class='row fone'><input type='text' placeholder='Telefone' class='fone-mask' name='fone[]' /><a href='#' class='remove-item'></a></div>");

        $('.fone-mask').mask(SPMaskBehavior, spOptions);
    });

    // Adicionar mais uma rede social
    $(document).on('click', '.add-social', function(e) {
        e.preventDefault();

        $(this).parent().before("<div class='row social'><input type='text' placeholder='Link' name='social[]' /><a href='#' class='remove-item'></a></div>");
    });

    // Adicionar mais um horario de atendimento
    $(document).on('click', '.add-atendimento', function(e) {
        e.preventDefault();

        $('.semana:last').after($('.semana:last').clone());

        var last = $('.semana:last');

        last.find('.bootstrap-select').find('.selectpicker').insertBefore($('.semana:last .bootstrap-select:first'));
        last.find('.bootstrap-select').remove();
        last.find('.remove-item').remove();
        last.find('select').val('').selectpicker();
        last.find('.bootstrap-select:last').after("<a href='#' class='remove-item'></a>");
    });

    // Remover item
    $(document).on('click', '#form-trabalho-config .remove-item', function(e) {
        e.preventDefault();

        $(this).parent().remove();
    });

    // Busca por areas
    $(document).on('change', 'select.tipo', function() {
        var select = $('#form-trabalho-config').find('select.area');

        select.find('option').remove();

        $.ajax({
            url: '/areas/get/' + $(this).val(),
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $(data.areas).each(function(index, element) {
                    select.append("<option value='" + element.id + "'>" + element.titulo + "</option>");
                });

                select.selectpicker('refresh');
            }
        });
    });

    // Buscar categorias
    $(document).on('change', 'select.area', function() {
        var select = $('#form-trabalho-config').find('select.categoria');

        select.find('option').remove();

        $.ajax({
            url: '/categorias/get/' + $(this).val(),
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $(data.categorias).each(function(index, element) {
                    select.append("<option value='" + element.id + "' data-title='" + element.titulo + "'>" + element.titulo + "</option>");
                });

                select.selectpicker('refresh');
            }
        });
    });

    // Modal alterar o status do trabalho
    $(document).on('click', '.switch', function(e) {
        e.preventDefault();

        var modal = $('#modal-alert')
            input = $(this).find('input');

        modal.find('.btn').addClass('btn-confirmar');

        if(input.is(':checked')) {
            modalAlert("Seu perfil de trabalho ficará oculto no infochat e os usuários " + '<b>não</b>' + " poderão entrar em contato com você. As conversas abertas seguirão normalmente!", 'Desativar');

            modal.find('.modal-footer').prepend("<button type='button' class='btn btn-default btn2' data-dismiss='modal'>Voltar</button>");
        } else {
            modalAlert('Seu perfil de trabalho ficará visivel no infochat e os usuários poderão entrar em contato com você.', 'Ativar');
        }

        modal.find('.modal-footer .btn-confirmar').unbind().on('click',function(e) {
            var status = input.is(':checked') ? '0' : '1';

            $.ajax({
                url: '/trabalho/config/status',
                method: 'POST',
                data: 'status=' + status,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (data) {
                    if(data.status == true) {
                        if(status == '0') {
                            input.prop('checked', false);
                        } else {
                            input.prop('checked', true);

                            modal.find('.modal-footer .btn').removeClass('btn-confirmar').text('OK');
                            modal.find('.modal-body').text('Mantenha o infochat aberto. Se sair fique atento ao seu e-mail, avisaremos por lá quando tiver mensagem para você (se necessário tire o infochat da lista de spam).');
                        }
                    } else {
                        modal.find('.modal-footer .btn').removeClass('btn-confirmar').text('OK');
                        modal.find('.modal-body').text(data.msg);
                    }

                    modal.find('.modal-footer .btn').unbind().on('click',function(e) {
                        return true;
                    });
                }
            });

            if(status == 1) {
                return false;
            }
        });
    });

    // Pre visualizar imagem
    $(document).on('change', '#form-trabalho-config .imagem input[type=file]', function() {
        var preview = $(this).prev();
        var reader = new FileReader();

        if($(this)[0].files[0].size > 5100000) {
            modalAlert('A imagem tem que ter no máximo 5mb.', 'OK');
        } else {
            reader.onload = function(e) {
                preview.removeClass('sem-imagem').css({
                    'background-image' : 'url(' + e.target.result + ')',
                    'background-size' : 'cover'
                });
            }

            preview.show();
            reader.readAsDataURL($(this)[0].files[0]);
        }
    });

    // Abrir, validar e submeter form
    $(document).on('click', '#open-trabalho-config', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('href'),
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                var modal = $('#modal-default');
                modal.addClass('modal-trabalho-config');
                modal.find('.modal-body').html(data.body);
                modal.modal('show');

                $('.selectpicker').selectpicker('refresh');

                $('#cep').mask('00000-000', {reverse: false, clearIfNotMatch : true});
                $('.fone-mask').mask(SPMaskBehavior, spOptions);

                $('#form-trabalho-config').validate({
                    rules: {
                        nome: {
                            required: true,
                            minlength: 1,
                            maxlength: 100
                        },
                        tipo: {
                            required: true,
                            minlength: 1
                        },
                        area_id : {
                            required: true,
                            minlength: 1
                        },
                        slug: {
                            required: true,
                            minlength: 1,
                            maxlength: 100
                        },
                        cep: {
                            required: true
                        },
                        bairro: {
                            required: true
                        },
                        logradouro: {
                            required: true
                        },
                        numero: {
                            required: true
                        },
                        cidade: {
                            required: true
                        },
                        estado: {
                            required: true
                        }
                    },
                    highlight: function (element, errorClass, validClass) {
                        $(element).addClass(errorClass).removeClass(validClass);

                        if($(element).hasClass('selectpicker')) {
                            $(element).prev().prev().addClass('error');
                        }
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).removeClass(errorClass).addClass(validClass);

                        if($(element).hasClass('selectpicker')) {
                            $(element).prev().prev().removeClass('error');
                        }
                    },
                    errorPlacement: function(error, element) {
                        if(element.hasClass('selectpicker')) {
                            $(element).prev().prev().addClass('error');
                        }
                    },
                    submitHandler: function(form) {
                        $(form).find('input[type=submit]').val('Salvando').prop('disabled', true);

                        $.ajax({
                            url: $(form).attr('action'),
                            method: 'POST',
                            dataType: 'json',
                            data: new FormData(form),
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                modalAlert(data.msg, 'OK');

                                $(form).find('input[type=submit]').val('Salvar').prop('disabled', false);
                            }
                        });

                        return false;
                    }
                });
            }
        });
    });
});