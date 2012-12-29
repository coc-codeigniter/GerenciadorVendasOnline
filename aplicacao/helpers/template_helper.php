<?php 

if(!function_exists('add_js')){
    
    function add_js($js /*string|array*/, $position = 'footer'/*top|footer*/){
        //force $js array
        if(!is_array($js)){
            $js =  array($js);
        }
        //instance CI
       
        
               $tpl = & get_instance();
        foreach($js as $script):
            // add script
           $tpl->template->add_js($script, $position);
        endforeach;
    }
    
}



if(!function_exists('add_css')){
    
    function add_css($css /*string|array*/, $position = 'top'/*top|footer*/){
        //force $js array
        if(!is_array($css)){
            $css =  array($css);
        }
        //instance CI
        
        $tpl = & get_instance();
        foreach($css as $style):
            // add script
           $tpl->template->add_css($style, $position);
        endforeach;
    }
    
}


function navbar_template($itens , $active = 'todos', $defaultMenu = array('url'=>'vendas/catalogo/todos','name'=>'Todos','label'=>'Todos'))
{     
   
    $url = site_url();
    $ci  = & get_instance();
    if(count($itens) > 0){
    $li =  '<li '.(preg_match('/('.strtolower($active).')/',strtolower($defaultMenu['name'])) ? 'class="active"':'').'>
                            <a href="'.site_url().$defaultMenu['url'] .'">'.$defaultMenu['label'].'</a></li>';
    foreach ($itens as $menu):
                        $li .= '<li '.(preg_match('/^('.strtolower($active).')$/',strtolower($menu->name)) ? 'class="active"':'').'>
                                <a href="'.site_url() .$menu->url.'">
                                '.utf8_decode($menu->name).'</a></li>';


     endforeach;
     //$ci->load->labrary('session');
     }else{
        $li = '';
     }
     $dtuser = $ci->session->userdata('usuario_data');
     $login  =  $dtuser['nome'];
    $ci = &get_instance();
    $db =  $ci->db;
    $info = $db->get_where('info_empresa',array('id'=>1))->row();
    $nav = <<<EOD
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand logoCliente" title="{$info->label}" href="{$url}"><img src="{$url}web/{$info->imagem}" /></a>
            <ul class="nav">
              {$li}
            </ul>
           <div class="pull-right" id="userInfoNav">
           <div class="btn-group pull-right">
              <a class="btn btn-primary" href="#"><i class="icon-user icon-white"></i> {$login}</a>
              <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#"><i class="icon-pencil"></i> Editar dados </a></li>
                
                <li><a href="{$url}autenticar/sair"><i class="icon-ban-circle"></i> Sair </a></li>
               
              </ul>
            </div>
           </div> 
          </div><!--/.nav-collapse -->
        
      
    
    

EOD;
    return $nav;
    
}

function prepareDataMenu($data){
      
      if(!is_array($data) && is_object($data)){
           $data  = array($data);
        }
      if(!is_object($data[0])){
              
              $d =  json_encode($data);
              //$r =  array();
              $data = json_decode($d);  
        }
       return $data;
    
}
?>
