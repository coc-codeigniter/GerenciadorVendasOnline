<div class="container" id="ContainerCliente">

<ul class="breadcrumb">

<li class="active">Clientes </li>
</ul>

<div class="listClientes">
<?php  if(!isset($pedidos)):
echo '<h3 class="label label-important infoLabel">Nenhum pedido cadastrado para esse cliente</h3>';
else:
 ?>
 <h4>Cliente : <?php  echo (isset($pedidos) ?$pedidos[0]->nome:'' ); ?> - <small> <?php  echo (isset($pedidos) ?$pedidos[0]->cid:'' ); ?></small></h4>
  <?php
  if(isset($pedidos)){
    $tb = '<table class="table table-bordered table-condensed mytable"><thead><tr><th>Cod.</th><th>Data</th><th>Pagamento</th><th>Valor</th></td></thead>';
      foreach($pedidos as $pedido){
        $tb .='<tr><td>'.$pedido->id.'</td><td>'.$pedido->data_pedido.'</td><td>'.$pedido->credor.'</td><td> R$ '.number_format($pedido->total,2,',','.').'</td></tr>';
      }
    
    $tb .='</table>';
    echo $tb;
  }
  
  
  endif;
 ?>
   
</div>


</div>
<?php
// add css and js
add_css(array('commom/clientes'));
add_js(array('plugins/jquery.qtip','common/jquery.clientesList'),'top');

 ?> 