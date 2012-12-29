$(document).ready(function(){

  $('.produto img').tooltip({placement:'bottom'});
  $('.ilinks').tooltip({placement:'bottom'});
  $('body').carrinho({ 
                    apiCart:
                          {
                              create:url_base + 'vendas/cartInsert',
                              remove:url_base + 'vendas/cartRemove',
                              update:url_base + 'vendas/cartUpdate',
                              read  :url_base + 'vendas/cartRead',
                              format:'json'
                          }
                     ,
                    apiCliente:{
                            read:url_base + 'vendas/listClientes',
                            create:url_base + 'vendas/saveDataCart'
                            
                     },
                    apiSavePedido:{
                           url:url_base + 'vendas/savePedido',
                           redirecto:url_base + 'pedidos/FluxoPedido/{num}'
                        } 
                          
                    }
                 
                 );

});//end 