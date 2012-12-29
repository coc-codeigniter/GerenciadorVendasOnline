<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MX_Controller {
   
   
    
	public function __construct(){
		
		parent::__construct();
	}
	
	public function index()
	{
		
		 $this->lib_sistema->render('header_login','content','template/footer');
	}
	
	public function validate_credentials()
	{		
		$this->load->model('usermember_model');
		
		$query = $this->usermember_model->validate();
		if($query) // if the user's credentials validated...
		{
			
			redirect('sistema/modulos');
		}
		else // caso usuario e senha seja incorreta 
		{
			$this->index();
		}
		
		
	}	
	
	public function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			echo 'Voce nao tem permissao para acessar essa pagina';	
			die();		
			//$this->load->view('login_form');
		}		
	}
    
        public  function loggout()
                {
            $this->session->sess_destroy();
                  $this->lib_sistema->render('header_login','content','template/footer');
                   
                }

}
