<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Produtos extends MX_Controller {

    private $template;

    public function __construct() {
        parent::__construct();
        modules::run('login/is_logged_in');
        modules::run('vendas_model');
        $this->template = $this->lib_sistema;
    }
    
   function index()
    {
       
       
    } 
    
    
   function detalhes($id)
    {
       
       echo $id;
       
    } 
    

}