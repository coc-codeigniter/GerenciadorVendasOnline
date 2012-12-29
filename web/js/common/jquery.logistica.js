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

$(document).ready(function(){
    
    $('.fluxoPedido').bind('click',function(){
       if(!confirm('Deseja dar fluxo nesse pedido ?')){return false;} 
    });
    $('.voltar').bind('click',function(){
       if(!confirm('Deseja voltar esse pedido ?')){return false;} 
    }).qtip();
    jQuery.fn.conferePedido = function(options){
        var  o = jQuery.extend({},{
              api:{
                readList:'',
                setConfirm:''
              }
        },options||{});       
        
        return this.each(function(){
            var base = this;
           $(base).bind('click',function(e){
              e.preventDefault();
              var at = $(this).attr('href'), id= at.substring(1);
              base.getData(id);
           });
           
           base.getData = function(id)
           {
              $.ajax({
                  url:o.api.readList,
                  data:{
                    id:id
                  },
                  
                  type:'post',
                  dataType:'json',
                  success:function(r){  $('body').data('data',r)},
                  complete:base.show
              })
           }
           
           base.show = function(){
             var data =  $('body').data('data');
            var i = data[0];
            var t = '<div class="alert alert-info">'+
            '<h5><i class="icon-user"></i>Cliente: '+i.cliente+' <span class="divider"> / </span> Pedido : '+i.id+'</h5>'
            +'</div><table class="table table-bordered table-condensed"><thead><tr><th>Produto</th><th>Pre&ccedil;o</th><th>Qtd</th><th>Estoque</th> <th>Conferir</th></tr></thead>';
            $.each(data,function(k,v){
                var preco =  v.price;
                t +='<tr><td>'+v.produto+'</td><td>'+parseFloat(preco).formatMoney(2,'R$ ','.',',')+'</td><td>'+v.qtd+'</td><td>'+v.estoque+'</td><td><input type="checkbox" name="item[]" '+(v.conferido == 1 ? 'checked="checked"':'')+'  estoque ="'+v.estoque+'" value="'+v.uid+'"/></td></tr>';
            })
             t +='</table>';
            var st =  $('window,body').find('.listItens').size();
            if(st == 0){
              $('<div class="listItens" title="Lista de Itens"><h3>Pedido</h3></div>').appendTo('body');   
            }
            
            $('.listItens').html(t).dialog({
                width:'80%',
                buttons:{
                    Confirmar:base.confirmar
                }
            }); 
            
           } 
           
           base.confirmar = function(){
               var inArray = {},noValid = new Array();
               $('.successConferencia,.errorsConferencia').remove();
               $('tr').removeClass('noValid');
               $(this).find('input[type="checkbox"]:checked').each(function(k,v){
                   if($(this).attr('estoque') < 0 ){
                      var line = $(this).parents('tr').addClass('noValid');
                   }else{
                     inArray[k]= {id:this.value}; 
                   }
                   
                  
               });
               
               var erros = $('.listItens').find('.noValid').size();
               
               if(erros > 0 ){
                   if($('body').find('.errorsConferencia').size() == 0){
                     $('.ui-dialog-buttonpane').append('<div class="errorsConferencia label label-important"><i class="icon-warning-sign icon-white"></i> Existem produtos sem estoque para entrega</div>');    
                   }
                
                return false;
               }else{
                $('.errorsConferencia').remove();
                   base.confirmaLista(inArray);
                 }
               
               
               
               
           }
           base.confirmaLista = function(data){
              $.ajax({
                   type:'post',
                   dataType:'json',
                   url:o.api.setConfirm,
                   data:{itens:data},
                   beforeSend:function(){
                    $('.ui-dialog-buttonpane').append('<p class="msgSend">Conferido estoque...</p>');
                  },
                   success:function(r){
                    $('.msgSend').remove();
                    
                    if(r.update){
                        var id = r.data[0];
                        $('<div class="successConferencia label label-success"><i class="icon-ok icon-white"></i> Itens do Pedido conferido para entrega </div>').appendTo('.ui-dialog-buttonpane')
                        $('.fluxoEntrega'+id.id_pedido).show();
                     }
                   }
                   
              });
           }
            
        })
        
    }
    
    
    
    $('.conferePedido').conferePedido({
         api:{
            readList: url_base + 'logistica/getListaItensPedido',
            setConfirm: url_base + 'logistica/confirmLista'
         }
    }).qtip({style:{name:'dark'}})
    
    
   
})

