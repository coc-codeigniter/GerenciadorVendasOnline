<div class="container" id="ContainerCliente">

<ul class="breadcrumb">
<li ><a href="<?php echo base_url('/entregas') ?>">Entregas</a> <span class="divider">/</span></li>
<li class="active">Pedidos em entrega </li>
</ul>
<div class="listClientes">
  <?php
if(isset($pedidos)){
   $tbl = '<table class="table"><thead>
      <tr><th>N&uacute;mero</th><th>Cliente</th><th>Data Pedido</th><th>Valor</th><th>Vendedor</th><th>Pagamento</th><th>Parcelas</th><th>&nbsp;</th></tr></thead><tbody>';
   foreach($pedidos as $pedido){
    
     $tbl .='<tr><td>'.$pedido->id.'</td><td>'.$pedido->nome.'</td><td>'.format_data_br($pedido->data_pedido).'</td><td>R$ '.number_format($pedido->total,2,',','.').'</td><td>'.$pedido->nome_usuario.'</td><td>'.$pedido->credor.'</td><td>'.$pedido->parcelas.'</td><td>'.($pedido->observacao_pedido != null ? '<a class="btn btn-mini confirmadoEntrega" data-id="'.$pedido->id.'" href="#finalizado"><i class="icon-ok"></i></a>' : '<a class="btn btn-mini confirmarEntregar" title="confirmar entregar" href="#confirmarEntregar" data-id="'.$pedido->id.'"><i class="icon-ok icon-green"></i></a>').' | <a title="Ver Nota Fiscal" class="btn btn-mini notaPreview" href="'.base_url('pedido/'.$pedido->id).'/notaFiscal.html"><i class="icon-file"></i></a> '.( $pedido->credor == 'boleto' ? '| <a href="'.base_url('pedido/'.$pedido->id.'/boleto.html').'" class="btn btn-mini previewBoleto" title="Gerar Boleto"><i class="icon-barcode"></i></a>':'').'</td></tr>'; 
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
add_js(array('plugins/jquery.qtip','plugins/mask/jquery.mask','common/jquery.logisticaEntregas'),'top');

 ?> 