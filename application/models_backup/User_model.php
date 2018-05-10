<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class User_model extends CI_Model

{



 public function userList(){
  $rows = array();
   
  $rows= $this->db->select('*')->order_by('id','desc')->get('users')->result_array();

 
  return $rows;
   
}




}

?>
