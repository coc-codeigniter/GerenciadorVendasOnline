<?php 


class Vendas_model extends CI_Model{
	
	
	public function  __construct(){
		
		parent::__construct();
	
	}
	
	
	public function getProdutos(){
		$this->db->order_by('nome','ASC');
		$query = $this->db->get('produtos_sistema');
		
		if($query->num_rows() > 0){
			
			return $query->result();
		}else{
			
			return false;
		}
		
		
	}
	
	
}
