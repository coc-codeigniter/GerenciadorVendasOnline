<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "sistema";
$route['404_override'] = '';
$route['entregas'] = 'logistica';
$route['entregas/Separacao'] = 'logistica/ListSeparados';
$route['entregas/PedidosEntregas'] = 'logistica/PedidosEntregas';
$route['cadastros'] = 'cadastros';
$route['usuarios.html'] = 'cadastros/usuarios';
$route['usuarios/novo.html'] = 'cadastros/usuariosNovo';
$route['usuarios/update'] = 'cadastros/usuariosUpdate';
$route['usuarios/gravar'] = 'cadastros/usuariosGravar';
$route['usuarios/verifyExistsRegister'] = 'cadastros/usuariosVerifyExistsRegister';
$route['usuarios/(:num)/edicao.html'] = 'cadastros/usuariosEditar/$1';
$route['produtos.html'] = 'cadastros/produtos';
$route['produtos/novo.html'] = 'cadastros/produtoNovo';
$route['produtos/(:num)/edicao.html'] = 'cadastros/produtosEditar/$1';
$route['produto/update'] = 'cadastros/produtoUpdate';
$route['produto/gravar'] = 'cadastros/produtoGravar';
$route['produtos/verifyExistsRegister'] = 'cadastros/produtoVerifyExistsRegister';
$route['clientes/(:num)/pedidos.html']  = 'clientes/pedidosGetPedidos/$1';
$route['pedido/(:num)/notaFiscal.html'] =  'logistica/notaFiscal/$1';
$route['pedido/(:num)/boleto.html'] =  'logistica/boleto/$1';
/* End of file routes.php */
/* Location: ./application/config/routes.php */