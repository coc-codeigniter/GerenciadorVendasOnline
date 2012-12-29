$(document).ready(function() {
	$('.confirmarEntregar').bind('click', function() {
		var pid = $(this).attr('data-id');
        
        
        
        if($('body').find('.boxConfimarPedido').size() == 0){
       	$('<div class="boxConfimarPedido" title="Confirma&ccedil;&atilde;o de entrega"><form action="post" class="form-horizontal"><input type="hidden" value="'+pid+'" name="pedido" /> ' + '<label>Nome do entregador</label><input type="text" name="entregador" class="input  input-xxlarge" />' + '<label>Hora da entregado</label><input type="text" name="dataentrega" class="input  input-medium" />' + '<label>Observa&ccedil;&atilde;o</label><textarea name="observacoes" class="input input-xxlarge" rows="9"></textarea>' + +' </form></div>').appendTo('body');
		}
        
        $('input[name="entregador"]').live('focusout keydown',function(e){
            var id =  $('form').index(this);
            clearMessage();
            if (e.type == 'focusout') {
				var v = checkNoEmpty(this.value);
				if(v) {
					$(this).removeClass('novalid');
				}
                else{
				    
                    if($('body').find('.label'+id).size() == 0){
                       $('<label class="error label'+id+'"> Os dados sao necessario </label>').insertAfter(this);    
                    }
                    $(this).addClass('error').addClass('novalid');  
                }
                
                
			}else{
			 $(this).removeClass('error');
             $('.label'+id).remove();
             $(this).removeClass('error');  
			}
        });
        $('input[name="dataentrega"]').mask('99/99/9999 99:99').live('focusout keydown', function(e) {
			 var id =  $('form').index(this);
             clearMessage();
            if (e.type == 'focusout') {
				var v = checkDate(this.value);
				if(v) {
					$(this).removeClass('novalid');
				}
                else{
                    if($('body').find('.label'+id).size() == 0){
                       $('<label class="error label'+id+'"> Informe uma data v&aacute;lida... </label>').insertAfter(this);    
                    }
				    
                    $(this).addClass('error').addClass('novalid');  
                }
                
                
			}else{
			 
			 $(this).removeClass('error');
              $('.label'+id).remove();
             $(this).removeClass('error');  
			}
		}).qtip({content:'Digite a data e hora da entrega', style:{name:'blue'}});
		$('.boxConfimarPedido').dialog({
			width: '50%',
			resizable: false,
			buttons: {
				Confirmar: function() {
				    var erros = $('form').find('.novalid').size();
                    if(erros > 0){
                        $('<div class="errorConferenciaPedido label label-important"><i class="icon-warning-sign icon-white"></i> Favor conferir os dados necessarios </div>').appendTo('.ui-dialog-buttonpane')
                        return false;
                    }
                    
					var md = $('form').serializeArray();
					$.ajax({
						url: url_base + '/logistica/confirmaPedidoEntregar',
						data: md,
						type: 'post',
						dataType: 'json',
						success: function(r) {
							console.log(r);
						}
					})
				}
			}
		});

		function checkDate(str) {
			//
			var ps = new RegExp(/^(0[1-9]|[012][0-9]|3[01])\/(0[1-9]|1[012])\/([12][0-9]{3}) ([01]\d|2[0-3])(:[0-5]\d){0,2}$/);
			if (ps.test(str)) {
				return true;
			}
			return false;
		}
        function checkNoEmpty(str){
            if(str.length > 0){
                return true;
            }
            return false;
        }
        function clearMessage(){
            
            $('.errorConferenciaPedido').remove();
        }
        	
	});
    
    $('.confirmadoEntrega').bind('click',function(){
         	var pid = $(this).attr('data-id');
            $.ajax({
						url: url_base + '/logistica/getObservacaoPedido',
						data:{pedido:pid},
						type: 'post',
						dataType: 'json',
						success: function(r) {
							  var t = '<table class="table table-bordered table-condensed"><thead><tr><th>Entregador</th><th>Data</th><th>Observa&ccedil;&otilde;es</th></tr></thead>';
            
                
                t +='<tr><td>'+r.entregador+'</td><td>'+r.data+'</td><td>'+r.observacoes+'</td></tr>';
            
             t +='</table>';
            var st =  $('window,body').find('.listItens').size();
            if(st == 0){
              $('<div class="listItens" title="Lista de Itens"><h3>Pedido</h3></div>').appendTo('body');   
            }
            
            $('.listItens').html(t).dialog({
                width:'80%',
                resizable:false
            });
						}
					})
    })
    
    
    $('.notaPreview,.confirmadoEntrega,.previewBoleto,.confirmarEntregar').tooltip();
    
});