$(document).ready(function() {
	
	
    $('.fluxoPedidoOuther').css('cursor','pointer').qtip();
    $('.fluxoPedido').qtip({
		content: 'Enviar pedido para o fluxo <strong>'  + $('.editarPedido').attr('dataId')+'</strong>',style  :{name:'red'}
        
	});
    $('.editarPedido').qtip({
		content: 'Editar pedido <strong>' + $('.editarPedido').attr('dataId')+'</strong>',style  :{name:'dark'}
        
	});
    
 
    
})