<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Login_model extends CI_Model

{



 public function login($email,$password){
  $rows = array();
  $result = array();
  
  $rows= $this->db->select('*')->where("email",$email)->where("password",md5($password))->get('users_admin')->row_array();
  return $rows;
   
}




}

?>
