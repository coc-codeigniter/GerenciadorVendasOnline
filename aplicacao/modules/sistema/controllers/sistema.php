<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sistema extends MX_Controller {

	public function __construct(){
	    
         parent::__construct();
        $this->template = $this->load->library('si/template');
        $this->user = $this->session->userdata('usuario_data');
        $this->defaultCategory = load_config_system('category');
        
       
         $this->template->set_value_menutop(navbar_template(array()));
       
        $user = $this->user;
        $this->id_user = $user['codigo'];
	}
	
	public function index()
	{   $this->load->model('sistema_model','si_model');
	    $data['modulos']     = $this->lib_sistema->getModulos();
	    $data['atualizacoes']= $this->si_model->readDestaques();
        
		$this->template->render('index',$data);
        
       
	}
	
	
	public function modulos(){
		
    
	 $data['modulos']     = $this->lib_sistema->getModulos();
	 $data['atualizacoes']= $this->lib_sistema->getAtualizacoes();
	 $this->lib_sistema->render('template/header','content','template/footer',$data);

	}
	
	
	
}
