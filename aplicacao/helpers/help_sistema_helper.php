<?php

if (!defined('BASEPATH'))
    exit('Sem permissao de acesso ao script');


if (!function_exists('load_header')) {

    function load_header($encode = 'UTF-8') {

        switch (strtolower($encode)) {

            case 'utf-8':

                break;
        }



        //echo $header ;
    }

}

if (!function_exists('jsUrl')) {

    function jsUrl() {

        //echo $content ;

        return '<script type="text/javascript"> var jsUrl = "'. site_url().'"; </script>';
    }

}


if (!function_exists('load_footer')) {

    function load_footer() {

        //echo $footer ;
    }

}

if (!function_exists('load_css')) {

    function load_css(array $css, $media = 'screen') {
        if (!is_array($css))
            $css = array($css);


        foreach ($css as $sheet) {

            echo '<link rel="stylesheet" href="' . base_url() . 'web/css/' . $sheet . '.css"  media="' . $media . '"/>' . "\n";
        }
    }

}


if (!function_exists('load_js')) {

    function load_js(array $js) {


        if (!is_array($js))
            $js = array($js);
        if (is_array($js)) {

            foreach ($js as $script) {

                echo '<script type="text/javascript" src="' . base_url() . 'web/js/' . $script . '.js"></script>' . "\n";
            }
        }
    }

}

if (!function_exists('load_modulos')) {

    function load_modulos(array $modulos) {
            $mod = '<ul class="thumbnails">';
        if (is_array($modulos) || is_object($modulos)) {

            foreach ($modulos as $mod) {

                echo '<li class="span1 modulo">
                 <a href="' . base_url() . $mod->url_link . '" title="' . utf8_decode($mod->descricao) . '">
                 <img src="' . base_url() . $mod->imagem . '" alt=""  class="img-polaroid" /></a>
                 <b>' . ucfirst($mod->nome) . '</b></li>' . "\n";
            }
        }
        $mod = '</ul>';
    }

}



if (!function_exists('get_atualizacoes')) {

    function get_atualizacoes(array $data) {

        if (is_array($data) || is_object($data)) {
            $i = 0;
            foreach ($data as $k => $v) {
                // display novidades
                if ($k == 'novidades') {
                    echo '<div class="displays novidades"><h4>Novidades</h4>
		    			                  ';
                    foreach ($v as $nov) {


                        echo '<img src="' . base_url() . 'web/images/' . $nov . '" alt="p_news" />
		    			                  <span class="detalhes">[detalhes]</span>
		    			                 ';
                    }
                    echo '</div>';
                }
                // display mais vendidos 
                if ($k == 'mais_vendidos') {
                    echo '<div class="displays mais_vendidos">
		    			              <h4>Mais Vendidos</h4>';

                    foreach ($v as $vend) {

                        $class = $i % 2 ? 'prod_mais_right' : 'prod_mais_left';
                        echo '<span class="' . $class . '">
		    				                  <img src="' . base_url() . 'web/images/' . $vend . '" alt=""  title=""/>
		    				                  <a href="#" class="detalhes_prod_mais">[+]detalhes</a>
		    			                      </span>
		    			                 ';
                        $i++;
                    }
                    echo '</div>';
                }

                if ($k == 'melhores_vendedores') {
                    $j = 0;
                    echo '<div class="displays melhores_vendedores">
		    			             <h4>Melhores Vendedores</h4>';
                    foreach ($v as $m_vend) {
                        $class_vend = $j < 1 ? 'vendedor_pri' : 'vendedor_sec';
                        echo '<span class="' . $class_vend . '">' . $m_vend . '</span>';
                        $j++;
                    }
                    echo '</div>';
                }
            }
        }
    }

}





if (!function_exists('format_data_br')) {


    function format_data_br($data) {


        $_exdat = explode('-', trim($data));

        return $_exdat[2] . '/' . $_exdat[1] . '/' . $_exdat[0];
    }

}

if (!function_exists('format_data_eu')) {


    function format_data_eu($data) {


        $_exdat = explode('/', trim($data));

        return $_exdat[2] . '-' . $_exdat[1] . '-' . $_exdat[0];
    }

}


if (!function_exists('get_status_pedido')) {


    function get_status_pedido($tip) {
        $status = ''; 
        switch ((int)$tip):
            case '1':
            $status = 'FINANCEIRO';
            break;
            case '2':
            $status = 'APROVACAO-CREDITO';
            break;
            case '3':
            $status = 'ADM-EXPEDICAO';
            break;
            case '4':
            $status = 'SEPARÇÃO';
            break;
            case '5':
            $status = 'SEP-ENTREGA';
            break;
            case '6':
            $status = 'ROT-ENTREGA';
            break;
            case '7':
            $status = 'ENTREGA';
            break;
            case '0':
            default :
            $status = 'VENDAS';
            break;
        endswitch;
        
        return $status;
    }

}



function load_config_system($index){
    $config = array('category'=>'todos');  
     return $config[$index];
    
}


function set_breacumbs_pedido($id , $departament, $id_user){
     //time_reference 
    $ci = &get_instance();
    $cf =  $ci->config->config;
    date_default_timezone_set($cf['time_reference']);
    __status_pedido(array('id'         => $id , 
           'data'=> array(
                         'time'        => date('Y-m-d H:i:s'),
                         'departament' => $departament,
                         'id_user'     => $id_user)
    ));
           
}

function __status_pedido(array $data = array()){
    $ci = &get_instance();
    $r = __pedido_breadcumbs_db($data['id']);
    $cont  = count($r);
    
    
    if($cont > 0)
    {
        $d =  array();
        foreach($r as $row){
           
            $d[]= array('time'=>$row->time,'departament'=>$row->departament,'id_user'=>$row->id_user);
        }
        //add new data
        $d[]= array('time'=>$data['data']['time'],'departament'=>$data['data']['departament'],'id_user'=>$data['data']['id_user']);
            
    }else{
        
       $d[]= array('time'=>$data['data']['time'],'departament'=>$data['data']['departament'],'id_user'=>$data['data']['id_user']); 
    }
    $udp =  array('time_status'=>json_encode($d));
    $u = $ci->db->update('pedidos_sistema',$udp,array('id'=>$data['id']));
    if($u){
          return true;
    }
    return false;
 }

function __pedido_breadcumbs_db($id){
    $ci = &get_instance();
    $db = $ci->db; 
    
    $db->select('time_status')->from('pedidos_sistema')->where('id',$id);
    $r = $db->get();
    return json_decode($r->row()->time_status);
    
}
function lockPedidoNoEntrega($id){
     $ci = &get_instance();
     $db = $ci->db;
     $r = $db->get_where('itens_pedidos_sistema',array('id_pedido'=>$id,'conferido'=>0));
     
     if($r->num_rows() > 0){
        return true;
     }
     return false;
}

function format_cnpj($cnpj){
    
    return substr($cnpj,0,3).'.'.substr($cnpj,3,3).'.'.substr($cnpj,6,3).'/'.substr($cnpj,9,4).'-'.substr($cnpj,13);
}
function format_cpf($cpf){
    
     return substr($cpf,0,3).'.'.substr($cpf,3,3).'.'.substr($cpf,6,3).'-'.substr($cpf,9);
}

function format_cep($cep){
     return substr($cep,0,5).'-'.substr($cep,5);
}

function format_moeda($val, $symbol = 'R$ ',$precision =2,$milhar ='.',$decimal = ',')
{
    return  $symbol.number_format((float) $val,$precision,$decimal,$milhar);
}

