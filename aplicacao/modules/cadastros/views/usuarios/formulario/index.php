<div class="container" id="ContainerCliente">
<ul class="breadcrumb">
<li><a href="<?php echo base_url('cadastros') ?>">Cadastros</a> <span class="divider">/</span></li>
<li><a href="<?php echo base_url('usuarios.html') ?>">Usu&aacute;rios</a> <span class="divider">/</span></li>
<li class="active"><?php echo ($editar ?  'Edi&ccedil;&atilde;o':'Novo') ?> </li>
</ul>

<?php 
$iname      = (isset($usuario) ? $usuario->nome : '');
$isobrenome = (isset($usuario) ? $usuario->sobrenome : '');
$ilogin     = (isset($usuario) ? $usuario->login_user : '');
$iemail     = (isset($usuario) ? $usuario->email : '');
;
$form  = form_open('#', array('method'=>'post','id'=>'clientesForm','class'=>'form-horizontal formUsuarios well '));
$form .= form_hidden('edition',$editar);
$form .= form_hidden('idUsuario',(isset($usuario) ? $usuario->id:''));
$form .='<div class="row">';
$form .='<div class="span3">';
$form .=form_label('Nome','nome',array('class'=>'required'));
$form .=form_input(array('name'=>'nomeUsuario','value'=>$iname, 'class'=>'input input-large'));
$form .='</div>';
$form .='<div class="span4">';
$form .=form_label('Sobrenome','sobrenome');
$form .=form_input(array('name'=>'sobrenomeUsuario','value'=>$isobrenome, 'class'=>'input input-xlarge'));
$form .='</div>';
$form .='</div>';
$form .='<div class="row">';
$form .='<div class="span3">';
$form .=form_label('Login','login',array('class'=>'required'));
$form .=form_input(array('name'=>'loginUsuario','value'=>$ilogin, 'class'=>'input input-small'));
$form .='</div>';
$form .='<div class="span4">';
$form .=form_label('Email','email',array('class'=>'required'));
$form .=form_input(array('name'=>'emailUsuario','value'=>$iemail, 'class'=>'input input-xxlarge'));
$form .='</div>';
$form .='</div>';

$form .='<div class="row">';
$form .='<div class="span3">';
$form .=form_label('Senha','senha',array('class'=>'required'));
$form .=form_password('passwordUsuario',null,'class="input input-medium" id="passwordUsuario"');

$form .='</div>';
$form .='<div class="span3">';
$form .=form_label('Confirmar senha','senha',array('class'=>'required'));
$form .=form_password('confirmpasswordUsuario',null,'class="input input-medium"');
$form .='</div>';
$form .='</div>';

$form .='<div class="form-actions">';
$form .= form_submit('','Salvar','class="btn btn-primary pull-right"');
$form .='</div>';
$form .='<div class="clearfix"></div>';
$form .= form_close();

echo $form;

add_css(array('commom/usuarios'));
add_js(array('plugins/jquery.qtip','plugins/validate/jquery.validate','plugins/validate.addMethods','plugins/validate/localization/messages_ptbr.js','plugins/mask/jquery.mask','common/jquery.usuarios'),'top');
?>

</div>