<?php if (!defined('BASEPATH'))exit('No direct script access allowed');



class Login_model extends CI_Model

{
   
   public function script()
    {
        $data = array();
        $options = array("cost" => 11);
        $data['password']  = password_hash("123456", PASSWORD_BCRYPT, $options);
    	$this->db->where('id','1')->update('appo_admin',$data);
    } 
//-------------------------------
// EMAIL EXISTS (true or false)
//---------------------------------
private function email_exists($email)
{
	$this->db->where('email', $email);
	$query = $this->db->get('users');
	if( $query->num_rows() > 0 ){ return TRUE; } else { return FALSE; }
}
 
//---------------------------------
// AJAX REQUEST, IF EMAIL EXISTS
//---------------------------------
function register_email_exists()
{
	if (array_key_exists('email',$_POST)) {
		if ( $this->email_exists($this->input->post('email')) == TRUE ) {
			echo json_encode(FALSE);
		} else {
			echo json_encode(TRUE);
		}
	}
}


private function forget_email_exists($email)
{
	$this->db->where('email', $email);
	$query = $this->db->get('users');
	if( $query->num_rows() > 0 ){ return TRUE; } else { return FALSE; }
}
 
//---------------------------------
// AJAX REQUEST, IF EMAIL EXISTS
//---------------------------------
function forget_pass_email_exists()
{
	if (array_key_exists('email',$_POST)) {
		if ( $this->forget_email_exists($this->input->post('email')) == TRUE ) {
			echo json_encode(TRUE);
		} else {
			echo json_encode(FALSE);
		}
	}
}


    
 function check_credentials(){
        
        $email  = $this->input->post('email');
        $password  = $this->input->post('email');
        
        $options = array("cost" => 11);
        $pass = password_hash($password, PASSWORD_BCRYPT, $options);
        $pass_verify = password_verify($password, $pass);
        
        if($pass_verify == 1)
        {
        $chkRs  = $this->db->select('*')->where('email', $email)->get('appo_admin');
        
        //echo $this->db->last_query();die;
        
        if($chkRs->num_rows() > 0){
            $temp   = array();
            $temp   = $chkRs->row_array();
            
            $this->session->set_userdata('logged_user', $temp);
            
              return 1;  
            
        }else{
            return 0;
        }
       }
       else
       {
           return 0;
       } 
    }   

}
