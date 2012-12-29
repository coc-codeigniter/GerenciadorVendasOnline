<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clientes extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template = $this->load->library('si/template');
        $this->user = $this->session->userdata('usuario_data');
        $this->defaultCategory = load_config_system('category');
        $menuPedidos = array(array(
                'url' => 'clientes/novo',
                'name' => 'novo',
                'label' => 'Cadastrar '));
        $menus = prepareDataMenu($menuPedidos);
        $active = !$this->uri->segment(2) ? 'clientes' : $this->uri->segment(2);
        $this->template->set_value_menutop(navbar_template($menus, $active, array(
            'url' => 'clientes',
            'name' => 'clientes',
            'label' => 'Clientes Home')));
        $this->load->model('clientes_model', 'cModel');
        $user = $this->user;
        $this->id_user = $user['codigo'];
    }

    public function index()
    {
        $data['clientes'] = $this->cModel->getClientes($this->id_user);
        $this->template->render('index/index', $data);
    }

    public function listarClientes()
    {
        return $data['clientes'] = $this->clientes_model->getClientes();
    }

    public function editar($id)
    {
        $this->novo($id, true);
        
     }
    public function novo($id = null, $editar = false)
    {

        $this->load->helper('form');
        $data['editar'] = $editar;
        if (!is_null($id)) {
            $data['cliente'] = $this->cModel->rowClienteById($id);
        }
        $this->template->render('formulario/index', $data);

    }
    public function getLogradouro()
    {
        $log = $this->input->post('cep');
        $r['logradouros'] = $this->cModel->loadPrefixLogradouros($log);
        header('content-type:application/json');
        echo json_encode($r);

    }
    
    function pedidosGetPedidos($id){
        
        $data['pedidos'] = $this->cModel->rowsPedidosCliente($id);
         $this->template->render('clientePedidos/index', $data);
        
    }
    

    function jsonCliente()
    {
           
          $pval = str_replace(array('.',
            '-',
            '/'), '', $this->input->post('valCheck'));
            
          
       if( $this->input->post('valCheck')  &&  (!$this->input->post('id') || $this->input->post('id') == null || $this->input->post('id') == 0 )){
          $r['cliente'] = $this->cModel->rowCliente($pval);
       }
       if($this->input->post('id') > 0){
           $d = $this->cModel->rowCliente($pval);
           $id =  $this->input->post('id');
           if($d->codigo == $id && $d->cnpj_cpf == $pval){
                $r['cliente'] = false; 
           }else{
               $r['cliente'] = true;
           }
       }    
        
        
        header('content-type:application/json');
        echo json_encode($r);


    }
    public function gravar()
    {


        header('content-type:application/json');
        $user = $this->user;

        $r['insert'] = false;
        $data_insert = $this->prepareData();
        $data_insert['data_cadastro'] = date('Y-m-d');
        $ins = $this->cModel->insertCliente($data_insert);
        if ($ins) {
            $r['insert'] = $ins;
        }
        echo json_encode($r);
        exit;
        //redirect('clientes');
    }
    
    
    private function prepareData()
    {
        return  array(
            'nome' => $this->input->post('nomeCliente'),
            'nome_fantasia' => $this->input->post('nomeFantasiaCliente'),
            'cnpj_cpf' => (((int)$this->input->post('cnpjCliente') > 0) ? str_replace(array
                (
                '.',
                '-',
                '/'), '', $this->input->post('cnpjCliente')) : str_replace(array('.', '-'), '',
                $this->input->post('cpfCliente'))), //preg_match('/\-\./','',$this->input->post('cpfCliente'))),
            'numero' => $this->input->post('numeroCliente'),
            'complemento' => $this->input->post('complementoCliente'),
            'contato' => $this->input->post('contatoCliente'),
            'observacoes' => $this->input->post('observacaoCliente'),
            'telefone' => $this->input->post('telefoneCliente'),
            'celular' => $this->input->post('celularCliente'),
            'user_id' => $this->id_user,
            'logradouro' => $this->input->post('logradouroCliente'),
            'tipo_logradouro' => $this->input->post('tipoLogradouro'),
            'bairro' => $this->input->post('bairroCliente'),
            'cidade' => $this->input->post('cidadeCliente'),
            'email' => $this->input->post('emailCliente'),
            'type_cliente' => $this->input->post('pessoa'),
            'cep' => preg_replace('/\-/', '', $this->input->post('cepCliente')),
           );
    }
    
    public function update()
    {    
        header('content-type:application/json');
        $r['update'] = false;
        if (!$this->input->post('idCliente')){
        
        
        }
        $dudp = $this->prepareData();
        $dudp['data_update'] = date('Y-m-d');
        $r['data'] = $dudp;
        $ac = $this->cModel->updateCliente($dudp, $this->input->post('idCliente'));
        if ($ac) {
         $r['update'] = true;
        }
         echo json_encode($r);
         exit;
        
    }

    public function excluir($cod)
    {
        if (!is_null($cod)) {

            $this->clientes_model->deleteCliente($cod);
            redirect('clientes');
        }
    }

}
