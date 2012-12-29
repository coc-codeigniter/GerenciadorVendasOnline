<?php

class Financeiro_model extends CI_Model {
    private $db_pedidos = 'pedidos_sistema';
    public function __construct() {

        parent::__construct();
    }

    public function getPedidos() {

        $this->db->select('ps.* , cs.nome, us.nome as nome_usuario')
                ->from('pedidos_sistema as ps')
                ->join('clientes_sistema as cs', 'cs.codigo = ps.id_cliente')
                ->join('users as us','us.id = ps.id_usuario')
                ->where(array('ps.status' => 2));

        $r = $this->db->get();
        if($r->num_rows() > 0){return $r->result();}
        return false;
        
    }
    public function getPedidosFaturados() {

        $this->db->select('ps.* , cs.nome, us.nome as nome_usuario')
                ->from('pedidos_sistema as ps')
                ->join('clientes_sistema as cs', 'cs.codigo = ps.id_cliente')
                ->join('users as us','us.id = ps.id_usuario')
                ->where(array('ps.status' => 3,'ps.faturado'=>1));

        $r = $this->db->get();
        if($r->num_rows() > 0){return $r->result();}
        return false;
        
    }
    function updatePedido($data){
        
        if($this->db->update('pedidos_sistema',$data['data'],$data['where'])){
            return true;
        }
        
        return false;
        
    }
    
    public function getPedido($id){
        $this->db->select('ps.* , cs.nome, us.nome as nome_usuario')
                ->from('pedidos_sistema as ps')
                ->join('clientes_sistema as cs', 'cs.codigo = ps.id_cliente')
                ->join('users as us','us.id = ps.id_usuario')
                ->where('ps.id',$id);

        $r = $this->db->get();
        if($r->num_rows() > 0){return $r->row();}
        return false;
    }
   public function fluxo($id, $tipo) {
        $up = $this->db;
        $up->where(array('id' => $id));
        if($up->update($this->db_pedidos, array('status' => $tipo))){
            return true;
        }
        return false;
    }
    function faturado($id){
              $r = $this->db->get_where($this->db_pedidos,array('faturado'=>1,'id'=>$id));
              if($r->num_rows() > 0){
                return true;
              }
              return false;        
    }
    public function voltar($id, $tipo) {
        $up = $this->db;
        $up->where(array('id' => $id));
        if($up->update($this->db_pedidos, array('status' => $tipo))){
            return true;
        }
        return false;
    }
    
}
