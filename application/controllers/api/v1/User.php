<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'libraries/REST_Controller.php';

class User extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'form'));
		$this->load->library('twilio');
		$this->load->library('email');
		$this->load->library('fcm');
		$this->load->model(array('Api_user_model','Admin_model'));
	}
	
	public function register_post(){

		$response = array();

		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$country = $this->input->post('country');
		$state = $this->input->post('state');
		$city = $this->input->post('city');
		$degree = $this->input->post('degree');
		$password = md5($this->input->post('password'));
		$user_type = $this->input->post('user_type');
		$token = $this->input->post('token');
		$device_type = $this->input->post('device_type');

		$otp = rand ( 1000 , 9999 );

		if($this->Api_user_model->isEmailExist($email, $user_type)){
			$response['status'] = false;
			$response['response'] = new stdClass();
			$response['error_code'] = 5004;
			$response['message'] = "email already exist";
		}else{
			$data = array();

			$last_inserted_id = $this->Api_user_model->register($first_name,$last_name,$email,$phone,$password,$user_type,$otp,$country,$state,$city,$degree);
			$user_date = $this->Api_user_model->getUser($last_inserted_id);
			$degree_image = $this->Api_user_model->getDegreeImage($last_inserted_id);
			$user_date['degree_images'] = $degree_image;
			$data['user_id'] = $last_inserted_id;
			$data['phone'] = $phone;
			if($user_type == "CLIENT"){
				$this->Api_user_model->sendOTP($phone,$otp,$last_inserted_id);
			}

			$this->Api_user_model->insertOrUpdateDeviceToken($last_inserted_id,$token,$device_type,$user_type);
			
			$response['status'] = true;
			$response['response'] = $user_date;
			$response['message'] = "register successfully";

		}
		$this->response($response);
	}
	
	public function updateClientProfile1_post(){

		$response = array();
		try {
			$id = $this->input->post('id');
			$data['first_name'] = $this->input->post('first_name');
			$data['last_name'] = $this->input->post('last_name');
			if($this->input->post('phone')){
				$data['phone'] = $this->input->post('phone');
			}
			$data['country'] = $this->input->post('country');
			$data['state'] = $this->input->post('state');
			$data['city'] = $this->input->post('city');
			//$data['degree'] = $this->input->post('degree');
			$data['is_phone_verified'] = '0';
			$profile_image = $this->input->post('profile_image');
			if(strlen($profile_image) > 10){
				$upload_image = $this->imageCreate($profile_image,'client_profile_image');
				$data['profile_image'] = $upload_image;
			}
			$license_image = $this->input->post('license_image');
			if(strlen($license_image) > 10){
				$license_upload_image = $this->imageCreate($license_image,'client_license_image');
				$data['license_image'] = $license_upload_image;
			}
			$st = $this->Api_user_model->updateClientProfile($data,$id);
			
			$user_date = $this->Api_user_model->getUser($id);
			$response['status'] = true;
			$response['response'] = $user_date;
			$response['message'] = "Updated successfully";
			$this->response($response);
		} catch(Exception $e){
			$response['status'] = false;
			$response['response'] = new stdClass();
			$response['message'] = "Updated unsuccessful";
			$this->response($response);
		}
		
	}

	public function updateClientProfile_post(){

		$response = array();
		try {
			$data = array();
			$id = $this->input->post('id');
			$first_name = $this->input->post('first_name');
			$data['first_name'] = $first_name;
			$last_name = $this->input->post('last_name');
			$data['last_name'] = $last_name;
			if($this->input->post('phone')){
				$data['is_phone_verified'] = '0';
				$data['phone'] = $this->input->post('phone');
			}
			$country = $this->input->post('country');
			$data['country'] = $country;
			$state = $this->input->post('state');
			$data['state'] = $state;
			$city = $this->input->post('city');
			$data['city'] = $city;
			
			if(!empty($_FILES['profile_image']['name'])){
				$profile_image_file_name = $this->uploadImage('./uploadImage/client_profile_image/',  $_FILES['profile_image'],'profile_image');
				if(!empty($profile_image_file_name)){
					$data['profile_image'] = $profile_image_file_name;
				}
				
			}

			if(!empty($_FILES['license_image']['name'])){
				$driving_licence_image_file_name = $this->uploadImage('./uploadImage/client_license_image/',$_FILES['license_image'],'license_image');
				if(!empty($driving_licence_image_file_name)){
					$data['license_image'] = $driving_licence_image_file_name;
				}
			}

			$st = $this->Api_user_model->updateClientProfile($data,$id);
			
			$user_date = $this->Api_user_model->getUser($id);
			$response['status'] = true;
			$response['response'] = $user_date;
			$response['message'] = "Updated successfully";
			$this->response($response);
		} catch(Exception $e){
			$response['status'] = false;
			$response['response'] = new stdClass();
			$response['message'] = "error";
			$this->response($response);
		}
		
	}

	public function updateLawyerProfile_post(){

		$response = array();
		try {
			$data = array();
			$id = $this->input->post('id');
			$first_name = $this->input->post('first_name');
			$data['first_name'] = $first_name;
			$last_name = $this->input->post('last_name');
			$data['last_name'] = $last_name;
			if($this->input->post('phone')){
				$data['is_phone_verified'] = '0';
				$data['phone'] = $this->input->post('phone');
			}
			$country = $this->input->post('country');
			$data['country'] = $country;
			$state = $this->input->post('state');
			$data['state'] = $state;
			$city = $this->input->post('city');
			$data['city'] = $city;
			$degree = $this->input->post('degree');
			$data['degree'] = $degree;
			
			if(!empty($_FILES['profile_image']['name'])){
				$profile_image_file_name = $this->uploadImage('./uploadImage/lawyer_profile_image/',  $_FILES['profile_image'],'profile_image');
				if(!empty($profile_image_file_name)){
					$data['profile_image'] = $profile_image_file_name;
				}
				
			}

			if(!empty($_FILES['degree_image_1']['name'])){
				$degree_image_file_name1 = $this->uploadImage('./uploadImage/degree/',$_FILES['degree_image_1'],'degree_image_1');
				if(!empty($degree_image_file_name1)){
					//$data['license_image'] = $driving_licence_image_file_name;
					//insertOrUpdateDegree
					$this->Api_user_model->insertOrUpdateDegree($id,'degree_image_1',$degree_image_file_name1);
				}
			}
			if(!empty($_FILES['degree_image_2']['name'])){
				$degree_image_file_name2 = $this->uploadImage('./uploadImage/degree/',$_FILES['degree_image_2'],'degree_image_2');
				if(!empty($degree_image_file_name2)){
					//$data['license_image'] = $driving_licence_image_file_name;
					$this->Api_user_model->insertOrUpdateDegree($id,'degree_image_2',$degree_image_file_name2);
				}
			}
			if(!empty($_FILES['degree_image_3']['name'])){
				$degree_image_file_name3 = $this->uploadImage('./uploadImage/degree/',$_FILES['degree_image_3'],'degree_image_3');
				if(!empty($degree_image_file_name3)){
					//$data['license_image'] = $driving_licence_image_file_name;
					$this->Api_user_model->insertOrUpdateDegree($id,'degree_image_3',$degree_image_file_name3);
				}
			}

			$st = $this->Api_user_model->updateClientProfile($data,$id);
			
			$user_date = $this->Api_user_model->getUser($id);
			$response['status'] = true;
			$response['response'] = $user_date;
			$response['message'] = "Updated successfully";
			$this->response($response);
		} catch(Exception $e){
			$response['status'] = false;
			$response['response'] = new stdClass();
			$response['message'] = "error";
			$this->response($response);
		}
		
	}
	
	public function deleteClient_post(){
		
		$response = array();
		try {
			$data = array();
			$id = $this->input->post('id');
			$st = $this->Api_user_model->deleteClientProfile($id);
			$response['status'] = true;
			$response['response'] = new stdClass();
			$response['message'] = "Deleted successfully";
			$this->response($response);
		} catch(Exception $e){
			$response['status'] = false;
			$response['response'] = new stdClass();
			$response['message'] = "error";
			$this->response($response);
		}
		
	}
	public function enableClient_post(){
		
		$response = array();
		try {
			$data = array();
			$id = $this->input->post('id');
			$st = $this->Api_user_model->enableClientProfile($id);
			$response['status'] = true;
			$response['response'] = new stdClass();
			$response['message'] = "Deleted successfully";
			$this->response($response);
		} catch(Exception $e){
			$response['status'] = false;
			$response['response'] = new stdClass();
			$response['message'] = "error";
			$this->response($response);
		}
		
	}	
	
	public function deleteBanner_post(){
		
		$response = array();
		try {
			$data = array();
			$id = $this->input->post('id');
			$st = $this->Api_user_model->deleteBanner($id);
			$response['status'] = true;
			$response['response'] = new stdClass();
			$response['message'] = "Deleted successfully";
			$this->response($response);
		} catch(Exception $e){
			$response['status'] = false;
			$response['response'] = new stdClass();
			$response['message'] = "error";
			$this->response($response);
		}
		
	}
	
	public function caseFile_post(){

		$response = array();
		try {
			$data = array();
			$user_id = $this->input->post('user_id');
			$data['user_id'] = $user_id;
			
			$case_number = 'CASE'.$user_id.rand(11111, 99999);
			$data['case_number'] = $case_number;
			
			$case_details = $this->input->post('case_details');
			$data['case_details'] = $case_details;
			
			$state = $this->input->post('state');
			$data['state'] = $state;
			
			$city = $this->input->post('city');
			$data['city'] = $city;
			
			if(!empty($_FILES['case_front_img']['name'])){
				$profile_image_file_name = $this->uploadImage('./uploadImage/case_image/',  $_FILES['case_front_img'],'case_front_img');
				if(!empty($profile_image_file_name)){
					$data['case_front_img'] = $profile_image_file_name;
				}
				
			}
			
			if(!empty($_FILES['case_rear_img']['name'])){
				$profile_image_file_name = $this->uploadImage('./uploadImage/case_image/',  $_FILES['case_rear_img'],'case_rear_img');
				if(!empty($profile_image_file_name)){
					$data['case_rear_img'] = $profile_image_file_name;
				}
				
			}

			if(!empty($_FILES['driving_license']['name'])){
				$driving_licence_image_file_name = $this->uploadImage('./uploadImage/client_license_image/',$_FILES['driving_license'],'driving_license');
				if(!empty($driving_licence_image_file_name)){
					$data['driving_license'] = $driving_licence_image_file_name;
				}
			}
			
			$data['created_at'] = date('Y-m-d H:i:s');

			$id = $this->Api_user_model->addCaseFile($data);
			
			$user_date = $this->Api_user_model->getCaseDetails($id);
			
			//to do push here
			//if push success then save into traffic_case_notifications table
			
			//fetch laywers
			$lawyers = $this->Admin_model->getLawyers();
			
			$pushNotificationData = array();
			foreach ($lawyers as $lawyer) {
				$pushNtfctn = array(
					'case_id' => $user_date['id'],
					'client_id' => $user_date['user_id'],
					'lawyer_id' => $lawyer['id'],
					'created_at' => date('Y-m-d H:i:s')
				);
				array_push($pushNotificationData, $pushNtfctn);
			}
			$push_save_status = $this->Api_user_model->saveLawyerPushDtls($pushNotificationData);
			
			
			$response['status'] = true;
			$response['response'] = $user_date;
			$response['message'] = "Case filed successfully";
			$this->response($response);
		} catch(Exception $e){
			$response['status'] = false;
			$response['response'] = new stdClass();
			$response['message'] = "error";
			$this->response($response);
		}
		
	}
	
	public function getAllCases_post(){

		$response = array();
		try {
			$data = array();
			$user_id = $this->input->post('user_id');
			$data['user_id'] = $user_id;
			$user_date = $this->Api_user_model->getCaseList($user_id);
			$response['status'] = true;
			$response['response'] = $user_date;
			$response['message'] = "fetched successfully";
			$this->response($response);
		} catch(Exception $e){
			$response['status'] = false;
			$response['response'] = new stdClass();
			$response['message'] = "error";
			$this->response($response);
		}
		
	}
	
	public function imageCreate($profile_image, $type='client_profile_image') {
		$file_name = uniqid();
		if(!is_dir('uploadImage')){
			mkdir('uploadImage', '0777');
		}
		if(!is_dir('uploadImage/'.$type)){
			mkdir('uploadImage/'.$type, '0777');
		}
		define('UPLOAD_DIR', './uploadImage/'.$type.'/');
		$img = $profile_image;
		$data = base64_decode($img);
		$file = UPLOAD_DIR . $file_name . '.png';
		$success = file_put_contents($file, $data);
		$this->load->library('image_lib');
		$configUpload22['upload_path'] = './uploadImage/'.$type.'/';
		$configUpload22['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
		$configUpload22['max_size'] = '0';
		$configUpload22['max_width'] = '0';
		$configUpload22['max_height'] = '0';
		$configUpload22['encrypt_name'] = true;

		$this->load->library('upload', $configUpload22);
		$this->upload->initialize($configUpload22);
		return $file_name . '.png';
	}

	public function validate_otp_post(){
		$response = array();

		$user_id = $this->input->post('user_id');
		$otp = $this->input->post('otp');
		if($this->Api_user_model->validateOTP($user_id,$otp)){
			$user_date = array();
			$user_date = $this->Api_user_model->getUser($user_id);
			$response['status'] = true;
			$response['response'] = $user_date;
			$response['message'] = "success";
			
		}else{
			$response['status'] = false;
			$response['response'] = new stdClass();
			$response['message'] = "otp does not match";
		}
		$this->response($response);
	}

	public function resend_otp_post(){
		$response = array();
		//$user_id = $this->input->post('user_id');
		$phone = $this->input->post('phone');
		$user_id = $this->input->post('user_id');
		$otp = rand ( 1000 , 9999 );
		$user_date = array();
		if($this->Api_user_model->sendOTP($phone,$otp,$user_id)){
			$response['status'] = true;
			$response['response'] = $user_date;
			$response['message'] = "success";
		}else{
			$response['status'] = false;
			$response['response'] = $user_date;
			$response['message'] = "error";
		}

		$this->response($response);
	}


	public function login_post(){
		$response = array();


		$email = $this->input->post('email');
		$password = md5($this->input->post('password'));
		$user_type = $this->input->post('user_type');
		$token = $this->input->post('token');
		$device_type = $this->input->post('device_type');

		$user_id = $this->Api_user_model->isLoginValid($email,$password,$user_type);
		if($user_id == '0'){
			$response['status'] = false;
			$response['response'] =new stdClass();
			$response['error_code'] = 5005;
			$response['message'] = "email or passowrd does not match";
		}else{
			$this->Api_user_model->insertOrUpdateDeviceToken($user_id,$token,$device_type,$user_type);
			$user_date = $this->Api_user_model->getUser($user_id);
			$degree_image = $this->Api_user_model->getDegreeImage($user_id);
			$user_date['degree_images'] = $degree_image;
			$response['status'] = true;
			$response['response'] = $user_date;
			$response['message'] = "login success";
		}
		$this->response($response);
	}

	public function register_facebook_post(){

		$response = array();

		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$user_type = $this->input->post('user_type');
		$image = $this->input->post('image');
		$facebook_id = $this->input->post('facebook_id');
		$country = $this->input->post('country');
		$state = $this->input->post('state');
		$city = $this->input->post('city');
		$token = $this->input->post('token');
		$device_type = $this->input->post('device_type');

		//$otp = '0000';
		$user_id = '';

		if(!$this->Api_user_model->isEmailExist($email, $user_type)){
			$user_id = $this->Api_user_model->register_facebook($first_name,$last_name,$email,$phone,$user_type,$image,$facebook_id,$country,$state,$city);
		}else{
			$user_id = $this->Api_user_model->userIdByEmail($email,$user_type);
		}

		$this->Api_user_model->insertOrUpdateDeviceToken($user_id,$token,$device_type,$user_type);
		$user_date = $this->Api_user_model->getUser($user_id);
		$degree_image = $this->Api_user_model->getDegreeImage($user_id);
			$user_date['degree_images'] = $degree_image;
		$response['status'] = true;
		$response['response'] = $user_date;
		$response['message'] = "success";
		
		
		$this->response($response);


	}

	public function register_google_post(){

		$response = array();

		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$user_type = $this->input->post('user_type');
		$image = $this->input->post('image');
		$google_id = $this->input->post('google_id');
		$country = $this->input->post('country');
		$state = $this->input->post('state');
		$city = $this->input->post('city');
		$token = $this->input->post('token');
		$device_type = $this->input->post('device_type');

		//$otp = '0000';
		$user_id = '';

		if(!$this->Api_user_model->isEmailExist($email, $user_type)){
			$user_id = $this->Api_user_model->register_google($first_name,$last_name,$email,$phone,$user_type,$image,$google_id,$country,$state,$city);
		}else{
			$user_id = $this->Api_user_model->userIdByEmail($email,$user_type);
		}

		$this->Api_user_model->insertOrUpdateDeviceToken($user_id,$token,$device_type,$user_type);
		$user_date = $this->Api_user_model->getUser($user_id);
		$degree_image = $this->Api_user_model->getDegreeImage($user_id);
			$user_date['degree_images'] = $degree_image;
		$response['status'] = true;
		$response['response'] = $user_date;
		$response['message'] = "success";
		
		$this->response($response);

		
	}

	public function fetchClientDtls_post(){

		$response = array();
		$id = $this->input->post('id');
		
		$data = $this->Api_user_model->getUser($id);
		$response['status'] = true;
		$response['response'] = $data;
		$response['message'] = "success";
		
		$this->response($response);
	}
	
	public function fetchBannerDtls_post(){

		$response = array();
		$id = $this->input->post('id');
		
		$data = $this->Api_user_model->getBanner($id);
		$response['status'] = true;
		$response['response'] = $data;
		$response['message'] = "success";
		
		$this->response($response);
	}

	public function states_post(){

		$response = array();
		$country_id = $this->input->post('country_id');
		
		$data = $this->Api_user_model->getStates($country_id);
		$response['status'] = true;
		$response['response'] = $data;
		$response['message'] = "success";
		
		$this->response($response);
	}
	
	public function getStatesByCntry_post(){

		$response = array();
		$country = $this->input->post('country');
		
		$data = $this->Api_user_model->getStatesByCntryName($country);
		$response['status'] = true;
		$response['response'] = $data;
		$response['message'] = "success";
		
		$this->response($response);
	}

	public function cities_post(){

		$response = array();
		$state = $this->input->post('state');
		
		$data = $this->Api_user_model->getCity($state);
		$response['status'] = true;
		$response['response'] = $data;
		$response['message'] = "success";
		
		$this->response($response);
	}

	public function country_post(){

		$response = array();
		
		$data = $this->Api_user_model->getCountry();
		$response['status'] = true;
		$response['response'] = $data;
		$response['message'] = "success";
		
		$this->response($response);
	}

	public function send_mail_otp_post() {

		$response = array();
		//$user_id = $this->input->post('user_id');
		$email = $this->input->post('email');
		$user_id = $this->input->post('user_id');
		$otp = rand ( 1000 , 9999 );
		if($this->Api_user_model->sendEmailOTP($email, $otp,$user_id)){
			$this->load->library('email');
			$config['protocol']    = 'smtp';
			$config['smtp_host']    = 'ssl://smtp.gmail.com';
			$config['smtp_port']    = '465';
			$config['smtp_timeout'] = '7';
			$config['smtp_user']    = 'buddytraffic@gmail.com';
			$config['smtp_pass']    = 'Traffic@1234';
			$config['charset']    = 'utf-8';
			$config['newline']    = "\r\n";
		$config['mailtype'] = 'text'; // or html
		$config['validation'] = TRUE; // bool whether to validate email or not      
		$this->email->initialize($config);


		$this->email->from('buddytraffic@gmail.com', 'Traffic Buddy');
		$this->email->to($email); 

		$message = 'Your verification code is '.$otp;
		$this->email->subject('Trafic Buddy OTP Validation');

		$this->email->message($message);  

		$this->email->send();

		$response['status'] = true;
		$response['response'] = new stdClass();
		$response['message'] = "success";
	}else{
		$response['status'] = false;
		$response['response'] = new stdClass();
		$response['message'] = "error";
	}

	$this->response($response);


}

public function validate_email_otp_post(){
	$response = array();

	$user_id = $this->input->post('user_id');
	$otp = $this->input->post('otp');
	if($this->Api_user_model->validateEmailOTP($user_id,$otp)){
		$user_date = array();
		$user_date = $this->Api_user_model->getUser($user_id);
		$response['status'] = true;
		$response['response'] = $user_date;
		$response['message'] = "success";

	}else{
		$response['status'] = false;
		$response['response'] = new stdClass();
		$response['message'] = "otp does not match";
	}
	$this->response($response);
}


public function banners_post(){
	$response = array();
	$data = $this->Api_user_model->getBanners();

	$response['status'] = true;
	$response['response'] = $data;
	$response['message'] = "success";
	$this->response($response);
}

public function degree_image_post(){
	$response = array();
	$user_id = $this->input->post('user_id');
	$data = $this->Api_user_model->getDegreeImage($user_id);

	$response['status'] = true;
	$response['response'] = $data;
	$response['message'] = "success";
	$this->response($response);
}
public function uploadImage($upload_path, $file_arr, $key) {
	$config = array();
	$config['upload_path']   = $upload_path; 
	$config['allowed_types'] = '*'; 
	$config['max_size']      = 0; 
	$config['max_width']     = 0; 
	$config['max_height']    = 0;
	$config['encrypt_name'] = true;  


	$this->load->library('upload', $config);
	$this->upload->initialize($config);


	if ( ! $this->upload->do_upload($key)) {
		$error = array('error' => $this->upload->display_errors());
		return '';
	}

	else { 
		$data = $this->upload->data(); 
		return $data['file_name'];

	}
}
}