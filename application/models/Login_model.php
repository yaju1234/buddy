<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Login_model extends CI_Model
{

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
        $password  = $this->input->post('password');
		
        $chkRs  = $this->db->select('*')->where('email', $email)->where('password', md5($password))->get('traffic_admin');
        
        if($chkRs->num_rows() > 0){
            $temp   = array();
            $temp   = $chkRs->row_array();
            $this->session->set_userdata('logged_user', $temp);
            return true;
        }else{
            return false;
        }
    }
}