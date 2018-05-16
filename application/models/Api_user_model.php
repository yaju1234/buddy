<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Api_user_model extends CI_Model
{
	public function register($first_name, $last_name, $email, $phone,$gender, $password,$user_type,$otp,$country,$state,$city){
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
		$user['admin_message'] = $user_type == 'CLIENT' ? '' : 'waiting for admin approval';
		$user['country'] = $country;
		$user['state'] = $state;
		$user['city'] = $city;

		
		$this->db->insert('traffic_users',$user);
		$insert_id = $this->db->insert_id();
		return $insert_id;


	}

	public function register_facebook($first_name, $last_name, $email, $phone,$gender, $password,$user_type,$image,$facebook_id,$country,$state,$city){
		$user = array();
		$user['first_name'] = $first_name;
		$user['last_name'] = $last_name;
		$user['email'] = $email;
		$user['phone'] = $phone;
		$user['gender'] = $gender;
		$user['password'] = $password;
		$user['user_type'] = $user_type;
		$user['image'] = $image;
		$user['is_active'] = $user_type == 'CLIENT' ? '1' : '0';
		$user['admin_message'] = $user_type == 'CLIENT' ? '' : 'waiting for admin approval';
		$user['register_from'] = 'FACEBOOK';
		$user['facebook_id'] = $facebook_id;
		$user['country'] = $country;
		$user['state'] = $state;
		$user['city'] = $city;
		
		$this->db->insert('traffic_users',$user);
		$insert_id = $this->db->insert_id();
		return $insert_id;


	}

	public function register_google($first_name, $last_name, $email, $phone,$gender, $password,$user_type,$image,$google_id,$country,$state,$city){
		$user = array();
		$user['first_name'] = $first_name;
		$user['last_name'] = $last_name;
		$user['email'] = $email;
		$user['phone'] = $phone;
		$user['gender'] = $gender;
		$user['password'] = $password;
		$user['user_type'] = $user_type;
		$user['image'] = $image;
		$user['is_active'] = $user_type == 'CLIENT' ? '1' : '0';
		$user['admin_message'] = $user_type == 'CLIENT' ? '' : 'waiting for admin approval';
		$user['register_from'] = 'GOOGLE';
		$user['google_id'] = $google_id;
		$user['country'] = $country;
		$user['state'] = $state;
		$user['city'] = $city;
		
		$this->db->insert('traffic_users',$user);
		$insert_id = $this->db->insert_id();
		return $insert_id;


	}

	public function isEmailExist($email, $user_type){

		$rows = array();
		$rows= $this->db->select('count(*) AS count')->where("email",$email)->where("user_type",$user_type)->get('traffic_users')->row_array();
		return $rows['count']>0 ? true : false;
	}

	public function userIdByEmail($email, $user_type){

		$rows = array();
		$rows= $this->db->select('id')->where("email",$email)->where("user_type",$user_type)->get('traffic_users')->row_array();
		return $rows['id'];
	}
	
	public function isLoginValid($email,$password,$user_type){

		$rows = array();
		$rows= $this->db->select('id')->where("email",$email)->where("password",$password)->where("user_type",$user_type)->get('traffic_users')->row_array();
		return $rows['id']>0 ? $rows['id'] : false;
	}
	
	public function isActiveUser($email){

		$rows = array();
		$rows= $this->db->select('count(*) AS count')->where("email",$email)->where("is_active",'1')->get('traffic_users')->row_array();
		return $rows['count']>0 ? true : false;
	}

	public function sendOTP($phone,$otp){
		$from = '+1 647-697-7286';
		$to = $phone;
		$message = 'Your verification code is '.$otp;
		$response = $this->twilio->sms($from, $to, $message);
		if($response->IsError)
			//echo 'Error: ' . $response->ErrorMessage;
			return false;
		else
			return true;
	}

	public function resendOTP($user_id,$otp){

		$rows = array();
		$rows= $this->db->select('phone')->where("id",$user_id)->get('traffic_users')->row_array();
		if($rows['phone']>0){
			$data  = array();
			$data['otp']= $otp;
			$this->sendOTP($rows['phone'],$otp);
			$this->db->where('id',$user_id)->update('traffic_users',$data);

		}
		return $rows['phone']>0 ? true : false;

	}

	public function validateOTP($user_id,$otp){

		$rows = array();
		$rows= $this->db->select('count(*) AS count')->where("id",$user_id)->where("otp",$otp)->get('traffic_users')->row_array();
		if($rows['count']>0){
			$data  = array();
			$data['is_phone_verified']= '1';
			$this->db->where('id',$user_id)->update('traffic_users',$data);

		}
		return $rows['count']>0 ? true : false;
	}

	public function getUser($userid){
 		$rows = array();
     	$rows= $this->db->select('id,first_name, last_name, email,phone, image,is_phone_verified, is_active,admin_message, status')->where("id",$userid)->get('traffic_users')->row_array();
     	return $rows;
	}
}
?>
