<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pedidos extends MX_Controller
{

    private $template;
    private $_lastPedido;
    private $cliente_id;

    public function __construct()
    {
        parent::__construct();
        modules::run('clientes/');
        modules::run('logistica/');
        modules::run('vendas/');
        
        $this->load->model('pedidos/pedidos_model', 'pedidosModel');
        $this->template = $this->load->library('si/template');
        $this->user = $this->session->userdata('usuario_data');
        $this->defaultCategory = load_config_system('category');
        $menuPedidos = array(array(
                'url' => 'pedidos/FluxoPedido',
                'name' => 'FluxoPedido',
                'label' => 'Fluxo Pedido'));
        $menus = prepareDataMenu($menuPedidos);
        $active = !$this->uri->segment(2) ? 'pedidos' : $this->uri->segment(2);
        $this->template->set_value_menutop(navbar_template($menus, $active, array(
            'url' => 'pedidos',
            'name' => 'Pedidos',
            'label' => 'Pedidos')));

        $this->uid = $this->user['codigo'];
    }

    public function index()
    {

        $data['pedidos'] = $this->pedidosModel->getPedidos($this->uid);

        $this->template->render('index/index', $data);
    }
    public function FluxoIPedido($pedido = null)
    {   
        
        $this->session->unset_userdata('payments');
        $this->session->unset_userdata('entregas');
        $this->session->set_userdata('pedidoLoadedId',$pedido);
        
        
        $this->sessionEntregas();
        modules::run('pagamentos/SessionPagamentos');
        redirect('pedidos/FluxoPedido/'.$pedido);
         echo '<div class="page-header"></div><pre>';
        print_r($this->session->all_userdata());
        echo '</pre>';
    }

    public function FluxoPedido($pedido = null)
    {
        $this->session->unset_userdata('pedidoLoadedId');
       // echo '<div class="page-header"></div><pre>';
       // print_r($this->session->all_userdata());
       // echo '</pre>';
        if (is_null($pedido)) {
            redirect('pedidos');
        }
        
        $chk = $this->pedidosModel->checkLockPedido($pedido, $this->user['codigo']);
        if (!$chk) {
            $this->template->render('message/error', array('message' =>
                    'O pedido esta bloquedo no momento outro usuario esta editando'));
        } else {
            $this->pedidosModel->lockPedido($pedido, $this->user['codigo']);
            $this->session->set_userdata('pedidoLoadedId', $pedido);
            $data['pedido_produtos'] = $this->vModel->getItensPedido((int)$pedido);
            $dataPedido = $this->gModel->rowPedido($pedido);
            if ($dataPedido->status > 1) {
                $this->template->render('message/error', array('message' =>
                        'O pedido nao esta mais nesse departamento'));

            } else {
                $data['pedido_id'] = (int)$pedido;
                $this->template->render('pedido/index', $data);
            }

        }

    }

    function Sendfluxo($id = null)
    {

        if (!is_null($id)) {
            $udp = array('data' => array('status' => 2), 'where' => array('id' => (int)$id));
            if ($this->pedidosModel->updatePedido($udp)) {
                redirect('pedidos');
            }
        }
        sleep(1);
        header('content-type:application/json');
        $data['responseFluxo'] = false;
        $data['id']            = $this->input->post('id');
        if ($this->gModel->fluxo($this->input->post('id'), 2)) {
            $data['responseFluxo'] = true;
        }
        echo json_encode($data);
    }
    public function TypePayments()
    {
        header('content-type:application/json');

        $data['payments'] = array(
            array('name' => utf8_encode('Cart�o de Cr�dito'), 'value' => 'cc'),
            array('name' => utf8_encode('Cart�o de D�dito'), 'value' => 'cd'),
            array('name' => utf8_encode('Dep�sito Banc�rio'), 'value' => 'db'),
            array('name' => utf8_encode('Boleto Banc�rio'), 'value' => 'bb'),
            array('name' => utf8_encode('Pagamento Cheque'), 'value' => 'pc'));


        echo json_encode($data);
    }


    public function Payments($type = null)
    {
        header('content-type:application/json');
        //return type module payment
        $data = array(
            'cc' => array('html' => '{paymentModuloCredito}'),
            'cd' => array('html' => '{paymentModuloDebito}'),
            'db' => array('html' => '{paymentModuloDeposito}'),
            'bb' => array('html' => '{paymentModuloBoleto}'),
            'pc' => array('html' => '{paymentModuloCheque}'));

        if (!isset($data[$type])) {
            $data['data'] = array('fail' => true);
            echo json_encode($data);
        } else {
            $r['data'] = $data[$type];
            echo json_encode($r);
        }
    }


    public function UpdatePedidoEntrega()
    {

        $dudp = array(
            'where' => array('id' => $this->input->post('id')),
            'data' => array(
                'entrega_diferente' => ($this->input->post('entregaDiferente') == 'true' ? 'S':'N'),
                'tipo_entrega' => $this->input->post('tipo_entrega'),
                'logradouro' => $this->input->post('logradouro') ?  $this->input->post('logradouro'):'',
                'contato' => $this->input->post('contato') ? $this->input->post('contato'):'',
                'uf' => $this->input->post('uf') ? $this->input->post('uf') :'',
                'numero' => $this->input->post('numero') ? $this->input->post('numero') :'',
                'complemento' => $this->input->post('complemento') ? $this->input->post('complemento'):'',
                'bairro' => $this->input->post('bairro') ? $this->input->post('bairro') :'',
                'cidade' => $this->input->post('cidade') ? $this->input->post('cidade'):'')
                );

        $update = $this->pedidosModel->updatePedido($dudp);

        header('content-type:application/json');

        // echo json_encode($dudp);
        // exit;
       //echo json_encode($_POST);
           $data['update'] = false;
        if ($update) {
            $data['update'] = true;
        }
        echo json_encode($data);

    }

    public function sessionEntregas()
    {   
        $this->session->unset_userdata('entregas');
        header('content-type:application/json');
        $ds['sessionEntrega'] = false;
        if ($this->session->userdata('pedidoLoadedId')) {
            
            $this->load->model('pedidos/Pedidos_model', 'pedidosModel');
            $chk = $this->pedidosModel->pedidoCheckedCompleteEntrega($this->session->
                userdata('pedidoLoadedId'));
               if ($chk) {
                $sd = array('entregas' => array(
                        'id' => $chk->id,
                        'entrega_diferente' => $chk->entrega_diferente,
                        'logradouro' => $chk->logradouro,
                        'bairro' => $chk->bairro,
                        'complemento' => $chk->complemento,
                        'contato' => $chk->contato,
                        'numero' => $chk->numero,
                        'uf' => $chk->uf,
                        'dados_entrega' => $chk->dados_entrega,
                        'tipo_entrega' => $chk->tipo_entrega,
                        'cidade' => $chk->cidade));
                $this->session->set_userdata($sd);
            }
        }
        if ($this->session->userdata('entregas')) {
            $ds['sessionEntrega'] = true;
            $ds['entregas'] = $this->session->userdata('entregas');
        }
        echo json_encode($ds);


    }

    function deleteItem()
    {
        header('content-type:application/json');
        $r['update'] = false;
        if ($this->input->post('idRemove') && $this->input->post('pedido')) {
            $udp = $this->pedidosModel->deleteItem($this->input->post('idRemove'), $this->
                input->post('pedido'));
            if ($udp) {
                $r['update'] = true;
                $r['pedidoTotal'] = $udp['pedido_total'];
            }
        }
        echo json_encode($r);

    }


    function updatePedidoItem()
    {
        header('content-type:application/json');
        $r['update'] = false;
        
       // print_r($_POST);
       // exit;
        if ($this->input->post('pedido') && $this->input->post('produto')) {
            $udp = $this->pedidosModel->updatePedidoItem(
            array(
                'where' => array('id_produto' => $this->input->post('produto'), 'id_pedido' => $this->
                        input->post('pedido')),
                'data' => array('qtd' => $this->input->post('qtd')),
                'id_pedido' => $this->input->post('pedido'))
                
                );

            if ($udp) {
                $r['update'] = true;
                $r['pedidoTotal'] = $udp['pedido_total'];
                $r['subtotal_produto'] = $udp['subtotal_produto'];
                $r['row_get'] = $udp['row'];
            }

        }
        echo json_encode($r);
    }

}
