<div id="container">
	<div class="container_12">
		<div class="grid_12" id="header">
			<h2><a href="<?php echo base_url(); ?>index.php/sistema/">Sistema de Vendas</a> - Home</h2>
		</div>
		<div class="grid_12 menu">
			<ul>
				<li class="menu_ativo">
					<a href="#1"><span>Informatica </span></a>
				</li>
				<li>
					<a href="#2"><span>Livros </span></a>
				</li>
				<li>
					<a href="#3"><span>Cartuchos </span></a>
				</li>
				<li>
					<a href="#4"><span>Softwares </span></a>
				</li>
				<li>
					<a href="#5"><span>Escritorio</span></a>
				</li>
			</ul>
		</div>
		<div class="content" >
			<div class="lista">
				<?php  
				//listando produtos 
				if(isset($produtos) && is_array($produtos)): ?>
				<?php  foreach($produtos as $item_produto): ?>
				
				<div class="produto_item">
					<h3><?php echo $item_produto->nome; ?></h3>
					<img src="<?php echo base_url()?>web/images/produtos/<?php echo $item_produto->imagem; ?>" class="produto_imagem" />
					<a href="#" class="detalhes_produto_item">[+] detalhes </a>
					<span class="preco_produto_item">R$ <?php echo number_format($item_produto->valor,2,',',' '); ?></span>
					<a href="#" class="add_cart" title="adicionar">adicionar</a>
				</div>
				<?php
				  endforeach;
				  endif;
				?>
		
				<div class="clear">
					&nbsp;
				</div>
			</div><!--list grid -->
			<div class="column_cart">
				<span class="info_categoria_column_cart">Categoria - Informatica</span>
				<form action="#" method="post" class="form_pesquisa_cart">
					<input type="text" name="txt_pesquisa" value="ves" maxlength="20" class="txt_pesquisa_produtos"/>
					<input type="submit" name="bt_pesquisa"  class="bt_pesquisa" value="&nbsp;" />
				</form>
				<div class="info_cliente">
					<h5>Dados do Or&ccedil;amento/Pedido</h5>
					<b>Cliente : </b>
					<span class="info_name_cliente">Valdir Santos - ME</span>
					<b>Codigo : </b>
					<span class="info_codigo_cliente">12898</span>
				</div>
				<div class="info_cart_itens">
					<!-- cartitens -->
					<span class="info_cart_item_titulo">Itens </span>
					<span class="info_cart_item_visualizar"><a href="#">Visualizar</a> </span>
					<div class="info_cart_item">
						<!--Item -->
						<span class="info_cart_item_quantidade">3</span>
						<span class="info_cart_item_name">Cartucho Epson T0133 </span>
						<a href="#" class="info_cart_item_excluir">excluir</a>
					</div><!-- end Item -->
					<div class="info_cart_item">
						<!--Item -->
						<span class="info_cart_item_quantidade">1</span>
						<span class="info_cart_item_name">Cartucho Epson T0133-M </span>
						<a href="#" class="info_cart_item_excluir">excluir</a>
					</div><!-- end Item -->
					<div class="info_cart_item">
						<!--Item -->
						<span class="info_cart_item_quantidade">1</span>
						<span class="info_cart_item_name">Toner Samsung </span>
						<a href="#" class="info_cart_item_excluir">excluir</a>
					</div><!-- end Item -->
				</div><!--end cartitens -->
				<span class="bt_form_submit">
					<input type="submit" class="input_form_submit" value="Finalizar"/>
				</span>
			</div>
			<div class="clear">
				&nbsp;
			</div>
			<div class="info_sistem">
				&nbsp;
			</div>
		</div><!-- end content -->
	</div>
</div>