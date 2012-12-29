$(document).ready(function()
{    
    $('<strong> *</strong>').appendTo('.required');
    var ed = $('input[name="edition"]').val();
    $('input[name="precoCustoProduto"],input[name="precoVendaProduto"]').maskMoney({symbol:'R$',thousands:'.',decimal:','})
    
    $.formClear = function() {
		$('form').find('input[type="text"],textarea,input[type="password"]').each(function() {
			this.value = ""
		});
	};
    
    var orules =  {
			nomeProduto: {
				required: true
			},
            precoCustoProduto: {
				required: true
			},
            precoVendaProduto: {
				required: true
			},
            pesoProduto: {
				required: true
			},
            imagemProduto: {
				required: true
			},
            estoqueProduto: {
				required: true
			}
            
            
		};
   
   if(!ed){
    orules.passwordUsuario = {required: true};
    orules.confirmpasswordUsuario ={required: true,equalTo:'#passwordUsuario'};
   }else{
    orules.confirmpasswordUsuario ={equalTo:'#passwordUsuario'};
   }
    $('form').validate({
        rules:orules
		,
		submitHandler: function(form) {
			
            var dataP = $(form).serializeArray(), ed = $('input[name="edition"]').val();
			var action =  ed == 'true' || ed ? 'produto/update': 'produto/gravar';
            console.log(url_base + action);
            $.ajax({
				url:url_base + action,
				data: dataP,
				type: 'post',
				dataType: 'json',
				beforeSend: function() {
					$('<div class="jploading"></div>').appendTo('form');
				},
				success: function(r) {
					
					if (r.insert) {
						$('.jploading').remove();
						$.formClear();
                        $('<div class="boxMessage label label-success"><i class="icon-ok icon-white"></i> Dados Cadastros com sucesso !!!</div>').appendTo('form').delay(5000).fadeOut();
					}
                    
                    if (r.update) {
						$('.jploading').remove();
						$('<div class="boxMessage label label-success"><i class="icon-ok icon-white"></i> Dados Alterados com sucesso !!!</div>').appendTo('form').delay(5000).fadeOut();
					}
                    
				}
			});
			return false;
		}
	});
    
    
    
    $.duplicidadeCliente = function(options) {
		var o = jQuery.extend({}, {
			element: '',
            id     : '',
			url: '',
		}, options || {}),
			jfocused = false;
		$('body').data('elementFocus', o.element);
		$(o.element).bind('focusout', function(e) {
			var _eval = $(this).val(), _id =  o.id;
            
			$.ajax({
				url: o.url,
				dataType: 'json',
				data: {
					val : _eval,
                    id  : _id
				},
				type: 'post',
				success: function(r) {
					var e = $('body').data('elementFocus');
					if (r.row) {
						$('label.error').remove();
						var m = '<label generated="true" class="ferro error"> J&aacute; existe um registro com esse dado.</label>';
						$('form').find('input[type="submit"]').each(function() {
							$(this).hide();
						});
						$(m).insertAfter(e);
						$(e).removeClass('error');
						$(e).addClass('error')
						if (!jfocused) {
							$(e).focus();
							jfocused = true;
						}
						return false
					} else {
						$('input[type="submit"]').show();
						$(e).removeClass('error');
                        $('.ferro').remove();
					}
				}
			})
		})
	}
    
    
    
    $.duplicidadeCliente({
			'url': url_base + 'produtos/verifyExistsRegister',
			element: 'input[name="nomeProduto"]',
            id     : $('input[name="idProduto"]').val()
		});
    
    
    
})