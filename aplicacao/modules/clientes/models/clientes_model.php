<?php 


class Clientes_model extends CI_Model{
	
	
	public function  __construct(){
		
		parent::__construct();
	
	}
	
	
	public function getClientes($id_user){
		$this->db->order_by('nome','ASC');
		$query = $this->db->get_where('clientes_sistema',array('user_id'=>$id_user));
		
		if($query->num_rows() > 0){
			
			return $query->result();
		}else{
			
			return false;
		}
		
		
	}
	
	public function getRowCliente($cod){
		
		  $this->db->select('c.*')
		            ->from('clientes_sistema as c')
				 	->where('c.codigo',$cod)
					->limit(1) ;
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			
			return $query->row();
		}			 
		  
		
	}
    
    public function loadLogradouros($log, $numrows = 12){
        
        $this->db->select('*')
                 ->from('logradouros')
                 ->like('logradouro',$log)
                 ->limit($numrows);
               
         $r = $this->db->get();
         
         return $r->result();        
        
    }
	
	function updateCliente(array $data, $id){
		
		if(is_array($data)){
			$this->db->where('codigo',$id);
			if($this->db->update('clientes_sistema',$data)){return true;};
		    
            return false;
            
		}
        return false;
	}
	function insertCliente($data){
		
		if($this->db->insert('clientes_sistema',$data)){
		  return $this->db->insert_id();
		};
		return false;
	}
	function deleteCliente($codigo){
		
		$this->db->where('codigo',$codigo);
		$this->db->delete('clientes_sistema');
		
	}
	
	function getRowLogradouro($name){
		
		    $this->db->like('logradouro',trim($name));
			$query = $this->db->get('logradouros_sistema');
			if($query->num_rows() > 0){
				 $row = $query->row();
				return $row->codigo;
				
			}else{
				
				   $this->db->insert('logradouros_sistema',
				   array('logradouro'=>$name,'codigo_tipo_logradouro'=>1,'bairro'=> $this->input->post('cliente_bairro') ,
				         'codigo_cidade'=>1)
				        );
				return $this->db->insert_id();
				//return $this->db->insert_id();
				
				
				//return false;
			}
			
		   	
	}
    function rowsPedidosCliente($id){
        
        $this->db->select('p.*,c.nome,c.cnpj_cpf as cid')
                  ->from('pedidos_sistema as p')
                  ->join('clientes_sistema as c','p.id_cliente=c.codigo')
                  ->where('c.codigo',$id);
                  
                  $r = $this->db->get();
          
          return $r->result();         
        
    }
    function rowCliente($cnpj){
        
        $r = $this->db->get_where('clientes_sistema',array('cnpj_cpf'=>$cnpj));
        if($r->num_rows() > 0){
            return $r->row();
        }
        return false;
    }
    function rowClienteById($id){
        
        $r = $this->db->get_where('clientes_sistema',array('codigo'=>$id));
        if($r->num_rows() > 0){
            return $r->row();
        }
        return false;
    }
    
	function loadPrefixLogradouros($cep){
	   $cep  =  preg_replace('/\-/','',$cep);
       
       
	   $this->db->select('*')->from('cep_log_index')->where('cep5',substr($cep,0,5));
       $r = $this->db->get();
       if($r->num_rows() > 0){
                $row =  $r->row(); 
               // return substr($cep,0,5).'-'.substr($cep,5);
                $this->db->select('*')->from($row->uf)->where('cep',substr($cep,0,5).'-'.substr($cep,5));
                $rs = $this->db->get();
                if($rs->num_rows() > 0){
                     return $rs->row();
                }
                 
                 return false;
       }
       return false;
	}
	
	
}
