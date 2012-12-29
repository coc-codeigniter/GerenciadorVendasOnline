<?php 


class Cadastros_model extends CI_Model{
	
	private $Tuser = 'users';
    private $Tprod = 'produtos_sistema';
	private $Tforn = 'fornecedores';
    private $Tcat = 'categorias_sistema';
    public function  __construct(){
		
		parent::__construct();
	
	}
	
    
    function getUsuarios(){
        $r = $this->db->get($this->Tuser);
        
        return $r->result();
    } 
    
    
    function createUsuario($data){
        if($this->db->insert($this->Tuser,$data)){
            return $this->db->insert_id();
        }
        return false;
        
    } 
    function updateUsuario($data){
        if($this->db->update($this->Tuser,$data['data'],$data['where']))
        {
            return true;
        }
        return false;
    }
    
    function readUsuario(array $where){
        
        $r = $this->db->get_where($this->Tuser,$where);
        if($r->num_rows() > 0){
            return $r->row();
        }
        return false;
        
    } 
    
    
    //products
    function readAllProdutos(){
        $this->db->select('pd.*,f.nome as fornecedor , c.name as categoria')
                   ->from($this->Tprod. ' as pd')
                   ->join($this->Tforn. ' as f','f.id=pd.fornecedor')
                   ->join($this->Tcat . ' as c','c.codigo=pd.categoria')
                   ->order_by('pd.name','ASC');
        $r = $this->db->get();
        return $r->result();
    }
    function readProduto(array $where){
        $r = $this->db->get_where($this->Tprod,$where);
        if($r->num_rows() > 0){
            return $r->row();
        }
        return false;
    } 
    function createProduto($data){
        if($this->db->insert($this->Tprod,$data)){
            return $this->db->insert_id();
        }
        return false;
    } 
    function updateProduto($data){
        if($this->db->update($this->Tprod,$data['data'],$data['where']))
        {
            return true;
        }
        return false;
    }
    
    //fornecedores
    
    
    
    function readFornecedores(){
              $this->db->order_by('nome','ASC');
        $r =  $this->db->get($this->Tforn);
        return $r->result();
    }
    function readFornecedor(array $where){
        $r = $this->db->get_where($this->Tforn,$where);
        if($r->num_rows() > 0){
            return $r->row();
        }
        return false;
    } 
    function createFornecedor($data){
        if($this->db->insert($this->Tforn,$data)){
            return $this->db->insert_id();
        }
        return false;
    } 
    function updateFornecedor($data){
        if($this->db->update($this->Tforn,$data['data'],$data['where']))
        {
            return true;
        }
        return false;
    }
    
    
     //categorias
    
    
    
    function readCategorias(){
        $r =  $this->db->get($this->Tcat);
        return $r->result();
    }
    function readCategoria(array $where){
        $r = $this->db->get_where($this->Tcat,$where);
        if($r->num_rows() > 0){
            return $r->row();
        }
        return false;
    } 
    function createCategoria($data){
        if($this->db->insert($this->Tcat,$data)){
            return $this->db->insert_id();
        }
        return false;
    } 
    function updateCategoria($data){
        if($this->db->update($this->Tcat,$data['data'],$data['where']))
        {
            return true;
        }
        return false;
    }
    
    
 }