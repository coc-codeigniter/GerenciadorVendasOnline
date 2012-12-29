<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Financeiro extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('financeiro_model','fModel');
        $this->template =  $this->load->library('si/template');
        $this->user     =  $this->session->userdata('usuario_data');
        $this->defaultCategory = load_config_system('category');
        $menuPedidos = array(
                             array('url'=>'financeiro/fluxopedidos','name'=>'FluxoPedidos','label'=>'Fluxo de Pedidos'),
                             array('url'=>'financeiro/faturamento','name'=>'PedidosFaturados','label'=>'Pedidos Faturados')
                             );
        $menus = prepareDataMenu($menuPedidos);
        $active =  !$this->uri->segment(2) ? 'financeiro' : $this->uri->segment(2) ;
        $this->template->set_value_menutop(navbar_template($menus,$active,array('url'=>'financeiro','name'=>'financeiro','label'=>'Financerio Home')));
        
    }

    public function index() {
         $data['pedidos'] =  $this->listaPedidos();
        $this->template->render('index/index',$data);
    }

    function listaPedidos() {
        return $this->fModel->getPedidos();
    }
    function faturamento(){
        
        $data['pedidos'] = $this->fModel->getPedidosFaturados();
        $this->template->render('faturamento/index',$data);
    }
    function fluxo($id) {
        header('content-type:application/json');
        $r['update'] =false;
        $r['faturado'] = true;
        $r['num_pedido'] = $id;
        if(!$this->fModel->faturado($id)){
            $r['faturado'] = false;
            echo json_encode($r);
            exit;
        }
        $f = $this->fModel->fluxo($id, 3);
        if($f){
        $r['update'] =true;    
        }
        
        echo json_encode($r);
    }

    function voltar($id) {
        header('content-type:application/json');
        $r['update'] =false;
        $r['faturado'] = true;
        $r['num_pedido'] = $id;
        if(!$this->fModel->faturado($id)){
            $r['faturado'] = false;
            echo json_encode($r);
            exit;
        }
        
        $f = $this->fModel->fluxo($id, 3);
        if($f){
        $r['update'] =true;    
        }
        
        echo json_encode($r);
    }
    
    function getPedido($id = null){
         header('content-type:application/json');
         $r['row_pedido'] = false;
         
         if(!is_null($id)){
            $d = $this->fModel->getPedido($id);
            if($d){
            $r['pedido'] = $d;
             $r['row_pedido'] = true; 
           }
         }
         echo json_encode($r);
            
        
    }
     
      function aprovarPedido(){
         header('content-type:application/json');
          $r['update'] = false;
            $data =  array('data' => array('status'=>3),
                           'where'=> array('id'=>$this->input->post('id')));
            if($this->fModel->updatePedido($data))
            {
            $r['update'] = true;                                         
            }
           echo json_encode($r);                                     
         
      }  
    function faturados() {
        
    }

    function faturar() {
           header('content-type:application/json');
           sleep(3); 
           $r['update'] = ( rand()%2 == 0 ? true: false);
           if($r['update']){
            $data =  array('data' => array('faturado'=>1),
                           'where'=> array('id'=>$this->input->post('id')));
            if($this->fModel->updatePedido($data))
            {
            $r['update'] = true;                                         
            }
           }
           echo json_encode($r);    
    }
    
    function setFatura(){
        
    }

}
