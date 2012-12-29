<div class="container" id="ContainerCliente">

<ul class="breadcrumb"><li><a href="<?php echo base_url('cadastros') ?>">Cadastros Home</a> <span class="divider"> / </span></li><li class="active">Produtos</li>
</ul>
<div class="listClientes">
<h4> </h4>

<div class="clearfix"></div>
  <?php
  if(isset($produtos)){
    $tb = '<table class="table table-bordered table-condensed mytable"><thead><tr><th>Cod.</th><th>Nome</th><th>Fornecedor</th><th>Categoria</th><th>Custo R$</th><th>Venda R$</th><th>Estoque</th><th></th></td></thead>';
      foreach($produtos as $prod){
        $tb .='<tr><td>'.$prod->codigo.'</td><td>'.$prod->name.'</td><td>'.$prod->fornecedor.'</td><td>'.$prod->categoria.'</td><td class="precoCusto">'.number_format($prod->preco_custo,2,',','.').'</td><td class="precoVenda"> R$ '.number_format($prod->price,2,',','.').'</td><td>'.$prod->estoque.'</td><td><a class="btn btn-mini linksListCliente" href="'.base_url('produtos/'.$prod->codigo.'/edicao.html').'" title="Editar Dados"><i class="icon-pencil"></i></a> | '.($prod->status == 1 ? '<span class="label label-success" style="font-size:9px;letter-spacing:1px;">ATIVO</span>':'<span class="label label-important" style="font-size:9px; letter-spacing:1px;">INATIVO</span>').'</td></tr>';
      }
    
    $tb .='</table>';
    echo $tb;
  }
    ?>
   
</div>
<a class="btn btn-danger pull-right" href="<?php echo base_url('produtos/novo.html') ?>" style="margin-bottom: 10px;"><i class="icon-plus-sign icon-white"></i> Novo</a>

</div>
<?php
// add css and js
add_css(array('commom/clientes'));
add_js(array('plugins/jquery.qtip','common/jquery.clientesList'),'top');

 ?> 