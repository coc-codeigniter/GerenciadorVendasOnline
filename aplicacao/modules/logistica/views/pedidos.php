<div id="container">
    <div class="container_12">
        <div class="grid_12" id="header">
            <h2><a href="<?php echo base_url(); ?>index.php/sistema/">Sistema de Vendas</a> - Home</h2>
        <div class="user-info">
            <a href="<?php echo base_url()?>vendas/catalogo/todos""><i class="icon-arrow-left icon-white"></i> Catalogo</a>
            <a href="#"><i class="icon-user icon-white"></i></a>
            <a href="<?php echo base_url()?>login/loggout">Sair</a>    
       </div>
        </div>
        <div class="grid_12 menu">
            <ul>
                <li class="menu_ativo">
                    <a href="<?php echo base_url() ?>vendas/catalogo/pedidos"><span> Lista de Pedidos </span></a>
                </li>

            </ul>
        </div>
        <div class="content" >

            <table class="table table-bordered" style="width: 99%; margin: 0 auto;">
                <thead  style="background: #f8f8f8;">
                    <tr>
                        <th colspan="6">
                            <strong>Vendedor : <?php echo $this->session->userdata('login_user'); ?> </strong> 
                        </th>
                    </tr>
                    <tr>

                        <th> NUMERO</th>
                        <th>DATA </th>
                        <th>CLIENTE</th>
                        <th>TOTAL</th>
                        <th>STATUS</th>
                        <th width="20px">AÇÕES</th>

                    </tr></thead><tbody>

                    <?php
                    $total = 0;
                      foreach($pedidos as $pedido):
                          ?>
                    <tr>
                        <td><?php echo $pedido->id ?></td>
                        <td><?php echo format_data_br($pedido->data_pedido); ?></td>
                        <td><?php echo $pedido->nome; ?></td>
                        <td>R$ <?php echo number_format($pedido->total,2,',','.'); ?></td>
                        <td><?php echo get_status_pedido($pedido->status); ?></td>
                        <td><?php  echo (get_status_pedido($pedido->status) =='ADM-EXPEDICAO' ? '<a href="'.  base_url().'logistica/separacao/'.$pedido->id.'" class="status_pedido"   title="Enviar para Separação"><i class="icon-chevron-right"></i></a>':'' ); ?></td>
                    </tr>
                    <?php
                    $total = $total + $pedido->total; 
                      endforeach;         
                    
                    ?>

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" >
                            <strong>Total dos Pedidos</strong>
                        </td>
                        <td colspan="2" style="text-align: right;"> 
                            <strong> R$ 
                            <?php
                            echo number_format($total,2,',','.');
                             ?>
                            </strong>
                        </td>
                    </tr>
                </tfoot>
            </table>


        </div><!-- end content -->
    </div>
</div>
<?php
load_js(array('jquery.tooltip','modules/vendas/jquery.pedidosLista'));
?>