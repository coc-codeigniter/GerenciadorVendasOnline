<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuracoes extends MX_Controller {

	public function __construct(){
		parent::__construct();
		modules::run('login/is_logged_in');
		$this->load->model('vendas_model');
	}
	
	public function index()
	{
		
       echo 'Modulo nao disponivel';
	}
	
	
	
	
	
	
	
}
