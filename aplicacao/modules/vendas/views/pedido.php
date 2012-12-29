<div class="container" id="ContainerProdutos">

   <div class="content" >

            <table class="table table-bordered" style="width: 99%; margin: 0 auto;">
                <thead  style="background: #f8f8f8;">
                    <tr>
                        <th colspan="5">
                            <strong>CLIENTE : SONY DO BRASIL <?php $cliente = $this->session->userdata('cliente'); echo $cliente['nome']; ?> </strong> 
                        </th>
                    </tr>
                    <tr>

                        <th>CÓDIGO</th>
                        <th>PRODUTO</th>
                        <th>QTD</th>
                        <th>SUBTOTAL</th>
                        <th width="20px">AÇÕES</th>

                    </tr></thead><tbody>

                    <?php
                    foreach ($pedido_produtos as $produto):
                        ?>
                        <tr>
                            <td><?php echo $produto->id_produto; ?></td>
                            <td><?php echo $produto->name; ?></td>
                            <td><?php echo $produto->qtd; ?></td>
                            <td><?php echo 'R$ '. number_format($produto->subtotal,2,',','.'); ?></td>
                            <td><a href="#"><i class="icon-remove"></i></a></td>
                        </tr>
                        <?php
                    endforeach;
                    ?>

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <strong>Total</strong>
                        </td>
                        <td colspan="2" style="text-align: right;"> 
                            <strong>
                            <?php
                             echo 'R$ '.number_format($pedido_produtos[0]->total,2,',','.');
                             ?>
                            </strong>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div class="form-actions" style="width: 94%; margin: 10px auto;">
                  <a  href="<?php  echo base_url() ?>vendas/fluxo/<?php echo $this->uri->segment(3) ?>" class="btn btn-primary">Faturamento</a>
                  <a  href="#" class="btn"><i class="icon-barcode"></i> Pagamentos</a>
                  <a  href="#" class="btn"><i class="icon-shopping-cart"></i> Entregas</a>
                  <a  href="#" class="btn"><i class="icon-comment"></i> Observações</a>
                  <a  href="<?php  echo base_url() ?>vendas" class="btn btn-info"><i class="icon-ban-circle"></i> Catalogo</a>
            </div>
              
                
            
        </div><!-- end content -->

</div>
<?php 
add_css('commom/pedidos');
add_js(array('plugins/jquery.qtip'),'top');
add_js(array('common/jquery.pedidos'));


?>
