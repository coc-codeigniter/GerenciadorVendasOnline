<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Carlos O Carvalho
 * @copyright 2012
 */

class Auth {
    
    public  $tb_users       = 'users';
    public  $default_login  = 'autenticar';
    private $_logged   = false;
    private $_data_user = array();
    
    public function __construct(){
        $this->ci = & get_instance();
        $this->user_db = $this->ci->db;
        $this->session = $this->ci->load->library('session'); 
    }
    
    public function is_loged(){
        if($this->session->userdata('usuario_data') && $this->session->userdata('usuario_logado')){return true;}
        return false;
    }
    
    public function login($user,$password){
        $this->session->unset_userdata('usuario_data');
        
        $r = $this->user_db->get_where($this->tb_users, array('login_user'=>$user,'login_password'=>md5($password)),1);
        $qsr = $r->result();
        
        if($r->num_rows() > 0){
            $this->set_data_user($this->userPrepareData($qsr[0]));
        }
        return false;
        
    }
    
    public function logout(){
        $this->session->unset_userdata('usuario_data');
        redirect($this->default_login);
    }
    
    
    private function set_data_user($data){
       $this->session->set_userdata($data);
    }
    
    public function get_data_user(){
       
        if($this->session->userdata('usuario_data')){
            return $this->session->userdata('usuario_data');
            }
        return false;
        
    }
    
    private function userPrepareData($data){
        $r =  array('usuario_data'=> 
                 array('nome'=>$data->login_user,'senha'       => $data->login_password,
                                                 'codigo'      => $data->id,
                                                 'logado_date' => date('h:i:s')
                                                   ),
                     'usuario_logado'            => true);
      return $r;
    }
    
    
}

?>