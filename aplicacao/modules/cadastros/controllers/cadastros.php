<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cadastros extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template = $this->load->library('si/template');
        $this->user = $this->session->userdata('usuario_data');
        $this->defaultCategory = load_config_system('category');
        $menuCadastros = array(array(
                'url' => 'usuarios.html',
                'name' => 'usuarios',
                'label' => 'Cadastrar '));
        $menus = prepareDataMenu($menuCadastros);
        $active = !$this->uri->segment(2) ? 'cadastros' : $this->uri->segment(2);
        $this->template->set_value_menutop(navbar_template($menus, $active, array(
            'url' => 'cadastros',
            'name' => 'cadastros',
            'label' => 'Home Cadastros')));

        $user = $this->user;
        $this->id_user = $user['codigo'];

        $this->load->model('cadastros_model', 'modelCad');

    }

    public function index()
    {
        
        $dm = array(
                array('nome'=>'usuarios','descricao'=>'usuarios ','url_link'=>'usuarios.html', 'imagem'=>'web/images/icone_clientes.png'),
                array('nome'=>'produtos','descricao'=>'Manunten&ccedil;&atilde;o de produtos ','url_link'=>'produtos.html', 'imagem'=>'web/images/produtos.png')
                   
                   );
        $data['modulos'] = $this->convertArrayObject($dm); 
        $this->template->render('index/index',$data);
    }

    function Novo($type = null)
    {
        if (is_null($type)) {
            redirect('cadastros');
        }

    }

    function usuarios()
    {
        $data['usuarios'] = $this->modelCad->getUsuarios();
        $this->template->render('usuarios/index/index', $data);
    }
    function usuariosEditar($id)
    {
        $this->load->helper('form');
        $data['editar'] = true;
        $data['usuario'] = $this->modelCad->readUsuario(array('id'=>(int)$id));
        $this->template->render('usuarios/formulario/index',$data);

    }
    function usuariosNovo(){
        $this->load->helper('form');
        $data['editar'] = false;
        $this->template->render('usuarios/formulario/index',$data);
    }
    
    private function convertArrayObject($array)
    {
                            
              $d =  json_encode($array);
              return json_decode($d);
        
    }
    
    function usuariosGravar(){
        header('content-type:application/json');
        $data =  $this->prepareDataPostUsuarios();
        $data['login_cadastro'] = date('Y-m-d');
        $in =  $this->modelCad->createUsuario($data);
        $r['insert'] = false;
        if($in){ $r['insert'] = true;}
        echo json_encode($r);
        
    }
    function usuariosUpdate(){
        header('content-type:application/json');
        $data['data'] = $this->prepareDataPostUsuarios();
        $data['where'] =  array('id'=>$this->input->post('idUsuario'));
        $up = $this->modelCad->updateUsuario($data);
        $r['update'] = false;
        if($up){ $r['update'] = true;}
        echo json_encode($r);
    }
    
    
    private function prepareDataPostUsuarios(){
        
             $data =  array('nome'=>$this->input->post('nomeUsuario'),
                            'sobrenome'=>$this->input->post('sobrenomeUsuario'),
                            'email'=>$this->input->post('emailUsuario'),
                            'login_user'=>$this->input->post('loginUsuario'),
                            );
            if($this->input->post('passwordUsuario')){
                 $data['login_password'] = md5($this->input->post('passwordUsuario'));   
            }
            
            return $data;                
    }
    
    function usuariosVerifyExistsRegister(){
         header('content-type:application/json');
         $r['row'] = false;
         if( $this->input->post('val')  &&  (!$this->input->post('id') || $this->input->post('id') == null || $this->input->post('id') == 0 )){
          $rs = $this->modelCad->readUsuario(array('email'=>$this->input->post('val')));
         
          if($rs){$r['row'] = true;}
       }
       if($this->input->post('id') > 0){
           $d = $this->modelCad->readUsuario(array('id'=>$this->input->post('id')));
           $r['data'] = $d;
           $r['post'] = $_POST;
           $id =  $this->input->post('id');
           if($d->id == $id && $d->email == $this->input->post('val')){
                $r['row'] = false; 
           }else{
               $r['row'] = true;
           }
       }    
        echo json_encode($r);
        
    }
    
    //produtos
    
    function produtos(){
        $data['produtos'] = $this->modelCad->readAllProdutos();
        $this->template->render('produtos/index/index',$data);
    }
    
    function produtoNovo(){
        $data['editar'] = false;
        $data['categorias'] = $this->modelCad->readCategorias();
        $data['fornecedores'] = $this->modelCad->readFornecedores();
        $this->template->render('produtos/formulario/index',$data);
    }
    
    function produtosEditar($id){
        $data['editar'] = true;
        $data['categorias'] = $this->modelCad->readCategorias();
        $data['fornecedores'] = $this->modelCad->readFornecedores();
        $data['produto']  = $this->modelCad->readProduto(array('codigo'=>$id));
        $this->template->render('produtos/formulario/index',$data);
    }
    
    function produtoGravar(){
         header('content-type:application/json');
         $data = $this->prepareDataPostProdutos();
         $data['data_cadastro'] = date('Y-m-d');
         $insert =  $this->modelCad->createProduto($data);
         $r['insert'] = false;
         if($insert){
         $r['insert'] = true;
         }
         echo json_encode($r);
    }
    function produtoUpdate(){
         header('content-type:application/json');
         $data['data'] = $this->prepareDataPostProdutos();
         $data['where'] = array('codigo'=>$this->input->post('idProduto'));
         $update =  $this->modelCad->updateProduto($data);
         $r['update'] = false;
         if($update){
         $r['update'] = true;
         }
         echo json_encode($r);
    }
    
    
    
    private function prepareDataPostProdutos(){
        
             $data =  array('name'=>$this->input->post('nomeProduto'),
                            'descricao'=>$this->input->post('descricaoProduto'),
                            'preco_custo'=> number_format((float)$this->input->post('precoCustoProduto') ,2,',',''),
                            'price'=> number_format((float) $this->input->post('precoVendaProduto'),2,',',''),
                            'curva'=>$this->input->post('curvaProduto'),
                            'peso'=>$this->input->post('pesoProduto'),
                            'embalagem'=>$this->input->post('embalagemProduto'),
                            'fornecedor'=>$this->input->post('fornecedorProduto'),
                            'imagem'=>$this->input->post('imagemProduto'),
                            'categoria'=>$this->input->post('categoriaProduto'),
                            'estoque'=>(int)$this->input->post('estoqueProduto'),
                            'status'=> (!$this->input->post('statusProduto') ? 0:1),
                            'destaque'=> (!$this->input->post('destaqueProduto') ? 0:1)  
                            
                            );
           
            
            return $data;                
    }
    
    
    function produtoVerifyExistsRegister(){
         header('content-type:application/json');
         $r['row'] = false;
         if( $this->input->post('val')  &&  (!$this->input->post('id') || $this->input->post('id') == null || $this->input->post('id') == 0 )){
          $rs = $this->modelCad->readProduto(array('name'=>$this->input->post('val')));
          if($rs){$r['row'] = true;}
          
       }
       if($this->input->post('id') > 0){
           $d = $this->modelCad->readProduto(array('id'=>$this->input->post('id')));
           $r['data'] = $d;
           $r['post'] = $_POST;
           $id =  $this->input->post('id');
           if($d->id == $id && $d->name == $this->input->post('val')){
                $r['row'] = false; 
           }else{
               $r['row'] = true;
           }
       }    
        echo json_encode($r);
        
    }
    //fornecedores
    
    
}
