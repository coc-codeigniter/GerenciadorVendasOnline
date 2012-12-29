<div class="container" id="ContainerCliente">

<ul class="breadcrumb">
<li ><a href="<?php echo base_url('/entregas') ?>">Entregas</a> <span class="divider">/</span></li>
<li class="active">Separa&ccedil;&atilde;o </li>
</ul>
<div class="listClientes">
  <?php
if(isset($pedidos)){
   $tbl = '<table class="table"><thead>
      <tr><th>N&uacute;mero</th><th>Cliente</th><th>Data Pedido</th><th>Valor</th><th>Vendedor</th><th>Pagamento</th><th>Parcelas</th><th>&nbsp;</th></tr></thead><tbody>';
   foreach($pedidos as $pedido){
    
     $tbl .='<tr><td>'.$pedido->id.'</td><td>'.$pedido->nome.'</td><td>'.format_data_br($pedido->data_pedido).'</td><td>R$ '.number_format($pedido->total,2,',','.').'</td><td>'.$pedido->nome_usuario.'</td><td>'.$pedido->credor.'</td><td>'.$pedido->parcelas.'</td><td><a href="#'.$pedido->id.'" class="conferePedido btn btn-mini" title="Conferir Pedido" ><i class="icon-list"></i></a> | <a href="'.base_url('/logistica/fluxo/'.$pedido->id.'/1/entregas-Separacao').'" class="voltar btn btn-mini" title="voltar pedido"><i class="icon-chevron-left"></i></a> | <a href="'.base_url('logistica/fluxo/'.$pedido->id.'/5/entregas-Separacao').'" class="btn btn-mini fluxoEntrega'.$pedido->id.' fluxoPedido '.(lockPedidoNoEntrega($pedido->id) ? 'hide' : '' ).'" title="enviar para separa&ccedil;&atilde;o"><i class="icon-chevron-right"></i></a></td></tr>'; 
   }
   $tbl .='</tbody></table>';
   echo $tbl;
   }else{
    echo '<h4 class="label label-important">Nenhum pedido encontrado para separa&ccedil;&atilde;o</h4>';
   }
    ?>
   
</div>


</div>
<?php
// add css and js
add_css(array('commom/logistica'));
add_js(array('plugins/jquery.qtip','common/jquery.logistica'),'top');

 ?> 