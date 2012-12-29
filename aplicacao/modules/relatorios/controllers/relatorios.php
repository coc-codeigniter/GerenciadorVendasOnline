<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Relatorios extends MX_Controller {

	public function __construct(){
		 parent::__construct();
        $this->template =  $this->load->library('si/template');
       // $this->template->autentic = false;
	}
	
	public function index()
	{
	   
       $this->template->render('content.php');
	}
	
	
	
	
	
	
	
}
