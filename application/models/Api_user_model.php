<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Api_user_model extends CI_Model

{



	public function register($first_name, $last_name, $email, $phone,$gender, $password,$user_type,$otp){
		$user = array();
		$user['first_name'] = $first_name;
		$user['last_name'] = $last_name;
		$user['email'] = $email;
		$user['phone'] = $phone;
		$user['gender'] = $gender;
		$user['password'] = $password;
		$user['user_type'] = $user_type;
		$user['otp'] = $otp;
		$user['is_active'] = $user_type == 'CLIENT' ? '1' : '0';
		$user['admin_message'] = $admin_message == 'CLIENT' ? '' : 'waiting for admin approval';
		
		$this->db->insert('users',$user);
		$insert_id = $this->db->insert_id();
		return $insert_id;


	}

	public function isEmailExist($email){

		$rows = array();
		$rows= $this->db->select('count(*) AS count')->where("email",$email)->get('users')->row_array();
		return $rows['count']>0 ? true : false;
	}

	public function sendOTP($phone,$otp){
			// OTP need to send Here
	}

	public function resendOTP($user_id,$otp){

		$rows = array();
		$rows= $this->db->select('phone')->where("id",$user_id)->get('users')->row_array();
		if($rows['phone']>0){
			$data  = array();
			$data['otp']= $otp;
			this->sendOTP($rows['phone'],$otp);
			$this->db->where('id',$user_id)->update('users',$data);

		}
		return $rows['phone']>0 ? true : false;

	}

	public function validateOTP($user_id,$otp){

		$rows = array();
		$rows= $this->db->select('count(*) AS count')->where("id",$user_id)->where("otp",$otp)->get('users')->row_array();
		if($rows['count']>0){
			$data  = array();
			$data['is_phone_verified']= '1';
			$this->db->where('id',$user_id)->update('users',$data);

		}
		return $rows['count']>0 ? true : false;
	}

	public function getUser($userid){
 		$rows = array();
     	$rows= $this->db->select('id,first_name, last_name, email,phone, image,is_phone_verified, is_active,admin_message, status')->where("id",$userid)>get('users')->row_array();
     	return $rows;
    
	}

	?>
