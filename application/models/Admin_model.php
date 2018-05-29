<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Admin_model extends CI_Model{
	function getClients(){
        $res = $this->db->select('*')->where('user_type','CLIENT')->where('status','1')->get('traffic_users')->result_array();
        return $res;
    }
	function getLawyers(){
        $res = $this->db->select('*')->where('user_type','LAWYER')->where('status','1')->get('traffic_users')->result_array();
        return $res;
    }
	function getBanners(){
        $res = $this->db->select('*')->get('traffic_banners')->result_array();
        return $res;
    }
}