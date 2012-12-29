<div class="container boxPedidoFluxo" id="ContainerPedido">
<ul class="breadcrumb">
  <li><a href="javascript:void(0);">Pedidos</a> <span class="divider">/</span></li>
  <li class="active"><?php echo ucfirst($this->uri->segment(2)); ?></li>
 
</ul>
<div class="content" >
<?php 
   $tbl = '<table class="table"><thead>
      <tr><th>N.Pedido</th><th>Cliente</th><th>Data Pedido</th><th>Valor</th><th></th></tr></thead><tbody>';
   foreach($pedidos as $pedido){
    
     $tbl .='<tr><td>'.$pedido->id.'</td><td>'.$pedido->nome.'</td><td>'.format_data_br($pedido->data_pedido).'</td><td>'.$pedido->total.'</td><td>'.($pedido->status > 1 ? '<span title="Pedido em outro departamento" class="fluxoPedidoOuther label label-success" style="font-size:10px;">completo</span>': '<a href="'.base_url( (empty($pedido->parcelas) || empty($pedido->tipo_entrega) ? 'pedidos/FluxoIPedido' : 'pedidos/Sendfluxo') ).'/'.$pedido->id.'" class="label fluxoPedido" dataId="'.$pedido->id.'" style="margin-left:3px;"><i class="icon-chevron-right"></i></a> <a href="'.base_url('pedidos/FluxoIPedido').'/'.$pedido->id.'" class="label editarPedido" dataId="'.$pedido->id.'" style="margin-right:3px;"><i class="icon-pencil icon-white"></i></a>'.(empty($pedido->parcelas) || empty($pedido->tipo_entrega) ? '<span class="label label-important" style="font-size:10px;">incompleto</span>' : '<span class="label label-info" style="font-size:10px;">completo</span>')).'</td></tr>'; 
   }
   $tbl .='</tbody></table>';
   echo $tbl;
   add_css('commom/pedidos');
add_js(array('plugins/jquery.qtip'),'top');
add_js(array('common/jquery.listaUserPedidos'));
?>
<div class="form-actions" style="width: 94%; margin: 10px auto;">
                  <a  href="<?php  echo base_url() ?>vendas" class="btn btn-info"><i class="icon-ban-circle"></i> Catalogo</a>
            </div>
</div>
</div>