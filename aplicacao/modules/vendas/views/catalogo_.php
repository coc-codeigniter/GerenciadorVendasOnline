
    <div class="container_12">
    
        <div class="grid_12" id="header">
            <h2><a href="<?php echo site_url(); ?>sistema/">Sistema de Vendas</a> - Home</h2>

            <div class="user-info">
                <a href="<?php echo site_url() ?>vendas/pedidos/"><i class="icon-list-alt icon-white"></i> Pedidos</a>
                <a href="#"><i class="icon-user icon-white"></i></a>
                <a href="<?php echo site_url() ?>login/loggout">Sair</a>    
            </div>

        </div>
        <div class="grid_12 menu">
            <ul>
                <li class="menu_ativo">
                    <a href="<?php echo base_url() ?>vendas/catalogo/todos"><span> Todas </span></a>
                </li>
                <?php
                if (isset($categorias)):
                    foreach ($categorias as $categoria):
                        ?>
                        <li class="menu_ativo">
                            <a href="<?php echo base_url() ?>vendas/catalogo/<?php echo $categoria->url; ?>"><span><?php echo $categoria->name; ?> </span></a>
                        </li>


                        <?php
                    endforeach;
                endif;
                ?>
            </ul>
        </div>
        <div class="content" >
            <div class="lista">
                <?php
                //listando produtos 
                if (isset($produtos) && is_array($produtos)):
                    ?>
                    <?php
                    foreach ($produtos as $item_produto):

                        echo form_open('vendas/add', 'class="formProduto"');
                        ?>

                        <div class="produto_item">
                            <h3><?php echo $item_produto->name; ?></h3>
                            <img src="<?php echo base_url() ?>web/images/produtos/<?php echo $item_produto->imagem; ?>" class="produto_imagem" />
                            <a href="<?php echo base_url() ?>produtos/detalhes/<?php echo $item_produto->codigo; ?>" class="detalhes_produto_item">[+] detalhes </a>
                            <span class="preco_produto_item">R$ <?php echo number_format($item_produto->price, 2, ',', ' '); ?></span>
                            <?php
                            if ($item_produto->estoque == 0) {
                                echo '<a href="#" class="no-estoque" title="Produto sem Estoque"><i class="icon-ban-circle"></i></a>';
                            } else {
                                echo form_input('qtd', 1, 'class="qtd-produto check-' . $item_produto->codigo . '" maxlength=3');
                                echo form_hidden('redirect', base_url() . 'vendas/catalogo/' . $this->uri->segment(3));
                                echo form_hidden('id', $item_produto->codigo);
                                echo form_hidden('name', $item_produto->name);
                                echo form_hidden('descricao', $item_produto->descricao);
                                echo form_hidden('price', $item_produto->price);
                                echo form_submit('action', 'add', 'class="add_cart" data-id="' . $item_produto->codigo . '" title="<strong>Estoque</strong> : ' . $item_produto->estoque . '"');
                            }
                            ?>

                        </div>
                            <?php
                            echo form_close();
                        endforeach;
                    endif;
                    ?>

                <div class="clear">
                    &nbsp;
                </div>
            </div><!--list grid -->

            <!-- Carrinho de Compras -->
            <div class="column_cart">

                <span class="info_categoria_column_cart">Categoria - <?php echo $this->uri->segment(3); ?></span>
                <!--
                <form action="#" method="post" class="form_pesquisa_cart">
                    <input type="text" name="txt_pesquisa" value="ves" maxlength="20" class="txt_pesquisa_produtos"/>
                    <input type="submit" name="bt_pesquisa"  class="bt_pesquisa" value="&nbsp;" />
                </form>
                -->
<?php
$dataCliente = $this->session->userdata('cliente');
?>
                <div class="info_cliente">
                    <h5>Dados do Or&ccedil;amento/Pedido</h5>
                    <b>Cliente : </b>
                    <span class="info_name_cliente"><?php echo ($dataCliente['nome']) ? $dataCliente['nome'] : 'sem Cliente' ?></span>
                    <br/><b>Codigo : </b>
                    <span class="info_codigo_cliente"><?php echo ($dataCliente['id_cliente']) ? $dataCliente['id_cliente'] : 'sem Cliente' ?></span>
                </div>
                <div class="info_cart_itens">
                    <!-- cartitens -->
                    <span class="info_cart_item_titulo">Itens </span>
                    <span class="n-orcamento"><a href="#" class="i-orcamento"><i class="icon-plus"></i>Orçamento</a> </span>

<?php
if ($cart = $this->cart->contents()):
    foreach ($cart as $pitem):
        ?>
                            <div class="info_cart_item">
                                <!--Item -->
                                <span class="info_cart_item_quantidade"><?php echo $pitem['qty'] ?> <span class="divider"> | </span></span>
                                <span class="info_cart_item_name"><?php echo $pitem['name'] ?> </span>
        <?php
        echo form_open('vendas/remove');
        echo form_hidden('redirect', base_url() . 'vendas/catalogo/' . $this->uri->segment(3));
        echo form_hidden('rowid', $pitem['rowid']);
        echo form_submit('action', 'ex', 'class="info_cart_item_excluir"');
        echo form_close();
        ?>
                                <div class="subtotal">subtotal R$ <?php echo number_format($pitem['subtotal'], '2', ',', '.'); ?></div>
                            </div><!-- end Item -->

        <?php
    endforeach;
    echo '<div class="vendas-total-pedido"> <strong>Total </strong> R$ ' . number_format($this->cart->total(), 2, ',', '.') . '</div>';
    ?>

                        <div class="form-actions">
                            <a href="<?php echo base_url(); ?>/vendas/finalizaPedido" class="btn btn-primary" id="save-finaliza" title="Finalizar o pedido">Finalizar</a>

                            <a href="<?php echo base_url(); ?>/vendas/salvarOrcamento" class="btn btn-small"  id="save-orcamento" title="Salvar Orçamento">Salvar</a>
                        </div>
    <?php
endif;
?>
                </div><!--end cartitens -->

            </div>
            <div class="clear">
                &nbsp;
            </div>
            <div class="info_sistem">
                &nbsp;
            </div>
        </div><!-- end content -->
    </div>


<?php
echo jsUrl();
load_js(array('jquery.tooltip','modules/vendas/jquery.catalogo'));
?>