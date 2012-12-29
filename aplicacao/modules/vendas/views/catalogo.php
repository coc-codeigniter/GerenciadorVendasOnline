<div class="container" id="ContainerProdutos">
  <ul class="breadcrumb">
  <li><a href="#">Vendas</a> <span class="divider">/</span></li>
  <li><a href="<?php echo site_url(); ?>vendas/catalogo">Catalogo</a> <span class="divider">/</span></li>
  <li class="active"><?php echo ucfirst($categoria); ?></li>
  
  <li class="pull-right">    <a href="#" class="ilinks"  title="<strong>Or&ccedil;amento</strong><br/>Para criar um novo or&ccedil;amento,<br/> precisa apenas escolher um produto e clicar no icone carrinho. <br/>">
                             <i class="icon-question-sign icon-red"> </i> &nbsp;<span class="divider-vertical">|
                             <a href="<?php echo site_url('clientes') ?>" class="link ilinks" title="Lista de Clientes e Ajustes">
                             <i class="icon-user"> </i>&nbsp;Clientes</a>&nbsp;<span class="divider-vertical">|
                             <a href="" class="link ilinks" title="Lista de Or&ccedil;amentos e Ajustes">
                             <i class="icon-list-alt"> </i>&nbsp;Or&ccedil;amentos</a >&nbsp;<span class="divider-vertical">|
                             </span>&nbsp;
                             <a class="link ilinks" title="Lista de Pedidos efetuados "href="<?php echo site_url() ?>pedidos"><i class="icon-list"> </i>&nbsp;Pedidos</a></li>
  </ul>
  
  <ul class="thumbnails">
       <?php
       //listando produtos no sitema 
       if($produtos):
       $i=1;
       foreach($produtos as $pro):
           echo '<li class="span2 '.($pro->estoque > 0 ? 'produto':'noproduto').'"><h5>'.$pro->name.'</h5><img class="img-polaroid" src="'.site_url().'/web/images/produtos/'.$pro->imagem.'" 
           '.($pro->estoque > 0 ? 'title="<strong>Quantidade em estoque <br/>'.$pro->estoque.'</strong>"':'').'/>
                 '.($pro->estoque > 0 ? '<strong class="pro-price">R$ '.number_format($pro->price,2,',','.').'</strong>
                 <div class="toolscart"><span>Quantidade </span><input class="qdt" id="produto-'.$pro->codigo.'" type="text" name="produto[]" value="1" maxlength="4" /><a class="addLink addProduto" id="'.$pro->codigo.'"  produtoPrice="'.$pro->price.'"produtoName="'.$pro->name.'" produtoID ="'.$pro->codigo.'"  href="#"><i class="icon-shopping-cart"></i></a></div>'
                 :'<span class="label label-important esgotadoLabel">Esgotado</span>' ).'</li>';
       //if($i > 6){echo '<li class="clearfix"></li>';}
       $i++;
       endforeach;
       endif;
       
       ?>
  </ul>  

</div>
<?php 
add_css('commom/vendas.css');
add_js(array('plugins/jquery.qtip','plugins/carrinhoCompras/jquery.carrinho'),'top');
add_js(array('common/jquery.vendas'));

?>