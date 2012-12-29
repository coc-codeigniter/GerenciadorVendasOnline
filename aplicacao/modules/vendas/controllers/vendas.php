<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendas extends MX_Controller
{

    private $template;
    private $_lastPedido;
    private $cliente_id;

    public function __construct()
    {
        parent::__construct();
        modules::run('clientes/');
        modules::run('logistica/');
        $this->load->model('vendas_model', 'vModel');
        $this->template = $this->load->library('si/template');
        $this->defaultCategory = load_config_system('category');
        $this->user = $this->session->userdata('usuario_data');
        $user = $this->user;
        $this->id_user = $user['codigo'];
    }

    public function index()
    {
        redirect('vendas/catalogo/todos');
    }

    public function catalogo($categoria = null)
    {
        if (is_null($categoria)) {
            $categoria = $this->defaultCategory;
        }
        //read products and categorys

        $filter = $this->vModel->getCategoria($categoria);

        $categoria = (!$filter ? $this->defaultCategory : $categoria);
        //read all categorys
        $this->template->set_value_menutop(navbar_template($this->vModel->getCategorias
            (), $categoria));
        //read products by category
        $data['produtos'] = $this->vModel->getProdutos((!$filter ? null : $filter->
            codigo));

        $data['categoria'] = $categoria;
        $this->template->render('catalogo', $data);
    }

    public function cartInsert()
    {

        $data = array(
            'id' => $this->input->post('id'),
            'name' => $this->input->post('name'),
            'qty' => $this->input->post('qtd'),
            'price' => $this->input->post('price'),
            'descricao' => $this->input->post('descricao'));

        $this->cart->insert($data);
        $data = $this->cart->contents();
        $adr = array();
        foreach ($data as $it):
            $adr[] = $it['rowid'];
        endforeach;
        $lastPositionItem = count($adr) - 1;
        if (!$this->input->post('orcamento_numero') && !$this->input->post('pedido_numero')) {
            $cart['is_exist_cart'] = true;
            $d = array('cartStatus' => array(
                    'session_cart_temporary' => true,
                    'session_cart_permanent' => false,
                    'last_orcamento_numero' => false));
            $this->session->set_userdata($d);

        }

        if ($this->input->post('createDataBase') && $this->input->post('orcamento_numero')) {
            $dataSe = $data[$adr[$lastPositionItem]];
            $dataCreate = array(
                'id_orcamento' => $this->input->post('orcamento_numero'),
                'id_produto' => $dataSe['id'],
                'name' => $dataSe['name'],
                'qtd' => $dataSe['qty'],
                'subtotal' => $dataSe['subtotal'],
                'price' => $dataSe['price']);
            $this->vModel->createItemOrcamento($dataCreate);
        }
        if ($this->input->post('createDataBase') && $this->input->post('pedido_numero')) {
            $dataSe = $data[$adr[$lastPositionItem]];
            $dataCreate = array(
                'id_pedido' => $this->input->post('pedido_numero'),
                'id_produto' => $dataSe['id'],
                'name' => $dataSe['name'],
                'qtd' => $dataSe['qty'],
                'subtotal' => $dataSe['subtotal'],
                'price' => $dataSe['price']);
            $this->vModel->createItemPedido($dataCreate);
        }

        //last ID add
        $data['send'] = $_POST;
        header('Content-type:application/json;');
        echo json_encode($data[$adr[$lastPositionItem]]);

    }

    public function createSessionProduto($id)
    {
        //header('Content-type:application/json;');
        $this->session->unset_userdata('cartStatus');
        $this->session->unset_userdata('clienteNewCartCreate');
        $this->cart->destroy();
        $data_set_session = array('cartStatus' => array('session_cart_pedido' => true,
                    'last_pedido_numero' => $id));
        $this->session->set_userdata($data_set_session);


        redirect('vendas');


    }

    public function createSessionNewOrcamento($idCliente)
    {
        //header('Content-type:application/json;');
        $this->session->unset_userdata('cartStatus');
        $this->session->unset_userdata('clienteNewCartCreate');
        $this->cart->destroy();
        $insOrc = $this->vModel->insertEmptyOrcamento(array(
            'id_cliente' => $idCliente,
            'id_usuario' => $this->id_user,
            'data_orcamento' => date('Y-m-d'),
            'total' => 0));
        $this->load->model('clientes_model', 'cModel');
        $rs = $this->cModel->rowClienteById($idCliente);
        $data_set_session = array(
            'is_exist_cart' => true,
            'clienteNewCartCreate' => array(
                'usuarioAutor' => $this->id_user,
                'idCliente' => $rs->codigo,
                'nomeCliente' => $rs->nome,
                'create' => date('Y-m-d H:i:s')),
            'cartStatus' => array(
                'session_cart_temporary' => false,
                'session_cart_permanent' => true,
                'last_orcamento_numero' => $insOrc));

        // echo '<pre>';
        // print_r($data_set_session);
        // exit;
        $this->session->set_userdata($data_set_session);
        redirect('vendas');


    }

    public function cartRemove()
    {

        $data = array('rowid' => $this->input->post('rowid'), 'qty' => 0);
        $data_itens_cart = $this->cart->contents();
        $rowid = $data_itens_cart[$this->input->post('rowid')];
        if ($this->input->post('orcamento_numero')) {
            $r['remove_database'] = $this->vModel->deleteItemOrcamento(array('id_produto' =>
                    (int)$rowid['id'], 'id_orcamento' => (int)$this->input->post('orcamento_numero')));
            $r['data_remove_database'] = $rowid;
            $remove = $this->cart->update($data);
            $r['remove'] = (bool)($remove ? true : false);
        }

        if ($this->input->post('pedido_numero')) {
            $r['remove_database'] = $this->vModel->deleteItemPedido(array('id_produto' => (int)
                    $rowid['id'], 'id_pedido' => (int)$this->input->post('pedido_numero')));
            $r['data_remove_database'] = $rowid;
            $remove = $this->cart->update($data);
            $r['remove'] = (bool)($remove ? true : false);
        }

        header('Content-type:application/json;');
        echo json_encode($r);

    }

    public function cartUpdate()
    {
        //funcao criada para ser usada com a tecnica ajax

        $data = array(
            'rowid' => $this->input->post('rowid'),
            'qty' => $this->input->post('qtd'),
            'price' => $this->input->post('price'));
        $update = $this->cart->update($data);
        if ($this->input->post('updateDataBase') && $this->input->post('orcamento_numero')) {
            $data_itens_cart = $this->cart->contents();
            $rowid = $data_itens_cart[$this->input->post('rowid')];
            $this->vModel->updateItemOrcamento(array('where' => array('id_produto' => (int)
                        $rowid['id'], 'id_orcamento' => (int)$this->input->post('orcamento_numero')),
                    'data' => array('subtotal' => $this->input->post('subtotal'), 'qtd' => $this->
                        input->post('qtd'))));
        }

        if ($this->input->post('updateDataBase') && $this->input->post('pedido_numero')) {
            $data_itens_cart = $this->cart->contents();
            $rowid = $data_itens_cart[$this->input->post('rowid')];
            $this->vModel->updateItemOrcamento(array('where' => array('id_produto' => (int)
                        $rowid['id'], 'id' => (int)$this->input->post('pedido_numero')), 'data' => array
                    ('subtotal' => $this->input->post('subtotal'), 'qtd' => $this->input->post('qtd'))));
        }
        $r['update'] = (bool)($update) ? true : false;
        header('Content-type:application/json;');
        echo json_encode($r);

    }

    public function cartRead()
    {
        //  echo '<pre>';
        header('Content-type:application/json;');
        // verificando se existe um sessao com orcamento aberto
        $statusCart = $this->session->userdata('cartStatus');
        $cart['is_exist_cart'] = false;
        if (!isset($statusCart['last_orcamento_numero']) && !isset($statusCart['last_pedido_numero']) &&
            $statusCart['session_cart_temporary']) {

            $data = $this->cart->contents();
            $cart['Cart'] = array();
            foreach ($data as $rowItem):
                $cart['Cart'][] = $rowItem;
            endforeach;
            $cart['totalProdutos'] = $this->cart->total();

        }

        if (isset($statusCart['last_orcamento_numero'])) {
            if ($od = $this->vModel->getItensOrcamento($statusCart['last_orcamento_numero'])) {
                $this->cart->destroy();
                $rs = $this->cModel->getRowCliente($od[0]->id_cliente);
                foreach ($od as $item):
                    $data_cart = array(
                        'id' => $item->id_produto,
                        'name' => $item->name,
                        'qty' => $item->qtd,
                        'price' => $item->price);
                    $this->cart->insert($data_cart);
                endforeach;


                $data_set_session = array('clienteNewCartCreate' => array(
                        'usuarioAutor' => $od[0]->id_usuario,
                        'idCliente' => $rs->codigo,
                        'nomeCliente' => $rs->nome,
                        'create' => date('Y-m-d H:i:s')), 'cartStatus' => array(
                        'session_cart_temporary' => false,
                        'session_cart_permanent' => true,
                        'last_orcamento_numero' => $statusCart['last_orcamento_numero']));

                $this->session->set_userdata($data_set_session);

            }

            $data = $this->cart->contents();
            $cart['Cart'] = array();

            foreach ($data as $rowItem):
                $cart['Cart'][] = $rowItem;
            endforeach;
            $cart['totalProdutos'] = $this->cart->total();

            if ($this->session->userdata('clienteNewCartCreate')) {
                $cart['cliente_session'] = $this->session->userdata('clienteNewCartCreate');
                $cart['status_cart'] = $this->session->userdata('cartStatus');
            }
            $cart['is_exist_cart'] = (bool)(count($cart['Cart']) == 0 || empty($cart['Cart']) ? false : true);

            if (count($cart['Cart']) == 0 or empty($cart['Cart'])) {
                unset($cart['Cart']);
                echo json_encode($cart);
                exit;
            }

        }

        if (isset($statusCart['last_pedido_numero'])) {

            $id = $statusCart['last_pedido_numero'];
            $this->cart->destroy();
            $pitens = $this->vModel->getPedido($id);


            foreach ($pitens as $item):
                $data_cart = array(
                    'id' => $item->id_produto,
                    'name' => $item->name,
                    'qty' => $item->qtd,
                    'price' => $item->price);
                $this->cart->insert($data_cart);
            endforeach;
            $data = $this->cart->contents();
            $cart['CartPedido'] = array();
            $cart['is_exist_cart'] = true;
            foreach ($data as $rowItem):
                $cart['CartPedido'][] = $rowItem;
            endforeach;
            $cart['last_pedido_numero'] = $id;
            $cart['totalProdutos'] = $this->cart->total();
            $cart['cliente'] = array('nomeCliente' => $pitens[0]->nome, 'idCliente' => $pitens[0]->
                    codigo);

        }


        echo json_encode($cart);

    }

    private function list_itens()
    {
        return $cart = $this->cart->contents();
    }

    public function savePedido()
    {
        //header('Content-type:application/json;');

        //caso pedido ja exista

        $data['save_pedido'] = false;
        $statusCart = $this->session->userdata('cartStatus');
        if (isset($statusCart['last_orcamento_numero']) != '') {


            $data['itens'] = $this->list_itens();
            $user = $this->session->userdata('usuario_data');
            $cliente = $this->session->userdata('clienteNewCartCreate');
            $data['clienteData'] = array('id' => $cliente['idCliente'], 'id_usuario' => $user['codigo']);
            $pedido = $this->vModel->createPedido($data);


            if ($pedido) {
                $oc = $this->session->userdata('cartStatus');
                if ($oc['session_cart_permanent']) {
                    $this->vModel->deleteOrcamento($oc['last_orcamento_numero']);
                }
                $this->session->unset_userdata('cartStatus');
                $this->cart->destroy();
                $data['save_pedido'] = true;
                $data['pedido_codigo'] = $pedido;


            }
        }

        if ($this->input->post('pedido_numero') != '') {
            $this->cart->destroy();
            $data['pedido_codigo'] = $this->input->post('pedido_numero');
            $this->session->unset_userdata('cartStatus');
            $data['save_pedido'] = true;

        }

        echo json_encode($data);


    }

    public function saveDataCart()
    {
        //$this->cart->destroy();
        // header('Content-type:application/json;');
        $r['result'] = false;
        $data['itens'] = $this->list_itens();
        $user = $this->session->userdata('usuario_data');
        $data['clienteData'] = array('id' => $this->input->post('idCliente'),
                'id_usuario' => $user['codigo']);
        //echo json_encode($data);
        if ($this->input->post('cliente_save_orcamento') == 1) {
            $id = $this->vModel->createOrcamento($data);
            if ($id > 0) {

                $dsession = array('cartStatus' => array(
                        'session_cart_temporary' => false,
                        'session_cart_permanent' => true,
                        'last_orcamento_numero' => (int)$id));
                $this->session->set_userdata($dsession);
                $r['orcamento_numero'] = $id;
                $r['result'] = true;
            }
        }


        echo json_encode($r);
        if ($r['result']) {
            $this->cart->destroy();
            $this->session->unset_userdata('clienteNewCartCreate');
        }

    }

    public function pedido($pedido)
    {


    }

    public function pedidos()
    {


        $data['pedidos'] = $this->vModel->getPedidos('o');

        $this->template->render('catalogo', $data);
    }

    public function checkEstoque()
    {

        $ch['resp'] = $this->vModel->checkEstoque($this->input->post('id_produto'), $this->
            input->post('check'));
        header('content-type:application/json');
        header('Expires:0');

        echo json_encode($ch);
    }

    public function listClientes()
    {

        if ($this->input->post('idCliente')) {
            $rs = $this->cModel->getRowCliente($this->input->post('idCliente'));
            if (!$rs):
                $data['exists_cliente'] = false;
            else:
                $sessionUser = $this->session->userdata('usuario_data');
                //reset session cart
                $data_set_session = array('clienteNewCartCreate' => array(
                        'usuarioAutor' => $sessionUser['codigo'],
                        'idCliente' => $this->input->post('idCliente'),
                        'nomeCliente' => $rs->nome,
                        'create' => date('Y-m-d H:i:s')), 'cartStatus' => array(
                        'session_cart_temporary' => true,
                        'session_cart_permanent' => false,
                        'last_orcamento_numero' => (int)0));

                $this->session->set_userdata($data_set_session);
                $data['cliente_session'] = $this->session->userdata('clienteNewCartCreate');
            endif;
            }
        else {
            $data['clientes'] = $this->cModel->getClientes($this->id_user);
        }

        header('content-type:application/json');
        header('Expires:0');

        echo json_encode($data);
    }

    public function saveSessionDataCliente()
    {

        $data['cliente'] = array('id_cliente' => $this->input->post('id'), 'nome' => $this->
                input->post('nome'));

        $this->session->set_userdata($data);
    }


    function finish()
    {

        $this->session->sess_destroy();
    }

}
