//plugins jqueryPedido
;
(function(a, window) {
	a.fn.pedidoFluxo = function(options) {
		var e = this,
			option = a.extend({}, {
				url: '',
				redirecto: '',
				urlLoadPedidoSession: '',
				urlConfirmPagamento: '',
				paymentConfig: {
					urlSession: '',
					urlType: '',
					urlGet: '' /*url data json root payments*/
					,
					urlLoadModulo: '' /*url responvel pela leitura de modulos implatados no sistema*/
					,
					textOptionOnePayment: 'É necessário selecionar uma forma de Pagamento'
				},
				entregaConfig: {
					urlOptionsEntregas: '',
					urlSession: '',
				},
				idFaturarBtn: '#faturarConfig',
				idPaymentBtn: '#paymentConfig',
				idEntregaBtn: '#entregarConfig',
				textInputValidate: 'Campo necess?rio'
			}, options),
			d = a('body');
		d.data('entregaConfig', {
			tipo: 'cif',
			enabled: false
		});
		d.data('pagamentoConfig', {
			tipo: 'd',
			enabled: false
		});
		d.data('paymentSelected', false);
		d.data('SelectedValRadio', 0);
		d.data('selectOptionVal', 0);
		d.data('completeFadeOut', false);
		d.data('changeSelect', false);
		d.data('entregaConfig', {
			tipo: 'd',
			enabled: false
		});
		d.data('optionEntrega', false);
		d.data('entregaDiferenteData', {
			dataExist: false,
			logradouro: '',
			numero: '',
			complemento: '',
			bairro: '',
			dados: '',
			uf: '',
			contato: '',
			cidade: ''
		});
		var uid = 0,
			credor = '',
			tipoPagamento = '',
			parcelas = '';
		a(option.idFaturarBtn).bind('click', function(ev) {
			var markup = ['<div id="ConfirmeFluxo" title="Fluxo de Pedido ' + a(this).attr('data-id') + '">' + '<div id="responseStatusFluxo" style="font-size:12px; position:absolute; top:5px; height:15px; left:5px; right:0px"></div>' + +'</div>'].join('');
			uid = a(this).attr('data-id');
			//check Payment
			var payment = d.data('pagamentoConfig');
			if (!payment.enabled) {
				_messageDialog('Para finalizar essa operação é necessário escolher o pagamento');
			} else {
				a(markup).appendTo('body');
				_setFluxo();
			}
		}); /*end click-me*/
		a(option.idPaymentBtn).live('click', function(ev) {
			uid = a(this).attr('data-id');
			_formPayment();
			ev.preventDefault();
		});

		function _formPayment() {
			var markupF = ['<div id="boxFormDialogPayment" title="Pagamentos"">', '<form method="post" id="formPaymentOptions" class="form-horizontal">', '<div class="span1"><label><strong>Numero</strong></label></div><div class="span1">' + uid + '</div>', '<select name="optionPayment" class="optionPayment" id="optionPayment">', '</select>', '<div id="containerPayment"></div>', '</form>', '</div>'].join('');
			if ($('#boxFormDialogPayment').length == 0) {
				$(markupF).appendTo('body');
			}
			_ajaxGetTypePayments();
			$('#boxFormDialogPayment').dialog({
				modal: true,
				width: 830,
				resizable: false,
				zIndex: 30000,
				position: "top",
				buttons: {
					'Salvar': confirmarPagamento,
					'Fechar': cancelarPagamento
				}
			});
		}

		function confirmarPagamento() {
			//ui-dialog-buttonpane
			var minOneOpt = a('form#formPaymentOptions ').find('input:checked').size();
			if (minOneOpt > 0) {
				ajaxConfirmaPagamento();
			} else {
				var markup = ['<div id="minMessage" class="alert alert-error">', , '</div>'].join('');
				a('#minMessage').remove();
				if (a('.ui-dialog-buttonpane').length > 0) {
					a(markup).appendTo('.ui-dialog-buttonpane').html('Seleciona um forma de pagamento');
				}
			}
		}

		function ajaxConfirmaPagamento() {
			var pos = '';
			a('form#formPaymentOptions').find('input:checked').each(function(k, v) {
				pos = a('form#formPaymentOptions input[type="radio"]').index(this);
			});
			var ird = a('form#formPaymentOptions input[type="radio"]').eq(pos).val();
			d.data('SelectedValRadio', ird);
			var sv = a('form#formPaymentOptions input[type="radio"]').eq(pos).val().split('-');
			var tp = a('form select[name="optionPayment"]').val();
			$.ajax({
				url: option.urlConfirmPagamento,
				type: 'post',
				dataTYpe: 'json',
				data: {
					credor: sv[0],
					tipoPagamento: tp,
					parcelas: sv[1],
					id: uid
				},
				beforeSend: showLoading,
				success: function(r) {
					
					if (r.altPedido) {
						hideLoading();
						$('.loadSendConfirmaPagamentoResponse').html('<i class="icon-info-sign"></i>Os dados foram salvos');
					} else {
						hideLoading();
						$('.loadSendConfirmaPagamentoResponse').html('<i class="icon-info-sign"></i>Os dados n?o foram salvos');
					}
				}
			})
		}

		function showLoading() {
			var ht = '<div class="loadSendConfirmaPagamento">&nbsp;Salvando os dados ...</div><div class="loadSendConfirmaPagamentoResponse"></div>';
			$('.loadSendConfirmaPagamentoResponse').remove();
			$(ht).appendTo('.ui-dialog-buttonpane');
			$('.loadSendConfirmaPagamentoResponse').hide();
		}

		function hideLoading() {
			if (a('.loadSendConfirmaPagamento').length > 0) {
				a('.loadSendConfirmaPagamento').fadeOut('slow', function() {
					$(this).remove();
					$('.loadSendConfirmaPagamentoResponse').delay(200).fadeIn().delay(5000).hide();
				})
			}
		}

		function cancelarPagamento() {
			a(this).dialog('close');
		}

		function resetInputForms() {
			a('form#formPaymentOptions ').find('input[type="radio"]').each(function(k, v) {
				$(this).removeAttr('checked');
			});
			a('.lblOptionPagamento').removeClass('paymentSelectActive');
		}
		a('#optionPayment').live('change', function() {
			// resetInputForms(); 
			d.data('changeSelect', true);
			a('#minMessage').hide();
			if (a(this).val() != 0) {
				d.data('selectOptionVal', a(this).val());
				_ajaxGetPayments();
			}
		});

		function _ajaxGetTypePayments() {
			//console.log(option.paymentConfig.urlType);
			a('#optionPayment').html('');
			a.ajax({
				url: option.paymentConfig.urlType,
				dataType: 'json',
				success: function(r) {
				    
					a('<option value="0"> Selecione </option>').appendTo('#optionPayment');
					a.each(r.payments, function(k, v) {
					    
						var item = '<option value="' + v.id + '" ' + (d.data('selectOptionVal') == v.id ? 'selected' : '') + '>' + v.name + '</option>';
						$(item).appendTo('#optionPayment');
					});
				}
			});
            
           
			if (d.data('selectOptionVal') > 0) {
				_ajaxGetPayments();
			}
		}

		function _ajaxGetPayments() {
			var idSel = (a('#optionPayment').val() != null && a('#optionPayment').val() != '' ? a('#optionPayment').val() : d.data('selectOptionVal'));
			var urlSend = option.paymentConfig.urlGet.replace(/(\{type\})|(\{id\})/gi, idSel);
			a.ajax({
				url: urlSend,
				dataType: 'html',
				success: paymentOptionsSelected
			});
		}

		function paymentOptionsSelected(r) {
			d.data('paymentSelected', true);
			//if exists data
			if (d.data('paymentSelected')){
				a('#containerPayment').html(r);
			}
			//SelectedValRadio
			a('form#formPaymentOptions ').find('input[type="radio"]').each(function(k, v) {
				//verificando se o input corresponde com o valor salvo 
				if (d.data('SelectedValRadio') == a(this).val()) {
					a(this).attr('checked', 'checked');
					a('.lblOptionPagamento').removeClass('paymentSelectActive');
					var pos = a('input[type="radio"]').index(this);
					a('.lblOptionPagamento').eq(pos).addClass('paymentSelectActive');
				}
			});
			a('form#formPaymentOptions input[type="radio"]').live('click', function(e) {
				// $('div').removeClass('')
				a('.lblOptionPagamento').removeClass('paymentSelectActive');
				//emitindo mensagem de alert informando que a opcao nao esta salva.
				if (d.data('SelectedValRadio') != a(this).val()) {}
				var pos = a('input[type="radio"]').index(this);
				//console.log(pos);
				a('.lblOptionPagamento').eq(pos).addClass('paymentSelectActive');
			});
		}

		function _messageDialog(msg) {
			var markupD = ['<div id="boxMessageDialogUI" title="Mensagem do Sistema""><div class="alert alert-info">', msg, '</div></div>'].join('');
			if (a('#boxMessageDialogUI').length == 0) {
				a(markupD).appendTo('body');
			}
			a('#boxMessageDialogUI').dialog();
		};

		function _setFluxo() {
			a('#ConfirmeFluxo').dialog({
				modal: true,
				resizable: false,
				draggable: false,
				width: 270,
				height: 140,
				zIndex: 30000,
				buttons: {
					'Confirmar': function() {
						a.ajax({
							url: option.url,
							type: 'POST',
							dataType: 'json',
							data: {
								id: uid
							},
							beforeSend: function() {
								a('#responseStatusFluxo').html('Enviando Pedindo para fluxo...<span class="loading"></span>');
							},
							success: function(r) {
								if (r.responseFluxo) {
									a('#responseStatusFluxo').html('Pedido <strong>' + r.id + '</strong> enviado para finanaceiro').delay(3000).fadeOut('slow', function() {
										a(this).remove();
										a('#ConfirmeFluxo').dialog('close');
                                        a('.table').fadeOut();
									});
								}
							}
						});
					},
					'Cancelar': function() {
						a(this).dialog('close');
					}
				}
			});
		}

		function initLoadSessionServer() {
			var uid = $('a#paymentConfig').attr('data-id');
			//  console.log(uid);
			$.ajax({
				url: option.paymentConfig.urlSession + '/' + uid,
				dataType: 'json',
				success: function(r) {
					if (r.sessionPayments) {
						var py = r.payments;
						d.data('SelectedValRadio', py.credor + '-' + py.parcelas);
						d.data('selectOptionVal', py.tipoPedido);
						d.data('pagamentoConfig', {
							enabled: true
						});
					}
				}
			});
		} /*functions entrega*/
		a(option.idEntregaBtn).bind('click', function(ev) {
			uid = $(this).attr('data-id');
			ev.preventDefault();
			b.showForm();
		});
		b = {
			showForm: function() {
				b.renderForm();
				$('#boxFormDialogEntrega').dialog({
					modal: true,
					width: 500,
					resizable: false,
					zIndex: 30000,
					buttons: {
						'Salvar': b.saveEntrega,
						'Fechar': b.cancelaEntrega
					}
				});
			},
			renderForm: function() {
				var markupF = ['<div id="boxFormDialogEntrega" title="Op&ccedil;&otilde;es de Entrega / Pedido ' + uid + ' ">', '<form method="post" id="formEntregaOptions" class="form-horizontal">', '', , '<div id="containerEntrega"></div>', '</form>', '</div>'].join('');
				if ($('#boxFormDialogEntrega').length == 0) {
					$(markupF).appendTo('body');
				}
				b.showOptions();
			},
			saveEntrega: function(ev) {
				//criando variaveis internas para uso.
				var endDiff = (a('input[name="enderecoDiferente"]').is(':checked') ? true : false),
					minOneOpt = a('#formEntregaOptions').find('input[type="radio"]:checked').size()
					er = [];
				//removendo a div com qualquer mensagem 
				a('#EntregaBoxMessage').remove();
				//checando se existe uma opcao selecionada.
				if (minOneOpt > 0 && endDiff) {
					//verificando se a opcao endereco diferente esta checada
					if (endDiff) {
						$('#containerOptionsEntregaSet').find('.obr').each(function(i, k) {
							var e = $(this);
							a('.required').remove();
							//checando os se os campos obrigatorios estao vazios
							if (e.val() == '' || e.val() == undefined) {
								var ind = a('.obr').index(this);
								//adicionado ao array de erros caso o campo esteja vazio
								er.push($(this).attr('name'));
							}
						});
						if (er.length > 0) {
							//criando a marcacao da div required 
							var makErro = ['<div class="required">', 'Este campo precisa de dados', '</div>'].join('');
							for (var i = 0; i < er.length; i++) {
								//posicionando as mensagens apos o campo
								var pi = a('input[name="' + er[i] + '"],select[name="' + er[i] + '"]').addClass('inputRequired');
								a('<div class="obrigatorio ' + er[i] + '">').html('<div class="messageRequired ">' + option.textInputValidate + '</div>').insertAfter(pi);
							}
							a('.obrigatorio').hide().fadeIn();
							return false;
						}
					}
					b.ajaxSaveEntega();
				} else if (minOneOpt > 0 && !endDiff) {
					b.ajaxSaveEntega();
				} else {
					var markup = ['<div id="EntregaBoxMessage" class="alert alert-error">', '</div>'].join('');
					if (a('.ui-dialog-buttonpane').length > 0) {
						a(markup).appendTo('.ui-dialog-buttonpane').html('Selecione uma op??o.');
					}
				}
			},
			cancelaEntrega: function() { a(this).dialog('close')},
			ajaxSaveEntega: function() {
				var idSel = (a('#entregarConfig').val() != null && a('#entregarConfig').val() != '' ? a('#entregarConfig').val() : d.data('selectOptionVal'));
				urlSend = option.entregaConfig.urlUpdateEntregas.replace(/(\{type\})|(\{id\})/gi, idSel);
				var c_tipo = 'cif';
				a('#formEntregaOptions').find('input[name="entregaTipo[]"]:checked').each(function() {
					c_tipo = $(this).val();
				});
                
				a.ajax({
					url: option.entregaConfig.urlUpdateEntregas,
					beforeSend: function() {
						a('<div id="responseStatusFluxo">').appendTo('.ui-dialog-buttonpane').html('Salvando os dados de entrega...<span class="loading"></span>');
					},
					data: {
						id: a('#entregarConfig').attr('data-id'),
						entregaDiferente: (a('input[name="enderecoDiferente"]').is(':checked') ? true : false),
						logradouro: a('input[name="logradouro"]').val(),
						numero: a('input[name="numero"]').val(),
						complemento: a('input[name="complemento"]').val(),
						bairro: a('input[name="bairro"]').val(),
						contato: a('input[name="contato"]').val(),
						uf: a('input[name="uf"]').val(),
						cidade: a('input[name="cidade"]').val(),
						tipo_entrega: c_tipo
					},
					dataType: 'json',
					type: 'post',
					success: b.successUpdate
				});
			},
			showOptions: function() {
				var sessionOption = (!d.data('optionEntrega') ? 'cif' : d.data('optionEntrega'));
				var markup = ['<div id="containerOptionsTipoEntrega">', '<ul class="thumbnails OptionsPayments">', '<li class="span5">', '<div  class="lblOptionEntrega"><input class="entregaTipo" type="radio" name="entregaTipo[]" ' + (sessionOption == 'cif' ? 'checked="checked"' : '') + ' value="cif" /> CIF</div>', '<div  class="lblOptionEntrega"><input class="entregaTipo" type="radio" name="entregaTipo[]" ' + (sessionOption == 'fob' ? 'checked="checked"' : '') + '  value="fob"/> FOB</div>', '<div  class="lblOptionEntrega"><input class="entregaTipo" type="radio" name="entregaTipo[]" ' + (sessionOption == 'retirada' ? 'checked="checked"' : '') + '  value="retirada" /> RETIRADA</div>', '</li>', '</ul>', '</div>', '<div id="containerOptionsEntregaSet" class="containerOptions">', '<ul class="thumbnails OptionsPayments">', '<li class="span5">', '<div  class="chkEnderecoEntregaTrue"><input type="checkbox" name="enderecoDiferente" />Entrega em endereco Diferente</div>', '</ul>', '</div>'].join('');
				a('#containerEntrega').html(markup);
				//verify exists session data
				var ex = d.data('entregaDiferenteData');
				if (ex.dataExist) {
					a('input[name="enderecoDiferente"]').trigger('click', function() {
						$(this).attr('checked', 'checked');
					});
					b.setNewEndereco();
				}
				a('input[name="enderecoDiferente"]').bind('click', function() {
					if (a(this).is(':checked')) {
						b.setNewEndereco();
					} else {
						b.oldNewEndereco();
					}
				})
			},
			ajaxGetOptions: function() {},
			successUpdate: function(r) {
			 
				if (r.update || r.update == 'true') {
					a('#responseStatusFluxo').html('Pedido <strong>' + a('#entregarConfig').attr('data-id') + '</strong> atualizado').delay(3000).fadeOut('slow', function() {
						a(this).remove();
					});
                    
				}
			},
			renderOptionsForm: function(r) {
				a('#containerEntrega').html(r);
			},
			setNewEndereco: function() {
				var ex = d.data('entregaDiferenteData') /*criando marcacao das opcoes*/
				,
					markup = ['<div class="newDataEntrega"><div class="span5"><label>Endere?o :</label><input type="text" name="logradouro" value="' + ex.logradouro + '" class="input input-xlarge obr" /></div>', '<div class="span1"><label>Numero : </label><input type="text" value="' + ex.numero + '" name="numero" class="input input-mini obr" /></div>', '<div class="span2"><label>Complemento : </label><input type="text"  value="' + ex.complemento + '" name="complemento" class="input input-small " /></div>', '<div class="span4"><label>Bairro : </label><input type="text"  value="' + ex.bairro + '" name="bairro" class="input input-large obr" /></div>', '<div class="span3"><label>Cidade : </label><input type="text" name="cidade" value="' + ex.cidade + '" class="input input-large obr" /></div>', '<div class="span1"><label>Estado : </label><input type="text" name="uf" value="' + ex.uf + '" class="input input-mini obr" /></div>', '<div class="span5"><label>Contato : </label><input type="text" value="' + ex.contato + '" name="contato" class="input input-xlarge obr" /></div>', '</div>'].join('');
				a(markup).appendTo('#containerOptionsEntregaSet').hide().slideDown();
				a('input').keypress(function() {
					var clsName = a(this).attr('name');
					if (clsName.length > 0) {
						if (a('.inputRequired').length > 0) {
							$(this).removeClass('inputRequired');
						}
						a('div.' + clsName).fadeOut(function() {
							a(this).remove();
						});
					}
				});
			},
			oldNewEndereco: function() {
				if (a('.newDataEntrega').length > 0) {
					a('.newDataEntrega').slideUp(function() {
						a(this).remove();
					});
				}
			}
		}

		function initLoadSessionEntregaServer() {
			$.ajax({
				url: option.entregaConfig.urlSession,
				dataType: 'json',
				success: function(r) {
					if (r.sessionEntrega) {
						var pe = r.entregas;
						if (pe.tipo_entrega != '') {
							d.data('entregaConfig', {
								tipo: 'd',
								enabled: true
							});
							d.data('optionEntrega', pe.tipo_entrega);
						}
						if (pe.entrega_diferente == 'S') {
							//  console.log(r);
							d.data('entregaDiferenteData', {
								dataExist: true,
								logradouro: (pe.logradouro != undefined ? pe.logradouro : ''),
								numero: (pe.numero != undefined ? pe.numero : ''),
								complemento: (pe.complemento != undefined ? pe.complemento : ''),
								bairro: (pe.bairro != undefined && pe.bairro != '' ? pe.bairro : ''),
								dados: (pe.dados_entrega != undefined && pe.dados_entrega != '' ? pe.dados_entrega : ''),
								uf: (pe.uf != undefined && pe.uf != '' ? pe.uf : ''),
								contato: (pe.contato != undefined ? pe.contato : ''),
								cidade: (pe.cidade != undefined ? pe.cidade : '')
							});
						}
					}
				},
				complete: function() {}
			});
		}
		initLoadSessionServer();
		initLoadSessionEntregaServer();
		return this;
	}
})(jQuery, window);
jQuery.fn.jactionsPedido = function(options) {
	var o = jQuery.extend({}, $.fn.jactionsPedidoOptions, options || {});
	return this.each(function() {
		var base = this,
			id = $(base).attr('uid'),
			pid = $(base).attr('pid');
		$(base).click(function(ev) {
			ev.preventDefault();
			var num_rows = $('tbody').children('tr').length,
				tpaction = $(this).attr('href').substr(1);
			if (num_rows == 1 && tpaction == 'remove') {
				base.messageRemove();
			} else {
				base.updatePedido();
			}
		});
		base.updatePedido = function() {
			$.ajax({
				type: 'post',
				datatype: 'json',
				url: o.remove.url,
				data: {
					pedido: pid,
					idRemove: id
				},
				beforeSend: base.loading,
				success: base.success
			});
		}
		base.loading = function() {
			jQuery(o.containerLoading).html('<div class="jpLoadingBox">Removendo ...<span class="jploading"></span></div>');
		}
		base.success = function(r) {
			if (r.update) {
				base.removeDOMItem();
				var total = r.pedidoTotal;
				$(o.displayTotal).html(total.formatMoney(2, 'R$', '.', ','));
				base.hideLoading();
			}
		}
		base.hideLoading = function() {
			$('.jpLoadingBox').fadeOut('slow', function() {
				$(this).remove();
			});
		}
		base.removeDOMItem = function() {
			$(base).parents('tr').remove();
		}
		base.messageRemove = function() {
			var md = '<div id="dialog-message" title="Mensagem do Sistema">' + '<div>' + '<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"/>' + o.remove.message + '</div>' + '</div>';
			$('#dialog-message').remove();
			$(md).remove().appendTo('body').dialog({
				modal: true,
				width: 500,
				resizable: false
			});
		}
	});
}
$.initializeInstantEdite = function() {
	$.fn.jEdita = function(options) {
		var i = 1,
			o = jQuery.extend({}, $.fn.jInstantEditeOptions, options),
			j = 0;
		return this.each(function() {
			var base = this,
				data = $(base).text(),
				pid = $(base).attr('pid');
			$(base).data('proid' + i, $(base).attr('proid'));
			$(base).addClass('tdRow' + i).html('<span class="rowEdit row-' + i + '" >' + data + '</span><a href="#editar" row-value="' + data + '" indice="' + i + '" class="jEditaRow jboxtools' + i + '"><i class="icon-pencil"></i></a>');
			$(base).parents('tr').addClass('trRow' + i);
			$('.trRow' + i).find('.subtotalitem').eq(0).addClass('rowSubTotalItem' + i);
			$('.jEditaRow').bind('click', function() {
				var id = $(this).attr('indice'),
					val = $(this).attr('row-value'),
					type = $(this).attr('href').substr(1);
				base.editable(id, val);
			});
			base.editable = function(id, val){
				$('.row-' + id).html('<input type="text" name="jrowInstantEdite" row-value="' + val + '" indice="' + id + '" style="width:40px;border:1px solid #ccc; padding:0px; margin:0px; float:none; position:absolute;"  value="' + val + '" />');
				$('.tdRow' + id).prepend('<a href="#cancel" class="jsave" title="salvar" row-value="' + val + '" indice="' + id + '"><i class="icon-ok icon-green"></i></a><a href="#save" class="jcancelar" title="cancelar" row-value="' + val + '" indice="' + id + '"><i class="icon-remove icon-red"></i></a>');
				$('.jcancelar').bind('click', base.cancel);
				$('.jsave').bind('click', base.save);
				//$('input[name="jrowInstantEdite"]').keydown(base.save);
			}
			base.save = function(e) {
				    e.preventDefault();
				if (e.charCode == 13 || e.type == 'click') {
					var indice = $(this).attr('indice'),
						proid = $(base).data('proid' + indice),
						qtd = $('.row-' + indice + ' input[name="jrowInstantEdite"]').val(),
						puid = $(this).attr('row-value');
					
                    if(qtd == 0){
                        $.fn.showMessage(
                        '&Eacute; necess&aacute;rio que a quantidade seja maior que 0 ');
                        return false;
                    }
                    if (proid != undefined) {
						$(base).data('idIndice', indice);
						var data = {
							pedido: pid,
							qtd: qtd,
							produto: proid
						}
						$.ajax({
							url: o.url,
							dataType: 'json',
							data: data,
							type: 'post',
							beforeSend: base.loading,
							success: base.success
						});
					}
				}
                return this;
			}
			base.loading = function() {
				jQuery(o.containerLoading).html('<div class="jpLoadingBox">Alterado ...<span class="jploading"></span></div>');
			}
			base.hideLoading = function() {
				$('.jpLoadingBox').fadeOut('slow', function() {
					$(this).remove();
				});
			}
			base.success = function(r) {
				if (r.update) {
					var total = r.pedidoTotal,
						ind = $(base).data('idIndice'),
						subtotal = r.subtotal_produto;
					$('.rowSubTotalItem' + ind).html(subtotal.formatMoney(2, 'R$', '.', ','));
					$(o.displayTotal).html(total.formatMoney(2, 'R$', '.', ','));
					var val = $('.row-' + ind + ' input[name="jrowInstantEdite"]').val();
					$('.row-' + ind).html(val);
					$('.tdRow' + ind).find('.jcancelar,.jsave,.jEditaRow').attr('row-value', val);
					$('.tdRow' + ind).find('.jcancelar,.jsave').remove();
					base.hideLoading();
				}
			}
			base.cancel = function() {
				var id = $(this).attr('indice'),
					val = $(this).attr('row-value'),
					type = $(this).attr('href').substr(1);
				$('.row-' + id).html(val);
				$('.tdRow' + id).find('.jcancelar,.jsave').remove();
			}
			i++;
		});
	};
	$('.editable').jEdita();
};
$.fn.jInstantEditeOptions = {
	url: '',
	displayTotal: 'body',
	containerLoading: ''
}
$.fn.jactionsPedidoOptions = {
	parent: 'tr',
	remove: {
		text: '',
		url: '',
		message: false
	},
	displayTotal: '',
	containerLoading: 'body',
	edite: []
}

$.fn.showMessage = function(msg){
    
    var md = '<div id="dialog-message" title="Mensagem do Sistema">' 
            + '<div>' 
            + '<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"/>' 
            + msg
            + '</div>' 
            + '</div>';
            
			$('#dialog-message').remove();
			$(md).remove().appendTo('body').dialog({
				modal: true,
				width: 500,
				resizable: false
			});
    
}
Number.prototype.formatMoney = function(places, symbol, thousand, decimal) {
	places = !isNaN(places = Math.abs(places)) ? places : 2;
	symbol = symbol !== undefined ? symbol : "$";
	thousand = thousand || ",";
	decimal = decimal || ".";
	var number = this,
		negative = number < 0 ? "-" : "",
		i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
		j = (j = i.length) > 3 ? j % 3 : 0;
	return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
};