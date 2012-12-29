<?php 


class Sistema_model extends CI_Model{
    
    
    function __construct(){
        parent::__construct();
    }
    
    
    function readDestaques(){
        $r = $this->db->get_where('produtos_sistema',array('destaque'=>1));
        return $r->result();
        
    }
    
}
