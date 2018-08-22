$(document).ready(function() {
    $('body').css('opacity', '1');

    // Abrir e fechar menu
    $(document).on('click', '#open-menu', function(e) {
        e.preventDefault()

        $(this).next().show();
    });
    $(document).click(function(e) {
        if($('.top-nav').find('nav ul').is(':visible') && !$(e.target).closest('.top-nav nav').length) {
            $('.top-nav').find('nav ul').hide();

            e.preventDefault();
        }
    });

    // Abrir e fechar busca
    $(document).on('click', '#open-search', function(e) {
        e.preventDefault();

        $('.top-nav').find('nav, #open-search, #logo-infochat').hide();

        $('#form-search').show();

        $('#form-search').find('input[type=text]').focus();
    });
    $(document).on('click', '.close-form-search', function(e) {
        e.preventDefault();

        $('.top-nav').find('nav, #open-search, #logo-infochat').show();

        $('#form-search').hide();
    });

    // Scroll custom para funcionar em conteudo carregado dinamicamente
    function listenForScrollEvent(e) {
        e.on('scroll', function() {
            e.trigger('custom-scroll');
        });
    }

    // Desable default press touch
    window.oncontextmenu = function(event) {
        event.preventDefault();
        event.stopPropagation();
        return false;
    };

    // Modal de alertas
    function modalAlert(body, btn) {
        var modal = $('#modal-alert');

        modal.find('.modal-footer .btn2').remove();
        modal.find('.modal-footer .btn-back').hide();

        modal.find('.modal-body').html(body);
        modal.find('.modal-footer .btn').removeClass('btn-confirmar').text(btn);
        modal.modal('show');

        $('.modal-backdrop:last').css('z-index', '1080');
    }

    $(document).on('click', '#open-contato', function(e) {
        e.preventDefault();

        modalAlert("Para entrar em contato conosco envie um e-mail para <a href='mailto:contato@infochat.com.br'>contato@infochat.com.br</a>", 'OK');
    });

    ////////////////////////////// MODAL LOGIN E CADASTRO //////////////////////////////

    $('#form-login-usuario').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function (data) {
                if(data.status) {
                    window.location = '/';
                } else {
                    modalAlert(data.msg);
                }
            }
        });

        return false;
    });

    $(document).on('click', '#recuperar-senha', function(e) {
        e.preventDefault();

        modalAlert("Informe o e-mail cadastrado.<input type='email' name='email' placeholder='digite aqui' />", 'ENVIAR');

        var modal = $('#modal-alert');

        modal.find('.modal-footer .btn').addClass('btn-confirmar');
        modal.find('.modal-footer .btn-back').show();

        modal.find('.modal-footer .btn-confirmar').unbind().on('click', function() {
            $.ajax({
                url: '/recuperar-senha/solicitar',
                method: 'POST',
                dataType: 'json',
                data: 'email=' + modal.find('input[name=email]').val(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    modal.find('.modal-footer .invalid-field').remove();

                    if(data.status) {
                        modal.find('.modal-body').html('Clique no link que enviamos para o seu e-mail para recuperar a sua conta.');
                        modal.find('.modal-footer .btn').text('OK').removeClass('btn-confirmar');
                        modal.find('.modal-footer .btn-back').hide();

                        modal.find('.modal-footer .btn').unbind().on('click', function() {
                            return true;
                        });
                    } else {
                        modal.find('.modal-footer').append("<span class='invalid-field'>E-mail não cadastrado</span>");
                    }
                }
            });

            return false;
        });
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
                        modalAlert(data.msg, 'OK');
                    }
                }
            });

            return false;
        }
    });

    ////////////////////////////// PAGINA COMO FUNCIONA //////////////////////////////

    if($('.pagina-como-funciona').length) {
        if(navigator.msMaxTouchPoints) {
            $('#slider').addClass('ms-touch');
        } else {
             var slider = {
                 el: {
                     slider: $("#slider"),
                     holder: $(".holder")
                 },

                 slideWidth: $('#slider').width(),
                 touchstartx: undefined,
                 touchmovex: undefined,
                 movex: undefined,
                 index: 0,
                 longTouch: undefined,

                 init: function() {
                     this.bindUIEvents();
                 },

                 bindUIEvents: function() {
                     this.el.holder.on("touchstart", function(event) {
                         slider.start(event);
                     });

                     this.el.holder.on("touchmove", function(event) {
                         slider.move(event);
                     });

                     this.el.holder.on("touchend", function(event) {
                         slider.end(event);
                     });
                 },

                 start: function(event) {
                     // Test for flick.
                     this.longTouch = false;
                     setTimeout(function() {
                         window.slider.longTouch = true;
                     }, 250);

                     // Get the original touch position.
                     this.touchstartx =  event.originalEvent.touches[0].pageX;
                     // The movement gets all janky if there's a transition on the elements.
                     $('.animate').removeClass('animate');
                 },

                 move: function(event) {
                     // Continuously return touch position.
                     this.touchmovex =  event.originalEvent.touches[0].pageX;
                     // Calculate distance to translate holder.
                     this.movex = this.index*this.slideWidth + (this.touchstartx - this.touchmovex);
                     // Defines the speed the images should move at.
                     //var panx = 100-this.movex/10;
                     if(this.movex < 1440) { // Makes the holder stop moving when there is no more content.
                         this.el.holder.css('transform','translate3d(-' + this.movex + 'px,0,0)');
                     }
                 },

                 end: function(event) {
                     // Calculate the distance swiped.
                     var absMove = Math.abs(this.index*this.slideWidth - this.movex);
                     // Calculate the index. All other calculations are based on the index.
                     if(absMove > this.slideWidth/2 || this.longTouch === false) {
                         if(this.movex > this.index*this.slideWidth && this.index < 4) {
                             this.index++;
                         } else if (this.movex < this.index*this.slideWidth && this.index > 0) {
                             this.index--;
                         }
                     }
                     // Move and animate the elements.
                     this.el.holder.addClass('animate').css('transform', 'translate3d(-' + this.index*this.slideWidth + 'px,0,0)');
                 }
             };

             slider.init();
         }
     }

     ////////////////////////////// ASIDE (CATEGORIAS E CIDADE) //////////////////////////////

     $(document).on('click', '#open-aside', function(e) {
         e.preventDefault();

         $('.aside-categorias').css({
             'width' : '80%',
             'left' : '0'
         });

         $('.aside-categorias').after("<div class='modal-backdrop fade in' id='close-aside'></div>");
     });
     $(document).on('click', '#close-aside', function(e) {
         e.preventDefault();

         $(this).hide();

         $('.aside-categorias').css({
             'width' : '0',
             'left' : '-50%'
         });
     });

     // Exibir categorias e subcategorias e pesquisar trabalhos ao seleciona-las
     $(document).on('click', '#categorias .cat-search', function(e) {
         e.preventDefault();

         $('#form-search-palavra-chave').val('');

         var next = $(this).parent().next(),
             submit = $(this).hasClass('close-area') ? false : true,
             $this = $(this);

         $('#form-search-palavra-chave').attr('placeholder', 'Pesquise em ' + $this.text());

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

                     if(data.categorias.length) {
                         $this.hasClass('close-area') ? area.show() : area.not($this.parent()).hide();

                         $this.toggleClass('close-area');
                     } else {
                         $('#categorias .area').removeClass('active');
                         $this.addClass('active');
                     }
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

                         if(!data.subcategorias.length) {
                             $('#categorias .categoria').removeClass('active');
                             $this.addClass('active');
                         }
                     }
                 });
             } else {
                 $('.aside-categorias').find('.subs a').removeClass('active');

                 $('#categorias .subcategoria').removeClass('active');
                 $(this).addClass('active');
             }

             $('#form-search-tag').val($(this).data('search'));
         }

         $('#form-search').submit();
     });

     // Exibir areas e pesquisar trabalhos ao selecionar um novo tipo
     $('#categorias').on('click', '.tipo', function(e) {
         e.preventDefault();

         $('#form-search-palavra-chave, #form-search-area, #form-search-tag').val('');

         $('#categorias').find('.tipo').removeClass('active');
         $(this).addClass('active');

         var tipo = $(this).data('search'),
             placeholder = (tipo == 'estabelecimentos' || tipo == 'profissionais') ? 'Pesquise em ' + tipo : 'Pesquise aqui';

         $('#form-search-palavra-chave').attr('placeholder', placeholder);

         if(!logged && tipo == 'favoritos') {
             modalAlert('Faça login para acessar seus favoritos.', 'OK');
         } else {
             $('#form-search-tipo').val($(this).data('search'));
             $('#form-search').submit();
         }

         if(tipo != 'favoritos') {
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
         }
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

     // Submeter form principal de busca
     $(document).on('submit', '#form-search', function() {
         $(this).find('#form-search-offset').val('');

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
     $('.resultados').on('scroll', function() {
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

    $(document).on('press', '.result', function(e) {
        e.preventDefault();

        var top = $('.top-nav');

        // Verify if options exists
        top.find('.manage-options').remove();

        // Hide top
        $('#logo-infochat, #open-search').hide();

        // Move options to top
        top.append("<div class='manage-options'><a href='#' class='close-content' id='close-manage-options'></a>" + $(this).find('.manage-options').html() + "</div>");

        // Add id to identify result after click
        top.find('.options a').attr('data-chatid', $(this).data('chatid'));
    });

    $(document).on('click', '#close-manage-options', function(e) {
        e.preventDefault();

        $('#logo-infochat, #open-search').show();
        $('.top-nav').find('.manage-options').remove();
    });

    $(document).on('click', '.top-nav .options a', function(e) {
        e.preventDefault();

        var id = $(this).attr('id'),
            chat_id = $(this).data('chatid');

        $('.top-nav').find('.manage-options').remove();
        $('#logo-infochat, #open-search').show();

        $.ajax({
            url: $(this).attr('href'),
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if(data.status) {
                    if(id == 'work-details') {
                        var modal = $('#modal-default');
                        modal.removeAttr('class');
                        modal.addClass('modal fade show-trabalho');
                        modal.find('.modal-body').html(data.trabalho);
                        modal.modal('show');

                        //Scroll custom para funcionar em conteudo carregdao dinamicamente
                        listenForScrollEvent($('.show-trabalho .modal-content'));
                    } else {
                        var result = $('.result[data-chatid=' + chat_id + ']');

                        if(id == 'close-chat') {
                            result.find('#close-chat').remove();
                            result.find('.options').prepend("<a href='" + data.route + "' id='open-chat'></a>");
                            result.find('.date').after("<span class='status-close'></span>");
                        } else if(id == 'open-chat') {
                            result.find('#open-chat').remove();
                            result.find('.options').prepend("<a href='" + data.route + "' id='close-chat'></a>");
                            result.find('.status-close').remove();
                        } else if(id == 'delete-chat') {
                            result.remove();

                            if($('.result').length == 0) {
                                setTimeout(function() {
                                    window.location.reload(true);
                                }, 100);
                            }
                        } else if(id == 'block-user') {
                            result.find('#block-user').remove();
                            result.find('.options a:first').after("<a href='" + data.route + "' id='unblock-user'></a>");
                            result.find('.date').after("<span class='status-block'></span>");
                        } else if(id == 'unblock-user') {
                            result.find('#unblock-user').remove();
                            result.find('.options a:first').after("<a href='" + data.route + "' id='block-user'></a>");
                            result.find('.status-block').remove();
                        }
                    }
                } else {
                    modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.', 'OK');
                }
            }
        });
    });

    ////////////////////////////// DETALHES DO TRABALHO //////////////////////////////

    // Favoritar (Add e remover)
    $(document).on('click', '.favoritar', function(e) {
        e.preventDefault();

        if(logged) {
            $.ajax({
                url: '/trabalho/favoritar/' + $(this).data('id'),
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('.favoritar').toggleClass('favorito');
                }
            });
        } else {
            modalAlert('É necessário estar logado para poder favoritar', 'OK');
        }
    });

    // Alternar entre abas
    $(document).on('click', '.show-trabalho .abas a', function(e) {
        e.preventDefault();

        $('.aba-aberta').hide();
        $('.abas').find('a').removeClass('active');

        $('.' + $(this).data('type')).show();
        $(this).addClass('active');
    });

    // Avaliar
    $(document).on('click', '#form-avaliar-trabalho .nota label', function() {
        var nota = $(this).prev().val();

        $(this).parents('.nota').find('label').each(function() {
            if($(this).prev().val() <= nota) {
                $(this).addClass('star-full');
            } else {
                $(this).removeClass('star-full');
            }
        });

        $(this).addClass('star-full');
    });

    $(document).on('submit', '#form-avaliar-trabalho', function() {
        if($(this).find('input[type=radio]').is(':checked')) {
            if(logged) {
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(data) {
                        if(data.status && data.descricao) {
                            $('.show-trabalho').find('.comentarios .sem-resultados').remove();

                            var imagem = data.imagem ? "<img src='/uploads/perfil/" + data.imagem + "' />" : "<img src='/img/paisagem.png' class='sem-imagem' />";

                            $('.show-trabalho').find('.comentarios').prepend("<div class='comentario'><div class='imagem-user'>" + imagem + "</div><div class='header-comentario'><h4>" + data.nome + "</h4><span class='nota'>" + data.nota + ".0</span><span class='data'>" + data.data + "</span></div><div class='descricao-comentario'><p>" + data.descricao + "</p></div></div>");
                        }

                        modalAlert(data.msg, 'OK');
                    }
                });
            } else {
                modalAlert('Acesse sua conta para poder avaliar.', 'OK');
            }
        }

        return false;
    });

    // Listar Comentarios
    $(document).on('custom-scroll', '.show-trabalho .modal-content', function() {
        var div = $('.show-trabalho').find('.comentarios');

        if(div.is(':visible') && $(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            $.ajax({
                url: '/trabalho/avaliar/list/' + $('.show-trabalho').find('input[name=trabalho_id]').val() + '/' + div.find('.comentario').length,
                method: 'GET',
                dataType:'json',
                success: function(data) {
                    $(data.avaliacoes).each(function(index, element) {
                        imagem = element.user.imagem ? "<img src='/uploads/perfil/" + element.user.imagem + "' />" : "<img src='/img/paisagem.png' class='sem-imagem' />";

                        div.append("<div class='comentario'><div class='imagem-user'>" + imagem + "</div><div class='header-comentario'><h4>" + element.user.nome + "</h4><span class='nota'>" + element.nota + ".0</span><span class='data'>" + element.created_at + "</span></div><div class='descricao-comentario'><p>" + element.descricao + "</p></div></div>");
                    });
                }
            });
        }
    });
});
