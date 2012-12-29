<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

defined('VERSION_SI')or define('VERSION_SI','1.1.2');
/**
 * Template
 * 
 * @package si
 * @author Carlos O Carvalho
 * @copyright 2012
 * @version $Id$
 * @access public
 * @description classe responsavel por usar um unico template
 */
class Template extends CI_Controller{
    
    
    public $template = 'default';
    public $prefixTitlePage   =  'Sistema de Vendas : ';
    public $autentic = true;// o template usara autenticacao 
    public $folder_web = 'web';
    private $js_all    =  array('top'=>array('library/jquery.js','plugins/bootstrapv2/bootstrap','common/jquery.template','plugins/jquery-ui/jquery-ui-1.8.18.custom.min'));
    private $css_all   =  array('top'=>array('plugins/bootstrapv2/bootstrap','plugins/blitzer/jquery-ui-1.8.23.custom')
                                           );
    private $tpl ;
    private $str;
    private $data = array();
    private $strmenu = '';
    
    
    public function __construct(){
        $this->ci = & get_instance();
        $this->si = $this->ci->load->library('parser');
        $this->ci->load->helper('template');
        $this->au = $this->ci->load->library('si/auth');
        
        
    }
    
    
    public function render($view, array $data = null){
        ob_start();
        $this->ci->load->view($view,$data);
        $this->markupVersion();
        $this->data['conteudo']     = ob_get_clean();
        $this->data['js_top']       = $this->show_js('top');
        $this->data['js_footer']    = $this->show_js();   
        $this->data['css_top']      = $this->show_css('top');
        $this->data['css_footer']   = $this->show_css();
        $this->data['templateName'] = $this->template.'PageTemplate';
        $this->data['title_page']   = $this->prefixTitlePage;
        $this->data['menutop']      = $this->strmenu;
        if($this->autentic):
            if(!$this->au->is_loged()){
               redirect($this->au->default_login); 
            }
        endif;
        $this->parseTemplate();
        
    }
    public function set_value_menutop( $value = null /* overwriter datamenu*/, $show=false)
    {      
            $menuTop = '<div class="navbar navbar-inverse navbar-fixed-top">
                         <div class="navbar-inner">
                         <div class="container-fluid">
                        '.$value.'</div></div></div>';
        
           $this->strmenu = ($value == null && !$show ? '' : $menuTop);
    }
    
    
    public function add_js($js/* name string */, $position ='footer' /*top|footer*/){
        //insert js in array if not exists
        
        if(isset($this->js_all[$position]) && !in_array($js,$this->js_all[$position])){
            $this->js_all[$position][]= $js; 
        }else{
            // 0 scripts in array
            $this->js_all[$position][]= $js;
        }
    }
    public function add_css($css, $position ='footer' /* top|footer*/){
        
        //insert css in array if not exists
        if(isset($this->css_all[$position]) && !in_array($css,$this->css_all[$position])){
            $this->css_all[$position][]= $css; 
             
        }else{
            $this->css_all[$position][]= $css;
        }
    }
    
    private function show_js($position = 'footer' /* top|footer*/)
    {
           
            $common = '';
            $plugins = '';
            $libray   = '';
            $urlbase  ='<script type="text/javascript"> var url_base = "'.base_url().'";</script>'."\n";
            $cache =  (ENVIRONMENT == 'production' ?  '':'nocache'); 
            if(isset($this->js_all[$position]) && count($this->js_all[$position]) > 0):
            foreach($this->js_all[$position] as $script ){
                   $script = str_replace('.js','',$script);
                  if(preg_match('/library/',$script)){
                      $libray.= '<script type="text/javascript" src="'.site_url().$this->folder_web.'/js/'.$script.'.js"></script>'."\n";
                  } 
                  if(preg_match('/plugins/',$script)){
                      $plugins .= '<script type="text/javascript" src="'.site_url().$this->folder_web.'/js/'.$script.'.js"></script>'."\n";
                  }
                  if(preg_match('/common/',$script)){
                      $common .= '<script type="text/javascript" src="'.site_url().$this->folder_web.'/js/'.$script.'.js"></script>'."\n";
                  }           
                
            }
            endif;
            
           return $urlbase.$libray.$plugins.$common;
    }
    private function show_css($position = 'footer' /* top|footer*/)
    {
            $common    = '';
            $plugins   = '';
            $libray    = '';
            $template  = '<link rel="stylesheet" type="text/css" href="'.site_url().$this->folder_web.'/css/templates/'.$this->template.'/'.$this->template.'.css" media="all"/>'."\n";
            $cache =  (ENVIRONMENT == 'production' ?  '':'nocache'); 
             
            if(isset($this->css_all[$position]) && count($this->css_all[$position]) > 0):
            foreach($this->css_all[$position] as $style ){
                if(!empty($style)):
                   $style = preg_replace('/\.css/','',$style);
                  if(preg_match('/library/',$style)){
                    
                      $libray .= '<link rel="stylesheet" type="text/css" href="'.site_url().$this->folder_web.'/css/'.$style.'.css" media="all"/>'."\n";
                  } 
                  if(preg_match('/plugins/',$style)){
                     
                      $plugins .= '<link rel="stylesheet" type="text/css" href="'.site_url().$this->folder_web.'/css/'.$style.'.css" media="all" />'."\n";
                  }
                  
                  if(preg_match('/commom/',$style)){
                      $common .= '<link rel="stylesheet" type="text/css" href="'.site_url().$this->folder_web.'/css/'.$style.'.css"  media="all" />'."\n";
                  }           
                  endif;
                
            }
            endif;
            
           return $libray.$plugins.$template.$common;
    }
    private function parseTemplate(/*class use library CI parser*/)
    {
         $this->tpl = 'template/'.( strpos($this->template,'.php') ? $this->template :$this->template.'.phtml' );
         $this->si->parse($this->tpl,$this->data) ;
        
    }
    
    
    
    private function markupVersion(){
        
        $version = "<script>
        $(document).ready(function(){

   $('<div class=\"pathVersion\">').html('Sistema de Gerenciamento de Vendas Online Version:".VERSION_SI."').css({
    'position':'fixed',
    'bottom'  :'0px',
    'left'    :'1px',
    'right'   :'1px',
    'text-align'    :'center'
      
   }).appendTo('body');

     });//end  </script> ";
     
    echo $version;
    }
    
}




?>