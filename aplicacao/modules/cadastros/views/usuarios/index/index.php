<div class="container" id="ContainerCliente">

<ul class="breadcrumb"><li><a href="<?php echo base_url('cadastros') ?>">Cadastros Home</a> <span class="divider"> / </span></li><li class="active">Usu&aacute;rios</li>
</ul>
<div class="listClientes">
<h4> </h4>

<div class="clearfix"></div>
  <?php
  if(isset($usuarios)){
    $tb = '<table class="table table-bordered table-condensed mytable"><thead><tr><th>Cod.</th><th>Nome</th><th>Login</th><th></th></td></thead>';
      foreach($usuarios as $user){
        $tb .='<tr><td>'.$user->id.'</td><td>'.$user->nome.'</td><td>'.$user->login_user.'</td><td><a class="btn btn-mini linksListCliente" href="'.base_url('usuarios/'.$user->id.'/edicao.html').'" title="Editar Dados"><i class="icon-pencil"></i></a></td></tr>';
      }
    
    $tb .='</table>';
    echo $tb;
  }
    ?>
   
</div>
<a class="btn btn-primary pull-right" href="<?php echo base_url('usuarios/novo.html') ?>" style="margin-bottom: 10px;"><i class="icon-user icon-white"></i> Novo</a>

</div>
<?php
// add css and js
add_css(array('commom/clientes'));
add_js(array('plugins/jquery.qtip','common/jquery.clientesList'),'top');

 ?> 