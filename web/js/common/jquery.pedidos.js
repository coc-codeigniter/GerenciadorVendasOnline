$(document).ready(function($){
    $('.boxPedidoFluxo').pedidoFluxo({
         url:url_base + 'pedidos/Sendfluxo',
         redirecto:url_base + 'pedidos',
         urlConfirmPagamento:url_base + 'pagamentos/ConfirmaPagamento',
         paymentConfig:{
                   urlGet:url_base + 'pagamentos/Payments/{type}',
                   urlType:url_base + 'pagamentos/TypePayments',
                   urlLoadModulo:url_base + 'pagamentos/modulo/{modulo}',
                   urlSession:url_base + 'pagamentos/SessionPagamentos'     
                    },
         entregaConfig:{
             urlOptionsEntregas:url_base + 'pagamentos/entregas',
             urlUpdateEntregas :url_base + 'pedidos/UpdatePedidoEntrega',
             urlSession         :url_base + 'pedidos/sessionEntregas'
             }           
    });
    
    
    $('.removeItemPedido').jactionsPedido({
        remove:{url:url_base + 'pedidos/deleteItem',
           message:'N&atilde;o &eacute; possivel deletar esse item do pedido, o pedido precisa ter mais de um item que esse item possa ser deletado.'},
        containerLoading:'.containerLoad',
        displayTotal:'.displayTotal',
        edite:[{element:'td',index:'0'}]
    });
     
     $.fn.jInstantEditeOptions = {
		url: url_base + 'pedidos/updatePedidoItem',
        containerLoading:'.containerLoad',
        displayTotal:'.displayTotal'
	}
     $.initializeInstantEdite();
    
});