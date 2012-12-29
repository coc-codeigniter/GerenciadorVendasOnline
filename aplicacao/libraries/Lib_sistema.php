<?php 

class Lib_sistema extends  MX_Controller{
	
	public function __construct(){
			parent::__construct();
	}
		
		
		public function render($header= null , $content = null,$footer = null, array $dados =null){
			$data['header']  = is_null($header) ? '' : $header ;
			$data['content'] = is_null($content) ? '' : $content;
			$data['footer']  = is_null($footer) ? 'template/footer'  : $footer ;
			$data['data']    = is_array($dados) && !is_null($dados) ? $dados : 'dados precisa ser em array' ;
			$this->load->view('template/template',$data);
		    }
		
		
		public function renderParse($view=null,$dataview = null,$header=null,$paser = true){
			$data['header'] = $header ;
			$data['content']= null;
			
			if($view != null){
				$data['content'] = $this->parser->parse($view,$dataview,true);	
			}	
			  $this->parser->parse('template/template',$data);
		    }
		public function getModulos()
		{
			
			$query = $this->db->get_where('modulos_sistema',array('active'=>1));
			
			return  $query->result();
			
		}
		
		public function getAtualizacoes()
		{
			
			$query = $this->db->get('modulos_sistema');
			
			$novidades = array('novo_1'=>'p_news_1.jpg','novo_2'=>'p_news_1.jpg');
			$mais_vendidos = array('prod_1'=>'novas-impressoras-epson.jpg','prod_2'=>'novas-impressoras-epson.jpg','prod_3'=>'novas-impressoras-epson.jpg');
			$melhores_vendedores = array(
			                     'm_vend1'=>'Alexandre Domus',
			                     'm_vend2'=>'Elaine Perez Lima',
			                     'm_vend3'=>'Assis Ribeiro ',
			                     'm_vend4'=>'Angela Maria Souza',
			                     'm_vend5'=>'Gabriela Candido'
								 
								 );
			return $data = array('novidades'=>$novidades ,'mais_vendidos'=>$mais_vendidos,'melhores_vendedores'=>$melhores_vendedores );
		}
		
	
}