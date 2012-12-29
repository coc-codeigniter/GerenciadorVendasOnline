<?php 

class Usermember_model extends CI_Model{
	
	
	function validate(){
			
		
		$this->db->where('login_user', $this->input->post('login_user'));
		$this->db->where('login_password', md5($this->input->post('login_password')));
		$query = $this->db->get('users');
		
		if($query->num_rows == 1)
		{
			
                    
                    $data = array(
				'login_user'   => $this->input->post('login_user'),
				'is_logged_in' => true,
                                'id_user'      => $query->row()->id
			);
			$this->session->set_userdata($data);
                    return true;
		}
	}



}
