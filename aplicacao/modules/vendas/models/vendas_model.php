<?php

class Vendas_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->table = 'pedidos_sistema';
        
        
    }

    public function getProdutos($filter = null) {
        
        if (!is_null($filter)) {
            $this->db->where('categoria', $filter);
        }
        $this->db->where('status', 1);
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('produtos_sistema');
        if ($query->num_rows() > 0) {

            return $query->result();
        } else {

            return false;
        }
    }
    
    function insertEmptyOrcamento($data){
        
        if($this->db->insert('orcamentos_sistema',$data)){
            return $this->db->insert_id();
        }
        return false;
    }

    public function getCategorias() {


        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('categorias_sistema');
        if($query->num_rows() > 0){
        return $query->result();    
        }
        return false;
    }

    public function getCategoria($categoria = null) {

            if(!is_null($categoria)){
              $this->db->where('name', $categoria);  
               }
               $query = $this->db->get('categorias_sistema');
            if($query->num_rows() > 0){
                return $query->row();    
              
            }
            return false;
        
    }

    public function createPedido($data) {
        //echo'<pre>';
        //echo $this->cart->total();

        $this->db->insert('pedidos_sistema', array('id_cliente' => (int) $data['clienteData']['id'],
            'id_usuario' => $data['clienteData']['id_usuario'],
            'user_lock'  => $data['clienteData']['id_usuario'],'lock'=>1,
            'data_pedido' => date('Y-m-d'),
            'total' => $this->cart->total()));


        $pedido_id = $this->db->insert_id();

        foreach ($data['itens'] as $item):
        /*
            $rp = $this->db->get_where('produtos_sistema',array('codigo'=>$item['id']))->row();
            $estoque =  ($rp->estoque - $item['qty']);
            $up = $this->db;
            $up->where(array('codigo'=>$item['id']));
            $up->update('produtos_sistema',array('estoque'=>$estoque));
            */
           
            $this->db->insert('itens_pedidos_sistema', array('id_pedido' => $pedido_id,
                'id_produto' => $item['id'],
                'name' => $item['name'],
                'qtd' => $item['qty'],
                'subtotal' => $item['subtotal'],
                'price' => $item['price']
            ));
        endforeach;
        return $pedido_id;
    }
    
    public function createOrcamento($data) {
        $this->db->insert('orcamentos_sistema', array('id_cliente' => (int) $data['clienteData']['id'],
            'id_usuario' => $data['clienteData']['id_usuario'],
            'data_orcamento' => date('Y-m-d'),
            'total' => $this->cart->total()));
        $oc_id = $this->db->insert_id();
        if(!$oc_id){return false;}
        foreach ($data['itens'] as $item):
            /** 
            * preparando os dados para isnerir na tabela de itens orcamentos 
            **/
            $dataCreate = array('id_orcamento' => $oc_id,
                                'id_produto'   => $item['id'],
                                'name'         => $item['name'],
                                'qtd'          => $item['qty'],
                                'subtotal'     => $item['subtotal'],
                                'price'        => $item['price']
            );
           $this->createItemOrcamento($dataCreate);
        endforeach;
        
        
        return $oc_id;
    }
    //deleteOrcamento
    public function  getItensOrcamento($id)
    {
        $this->db->select('oc.* , i.*')
                ->from('itens_orcamento as i')
                ->join('orcamentos_sistema as oc', 'oc.id = i.id_orcamento')
                ->where(array('oc.id' => $id));
        $r = $this->db->get();
        if($r->num_rows() > 0){
          
            return $r->result();    
        }
        return false;
        
        
    }
    
    public function deleteItemOrcamento($where)
    {
           if($this->db->delete('itens_orcamento',$where)){
            return true;
           }
           
           return false;
    }
    
    public function deleteItemPedido($where)
    {
           if($this->db->delete('itens_pedidos_sistema',$where)){
            return true;
           }
           
           return false;
    }
    public function deleteOrcamento($id)
    {
           if($this->db->delete('orcamentos_sistema',array('id'=>$id))){
                 $this->deleteItemOrcamento(array('id_orcamento'=>$id));
                return true;
           }
           return false;
    }
     
     public function createItemOrcamento($data)
     {  
        $this->db->insert('itens_orcamento',$data);
        if($this->db->insert_id() > 0){
            return true;
        }
        return false;
     }
     
     public function createItemPedido($data)
     {  
        $this->db->insert('itens_pedidos_sistema',$data);
        if($this->db->insert_id() > 0){
            return true;
        }
        return false;
     }
     //updateItemOrcamento
     public function updateItemOrcamento($data)
     {
           if($this->db->update('itens_orcamento',$data['data'],$data['where'])){
               return true;
             }
           return false;
     }
     
     public function updateItemPedido($data)
     {
           if($this->db->update('itens_pedido',$data['data'],$data['where'])){
               return true;
             }
           return false;
     }
    
    function getPedido($id){
        
        $this->db->select('p.*,i.*,c.nome,c.codigo')
                ->from('itens_pedidos_sistema as i')
                ->join('pedidos_sistema as p', 'p.id = i.id_pedido')
                ->join('clientes_sistema as c','c.codigo=p.id_cliente')
                ->where(array('p.id' => $id));
        $r = $this->db->get();
        
        return $r->result();
        
    }
     
    public function getItensPedido($id) {

        $this->db->select('p.* , i.*')
                ->from('itens_pedidos_sistema as i')
                ->join('pedidos_sistema as p', 'p.id = i.id_pedido')
                ->where(array('p.id' => $id));
        $r = $this->db->get();

        return $r->result();
    }
    
    
    //public 

    public function getPedidos($where) {
        
        $this->db->select('ps.* , cs.nome')
                ->from('pedidos_sistema as ps')
                ->join('clientes_sistema as cs', 'cs.codigo = ps.id_cliente')
                ->where($where);

        $r = $this->db->get();
        return $r->result();
    }

    public function checkEstoque($id, $qtd) {
        $r = $this->db->get_where('produtos_sistema', array('codigo' => $id));
        $row = $r->row();

        if ($row->estoque < (int) $qtd) {

            return false;
        } else {

            return true;
        }
    }
    
    public function sessionCliente() {
        return $this->session->userdata('cliente');
    }

}
