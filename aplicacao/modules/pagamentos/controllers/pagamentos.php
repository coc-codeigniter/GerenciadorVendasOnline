<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pagamentos extends MX_Controller {

    private $template;
    private $_lastPedido;
    private $cliente_id;
   
    public function __construct() {
        parent::__construct();
        
        $this->template =  $this->load->library('si/template');
        $this->user     =  $this->session->userdata('usuario_data');
        $this->load->model('pagamentos_model','pModel');
        $this->defaultCategory = load_config_system('category');
        $menuPedidos = array(
                             array('url'=>'pagamentos/Cadastros','name'=>'cadastros','label'=>'Cadastros'),
                             array('url'=>'pagamentos/Modulos','name'=>'modulos','label'=>'Modulos')
                             
                             );
        $menus = prepareDataMenu($menuPedidos);
        $active =  !$this->uri->segment(2) ? 'pagamentos' : $this->uri->segment(2) ;
        $this->template->set_value_menutop(navbar_template($menus,$active,array('url'=>'pagamentos','name'=>'Pagamentos','label'=>'Pagamentos')));
        
    }

    public function index() {
     $this->template->render('index/index');
    }
    
    
    public function Modulos($type = null){
       
        header('content-type:application/json');
        $d['parcelas'] = array(
                               array('label'=>'1x sem juros','value'=>1),
                               array('label'=>'2x sem juros','value'=>2),
                               array('label'=>'3x sem juros','value'=>3),
                               array('label'=>'4x sem juros','value'=>4),
                               array('label'=>'5x sem juros','value'=>5),
                               array('label'=>'6x sem juros','value'=>6),
                               array('label'=>'7x sem juros','value'=>7),
                               array('label'=>'8x sem juros','value'=>8),
                               array('label'=>'9x sem juros','value'=>9),
                               array('label'=>'10x sem juros','value'=>10),
                               array('label'=>'11x sem juros','value'=>11),
                               array('label'=>'12x sem juros','value'=>12)
                               );
        $d['urlsend']  = 'https://master.com.br';
        $d['urlresponse'] = 'https://master.com.br';
        $d['imagem']      = 'images/cartoes/master.png';  
        echo json_encode($d);
        
    }
    
    
    public function TypePayments(){
    header('content-type:application/json');    
    $data['payments'] =  $this->pModel->readTiposPagamentos();
    echo json_encode($data);
   }
   
   
   public function Payments($id)
   { 
         if($this->pModel->rowPagamento($id)){
            $r = $this->pModel->readModulos(array(  'tipoPagamento'=> $id));
            $data['modulos'] = $r ? $r : array();
            
            $this->load->view('modulos/index', $data);
            
         }
   }
   
   function ConfirmaPagamento(){
    
      $r['altPedido'] = false;
      if($this->input->post('id')){
      $this->session->unset_userdata('payments');
      $this->load->model('pedidos/Pedidos_model','pedidosModel');
      $idPedido      = $this->input->post('id');
      $tipoPagamento = $this->input->post('tipoPagamento');
      $parcelas      = $this->input->post('parcelas');
      $credor        = $this->input->post('credor');
      $data['data'] = array('pagamento_tipo'=>$tipoPagamento,'parcelas'=>$parcelas,'credor'=>$credor );
      $data['where']= array('id'=>(int)$idPedido);
      if($this->pedidosModel->updatePedido($data)){
        $sd = array('payments'=>array('id'=>$idPedido,'tipoPedido'=>$tipoPagamento,'parcelas'=>$parcelas,'credor'=>$credor));
        $this->session->set_userdata($sd);
        $r['altPedido'] = true;}
      
      }
      
      
      header('content-type:application/json'); 
      echo json_encode($r);
      
    }
    
    
    function SessionPagamentos($id = null){
      header('content-type:application/json');
      $ds['sessionPayments'] = false; 
      if(!is_null($id) && $this->session->userdata('pedidoLoadedId') != $id){
        $this->session->unset_userdata('payments');
        $this->load->model('pedidos/Pedidos_model','pedidosModel');
        $chk = $this->pedidosModel->pedidoCheckedComplete(trim($id));
        if($chk){
            $sd = array('payments'=>array('id'=>$chk->id,'tipoPedido'=>$chk->pagamento_tipo,'parcelas'=>$chk->parcelas,'credor'=>$chk->credor));
            $this->session->set_userdata($sd);
         }
         
      
      }else
      if($this->session->userdata('pedidoLoadedId') && is_null($id)){
        $this->load->model('pedidos/Pedidos_model','pedidosModel');
        $chk = $this->pedidosModel->pedidoCheckedComplete($this->session->userdata('pedidoLoadedId'));
        if($chk){
            $sd = array('payments'=>array('id'=>$chk->id,'tipoPedido'=>$chk->pagamento_tipo,'parcelas'=>$chk->parcelas,'credor'=>$chk->credor));
            $this->session->set_userdata($sd);
         }
      }
      if($this->session->userdata('payments')){
        $ds['sessionPayments'] = true;
        $ds['payments'] = $this->session->userdata('payments');
      }
      echo json_encode($ds);  
    }
    
    function entregas(){
        $this->load->model('pagamentos/entregas_model','entregaModel');
    }
    
    
    
       
}