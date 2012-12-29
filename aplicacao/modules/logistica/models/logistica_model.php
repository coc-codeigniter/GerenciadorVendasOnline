<?php

class Logistica_model extends CI_Model {

    public function __construct() {

        parent::__construct();
    }

    public function getPedidos($iduser) {

        $this->db->select('ps.* , cs.nome,us.nome as  nome_usuario,us.id')
                ->from('pedidos_sistema as ps')
                ->join('clientes_sistema as cs', 'cs.codigo = ps.id_cliente')
                ->join('users as us','ps.id_usuario = us.id')
                ->where(array('ps.status' => 3,'us.id'=>$iduser));

        $r = $this->db->get();
        return $r->result();
    }
    public function getPedidosLista($status=3) {

        $this->db->select('ps.* , cs.nome,us.nome as nome_usuario')
                ->from('pedidos_sistema as ps')
                ->join('clientes_sistema as cs', 'cs.codigo = ps.id_cliente')
                ->join('users as us','ps.id_usuario = us.id')
                ->where(array('ps.status' => $status));

        $r = $this->db->get();
        return $r->result();
    }
    
    
    function getPedidoById($id){
        
        $this->db->select('p.*,i.qtd,i.id as uid,i.name as produto,i.price,i.subtotal,i.conferido,c.nome as cliente,c.codigo,pt.estoque')
                ->from('itens_pedidos_sistema as i')
                ->join('pedidos_sistema as p', 'p.id = i.id_pedido')
                ->join('clientes_sistema as c','c.codigo=p.id_cliente')
                ->join('produtos_sistema as pt','i.id_produto = pt.codigo')
                ->where(array('p.id' => $id));
        $r = $this->db->get();
        
        return $r->result();
        
    }
    function updatePedido($data){
        
        $r = $this->db->update('pedidos_sistema',$data['data'],$data['where']);
        
        if($r){
            return true;
        }
        
        return false;
    }
    
    function getNotaByIdPedido($id){
        
      $r =  $this->db->get_where('notas_fiscais',array('id_pedido'=>$id));
      if($r->num_rows() == 0){
          $this->db->insert('notas_fiscais',array('id_pedido'=>(int)$id,'data_nota'=>date('Y-m-d')));
      }  
      
     $this->db->select('p.*,i.qtd,nf.id as nota,nf.data_nota,i.id as uid,i.name as produto,i.price,i.subtotal,i.conferido,c.logradouro,c.tipo_logradouro,c.nome as cliente,c.codigo,pt.estoque')
                ->from('itens_pedidos_sistema as i')
                ->join('pedidos_sistema as p', 'p.id = i.id_pedido')
                ->join('clientes_sistema as c','c.codigo=p.id_cliente')
                ->join('produtos_sistema as pt','i.id_produto = pt.codigo')
                ->join('notas_fiscais as nf','nf.id_pedido = p.id')
                ->where(array('p.id' => $id));
        $r = $this->db->get();
        
        return $r->result();
        
        
    }
    
    function lockPedidoByItemNoConferido($id)
    {
            $r = $this->db->get_where('itens_pedidos_sistema',array('id_pedido'=>$id,'conferido'=>0));
            return $r->num_rows();
    }
    
    public function fluxo($id, $tipo) {
        $up = $this->db;
        $up->where(array('id' => $id));
        if($up->update('pedidos_sistema', array('status' => $tipo))){
            
            return true;
        }
        return false;
    }
    
    public function rowPedido($id)
    {     
          $this->db->select('p.*, c.*')
              ->from('pedidos_sistema as p')
              ->join('clientes_sistema as c','c.codigo=p.id_cliente')
              ->where('p.id',$id);;
          $r = $this->db->get();
          return $r->row();
    }
    
    function  updateEstoquePedido($data){
           $valids =  array();
          
           foreach($data['itens'] as $item){
               ///$item =  $data['itens'][$i];
               $ipedido =  $this->db->get_where('itens_pedidos_sistema',array('id'=>(int) $item['id'],'conferido'=>0))->row();
               if($ipedido){
                    $rp = $this->db->get_where('produtos_sistema',array('codigo'=>$ipedido->id_produto))->row();
                    $estoque =  ($rp->estoque -$ipedido->qtd);
                    $up = $this->db;
                    $up->where(array('codigo'=>$ipedido->id_produto));
                    $up->update('produtos_sistema',array('estoque'=>$estoque));
                    $this->db->update('itens_pedidos_sistema',array('conferido'=>1),array('id'=>(int) $item['id']));
                    $valids[] = $ipedido;
               }
             
            
           }
           if(count($valids) > 0){
            return $valids;
           }
     return  false; 
           
           
            
    }

}


