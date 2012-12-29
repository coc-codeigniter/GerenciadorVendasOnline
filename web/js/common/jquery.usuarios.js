$(document).ready(function()
{    
    $('<strong> *</strong>').appendTo('.required');
    var ed = $('input[name="edition"]').val();
    
    $.formClear = function() {
		$('form').find('input[type="text"],textarea,input[type="password"]').each(function() {
			this.value = ""
		});
	};
    
    var orules =  {
			nomeUsuario: {
				required: true
			},
            loginUsuario: {
				required: true
			},
            emailUsuario: {
				required: true,
                email:true
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
			var action =  ed || ed == 'true' ? 'usuarios/update': 'usuarios/gravar';
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
			'url': url_base + 'usuarios/verifyExistsRegister',
			element: 'input[name="emailUsuario"]',
            id     : $('input[name="idUsuario"]').val()
		});
    
    
    
})