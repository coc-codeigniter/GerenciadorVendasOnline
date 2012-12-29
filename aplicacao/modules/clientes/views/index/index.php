<div class="container" id="ContainerCliente">

<ul class="breadcrumb"><li class="active">Clientes  </li>
</ul>
<div class="listClientes">
  <?php
  if(isset($clientes)){
    $tb = '<table class="table table-bordered table-condensed mytable"><thead><tr><th>Cod.</th><th>Nome</th><th>Endere&ccedil;o</th><th></th></td></thead>';
      foreach($clientes as $cliente){
        $tb .='<tr><td>'.$cliente->codigo.'</td><td>'.$cliente->nome.'</td><td>'.$cliente->logradouro.'</td><td><a class="btn btn-mini linksListCliente" href="'.base_url('vendas/createSessionNewOrcamento/'.$cliente->codigo).'" title="Criar Or&ccedil;amento"><i class="icon-plus-sign icon-green"></i>  </a> <a class="btn btn-mini linksListCliente" href="'.base_url('clientes/editar/'.$cliente->codigo).'" title="Editar Dados"><i class="icon-pencil"></i></a> | <a href="'.base_url('clientes/'.$cliente->codigo.'/pedidos.html').'" title="Ver os pedidos desse cliente" class="btn btn-mini previewClientePedidos"><i class="icon-list"></i></a></td></tr>';
      }
    
    $tb .='</table>';
    echo $tb;
  }
    ?>
   
</div>


</div>
<?php
// add css and js
add_css(array('commom/clientes'));
add_js(array('plugins/jquery.qtip','common/jquery.clientesList'),'top');

 ?> 