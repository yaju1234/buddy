<?php
defined('BASEPATH') OR exit('No direct script access allowed');



function calculateTotaluser(){
	$ci =& get_instance();
	$ci->load->database();
	$rows= $ci->db->select('*')->order_by('id','desc')->get('users')->result_array();;
	 //Process your query here...
		return count($rows);
	
}

?>