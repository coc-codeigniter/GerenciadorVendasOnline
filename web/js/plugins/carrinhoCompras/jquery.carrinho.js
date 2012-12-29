/*
   @JqueryPluginName:Carrinho de Compras
   @author          :Carlos O Carvalho 
   @date            :2012-08-28
   @description     :Plugin carrinho de compras, esse plugin tem algumas funcoes especificas para um sistema 
   de compras, onde para poder finalizar ou salvar (pedidos/orcamentos), sera necessario que o sistema server(PHP|JAVA|.NET)
   tenha suporte persistencia de dados ex.(SESSION|COOKIE). 
   Alguns metodos do sistema fara uma verificacao no server para descobrir se existem dados persistentes em caso de 
   refresh na pagina.
   @Version         :1.0
   @siteAuthot      :http://www.carlosocarvalho.com.br
 */
;
(function(c) {
	c.fn.carrinho = function(options) {
		var defaults = {
			ajax: true,
			autoSave: true,
			apiCart: {
				format: null,
				create: '',
				remove: '',
				update: '',
				read: ''
			},
			apiCliente: {
				format: null,
				create: '',
				remove: '',
				update: '',
				read: '',
				fieldId: '',
				root: null,
			},
			apiSavePedido: {
				url: '',
				redirecto: false,
				timeredirectPage: 5000
			},
			formatoValores: {
				moeda: 'R$ ',
				decimal: ',',
				milhar: '.',
				casasDecimais: 2
			},
			textosDisplay: {
				textObrigatoryCliente: 'Para finalizar essa operação é necessário selecionar um cliente',
				tituloCarrinho: 'Cesta de Compras',
				delaySendPedido: 'Aguarde estamos gerando o pedido ...'
			},
			idContainerListItens: '#containerItensCart'
		};
		var o = $.extend({}, defaults, options),
			produtoName = '',
			produtoId = 0,
			produtoQtd = 0,
			produtoPrice = 0,
			lineItem = '',
			ccontainerItens = o.idContainerListItens,
			uid = 0 /* global neste escopo*/
			,
			saveOrcamento = false,
            savePedido    = false,
			urlRedirectTo = '';
		c('.addProduto').bind('click', function(e) {
			e.preventDefault();
			produtoName = c(this).attr('produtoName');
			produtoId = c(this).attr('produtoID');
			produtoQtd = c('#produto-' + produtoId).val();
			produtoPrice = c(this).attr('produtoPrice');
			lineItem = '<tr class="produtoItemCartAdd" id="line-' + produtoId + '" >' + '<td><input type="hidden" name="codigo[]" id="inputId-' + produtoId + '" value="' + produtoId + '" />' + produtoId + '</td>' + '<td><input type="hidden" name="name[]" id="inputName-' + produtoId + '" value="' + produtoName + '"/>' + produtoName + '</td>' + '<td><input type="hidden" name="quantidade[]" id="inputQtd-' + produtoId + '" value="' + Math.abs(produtoQtd) + '" /><span>' + (produtoQtd) + '</span></td>' + '<td><input type="hidden" name="price[]" id="inputPrice-' + produtoId + '"  value="' + produtoPrice + '" /><span>' + parseFloat(produtoPrice).formatMoney(o.formatoValores.casasDecimais, o.formatoValores.moeda, o.formatoValores.milhar, o.formatoValores.decimal) + '</span></td> ' + '<td><input type="hidden" name="subtotal[]" id="inputSubTotal-' + produtoId + '"  value="' + (produtoPrice * produtoQtd) + '" /><span>' + parseFloat(produtoPrice * produtoQtd).formatMoney(o.formatoValores.casasDecimais, o.formatoValores.moeda, o.formatoValores.milhar, o.formatoValores.decimal) + '</span></td> ' + '<td> <a id="linkRemove-' + produtoId + '" idupdate="0" class="remove-item" href="javascript:void(0);"><i class="icon-remove"></i></a></td></tr>';
			is_itemAddCart = 0;
			is_itemAddCart = c(ccontainerItens).find('#line-' + produtoId).size();
			if (is_itemAddCart > 0) { //update values cart
				update(produtoId);
			} else {//adcionando produto ao carrinho
				add();
			}
			
			if (c('.cartOpened').length == 0) {
				cartHiddenTop();
			};
		});
		c('.remove-item').live('click', function(e) {
			var idU = c(this).attr('id').split('-');
			rowid = c(this).attr('idupdate');
			remove(idU[1]);
			e.preventDefault();
		});
		c('input[name="saveOrcamento"]').live('click', function() {
			var ccliente_exists = c('input[name="clienteID"]').val();
			if (ccliente_exists == 0) {
				selectCliente();
			} else {
				var data = ajaxSaveOrcamento();
				
			}
		});
		
        
        c('input[name="savePedido"]').live('click', function() {
			var ccliente_exists = c('input[name="clienteID"]').val();
           	 
               if (ccliente_exists == 0) {
				selectCliente();
			} else {
			  
			   var data = OnsavePedido();
			}
		});
        
        

		function ajaxSaveOrcamento() {
			var data = {};
			c.ajax({
				url: o.apiCliente.create,
				type: 'POST',
				data: {
					cliente_save_orcamento: 1,
					idCliente: c('input[name="clienteID"]').val()
				},
				dataType: 'json',
				success: function(r) {
					data['json_result'] = r.result;
					data['orcamento_numero'] = r.orcamento_numero
				}
			});
			return data;
		}

		function selectCliente() {
			//depencias do JQUEYUI
			var message = '<div class="ui-widget">' + '<div class="ui-state-error ui-corner-all" style="padding: 5px; ">' + '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><p style="float:left;">' + '</p>' + o.textosDisplay.textObrigatoryCliente + '' + '</div>' + '</div>',
				markupOptions = ['<select id="CartOptionCliente" name="CartOptionCliente">', '</select>'].join(''),
				markup = ['<div id="CartDialog" title="Seleção de Clientes">', message, '<div class="cartOptions">' + markupOptions + '</div>', , '</div>'].join('');
			if (c('#CartDialog').length == 0) {
				c(markup).appendTo('body');
			}
			c.ajax({
				url: o.apiCliente.read,
				dataType: 'json',
				success: function(data) {
					var markupOpt = '<option value="0">--Selecione--</option>';
					c.each(data.clientes, function(k, v) {
						markupOpt += '<option value="' + v.codigo + '">' + v.nome + '</option>';
					});
					c('#CartOptionCliente').html(markupOpt);
					//c(markupOpt).appendTo('#CartOptionCliente');
				}
			});
			c('#CartDialog').dialog({
				zIndex: 20000,
				resizable: false,
				modal: true,
				minHeight: 180,
				width: 500,
				buttons: {
					'Salvar': function() {
						var id = c('#CartOptionCliente').val();
						c('input[name="clienteID"]').val(id);
						atualizaDataLabelCliente();
						//alert(id);
						//c(this).dialog('close');
					},
					'Cancelar': function() {
						c(this).dialog('close');
					}
				}
			});
		}

		function add() {
			//if(Mat)
			var qtd = (produtoQtd < 1 ? 0 : 1);
			// caso o numero adicionado seja negativo
			if (qtd == 1) {
				c(lineItem).appendTo(ccontainerItens);
				atualizaDisplayItens();
			}
			atualizaDislpayTotais();
			ajaxInsert();
		}

		function remove(id) {
			id = (id != undefined ? id : produtoId);
			c('tr#line-' + id).remove();
			atualizaDisplayItens();
			atualizaDislpayTotais();
			ajaxRemove();
		}

		function update(id) {
			rowid = c('#linkRemove-' + id).attr('idupdate');
			var inputUDP = c('#inputQtd-' + id),
				inputSubTotal = c('#inputSubTotal-' + id),
				priceProd = c('#inputPrice-' + id).val(),
				updateQtd = (parseInt(inputUDP.val()) + parseInt(produtoQtd));
			if (updateQtd == 0) {
				
				remove();
			}
			c('#line-' + id).children('td').eq(2).children('span').html(updateQtd);
			//replace value input
			inputUDP.val(updateQtd);
			// atualizaDislpayTotais();
			var subTotal = parseFloat(updateQtd * (priceProd));
			inputSubTotal.val(subTotal);
			c('#line-' + id).children('td').eq(4).children('span').html(subTotal.formatMoney(o.formatoValores.casasDecimais, o.formatoValores.moeda, o.formatoValores.milhar, o.formatoValores.decimal));
			atualizaDislpayTotais();
			ajaxUpdate();
		}

		function atualizaDisplayItens() {
			var totalItens = c(ccontainerItens).find('.produtoItemCartAdd').size();
			c('.infoCountItens').html((totalItens > 0 ? totalItens + ' item(s)' : 'Nenhum item adicionado'));
		}

		function atualizaDislpayTotais() {
			var total = 0;
			//console.log(total.toFixed(2));
			c(ccontainerItens).find('input[name="subtotal[]"]').each(function() {
				total = parseFloat(total) + parseFloat(c(this).val());
			});
			var prods = c(ccontainerItens).find('tr').size();
			var cline = '<tr><td colspan="4">Total dos Produtos</td><td colspan="2"><input type="hidden" name="totalCompra" value="' + total.toFixed(2) + '"/>' + total.formatMoney(o.formatoValores.casasDecimais, o.formatoValores.moeda, o.formatoValores.milhar, o.formatoValores.decimal) + '</td></tr>' + '<tr><td colspan="6"><div class="pull-right"><span id="ResponseSendPedido"></span><input type="button" class="btn btn-mini" name="saveOrcamento" value="Salvar Orçamento"/><input type="button" name="savePedido" class="btn btn-mini btn-primary" style="margin-left:10px;"  value="Finalizar Pedido"/></div></td></tr>';
			if (prods > 0) {
				c('#containerValues').html('').append(cline);
			} else {
				c('#containerValues').html('');
			}
			//hide button orcamento
			if (saveOrcamento) {
				$('input[name=saveOrcamento]').hide();
			}
            
            if (savePedido){
                $('input[name=savePedido]').val('Finalizar Edição').addClass('finalizePedido');
            }
		}

		function OnsavePedido() {
		  
          
			c.ajax({
				url: o.apiSavePedido.url,
				type: 'POST',
				dataType: 'json',
				data: {
					orcamento_numero: $('input[name="orcamento_numero"]').val(),
                    pedido_numero:  $('input[name="pedido_numero"]').val()
				},
				success: function(r) {
					
                    
                   
                    if(r.save_pedido){
                       	$('#ResponseSendPedido').html(o.textosDisplay.delaySendPedido);
						c('input[name="savePedido"]').slideUp();
						urlRedirectTo = o.apiSavePedido.redirecto.replace(/(\{num\})|(\{pedido\})/gi,r.pedido_codigo);
						var time = o.apiSavePedido.timeredirectPage != undefined ? o.apiSavePedido.timeredirectPage : 2000;
						setTimeout(redirect, time);
                   }
                    
                    
				}
			});
		}

		function redirect() {
			window.location = urlRedirectTo;
		}

		function ajaxInsert() {
			c.ajax({
				url: o.apiCart.create,
				type: 'POST',
				dataType: 'json',
				data: {
					createDataBase: o.autoSave,
					orcamento_numero: $('input[name="orcamento_numero"]').val(),
                    pedido_numero:  $('input[name="pedido_numero"]').val(),
					id: c('#inputId-' + produtoId).val(),
					name: c('#inputName-' + produtoId).val(),
					qtd: c('#inputQtd-' + produtoId).val(),
					price: c('#inputPrice-' + produtoId).val()
				},
				success: function(data) {
				    console.log(data);
					c('#linkRemove-' + produtoId).attr('idupdate', data.rowid);
				}
			});
		}

		function ajaxUpdate() {
		
			c.ajax({
				url: o.apiCart.update,
				type: 'POST',
				dataType: 'json',
				data: {
					rowid: rowid,
					updateDataBase: o.autoSave,
					orcamento_numero: $('input[name="orcamento_numero"]').val(),
                    pedido_numero:  $('input[name="pedido_numero"]').val(),
					qtd: c('#inputQtd-' + produtoId).val(),
					price: c('#inputPrice-' + produtoId).val(),
					subtotal: c('#inputSubTotal-' + produtoId).val(),
				},
				success: function(data) {
					//  console.log(data);
				}
			});
		}

		function ajaxRemove() {
			c.ajax({
				url: o.apiCart.remove,
				type: 'POST',
				dataType: 'json',
				data: {
					rowid: rowid,
					orcamento_numero: $('input[name="orcamento_numero"]').val(),
                    pedido_numero:  $('input[name="pedido_numero"]').val(),
					removeDataBase: o.autoSave
				},
				success: function(dta) {
					//  console.log(dta);
				}
			});
		}

		function loadExitsCart() {
			c.ajax({
				url: o.apiCart.read,
				type: 'GET',
				dataType: 'json',
				success: function(data) {
				     
					if (data.is_exist_cart && data.Cart) {
						c.each(data.Cart, function(k, v) {
							var lineItem = '<tr class="produtoItemCartAdd" id="line-' + v.id + '" >' + '<td><input type="hidden" name="codigo[]" id="inputId-' + v.id + '" value="' + v.id + '" />' + v.id + '</td>' + '<td><input type="hidden" name="name[]" id="inputName-' + v.id + '" value="' + v.name + '"/>' + v.name + '</td>' + '<td><input type="hidden" name="quantidade[]" id="inputQtd-' + v.id + '" value="' + Math.abs(v.qty) + '" /><span>' + (v.qty) + '</span></td>' + '<td><input type="hidden" name="price[]" id="inputPrice-' + v.id + '"  value="' + parseFloat(v.price) + '" /><span>' + parseFloat(v.price).formatMoney(o.formatoValores.casasDecimais, o.formatoValores.moeda, o.formatoValores.milhar, o.formatoValores.decimal) + '</span></td> ' + '<td><input type="hidden" name="subtotal[]" id="inputSubTotal-' + v.id + '"  value="' + (v.subtotal) + '" /><span>' + parseFloat(v.subtotal).formatMoney(o.formatoValores.casasDecimais, o.formatoValores.moeda, o.formatoValores.milhar, o.formatoValores.decimal) + '</span></td> ' + '<td> <a id="linkRemove-' + v.id + '"  idupdate="' + v.rowid + '" class="remove-item" href="javascript:void(0);"><i class="icon-remove"></i></a></td></tr>';
							c(lineItem).appendTo(ccontainerItens);
							cartHiddenTop();
						});
						atualizaDisplayItens();
						
					}
                    
                    if (data.is_exist_cart && data.CartPedido) {
                        $('input[name=orcamento_numero]').attr('name','pedido_numero').val(data.last_pedido_numero);
                        c.each(data.CartPedido, function(k, v) {
							var lineItem = '<tr class="produtoItemCartAdd" id="line-' + v.id + '" >' + '<td><input type="hidden" name="codigo[]" id="inputId-' + v.id + '" value="' + v.id + '" />' + v.id + '</td>' + '<td><input type="hidden" name="name[]" id="inputName-' + v.id + '" value="' + v.name + '"/>' + v.name + '</td>' + '<td><input type="hidden" name="quantidade[]" id="inputQtd-' + v.id + '" value="' + Math.abs(v.qty) + '" /><span>' + (v.qty) + '</span></td>' + '<td><input type="hidden" name="price[]" id="inputPrice-' + v.id + '"  value="' + parseFloat(v.price) + '" /><span>' + parseFloat(v.price).formatMoney(o.formatoValores.casasDecimais, o.formatoValores.moeda, o.formatoValores.milhar, o.formatoValores.decimal) + '</span></td> ' + '<td><input type="hidden" name="subtotal[]" id="inputSubTotal-' + v.id + '"  value="' + (v.subtotal) + '" /><span>' + parseFloat(v.subtotal).formatMoney(o.formatoValores.casasDecimais, o.formatoValores.moeda, o.formatoValores.milhar, o.formatoValores.decimal) + '</span></td> ' + '<td> <a id="linkRemove-' + v.id + '"  idupdate="' + v.rowid + '" class="remove-item" href="javascript:void(0);"><i class="icon-remove"></i></a></td></tr>';
							c(lineItem).appendTo(ccontainerItens);
							cartHiddenTop();
						});
                        
                        saveOrcamento = true;
                        savePedido    = true;
						atualizaDisplayItens();
						if (data.cliente) {
							cdataClienteSession = data.cliente;
							c('input[name="clienteID"]').val(cdataClienteSession.idCliente);
							setValueDataLabelCliente('Cliente : ' + cdataClienteSession.nomeCliente);
							
						}
                        
                    }
                    
                    
                    if (data.cliente_session) {
							cdataClienteSession = data.cliente_session;
							c('input[name="clienteID"]').val(cdataClienteSession.idCliente);
							setValueDataLabelCliente('Cliente : ' + cdataClienteSession.nomeCliente);
							if (data.status_cart) {
								ccartStatus = data.status_cart;
								if (ccartStatus.session_cart_temporary) {
									showDisplayMessagesCart('Orçamento não salvo', 'warning');
								}
								if (ccartStatus.session_cart_permanent) {
									saveOrcamento = true;
									$('input[name=orcamento_numero]').val(ccartStatus.last_orcamento_numero);
								}
							}
						}
                    
                    
					//atualizando os dados
					atualizaDislpayTotais();
					cartHiddenTop();
				}
			});
		}

		function atualizaDataLabelCliente() {
			var uidCliente = c('input[name="clienteID"]').val();
			if (uidCliente > 0) {
				c.ajax({
					url: o.apiCliente.read,
					type: 'post',
					dataType: 'json',
					data: {
						idCliente: uidCliente
					},
					success: function(data) {
						var clienteData = data.cliente_session;
						setValueDataLabelCliente('Cliente :  ' + clienteData.nomeCliente + (c('input[name="orcamento_numero"]').val() > 0 ? ' Orçamento : <strong>' + c('input[name="orcamento_numero"]').val() + '</strong>' : ''));
					}
				});
			}
		}

		function setValueDataLabelCliente(cvalue) {
			cvalue = cvalue != undefined ? cvalue : '';
			c('#ClienteDataLabel').html(cvalue);
		}

		function createMarkupTableItens() {
			markupCartItens = ['<div class="container"><table id="tblCart" class="table ">', '<thead>', '<tr><th style="width:30px;">Codigo </th><th>Nome</th><th> Quantidade</th><th>Preço</th><th>Subtotal</th><th style="width:25px;"></th></tr>', '</thead>', '<tbody id="containerItensCart">', '</tbody>', '<tfoot id="containerValues">', '</tfoot>', '</table><div id="resultData"></div></div>'].join('');
			cform = c('form#cartContainerItens');
			if (c('#tblCart').length == 0) {
				c(markupCartItens).appendTo(cform);
			};
		}

		function showDisplayMessagesCart(cmessage, ctype) {
			ctype = ctype != undefined || ctype != '' ? ctype : 'success';
			var markup = '<div class="CartMessage alert alert-' + ctype + '">' + cmessage + '</div>';
			c(markup).appendTo('.wraperCart').css({
				'z-index': '30000',
				'padding': '5px',
				'position': 'absolute',
				'left': '5%',
				'right': '5%',
				'top': '5px'
			}).slideDown();
		}

		function hideDisplayMessagesCart() {
			$('.CartMessage').fadeOut();
		}

		function createFormCart() {
			var markupCart = ['<div class="wraperCart"><a class="btn-mini bt-close" href="#"><i class="icon-remove icon-white"></i></a>', '<form action="" id="cartContainerItens" class="form-horizontal well">', '<input type="hidden" name="clienteID" value="0" />', '<input type="hidden" value="0" name="orcamento_numero"/>', '<div class="container"><h5><i class="cesta-icon"></i>' + o.textosDisplay.tituloCarrinho + '<span id="ClienteDataLabel"></span></h5></div>', '</form>', '<div class="bt_cart showCart"><a href="javascript:void(0);"><i class="cesta-icon"></i>Carrinho</a><span class="infoCountItens">Nenhum item adcionado</span></div>', '</div>'].join('');
			c(markupCart).hide().appendTo('body').delay(500).fadeIn(); //.append('<div class="cartContainerItens container"><h4><i class="cesta-icon"></i>Carrinho de Compras</h4></div>');  
			c('.bt-close').bind('click', function() {
				var heighDCART = c('.wraperCart').height() + 20;
				c('.wraperCart').animate({
					top: -(heighDCART)
				}, 500, function() {
					c('.showCart').show();
				}).removeClass('cartOpened');
			})
			c('.showCart > a').live('click', function() {
				c('.showCart').hide();
				c('.wraperCart').animate({
					top: 0
				}).addClass('cartOpened').removeClass('cartClose');
			});
		}

		function cartHiddenTop() {
			var heighDCART = c('.wraperCart').height() + 20;
			c('.wraperCart').css({
				top: '-' + (heighDCART) + 'px'
			}).addClass('cartClose');
		}

		function init() {
			createFormCart();
			cartHiddenTop();
			createMarkupTableItens();
			cartHiddenTop();
			loadExitsCart();
		}
		init();
	};
	return this; //permission chaining
})(jQuery);
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