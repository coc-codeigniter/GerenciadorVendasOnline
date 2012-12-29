<div class="container boxPedidoFluxo" id="ContainerPedido">
<ul class="breadcrumb">
  <li><a href="javascript:void(0);">Financeiro</a> <span class="divider">/</span></li>
  <li class="active"><?php echo ucfirst($this->uri->segment(2)); ?></li>
 
</ul>
<div class="content" >
<?php 

if($pedidos){
   $tbl = '<table class="table"><thead>
      <tr><th>Número</th><th>Cliente</th><th>Data Pedido</th><th>Valor</th><th>Vendedor</th><th>Pagamento</th><th>Parcelas</th><th>&nbsp;</th></tr></thead><tbody>';
   foreach($pedidos as $pedido){
    
     $tbl .='<tr><td>'.$pedido->id.'</td><td>'.$pedido->nome.'</td><td>'.format_data_br($pedido->data_pedido).'</td><td>R$ '.number_format($pedido->total,2,',','.').'</td><td>'.$pedido->nome_usuario.'</td><td>'.$pedido->credor.'</td><td>'.$pedido->parcelas.'</td><td><a href="#pedidoFluxo$'.$pedido->id.'" class="fluxoPedido" ><i class="icon-chevron-right"></i></a> | </td></tr>'; 
   }
   $tbl .='</tbody></table>';
   echo $tbl;
   }else{
    echo '<h4 class="label label-important">Nenhum pedido encontrado para faturamento</h4>';
   }
   
   
   
add_css(array('commom/pedidos','commom/financeiro'));
add_js(array('plugins/jquery.qtip','common/jquery.financeiro'),'top');

?>
</div>
</div>