<?php 
$boxMod     = $editar ? 'hide' :'show';
$formMod    = $editar ? 'show' :'hide';
$typeMod    = isset($cliente) &&  $cliente->type_cliente == 'F' ? 'jhide' : (isset($cliente) &&  $cliente->type_cliente == 'J' ? 'fhide':'') ;
$iname      = isset($cliente)  ? utf8_decode($cliente->nome) :''; 
$icnpjcpf   = isset($cliente) ? ($cliente->type_cliente == 'F' ? format_cpf($cliente->cnpj_cpf): format_cnpj($cliente->cnpj_cpf)) :'';
$infantasia = isset($cliente) ? $cliente->nome_fantasia :'';
$icomplemento= isset($cliente) ? $cliente->complemento :'';
$inum      = isset($cliente) ? $cliente->numero :'';
$ibairro   = isset($cliente) ? $cliente->bairro :'';
$icidade   = isset($cliente) ? utf8_decode($cliente->cidade) :'';
$iuf       = isset($cliente) ? $cliente->uf :'';
$ilog      = isset($cliente) ? $cliente->logradouro :'';
$itplog    = isset($cliente) ? $cliente->tipo_logradouro :'';
$icontato  = isset($cliente) ? $cliente->contato :'';
$itelefene = isset($cliente) ? $cliente->telefone :'';
$icelular  = isset($cliente) ? $cliente->celular :'';
$iemail    = isset($cliente) ? $cliente->email :'';
$icep    = isset($cliente) ? format_cep($cliente->cep) :'';
$itpcli  =  isset($cliente) ? $cliente->type_cliente  :'';
?>
<div class="container" id="ContainerCliente">
<ul class="breadcrumb">
<li><a href="<?php echo base_url('clientes') ?>">Clientes</a> <span class="divider">/</span></li>
<li class="active">Cadastro Novo  </li>
</ul>

<div class="well mainBox">

<div class="novoClienteBoxOptions <?php echo $boxMod;?>">
<h4>Cadastro de Clientes</h4>
  <a href="#fisica" class="novoCliente btn"><i class="icon-user icon-reded"></i>Pessoa F&iacute;sica</a>
  <a href="#juridica" class="novoCliente btn"><i class="icon-user icon-reded"></i>Pessoa Jur&iacute;dica</a>
</div>
<?php 


$form  = form_open('#', array('method'=>'post','id'=>'clientesForm','class'=>'form-horizontal formClientes '.$formMod.''));
$form .= form_hidden('pessoa',$itpcli);
$form .= form_hidden('edition',($editar ? 'true':'false'));
$form .= form_hidden('idCliente',(isset($cliente) ? $cliente->codigo:0));
$form .='<div class="pessoajuridica '.$typeMod.'">';
$form .='<h4><i class="icon-user"></i> Cliente Pessoa Jur&iacute;dica</h4><a class="showOptionNovoCliente '.$boxMod.' link" href="#novoClienteOptions"><i class="icon-refresh"></i> Escolher tipo de Cliente</a>';
$form .='<div class="row">';
$form .='<div class="span7 boxNameCliente">'; 
$form .=form_label('Raz&atilde;o Social','nome',array('class'=>'required'));
$form .=form_input(array('name'=>'nomeCliente','value'=>$iname, 'class'=>'input input-xxlarge'));
$form .='</div>';
$form .='<div class="span4">';
$form .=form_label('CNPJ','cnpj',array('class'=>'required'));
$form .=form_input(array('name'=>'cnpjCliente','value'=>$icnpjcpf, 'class'=>'input input-large'));
$form .='</div>';
$form .='</div>';
$form .='<div class="row">';
$form .='<div class="span7">';
$form .=form_label('Nome Fantasia');
$form .=form_input(array('name'=>'nomeFantasiaCliente','value'=>$infantasia, 'class'=>'input input-xxlarge'));
$form .='</div>';
$form .='</div>';
$form .='</div>';
$form .='<div class="row pessoafisica '.$typeMod.'">';
$form .='<h4><i class="icon-user"></i> Cliente Pessoa F&iacute;sica</h4><a class="showOptionNovoCliente '.$boxMod.' link" href="#novoClienteOptions"><i class="icon-refresh"></i> Escolher tipo de Cliente</a>';
$form .='<div class="span7 boxNameCliente">'; 
$form .=form_label('Nome','nome',array('class'=>'required'));
$form .=form_input(array('name'=>'nomeCliente','value'=>$iname, 'class'=>'input input-xxlarge'));
$form .='</div>';
$form .='<div class="span4">';
$form .=form_label('CPF ','cpf',array('class'=>'required'));
$form .=form_input(array('name'=>'cpfCliente','value'=>$icnpjcpf, 'class'=>'input input-large'));
$form .='</div>';
$form .='</div>';

$form .='<div class="row page-header"></div>';
$form .='<div class="row">';
$form .='<div class="span2 boxCepCliente">';
$form .=form_label('Cep','cep',array('class'=>'required'));
$form .=form_input(array('name'=>'cepCliente','value'=>$icep, 'class'=>'input input-small'));
$form .='</div>';
$form .='<div class="span7">';
$form .=form_label('Logradouro','logradouro',array('class'=>'required'));
$form .=form_input(array('name'=>'logradouroCliente','value'=>$ilog, 'class'=>'input input-xxlarge'));
$form .='</div>';
$form .='<div class="span2">';
$form .=form_label('Tipo');
$form .=form_dropdown('tipoLogradouro',array('rua'=>'Rua','Travessa'=>'Travessa','quadra'=>'Quadra','Avenida'=>'Avenida','Estrada','Estrada','Modulo'=>'Módulo'),$itplog,'class="input input-medium"');
$form .='</div>';
$form .='</div>';
$form .='<div class="row">';

$form .='<div class="span1">';
$form .=form_label('numero','numero',array('class'=>'required'));
$form .=form_input(array('name'=>'numeroCliente','value'=>$inum, 'class'=>'input input-mini'));
$form .='</div>';
$form .='<div class="span1">';
$form .=form_label('Compl.');
$form .=form_input(array('name'=>'complementoCliente','value'=>$icomplemento, 'class'=>'input input-mini'));
$form .='</div>';
$form .='<div class="span4">';
$form .=form_label('Bairro');
$form .=form_input(array('name'=>'bairroCliente','value'=>$ibairro, 'class'=>'input input-xlarge'));
$form .='</div>';
$form .='<div class="span4">';
$form .=form_label('Cidade');
$form .=form_input(array('name'=>'cidadeCliente','value'=>$icidade, 'class'=>'input input-xlarge'));
$form .='</div>';
$form .='</div>';
$form .='<div class="row page-header"></div>';
$form .='<div class="row">';
$form .='<div class="span3">';
$form .=form_label('Contato');
$form .=form_input(array('name'=>'contatoCliente','value'=>$icontato, 'class'=>'input input-large'));
$form .='</div>';
$form .='<div class="span4">';
$form .=form_label('Email','email',array('class'=>'required'));
$form .=form_input(array('name'=>'emailCliente','value'=>$iemail, 'class'=>'input input-xxlarge'));
$form .='</div>';
$form .='</div>';
$form .='<div class="row">';
$form .='<div class="span3">';
$form .=form_label('Telefone 1','telefone',array('class'=>'required'));
$form .=form_input(array('name'=>'telefoneCliente','value'=>$itelefene, 'class'=>'input input-medium'));
$form .='</div>';
$form .='<div class="span3">';
$form .=form_label('Telefone 2');
$form .=form_input(array('name'=>'celularCliente','value'=>$icelular, 'class'=>'input input-medium'));
$form .='</div>';
$form .=form_submit(array('name'=>'cadastrar','value'=>'Salvar', 'class'=>'btn btn-primary pull-right btn-cliente'));

$form .='</div>';
$form .='<div class="clearfix"></div>';
$form .= form_close();

echo $form;

add_css(array('commom/clientes'));
add_js(array('plugins/jquery.qtip','plugins/validate/jquery.validate','plugins/validate.addMethods','plugins/validate/localization/messages_ptbr.js','plugins/mask/jquery.mask','common/jquery.clientes'),'top');
?>
</div>
</div>