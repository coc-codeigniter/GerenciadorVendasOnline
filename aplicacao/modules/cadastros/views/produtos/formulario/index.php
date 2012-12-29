<div class="container" id="ContainerCliente">
<ul class="breadcrumb">
<li><a href="<?php echo base_url('cadastros') ?>">Cadastros</a> <span class="divider">/</span></li>
<li><a href="<?php echo base_url('produtos.html') ?>">Produtos</a> <span class="divider">/</span></li>
<li class="active"><?php echo ($editar ?  'Edi&ccedil;&atilde;o':'Novo') ?> </li>
</ul>

<?php 
$iname      = (isset($produto) ? $produto->name : '');
$iemb       = (isset($produto) ? $produto->embalagem : '');
$icat       = (isset($produto) ? $produto->categoria : '');
$iforn      = (isset($produto) ? $produto->fornecedor : '');
$icurva     = (isset($produto) ? $produto->curva : '');
$ipricec    = (isset($produto) ? number_format($produto->preco_custo,2,',','.') : '');
$ipricev    = (isset($produto) ? number_format($produto->price,2,',','.') : '');
$ipeso      = (isset($produto) ? $produto->peso : '');
$iestoque   = (isset($produto) ? $produto->estoque : '');
$idesc      = (isset($produto) ? $produto->descricao : '');
$istatus    = (isset($produto) ? ($produto->status == 1 ? true:false) : true);
$imagem     = (isset($produto) ? $produto->imagem : '');
$idestaque  = (isset($produto) ? ((int) $produto->destaque == 1 ? true: false) :false );  



#------------prepareArray Fornecedores ----------- 
$optFornecedores = '';
if(isset($fornecedores)){
        foreach($fornecedores as $fornecedor):
                  $optFornecedores[$fornecedor->id]= $fornecedor->nome;
        endforeach;
}
$optCategorias = '';
if(isset($categorias)){
        foreach($categorias as $categoria):
                  $optCategorias[$categoria->codigo]= $categoria->name;
        endforeach;
}

$form  = form_open('#', array('method'=>'post','id'=>'clientesForm','class'=>'form-horizontal formProdutos well '));
$form .= form_hidden('edition',$editar);
$form .= form_hidden('idProduto',(isset($produto) ? $produto->codigo:''));
$form .='<div class="row">';
$form .='<div class="span3">';
$form .=form_label('Nome Produto','nome',array('class'=>'required'));
$form .=form_input(array('name'=>'nomeProduto','value'=>$iname, 'class'=>'input input-xxlarge'));
$form .='</div>';
$form .='</div>';
$form .='<div class="row">';
$form .='<div class="span2">';
$form .=form_label('Pre&ccedil;o de custo','precocusto',array('class'=>'required'));
$form .=form_input(array('name'=>'precoCustoProduto','value'=>$ipricec, 'class'=>'input input-small'));
$form .='</div>';
$form .='<div class="span2">';
$form .=form_label('Pre&ccedil;o de venda','precovenda',array('class'=>'required'));
$form .=form_input(array('name'=>'precoVendaProduto','value'=>$ipricev, 'class'=>'input input-small'));
$form .='</div>';
$form .='<div class="span1">';
$form .=form_label('Curva','curva',array('class'=>'required'));
$form .=form_dropdown('curvaProduto',array('A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E'),$icurva,'class="input input-mini"');
$form .='</div>';
$form .='<div class="span2">';
$form .=form_label('Tipo de embalagem','embalagem',array('class'=>'required'));
$form .=form_dropdown('embalagemProduto',array('un'=>'Unidade','cx'=>'Caixa','kg'=>'Kilograma'),$iemb,'class="input input-medium"');
$form .='</div>';
$form .='</div>';

$form .='<div class="row">';
$form .='<div class="span2">';
$form .=form_label('Peso (gr)','embalagem',array('class'=>'required'));
$form .= form_input(array('name'=>'pesoProduto','value'=>$ipeso,'class'=>'input input-small'));
$form .='</div>';
$form .='<div class="span2">';
$form .=form_label('Estoque','estoque',array('class'=>'required'));
$form .= form_input(array('name'=>'estoqueProduto','value'=>$iestoque,'class'=>'input input-small'));
$form .='</div>';
$form .='<div class="span3">';
$form .=form_label('Imagem','imagem',array('class'=>'required'));
$form .= form_input(array('name'=>'imagemProduto','value'=>$imagem,'class'=>'input input-large'));
$form .='</div>';
$form .='</div>';

$form .='<div class="row">';
$form .='<div class="span3">';
$form .=form_label('Categoria','categoria',array('class'=>'required'));
$form .=form_dropdown('categoriaProduto',$optCategorias,$icat,'class="input"');
$form .='</div>';

$form .='<div class="span3">';
$form .=form_label('Fornecedor','fornecedor',array('class'=>'required'));
$form .=form_dropdown('fornecedorProduto',$optFornecedores,$iforn,'class="input"');
$form .='</div>';
$form .='<div class="span1">';
$form .=form_label('Ativar','ativar');
$form .=form_checkbox('statusProduto','1',$istatus);
$form .='</div>';
$form .='<div class="span1">';
$form .=form_label('Destaque','destacar');
$form .=form_checkbox('destaqueProduto','1',$idestaque);
$form .='</div>';
$form .='</div>';

$form .='<div class="row">';
$form .='<div class="span8">';
$form .=form_label('Descri&ccedil;&atilde;o do Produto','descricao',array('class'=>'required'));
$form .= form_textarea(array('name'=>'descricaoProduto','rows'=>5,'cols'=>60),$idesc,'class="input input-xxlarge"');
$form .='</div>';
$form .='</div>';

$form .='<div class="form-actions">';
$form .= form_submit('','Salvar','class="btn btn-primary pull-right"');
$form .='</div>';
$form .='<div class="clearfix"></div>';
$form .= form_close();

echo $form;

add_css(array('commom/produtos'));
add_js(array('plugins/jquery.qtip','plugins/validate/jquery.validate','plugins/maskMoney/jquery.maskMoney','plugins/validate.addMethods','plugins/validate/localization/messages_ptbr.js','plugins/mask/jquery.mask','common/jquery.produtos'),'top');
?>

</div>