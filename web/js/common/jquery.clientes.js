$(document).ready(function() {
	
    $('<strong> *</strong>').appendTo('.required');
	$('input[name="cepCliente"]').mask('99999-999', {
		placeholder: ''
	})
    
    
	$('input[name="cpfCliente"]').mask('999.999.999-99');
	$('input[name="cnpjCliente"]').mask('99.999.999/9999-99');
	//$('input[name="telefoneCliente"],input[name="celularCliente"]').mask('(99)9999-9999~9');
    
	$('form').validate({
		rules: {
			nomeCliente: {
				required: true
			},
			cpfCliente: {
				required: true,
				cpf: true,
			},
			cnpjCliente: {
				required: true,
				cnpj: true,
			},
			logradouroCliente: {
				required: true
			},
			numeroCliente: {
				required: true
			},
			telefoneCliente: {
				required: true
			},
			cepCliente: {
				required: true
			},
			emailCliente: {
				required: true,
				email: true
			}
		},
		submitHandler: function(form) {
			var dataP = $(form).serializeArray(), ed = $('input[name="edition"]').val();
			var urlAc = ed == 'true' || ed ? '/clientes/update': '/clientes/gravar';
            
            $.ajax({
				url:url_base + urlAc,
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
                        $('<div class="boxMessage label label-success">Dados Cadastros com sucesso !!!</div>').appendTo('form').delay(3000).fadeOut();
					}
                    
                    if (r.update) {
						$('.jploading').remove();
						$('<div class="boxMessage label label-success">Dados Alterados com sucesso !!!</div>').appendTo('form').delay(3000).fadeOut();
					}
                    
				}
			})
			return false;
		}
	});
	$.formClear = function() {
		$('form').find('input[type="text"],textarea,password').each(function() {
			this.value = ""
		});
	}
    
    
    
	$.autoLoadEndereco = function(options) {
		var o = jQuery.extend({}, {
			url: '',
			input: {
				termo: 'termo',
				event: 'focusout'
			},
			complete: [] // campos que receberao os dados autocomplete
		}, options || {});
		var _ELEMENTS_FORM = 'input[name="$1"],select[name="$1"],textarea[name="$1"]';
		var actions = {
			disbledInputs: function() {
				var ipts = o.complete,
					t = ipts.length;
				if (t == 0) {
					return false;
				}
				for (var i = 0; i < t; i++) {
					var _input = ipts[i];
					if (typeof _input == 'object') {
						$(_ELEMENTS_FORM.replace(/\$1/g, _input.name)).attr('readonly', 'readonly');
					}
				}
			},
			enabaleInputs: function() {
				var ipts = o.complete,
					t = ipts.length;
				if (t == 0) {
					return false;
				}
				for (var i = 0; i < t; i++) {
					var _input = ipts[i];
					if (typeof _input == 'object') {
						$(_ELEMENTS_FORM.replace(/\$1/g, _input.name)).attr('readonly', false);
					}
				}
			},
			setInput: function(data) {
				var ipts = o.complete,
					t = ipts.length;
				if (t == 0) {
					return false;
				}
				if (!data) {
					actions.enabaleInputs();
				}
				for (var i = 0; i < t; i++) {
					var _input = ipts[i];
					if (typeof _input == 'object') {
						var iname = _input.DbName,
							ival = data.iname;
						$(_ELEMENTS_FORM.replace(/\$1/g, _input.name)).val(data[iname]);
						//checando se esta vazio
						if (data[iname] == '' || data[iname] == undefined) {
							$(_ELEMENTS_FORM.replace(/\$1/g, _input.name)).attr('readonly', false);
						}
					}
				}
				actions.hideLoading();
			},
			showLoading: function() {
				$('<div class="jploading"></div>').insertAfter(o.input.termo);
			},
			hideLoading: function() {
				$('.jploading').remove();
			}
		}
		actions.enabaleInputs();
		$(o.input.termo).bind('focusout', function() {
			var cep = $(this).val(),
				cepPrefix = cep.replace(/\-/g, '').substring(0, 5);
			if (cep.length >= 8) {
				actions.disbledInputs();
				actions.showLoading();
				$.ajax({
					url: url_base+ 'clientes/getLogradouro',
					type: 'post',
					data: {
						cepPrefix: cepPrefix,
						cep: cep
					},
					dataType: 'json',
					success: function(r) {
						actions.setInput(r.logradouros);
					}
				})
			}
		});
	}
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
					valCheck: _eval,
                    id  : _id
				},
				type: 'post',
				success: function(r) {
					var e = $('body').data('elementFocus');
					if (r.cliente) {
						$('label.error').remove();
						var m = '<label for="cpfCliente" generated="true" class="error"> J&aacute; existe um registro com esse dado.</label>';
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
					}
				}
			})
		})
	}
	$.actionsForm = function() {
		$.autoLoadEndereco({
			input: {
				termo: 'input[name="cepCliente"]'
			},
			complete: [{
				name: 'logradouroCliente',
				DbName: 'logradouro'
			},
			{
				name: 'tipoLogradouro',
				DbName: 'tp_logradouro'
			},
			{
				name: 'bairroCliente',
				DbName: 'bairro'
			},
			{
				name: 'cidadeCliente',
				DbName: 'cidade'
			}]
		});
		$.duplicidadeCliente({
			'url': url_base+'clientes/jsonCliente',
			element: 'input[name="cnpjCliente"],input[name="cpfCliente"]',
            id     : $('input[name="idCliente"]').val()
		});
	}
	$('.novoCliente').bind('click', function(e) {
		e.preventDefault();
		var typec = $(this).attr('href');
		if (typec.substring(1) == 'fisica') {
		    $('input[name="pessoa"]').val('F');
			$('.novoClienteBoxOptions').hide();
			$('form').fadeIn('slow');
			$('.pessoafisica').show();
			$('.pessoajuridica').hide();
		}
		if (typec.substring(1) == 'juridica') {
		    $('input[name="pessoa"]').val('J');
			$('.novoClienteBoxOptions').hide();
			$('form').fadeIn('slow');
			$('.pessoajuridica').show();
			$('.pessoafisica').hide();
		}
		$.actionsForm();
	});
	$('.showOptionNovoCliente').bind('click', function(e) {
		e.preventDefault();
		$('form').hide();
		$.formClear();
		$('.novoClienteBoxOptions').fadeIn('slow');
	});
    
    
    $('form').find('input[name="edition"]').each(function(){ 
        
          if(this.value){
            $.actionsForm();
          }
    });
});