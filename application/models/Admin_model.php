<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Admin_model extends CI_Model{
	function getClients(){
        $res = $this->db->select('*')->where('user_type','CLIENT')->get('traffic_users')->result_array();
        return $res;
    }
	function getLawyers(){
        $res = $this->db->select('*')->where('user_type','LAWYER')->get('traffic_users')->result_array();
        return $res;
    }
}