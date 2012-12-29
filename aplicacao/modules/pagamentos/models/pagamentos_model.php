<?php 


class Pagamentos_Model extends CI_Model{
    
    public function __construct(){
        parent::__construct();
        $this->modulosTB = 'modulos_pagamentos';
        $this->tiposTB   = 'tipos_pagamentos';  
    }
    
    
    function readModulos($where)
    {
        $r = $this->db->get_where($this->modulosTB,$where);
        if($r->num_rows() > 0){return $r->result();}
        return false;   
           
           
    }
    
    function readTiposPagamentos(){
        
        $r = $this->db->get_where($this->tiposTB,array('status'=>2));
        if($r->num_rows() > 0){return $r->result();}
        return false; 
    }
    function rowPagamento($id){
        $r = $this->db->get_where($this->tiposTB,array('status'=>2));
        if($r->num_rows() > 0){return $r->row();}
        return false; 
        
    }
    
}