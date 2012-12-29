$(document).ready(function() {
     
	jQuery.fn.jqueryActionsPedido = function(options) {
		
        
        
        var o = jQuery.extend({},{
              url:'',
              actionFluxo :'/fluxo/'
        }, options || {});
        return this.each(function() {
			var base = this;
			$(base).bind('click', function(ev) {
				ev.preventDefault();
				attr = this.getAttribute('href').split('$');
				base._fluxo(attr['1']);
			});
			base._fluxo = function(uid) {
	              
              if(uid == 0){return false;}
              
              $.ajax({
                url:o.url + o.actionFluxo + uid,
                dataType:'json',
                success:base.success
              });    
             
			}
            
            base.success = function(r){
               
                if(!r.faturado){
                    base.Dialog('Pedido '+ r.num_pedido +' não faturado','important');
                }
               
                if(r.update){
                    $(base).parents('tr').fadeOut(function(){$(this).remove()})
                }
                
            }
            
            base.Dialog = function(msg,type)
            {   type = type == undefined ? 'success' : type;
                $('<div class="dialog " title="Mensagem"><span class="label label-'+type+'">'+msg+'</span></div>')
                 .dialog();
            }
            
            
		});
	}
    
    jQuery.fn.jfaturaPedido = function(options){
        var o = jQuery.extend({},{
              url:'',
              actionFaturar :'/faturar/',
              actionGet    :'/getPedido/',
              actionSendPagamento:'/faturar/',
              actionAprovarPedido:''
        }, options || {});
        
          return this.each(function() {
			var base = this,_attr = this.getAttribute('href').split('$'), gId = _attr[1];
			$(base).bind('click', function(ev) {
				ev.preventDefault();
				attr = this.getAttribute('href').split('$');
				base._getPedido(attr['1']);
			});
            
            
            base._getPedido = function(uid){
                if(uid == 0){return false;}
                $.ajax({
                    url:o.url + o.actionGet + uid,
                    dataType:'json',
                    success:base.DialogFatura
                }); 
            }
            
            
            
              base.DialogFatura = function(r)
            {  
                $('.dialogFaturamento').remove();
                var pedido = r.pedido, vl = pedido.total  ;
                
                var btns = {}
                if(pedido.credor != 'boleto'){btns['Enviar'] = function(){base.faturar()};    
                }else{
                    btns['Aprovar'] = function(){base.aprovar();}
                }
                
                $('<div class="dialogFaturamento" title="Faturamento Pedido"><h3 class="label label-info"> Pedido '+pedido.id
                +'</h3>'+
                '<div class="label" style="margin-top:2px;"><strong>Cliente</strong> : '+pedido.nome+'</div>'+
                '<div class="label" style="margin-top:2px;"><strong>Valor Total do Pedido </strong> : '+ parseFloat(vl).formatMoney(2,'R$ ','.',',') +'</div>'+
                '<div class="label" style="margin-top:2px;"><strong>Pagamento </strong> <img src="web/images/cartoes/'+pedido.credor+'.png" width="45px" /> </div>'+
                '<div class="label" style="margin-top:2px;"><strong>Parcelas </strong> : '+pedido.parcelas+'</div>'               
                    +'</div>').appendTo('body')
                 .dialog({
                    
                    buttons:btns});
            }
            base.aprovar = function(){
                $('tr').removeClass('jrow');
                var ptr = $(this).parents('tr');$(ptr).addClass('jrow');
                $.ajax({
                     url:o.url + o.actionAprovarPedido,
                     dataType:'json',
                     data:{id:gId},
                     type:'post',
                     beforeSend:base.loading,
                     success:function(r){
                        if(r.update){
                             $('<p> Pedido aprovado ... </p>').appendTo('.ui-dialog-buttonpane').delay(4000).remove();
                             $('.jrow').remove();
                             $('.load').remove();
                        }
                        
                     }
                
               });
            }
            base.faturar = function(){
                $('tr').removeClass('jrow');
              var ptr = $(this).parents('tr');$(ptr).addClass('jrow');
              
              
              
               $.ajax({
                     url:o.url + o.actionFaturar,
                     dataType:'json',
                      data:{id:gId},
                     type:'post',
                     beforeSend:base.loading,
                     success:base.success
                
               });
            }
            base.success = function(r){
                
                $('.load').remove();
                if(r.update){
                    
                    $('.jrow').removeClass('reprovado');
                    $('.jrow').addClass('aprovado');
                }else{
                    $('.jrow').removeClass('aprovado');
                    $('.jrow').addClass('reprovado');
                }
            }
            base.loading = function(){
                var v = '<div class="load" style="position:relative; width:170px;">Enviando dados <span class="loading"></span></div>';
                $(v).appendTo('.ui-dialog-buttonpane');
                
            }
            
        });
    }
    
    //iniciando plugim
	$('.fluxoPedido').jqueryActionsPedido({url:'http://sistemadevendas.com/financeiro'}).qtip({
		content: 'Fluxo no pedido ',
		style: {
			name: 'dark'
		}
	});
    
    
    $('.faturarPedido').jfaturaPedido(
      {
        url:url_base +'/financeiro/',
        actionAprovarPedido:'/aprovarPedido'
        
       }
      ).qtip({
		content: 'Faturar pedido',
		style: {
			name: 'green'
		}
	}) 
    
});


Number.prototype.formatMoney = function(places, symbol, thousand, decimal)
{
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