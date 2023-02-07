function get_hash() {

    var GET = {};

    try {

        var hash = window.location.hash.split('#')[1];

        var partes = hash.split('&');

        partes.forEach(function(parte) {

            if (parte != '') {

                var chaveValor = parte.split('=');

                GET[chaveValor[0]] = chaveValor[1];

            }

        });

    } catch (e) {

        console.log(e)

    }

    return GET;

}

jQuery(window).bind('hashchange', function(event) {

    let rashTag = get_hash();

    if (rashTag.addOrcamento) {

        orcamento('', rashTag.addOrcamento, 'page-produto', '');

        return false;

    }

});

jQuery.fn.extend({

    animateCss: function(animationName) {

        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

        jQuery(this).addClass('animated ' + animationName).one(animationEnd, function() {

            jQuery(this).removeClass('animated ' + animationName);

        });

    }

});



function alteraQuant(id, i) {

    var quant = jQuery(i).val();

    jQuery('#quant' + id).val(quant);

    orcamento(id, 2);

    quantCarrinho();

}



function quantCarrinho() {

    var data = { 'action': 'quantCarrinho' }



    jQuery.post(ajax_object.ajax_url, data, function(response) {

        jQuery('#retorno').html(response);

    });

}

quantCarrinho();



function orcamento(key, id, origem, exclui) {

    jQuery('.acessaOrcamento').attr({ disabled: '' });

    var botao = jQuery('.produto-' + id).html();

    jQuery('.produto-' + id).html('<b>Aguarde... <span class="fa fa-cog fa-spin"></span></b>');

    var quant = jQuery('input.quant' + key).val();

    if (!quant) {

        quant = 1;

    }

    if (exclui == 'exclui') {

        jQuery('#exclui' + key).html('<span class="fa fa-cog fa-spin"></span>');

    }



    var data = {

        'action': 'pedirOrcamento',

        'key': key,

        'id_produto': id,

        'quantidade': quant,

        'origem': origem,

        'exclui': exclui

    }

    jQuery.post(ajax_object.ajax_url, data, function(response) {

        jQuery('.acessaOrcamento').removeAttr('disabled');

        jQuery('#retorno').html(response);

        jQuery('.produto-' + id).html(botao);

        quantCarrinho();

    });



}



function pisca() {

    jQuery('.pisca').animate({ opacity: '0.1' }, 400);

    jQuery('.pisca').animate({ opacity: '1' }, 350);

    jQuery('.pisca').animate({ opacity: '0.1' }, 200);

    jQuery('.pisca').animate({ opacity: '1' }, 100);

    jQuery('.pisca').animate({ opacity: '0.1' }, 50);

    jQuery('.pisca').animate({ opacity: '1' }, 50);

    jQuery('.pisca').animate({ opacity: '0.1' }, 50);

    jQuery('.pisca').animate({ opacity: '1' }, 50);

}



function excluiModal(id) {

    jQuery('#' + id + ' button').html('<span class="fa fa-cog fa-spin"></span>');

    var data = {

        'action': 'clicou_orcamento',

        'id': id,

        'exclui': 'exclui'

    }



    jQuery.post(ajax_object.ajax_url, data, function(response) {

        jQuery('#retorno').html(response);

        quantCarrinho();

    });

}



jQuery.fn.modalCw = function(cls) {

    console.log(this);

    if (cls == 'close') {

        this.hide(500);

    } else {

        this.show(500);

    }

};



function desablitaSubtracao() {

    jQuery('.actioAddOrcamento .quantidade').each(function() {

        let inputQuant = jQuery(this).find('input[name="quantidade"]');

        let quant = parseFloat(inputQuant.val());

        if (quant <= 1) {

            jQuery(this).find('.subtrai').addClass('disabled')

        } else {

            jQuery(this).find('.subtrai').removeClass('disabled')

        }

    });

}



function alteraQuant(btn) {

    let inputQuant = jQuery(btn).closest('.actioAddOrcamento').find('input[name="quantidade"]');

    let quant = parseFloat(inputQuant.val());

    if (jQuery(btn).hasClass("soma")) {

        quant = ((quant + 1) < 1) ? 1 : quant + 1;

        inputQuant.val(quant);

    } else {

        quant = ((quant - 1) < 1) ? 1 : quant - 1;

        inputQuant.val(quant);

    }

    desablitaSubtracao();

    inputQuant.focus();

}



jQuery(document).ready(function($) {

    jQuery('.cw_form_ajax').submit(function() {

        var id = jQuery(this).attr('id');

        var textButton = jQuery('#' + id + ' button[type="submit"]').html();

        jQuery('#' + id + ' button[type="submit"]').html('Aguarde <i class="fa fa-spinner fa-pulse"></i>');



        jQuery('#' + id + ' button[type="submit"]').attr({ disabled: 'disabled' });

        jQuery('#' + id + ' .resp').html('');

        var data = jQuery(this).serialize();



        $.post(ajax_object.ajax_url, data, function(data) {

            jQuery('#' + id + ' .resp').html(data);

            jQuery('#' + id + ' button[type="submit"]').html(textButton);

            jQuery('#' + id + ' button[type="submit"]').removeAttr('disabled');

        }).fail(function() {

            jQuery('#' + id + ' .resp').html('<div class="alert alert-danger">Erro! Tente mais tarde</div>');

            jQuery('#' + id + ' button[type="submit"]').html(textButton);

            jQuery('#' + id + ' button[type="submit"]').removeAttr('disabled');

        });



        jQuery('#' + id + ' .resp').show(500);



        setTimeout(function() {

            jQuery('#' + id + ' .resp').hide(1500);

        }, 4000);



        return false;

    });



    jQuery('.modal-cw .close').click(function() {

        jQuery(this).closest('.modal-cw').modalCw('close');

    });



    desablitaSubtracao()



    $('.row-cw.produtos').each(function() {

        let largura = $(this).width();

        let cls = '';

        if (largura <= 1300) {

            cls = 's50';

        }

        if (largura < 1200) {

            cls = 's3';

        }

        if (largura <= 990) {

            cls = 's4';

        }

        if (largura <= 650) {

            cls = 's6';

        }



        $(this).find('.col-cw').addClass(cls);

    });



    jQuery('input[name="tipo_pessoa"]').change(function() {

        var pessoa = $(this).val();

        if (pessoa == 'f') {

            $('#div_tipo_pessoa_j').slideUp();

            $('#div_tipo_pessoa_f').slideDown();

            $('#div_tipo_pessoa_f input').attr({ 'required': 'required' });

            $('#div_tipo_pessoa_v input').removeAttr('required');

        } else {

            $('#div_tipo_pessoa_f').slideUp();

            $('#div_tipo_pessoa_j').slideDown();

            $('#div_tipo_pessoa_j input').attr({ 'required': 'required' });

            $('#div_tipo_pessoa_f input').removeAttr('required');

        }

    });

    $('#cw-cep').mask("99999-999");
    $('input[name="rg"]').mask("99.999.999-9");
    $('input[name="cpf"]').mask("999.999.999-99");
    $('input[name="cnpj"]').mask("99.999.999/9999-99");

    if(jQuery('.swiper-container').length){
        console.log("teste aaaaaa");
        jQuery("a[rel^='prettyPhoto']").prettyPhoto();
    }

    if (jQuery('.gallery-thumbs').length) {


        var galleryThumbs = new Swiper('.gallery-thumbs', {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
          });
          var galleryTop = new Swiper('.gallery-top', {
            spaceBetween: 10,
            navigation: {
              nextEl: '.swiper-button-next',
              prevEl: '.swiper-button-prev',
            },
            thumbs: {
              swiper: galleryThumbs
            }
          });

          
        // var swiper = new Swiper('.swiper-container', {
        //     navigation: {
        //       nextEl: '.swiper-button-next',
        //       prevEl: '.swiper-button-prev',
        //     },
        //   });

        // var swiper = new Swiper('.swiper-container', {
        //     scrollbar: '.swiper-scrollbar',
            
        //     navigation: {
        //         nextEl: '.swiper-button-next',
        //         prevEl: '.swiper-button-prev',
        //     },
        //     pagination: {
        //         el: '.swiper-pagination',
        //     },
        //     scrollbarHide: true,
        //     slidesPerView: 'auto',
        //     freeMode: true,
        //     freeModeSticky: true,
        //     centeredSlides: true,
        //     spaceBetween: 30,
        //     grabCursor: true
        // });

        // var galleryThumbs = new Swiper('.gallery-thumbs', {
        //     spaceBetween: 10,
        //     slidesPerView: 4,
        //     freeMode: true,
        //     watchSlidesVisibility: true,
        //     watchSlidesProgress: true,
        //   });
        // var galleryTop = new Swiper('.gallery-top', {
        //     spaceBetween: 10,
        //     navigation: {
        //       nextEl: '.swiper-button-next',
        //       prevEl: '.swiper-button-prev',
        //     },
        //     thumbs: {
        //       swiper: galleryThumbs
        //     }
        //   });
        //galleryTop.controller.control = galleryThumbs;
        //galleryThumbs.controller.control = galleryTop;
    }





    jQuery('.form-orcamento').submit(function() {

        jQuery('.form-orcamento button').attr({ disabled: '' });

        jQuery('.form-orcamento .enviar button').html('<b>Aguarde... <span class="fa fa-cog fa-spin"></span></b>');

        if (jQuery('.form-orcamento input[name="arquivo"').length) {

            //var data = jQuery( this ).serialize(); 

            var form_data = new FormData(this);



            var arquivo = jQuery('.form-orcamento input[name="arquivo"').val();

            if (arquivo != '') {

                var arquivo = jQuery('.form-orcamento input[name="arquivo"').prop('files')[0];

            }

            form_data.append('file', arquivo);

            jQuery.ajax({

                url: ajax_object.ajax_url,

                type: 'post',

                contentType: false,

                processData: false,

                data: form_data,

                success: function(data) {

                    jQuery('.form-orcamento .enviar button').html('<b>FINALIZAR</b>');

                    jQuery('.form-orcamento .enviar #resp-orcamento').html(data);

                    jQuery('.form-orcamento button').removeAttr('disabled');

                }

            });

        } else {

            var form_data = jQuery(this).serialize();

            jQuery.ajax({

                url: ajax_object.ajax_url,

                type: 'post',

                data: form_data,

                success: function(data) {

                    jQuery('.form-orcamento .enviar button').html('<b>FINALIZAR</b>');

                    jQuery('.form-orcamento .enviar #resp-orcamento').html(data);

                    jQuery('.form-orcamento button').removeAttr('disabled');

                }

            });

        }



        return false;

    });



    jQuery('#cw-cep').blur(function() {

        var cep = jQuery('#cw-cep').val();

        jQuery('#cw-load_cep').html("<i class='fa fa-spinner fa-pulse'></i>");

        var data = {

            action: 'pesquisaCep',

            cep: cep

        }

        jQuery.post(ajax_object.ajax_url, data, function(data) {

            console.log(data);

            console.log(data);

            if (data.resultado != 0) {

                jQuery('#cw-rua').val(data.logradouro);

                jQuery('#cw-bairro').val(data.bairro);

                jQuery('#cw-cidade').val(data.cidade);

                jQuery('#cw-estado').val(data.uf);

                jQuery('#cw-numero').focus();

                jQuery('#cw-load_cep').html("<i class='fa fa-check text-success'></i>");

            } else {

                jQuery('#cw-load_cep').html("<span style='color:red'>CEP Invalido</span>");

            }

        });

    });



    jQuery('.pedirOrcamento').submit(function() {

        var button = jQuery('.pedirOrcamento button[type="submit"]').html();

        jQuery('.pedirOrcamento button[type="submit"]').html('Aguarde <i class="fa fa-spinner fa-pulse"></i>');

        jQuery('.pedirOrcamento button[type="submit"').attr({ disabled: 'disabled' });

        var data = jQuery(this).serialize();

        jQuery.ajax({

            url: ajax_object.ajax_url,

            type: 'post',

            data: data,

            success: function(data) {

                jQuery('.pedirOrcamento button[type="submit"]').html(button);

                jQuery('.pedirOrcamento .resp').html(data);

                jQuery('.pedirOrcamento button').removeAttr('disabled');

                quantCarrinho();

            }

        });

        return false;

    });



});