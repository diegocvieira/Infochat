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
});
