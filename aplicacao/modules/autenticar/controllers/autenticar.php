<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Autenticar extends MX_Controller {

	public function __construct(){
		 parent::__construct();
        
        $this->template = $this->load->library('si/template');
        $this->template->template = 'autenticar'; 
        $this->template->autentic = false;
        $this->autenticao = $this->template->au;
	}
	
	public function index()
	{
	   $this->template->render('index');
	}
	
    public function validate(){
        
        sleep(1);
        
       $this->autenticao->login($this->input->post('login'),$this->input->post('password'));
       $return['result'] = $this->autenticao->is_loged();
       header('Content-type: application/json');
       echo json_encode($return);
        
    }
	
    public function sair(){
        $this->autenticao->logout();
    }
 
    
	
	
	
	
	
}
