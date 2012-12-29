<div class="container" id="ContainerPedido">
<ul class="breadcrumb">
  <li><a href="#">Pedidos</a> <span class="divider">/</span></li>
  <li class="active"><?php echo ucfirst($this->uri->segment(2)); ?></li>
  <li class="pull-right containerLoad" style="position: relative;"></li>
</ul>
<div class="content" >

            <table class="table table-bordered" style="width: 99%; margin: 0 auto;">
                <thead  style="background: #f8f8f8;">
                    <tr>
                        <th colspan="6">
                            <strong>CLIENTE : <?php $cliente = $this->session->userdata('cliente'); echo $cliente['nome']; ?> </strong> 
                        </th>
                    </tr>
                    <tr>

                        <th>C&Oacute;DIGO</th>
                        <th>PRODUTO</th>
                        <th>QTD</th>
                        <th>PRE&Ccedil;O</th>
                        <th>SUBTOTAL</th>
                        <th width="20px">&nbsp;</th>

                    </tr></thead><tbody>

                    <?php
                    foreach ($pedido_produtos as $produto):
                        ?>
                        <tr>
                            <td><?php echo $produto->id_produto; ?></td>
                            <td><?php echo $produto->name; ?></td>
                            <td class="editable" pid="<?php echo $produto->id_pedido; ?>" proid="<?php echo $produto->id_produto; ?>"><?php echo $produto->qtd; ?></td>
                            <td><?php  echo 'R$ '. number_format($produto->price,2,',','.'); ?></td>
                            <td class="subtotalitem"><?php echo 'R$ '. number_format($produto->subtotal,2,',','.'); ?></td>
                            <td><a href="#remove" class="removeItemPedido" uid="<?php echo $produto->id_produto; ?>" pid="<?php echo $produto->id_pedido; ?>"><i class="icon-trash"></i></a></td>
                        </tr>
                        <?php
                    endforeach;
                    ?>

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">
                            <strong>Total</strong>
                        </td>
                        <td colspan="2" style="text-align: right;"> 
                            <strong class="displayTotal">
                            <?php
                             echo 'R$ '.number_format($pedido_produtos[0]->total,2,',','.');
                             ?>
                            </strong>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div class="form-actions" style="width: 94%; margin: 10px auto;">
                  <input name="faturarPedido" id="faturarConfig" type="button" data-id="<?php echo $pedido_id; ?>" class="btn btn-primary btn-small"  value="Faturamento" />
                  <a  href="#" class="btn btn-small" id="paymentConfig" data-id="<?php echo $pedido_id; ?>" ><i class="icon-barcode"></i> Pagamentos</a>
                  <a  href="#" class="btn btn-small" id="entregarConfig" data-id="<?php echo $pedido_id; ?>"><i class="icon-shopping-cart"></i> Entregas</a>
                  <a  href="#" class="btn btn-small"><i class="icon-comment"></i> Observa&ccedil;&otilde;es</a>
                  <a  href="<?php  echo base_url() ?>pedidos" class="btn btn-danger btn-small"><i class="icon-list "></i> Pedidos</a>
                  <a  href="<?php  echo base_url() ?>vendas" class="btn btn-info btn-small"><i class="icon-ban-circle"></i> Catalogo</a>
                  <a  href="<?php  echo base_url() ?>vendas/createSessionProduto/<?php echo $pedido_id; ?>" class="btn btn-small btn-success"><i class="icon-plus icon-white"></i> Adicionar Produto</a>
            </div>
              
                
            
        </div><!-- end content -->

</div>
<?php 
add_css('commom/pedidos');
add_js(array('plugins/jquery.qtip','plugins/validate/jquery.validate','plugins/actions.pedidos/jquery.ActionsPedidos'),'top');
add_js(array('common/jquery.pedidos'));


?>
