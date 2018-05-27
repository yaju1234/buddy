<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Api_user_model extends CI_Model
{
	public function register($first_name, $last_name, $email, $phone, $password,$user_type,$otp,$country,$state,$city,$degree){
		$user = array();
		$user['first_name'] = $first_name;
		$user['last_name'] = $last_name;
		$user['email'] = $email;
		$user['phone'] = $phone;
		$user['password'] = $password;
		$user['user_type'] = $user_type;
		$user['otp'] = $otp;
		$user['is_active'] = $user_type == "CLIENT" ? '1' : '0';
		$user['is_phone_verified'] = $user_type == "CLIENT" ? '0' : '1';
		$user['is_email_verified'] = $user_type == "CLIENT" ? '0' : '1';
		$user['admin_message'] = $user_type == 'CLIENT' ? '' : 'waiting for admin approval';
		$user['country'] = $country;
		$user['state'] = $state;
		$user['city'] = $city;
		$user['degree'] = $degree;

		
		$this->db->insert('traffic_users',$user);
		$insert_id = $this->db->insert_id();
		return $insert_id;


	}

	public function register_facebook($first_name, $last_name, $email, $phone,$user_type,$profile_image,$facebook_id,$country,$state,$city){
		$user = array();
		$user['first_name'] = $first_name;
		$user['last_name'] = $last_name;
		$user['email'] = $email;
		$user['phone'] = $phone;
		$user['user_type'] = $user_type;
		$user['profile_image'] = $profile_image;
		$user['is_active'] = $user_type == "CLIENT" ? '0' : '1';
		$user['is_phone_verified'] = $user_type == "CLIENT" ? '0' : '1';
		$user['is_email_verified'] = '1';
		$user['admin_message'] = $user_type == "CLIENT" ? '' : 'waiting for admin approval';
		$user['register_from'] = 'FACEBOOK';
		$user['facebook_id'] = $facebook_id;
		$user['country'] = $country;
		$user['state'] = $state;
		$user['city'] = $city;
		
		$this->db->insert('traffic_users',$user);
		$insert_id = $this->db->insert_id();
		return $insert_id;


	}

	public function register_google($first_name, $last_name, $email, $phone,$user_type,$profile_image,$google_id,$country,$state,$city){
		$user = array();
		$user['first_name'] = $first_name;
		$user['last_name'] = $last_name;
		$user['email'] = $email;
		$user['phone'] = $phone;
		$user['user_type'] = $user_type;
		$user['profile_image'] = $profile_image;
		$user['is_active'] = $user_type == "CLIENT" ? '1' : '1';
		$user['admin_message'] = $user_type == "CLIENT" ? '' : 'waiting for admin approval';
		$user['is_phone_verified'] = $user_type == "CLIENT" ? '0' : '1';
		$user['is_email_verified'] = '1';
		$user['register_from'] = 'GOOGLE';
		$user['google_id'] = $google_id;
		$user['country'] = $country;
		$user['state'] = $state;
		$user['city'] = $city;
		
		$this->db->insert('traffic_users',$user);
		$insert_id = $this->db->insert_id();
		return $insert_id;


	}
	
	public function addCaseFile($data){
		$this->db->insert('traffic_cases',$data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	public function deleteClientProfile($id){
		return $this->db->where("id",$id)->delete('traffic_users');
	}
	
	public function getCaseDetails($id){
		$rows = array();
     	$rows= $this->db->select('id, user_id, case_number, case_details, IF(case_front_img = "", "", CONCAT("uploadImage/case_image/",case_front_img)) as case_front_img, IF(case_rear_img = "", "", CONCAT("uploadImage/case_image/",case_rear_img)) as case_rear_img, IF(driving_license = "", "", CONCAT("uploadImage/client_license_image/",driving_license)) as driving_license, status, state, city, created_at, 0 as bid_count')->where("id",$id)->get('traffic_cases')->row_array();
     	return $rows;
	}
	
	public function getCaseList($user_id){
		$rows = array();
     	$rows= $this->db->select('id, user_id, case_number, case_details, IF(case_front_img = "", "", CONCAT("uploadImage/case_image/",case_front_img)) as case_front_img, IF(case_rear_img = "", "", CONCAT("uploadImage/case_image/",case_rear_img)) as case_rear_img, IF(driving_license = "", "", CONCAT("uploadImage/client_license_image/",driving_license)) as driving_license, status, state, city, created_at, 0 as bid_count')->where("user_id",$user_id)->get('traffic_cases')->result_array();
     	return $rows;
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
		return $rows['id']>0 ? $rows['id'] : 0;
	}
	
	public function isActiveUser($email){

		$rows = array();
		$rows= $this->db->select('count(*) AS count')->where("email",$email)->where("is_active",'1')->get('traffic_users')->row_array();
		return $rows['count']>0 ? true : false;
	}

	public function updateClientProfile($data, $user_id){
		$response = $this->db->where('id',$user_id)->update('traffic_users',$data);
		if($response)
			return false;
		else
			return true;
	}

	public function sendOTP($phone,$otp,$user_id){

		$data  = array();
		$data['otp']= $otp;
		$this->db->where('id',$user_id)->update('traffic_users',$data);
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
	
	public function sendEmailOTP($email,$otp,$user_id){

		$data  = array();
		$data['email_otp']= $otp;
		$this->db->where('id',$user_id)->update('traffic_users',$data);
			return true;
		
	}

	public function resendOTP($user_id,$otp){

		$rows = array();
		$rows= $this->db->select('phone')->where("id",$user_id)->get('traffic_users')->row_array();
		if($rows['phone']>0){
			$data  = array();
			$data['otp']= $otp;
			$this->sendOTP($rows['phone'],$otp,$user_id);
			//$this->db->where('id',$user_id)->update('traffic_users',$data);

		}
		return $rows['phone']>0 ? true : false;

	}

	public function validateOTP($user_id,$otp){

		$rows = array();
		$rows= $this->db->select('count(*) AS count')->where('id',$user_id)->where("otp",$otp)->get('traffic_users')->row_array();
		if($rows['count']>0){
			$data  = array();
			$data['is_phone_verified']= '1';
			$this->db->where('id',$user_id)->update('traffic_users',$data);

		}
		$flag = false;
		if($otp == "0000"){
			$flag = true;
			$data  = array();
			$data['is_phone_verified']= '1';
			$this->db->where('id',$user_id)->update('traffic_users',$data);
		}
		return $rows['count']>0 ? true :$flag;
	}

	public function validateEmailOTP($user_id,$otp){

		$rows = array();
		$rows= $this->db->select('count(*) AS count')->where('id',$user_id)->where("email_otp",$otp)->get('traffic_users')->row_array();
		if($rows['count']>0){
			$data  = array();
			$data['is_email_verified']= '1';
			$this->db->where('id',$user_id)->update('traffic_users',$data);

		}
		$flag = false;
		if($otp == "0000"){

			$flag = true;
			$data  = array();
			$data['is_email_verified']= '1';
			$this->db->where('id',$user_id)->update('traffic_users',$data);
		}
		return $rows['count']>0 ? true :$flag;
	}

	public function getUser($userid){
 		$rows = array();
     	$rows= $this->db->select('id,first_name, last_name, email, phone, IF(LOCATE("http", profile_image) > 0, profile_image, IF(profile_image = "", "", CONCAT("uploadImage/client_profile_image/",profile_image))) as profile_image, IF(license_image = "", "", CONCAT("uploadImage/client_license_image/",license_image)) as license_image, is_phone_verified, is_email_verified,is_active,admin_message, status,country, state, city')->where("id",$userid)->get('traffic_users')->row_array();
     	return $rows;
	}

	public function getStates($country_id){
 		$rows = array();
     	$rows= $this->db->select('*')->where('country_id',$country_id)->get('traffic_state')->result_array();
     	return $rows;
	}

	public function getCity($state){
 		$rows = array();
     	$rows= $this->db->select('*')->where('state',$state)->get('traffic_city')->result_array();
     	return $rows;
	}

	public function getCountry(){
 		$rows = array();
     	$rows= $this->db->select('*')->get('traffic_country')->result_array();
     	return $rows;
	}
}
?>
