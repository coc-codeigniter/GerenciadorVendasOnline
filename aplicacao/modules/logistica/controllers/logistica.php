<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logistica extends MX_Controller {
    
    public function __construct() {
       parent::__construct();
        $this->load->model('logistica_model','gModel');
        $this->template =  $this->load->library('si/template');
        $this->user     =  $this->session->userdata('usuario_data');
        $this->defaultCategory = load_config_system('category');
        $menuPedidos = array(
                             array('url'=>'entregas/Separacao','name'=>'Separacao','label'=>'Separação'),
                             array('url'=>'entregas/PedidosEntregas','name'=>'PedidosEntregas','label'=>'Pedidos Entregas')
                             );
        $menus = prepareDataMenu($menuPedidos);
        $active =  !$this->uri->segment(2) ? 'entregas' : $this->uri->segment(2) ;
        $this->template->set_value_menutop(navbar_template($menus,$active,array('url'=>'entregas','name'=>'entregas','label'=>'Logisitica')));
         $user = $this->user;
        $this->id_user = $user['codigo'];
    }

    public function index() {

        $data['pedidos'] = $this->gModel->getPedidosLista();
        $this->template->render('index/index',$data);
    }
    function fluxo($id,$type,$redirect){
        $this->gModel->fluxo($id,$type);
        redirect(str_replace('-','/',$redirect));
        
    }
    function  separacao($id)
    {
        $this->gModel->fluxo($id, 4);
        redirect('financeiro');
        
    }
    
    function getListaItensPedido(){
          $row['rowPedido'] = false;
          $r = $this->gModel->getPedidoById($this->input->post('id')) ;
          if($r){
             $row['rowPedido'] = $r; 
          }
          
           header('Content-type:application/json;');
        echo json_encode($r);
    }
   function confirmLista(){
    
    
    $u = $this->gModel->updateEstoquePedido($_POST);
     header('Content-type:application/json;');
     $r['update'] = false;
     if($u){
       $r['update'] = true; 
       $r['data'] = $u;
         }
      echo json_encode($r);
    
   }
   
   function confirmaPedidoEntregar(){
     header('Content-type:application/json;');
     $obs = array('entregador'=>$this->input->post('entregador'),
                           'data'=>$this->input->post('dataentrega'),
                           'observacoes'=>$this->input->post('observacoes'),
                           'usuario'=>$this->id_user
                           );
     $data['data'] =  array('observacao_pedido'=>json_encode($obs));
     $data['where'] = array('id'=>$this->input->post('pedido'));
     $ud = $this->gModel->updatePedido($data);
     
     $r['update'] = false;
     
     if($ud){
        $r['update'] = true;
     }
     echo json_encode($r);
   }
   
   function getObservacaoPedido(){
       $r = $this->gModel->rowPedido($this->input->post('pedido'));
       header('Content-type:application/json;');
       
       echo $r->observacao_pedido;
   }
   function ListSeparados(){
     $data['pedidos'] = $this->gModel->getPedidosLista(4);
      $this->template->render('separacao/index',$data);
   }
   
   function PedidosEntregas(){
     $data['pedidos'] = $this->gModel->getPedidosLista('5');
     $this->template->render('finalizados/index',$data);
   }
   
   
   function notaFiscal($id){
    
      $pdf = $this->load->library('pdf/pdf');
      $data['nota'] = $this->gModel->getNotaByIdPedido($id);
      ob_start();
      $this->load->view('nota/index',$data);
      $file =  ob_get_clean();
     // exit($file);
        $pdf->load_html($file);
        $pdf->set_paper('A4','portrait');
        $pdf->render();
        $pdf->stream("nota{$id}.pdf", array('Attachment' => 0));
      //print_r($pdf);
   }
   
function boleto($id){
    
    $blt          =  $this->load->library('si_boleto/boleto');  
    $this->_boleto = $blt->load->boleto('santander');
    
    $pd = $this->gModel->rowPedido($id);
    
    $data =  array('valor'=>$pd->total,
                   'carteira'=>101,
                   'data_vencimento'=>'12/12/2012',
                   'codigo_cliente' =>'109290',
                   'nosso_numero'=>'1020989',
                   'cedente'=>'CAFECONTI.COM');
    
    $data['sacado'] = array('name'=>$pd->nome .' - '.format_cnpj($pd->cnpj_cpf));
    $data['cnpj_cpf']= '10099982000198';
    
    $this->_boleto->setDataBoleto($data);
    $b = $this->_boleto->getDataBoleto();
    
    
    $this->_boleto->createBoleto();
}


   
}
