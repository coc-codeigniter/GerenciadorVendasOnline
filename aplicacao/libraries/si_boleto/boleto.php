<?php
class Boleto
{
    var $load;
    function __construct()
    {
        $this->load = new Type;
    }
}


class Type
{
    private $ci = false;
    private $layout;
    private $name = null;
    var $ci_config;
    private static $_instance_type;
    private $path_layouts = 'libraries/si_boleto/layouts/';
    protected $_is_loaded = array();
    protected $vars = array();
    
    function __construct()
    {   $this->ci = &get_instance();
        $this->ci_config = $this->ci->config->config;
        self::$_instance_type = &$this;
        if (isset($this->ci_config['directory_layouts_boletos'])) {
            $this->path_layouts = $this->ci_config['directory_layouts_boletos'];
        }
    }


    public static function &type_instance()
    {
        return self::$_instance_type;
    }
    function boleto($name)
    {
        
        
        $this->name = strtolower($name);
        if (isset($this->ci_config['directory_types_boletos'])) {
            $this->ci->load->file($this->ci_config['directory_types_boletos'] . $name .
                '.php');
        } else {
            $this->ci->load->file(APPPATH . 'libraries/si_boleto/types/' . $name . '.php');
        }
        return new $name;
    }

    function layout()
    {
        
        $data['boleto'] = array('data_vencimento' => '16/09/2012');
        $data =  array_merge_recursive($data,$this->vars);
        $this->_tp_load($this->name,$data);

    }
    
    
    
    

   private function _tp_load($load,$vars){
    
       if(!in_array($load,$this->_is_loaded)){
          $this->_is_loaded[]= $load;
          
       }
        
        extract($vars);
        ob_start();
        include($this->path_layouts.$load.'.php');
        $outuput =  ob_get_clean();
        echo $outuput;

    
   }
   function  set_var($var,$key = null)
   {
         if(!is_array($var)){
            $var =  array($var);
         }
         foreach($var as $key=>$val){
            $this->vars[$key]=$val;
         }
         
         
   }

}
