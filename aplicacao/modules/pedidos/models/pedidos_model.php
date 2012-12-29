<?php 

class Pedidos_model extends CI_Model{
    
    public function __construct(){
        parent::__construct();
        
        $this->table      = 'pedidos_sistema';
        $this->table_i    = 'itens_pedidos_sistema'; 
        
    }
    
    public function getPedidos($user) {

        $this->db->select('ps.* , cs.nome')
                ->from('pedidos_sistema as ps')
                ->join('clientes_sistema as cs', 'cs.codigo = ps.id_cliente')
                ->where(array('ps.id_usuario' => $user))->order_by('ps.data_pedido','desc');

        $r = $this->db->get();
        return $r->result();
    }
    
    function updatePedido($data){
         if($this->db->update($this->table,$data['data'],$data['where'])){
            return true;
         }
         return false;
    }
    
    function lockPedido($id,$user)
    {
        
        if($this->db->update($this->table,array('lock'=>1,'user_lock'=>$user),array('id'=>$id))){
            return true;
         }   
    }
    
    function checkLockPedido($id,$user){
        //verificando se o pedido pode ser editado pelo usuario
        $chl = $this->db->get_where($this->table,array('id'=>$id));
        if($chl->num_rows() > 0){
           $r =  $chl->row();
            if($r->user_lock == $user && $r->lock == 1){
              return true;  
            }
            if($r->lock == 0){
              return true;  
            }
        }
        return false;
       
    }
    
    
    function unlockPedido($id)
    {
        
        if($this->db->update($this->table,array('lock'=>0),array('id'=>$id))){
            return true;
         }   
    }
    
    function pedidoCheckedComplete($id){
        
      $ckl =  $this->db->get_where($this->table,array('id'=>$id));
      if($ckl->num_rows() > 0){
         $row =  $ckl->row(); 
         if($row->pagamento_tipo != null){
            return $row;
         } 
      }
      
      return false;
      
    }
    
    function pedidoCheckedCompleteEntrega($id){
        
      $ckl =  $this->db->get_where($this->table,array('id'=>$id));
      if($ckl->num_rows() > 0){
         
         
         $row =  $ckl->row(); 
        
         if($row->entrega_diferente == 'S' && $row->tipo_entrega != ''){
            return $row;
         }
         if($row->tipo_entrega != '' || empty($row->tipo_entrega)){
            return $row;
         } 
      }
      
      return false;
      
    }
    
    
    function deleteItem($id,$pid){
        if($this->db->delete($this->table_i,array('id_pedido'=>$pid,'id_produto'=>$id))){
            
            $r = $this->db->get_where($this->table_i,array('id_pedido'=>$pid));
            $rs =  $r->result();
            $ntotal = 0;
            foreach($rs as $item):
                   $ntotal += $item->subtotal;   
            endforeach;
            $this->updatePedido(array('data'=>array('total'=>$ntotal), 'where'=>array('id'=>$pid)));
            return array('pedido_total'=> $ntotal);
        }
        
        return false;
    }
    
    
    function updatePedidoItem($data){
        $r = $this->db->get_where($this->table_i,$data['where']);
        if($r->num_rows() > 0){
        $row =  $r->row();
        $data['data']['subtotal'] = ($row->price * $data['data']['qtd']); 
        if($this->db->update($this->table_i,$data['data'],$data['where'])){
            
            $r = $this->db->get_where($this->table_i,array('id_pedido'=>$data['id_pedido']));
            $rs =  $r->result();
            $ntotal = 0;
            foreach($rs as $item):
                   $ntotal += $item->subtotal;   
            endforeach;
            $this->updatePedido(array('data'=>array('total'=>$ntotal), 'where'=>array('id'=>$data['id_pedido'])));
            return array('pedido_total'=> $ntotal , 'subtotal_produto'=> $data['data']['subtotal'],'row'=>$row);
        }
        }
        return false;
    }
    
}
