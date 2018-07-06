<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'libraries/REST_Controller.php';

class User extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'form'));
		$this->load->library('twilio');
		$this->load->library('email');
		$this->load->library('fcm');
		$this->load->model(array('Api_user_model'));
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
			$degree_image = $this->Api_user_model->getDegreeImage($id);
			$user_date['degree_images'] = $degree_image;
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
			$degree_image = $this->Api_user_model->getDegreeImage($id);
			$user_date['degree_images'] = $degree_image;
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
	
	public function disproveLawyer_post(){
		
		$response = array();
		try {
			$data = array();
			$id = $this->input->post('id');
			$st = $this->Api_user_model->disproveLawyer($id);
			$response['status'] = true;
			$response['response'] = new stdClass();
			$response['message'] = "Disproved successfully";
			$this->response($response);
		} catch(Exception $e){
			$response['status'] = false;
			$response['response'] = new stdClass();
			$response['message'] = "error";
			$this->response($response);
		}
		
	}
	
	public function verifyLawyer_post(){
		
		$response = array();
		try {
			$data = array();
			$id = $this->input->post('id');
			$st = $this->Api_user_model->verifyLawyer($id);
			$title = "Account verified";
			$message = "Your account has been verified";
			$this->Api_user_model->pushNotificationForlawyer($id,$title,$message,"ACTIVITY_DEFAULT");
			$response['status'] = true;
			$response['response'] = new stdClass();
			$response['message'] = "Verified successfully";
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
			//var_dump($this->input->post());
			$user_id = $this->input->post('user_id');

			//echo $user_id;
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
			//echo "<pre/>";
			//print_r($data);

			$id = $this->Api_user_model->addCaseFile($data);
			
			$user_date = $this->Api_user_model->getCaseDetails($id);
			
			//to do push here
			//if push success then save into traffic_case_notifications table
			
			//fetch laywers
			$lawyers = $this->Api_user_model->getLawyers();

			foreach ($lawyers as $lawyer) {
				$title = "New case file";
				$message = "New case file";
				$this->Api_user_model->pushNotificationForlawyer($lawyer['id'],$title,$message,"ACTIVITY_CASEFILE");
			}
			
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
	
	public function fetchCasesOflawyer_post(){
		$response = array();
		try {
			$data = array();
			$lawyer_id = $this->input->post('lawyer_id');
			$data['lawyer_id'] = $lawyer_id;
			$user_date = $this->Api_user_model->getCaseListOfLawyer($lawyer_id);
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

	public function getuser_post(){
		$response = array();


		$user_id = $this->input->post('user_id');
		
		$user_date = $this->Api_user_model->getUser($user_id);
		$degree_image = $this->Api_user_model->getDegreeImage($user_id);
		$user_date['degree_images'] = $degree_image;
		$response['status'] = true;
		$response['response'] = $user_date;
		$response['message'] = "login success";
		
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


		$milliseconds = round(microtime(true) * 1000);
		$milliseconds = $milliseconds+1000*60*60*12;
		$randNum = md5(uniqid(rand(), true));


		$otp = rand ( 1000 , 9999 );
		if($this->Api_user_model->sendEmailOTP($user_id,$randNum,$milliseconds)){
			$this->load->library('email');
			$config['protocol']    = 'smtp';
			$config['smtp_host']    = 'ssl://smtp.gmail.com';
			$config['smtp_port']    = '465';
			$config['smtp_timeout'] = '7';
			$config['smtp_user']    = 'buddytraffic@gmail.com';
			$config['smtp_pass']    = 'Traffic@1234';
			$config['charset']    = 'utf-8';
			$config['newline']    = "\r\n";
		$config['mailtype'] = 'html'; // or html
		$config['validation'] = TRUE; // bool whether to validate email or not      
		$this->email->initialize($config);


		$this->email->from('buddytraffic@gmail.com', 'Traffic Buddy');
		$this->email->to($email); 

		$urllink = base_url().'admin/clients/verifyemail/'.$randNum;

		//$message = $urllink;
		$message = '<!DOCTYPE html>
		<table width="650" bgcolor="#f2f2f2" cellpadding="0" cellspacing="0" border="0"  style="font-family: "Arial", sans-serif; padding: 30px;">
			<tbody style="font-family: "Arial", sans-serif; border:1px solid #000;">
				<tr>
					<td valign="top" align="center" style="font-size: 30px; line-height: 36px; color: #0d81d0; text-transform: capitalize; padding: 30px 30px 20px;">
						active your account
					</td>
				</tr>
				<tr>
					<td valign="top" align="center" style="font-size: 14px; line-height: 20px; color: #333; padding: 0px 30px 40px;">
						Circus Avenue Southern Flank is closed in between Karaya Road and Beckbagan Row to facilitate construction work of west bound ramp of Maa Flyover.
					</td>
				</tr>
				<tr>
					<td valign="top" align="center" style="padding: 0px 30px;">
						<a href="'.$urllink.'" style="font-size: 16px; line-height: 22px; color: #fff; background-color: #0d81d0; padding: 10px 30px; text-transform: uppercase; text-decoration: none; display: inline-block; vertical-align: top;">active now</a>
					</td>
				</tr>
				<tr>
					<td valign="top" align="center" style="font-size: 12px; line-height: 18px; color: #666; padding: 20px 30px 0px;">
						to contact us please visit
					</td>
				</tr>
				<tr>
					<td valign="top" align="center" style="padding: 0px 30px 30px;">
						<a href="javascript:void(0)" style="font-size: 12px; line-height: 18px; color: #0d81d0;">support.trafficbuddy.com</a>
					</td>
				</tr>
			</tbody>
		</table>
		';
		$this->email->subject('Trafic Buddy OTP Validation');

		$this->email->message($message);  

		$this->email->send();

		$response['status'] = true;
		$response['response'] = new stdClass();
		$response['expire'] = $milliseconds;
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

public function pushtest_post(){
	$response = array();
	$data = array();
	$title = "Test push";
	$message = "Test push";
	$user_type = $this->input->post('user_type');
	$id = $this->input->post('id');
	if($user_type == "LAWYER"){
		$this->Api_user_model->pushNotificationForlawyer($id,$title,$message,"ACTIVITY_TEST");
		$data['user_type'] = $user_type;
	}else{
		$this->Api_user_model->pushNotificationForclient($id,$title,$message,"ACTIVITY_TEST");
		$data['user_type'] = $user_type;
	}
	

	$response['status'] = true;
	$response['response'] = $data;
	$response['message'] = "success";
	$this->response($response);
}


public function pushtestbid_post(){
	$response = array();
	$data = array();
	$title = "Test push";
	$message = "Test push";
	$id = $this->input->post('id');
	$case_id = $this->input->post('case_id');
	$this->Api_user_model->pushNotificationForclientBids($id,$title,$message,"ACTIVITY_TEST",$case_id);
	

	$response['status'] = true;
	$response['response'] = $data;
	$response['message'] = "success";
	$this->response($response);
}

public function placebid_post(){

	$response = array();

	$lawyer_id = $this->input->post('lawyer_id');
	$client_id = $this->input->post('client_id');
	$case_id	 = $this->input->post('case_id');
	$bid_amount	 = $this->input->post('bid_amount');
	$bid_text = $this->input->post('bid_text');

	$last_inserted_id = $this->Api_user_model->placebid($lawyer_id,$client_id,$case_id,$bid_amount,$bid_text);

	if($last_inserted_id>0){

		$title = "New bid";
		$message = "New bid";
		$this->Api_user_model->pushNotificationForclientBids($client_id,$title,$message,"ACTIVITY_PLACEBID",$case_id);
		
		$response['status'] = true;
		$response['response'] = new stdClass();
		$response['message'] = "success";
	}else{
		$response['status'] = false;
		$response['response'] = new stdClass();
		$response['message'] = "already place bid";
	}

	$this->response($response);
}


public function editbid_post(){

	$response = array();
	$id = $this->input->post('id');
	$lawyer_id = $this->input->post('lawyer_id');
	$client_id = $this->input->post('client_id');
	$case_id	 = $this->input->post('case_id');
	$bid_amount	 = $this->input->post('bid_amount');
	$bid_text = $this->input->post('bid_text');

	$last_inserted_id = $this->Api_user_model->editbid($id,$lawyer_id,$client_id,$case_id,$bid_amount,$bid_text);

	if($last_inserted_id>0){
		$title = "Edit bid";
		$message = "Edit bid";
		$this->Api_user_model->pushNotificationForclient($client_id,$title,$message,"ACTIVITY_PLACEBID");
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


public function getBids_post(){

	$response = array();
	$case_id = $this->input->post('case_id');
	
	$data = $this->Api_user_model->getBids($case_id);

	if(sizeof($data)>0){
		$response['status'] = true;
		$response['response'] =$data;
		$response['message'] = "success";
	}else{
		$response['status'] = false;
		$response['response'] = new stdClass();
		$response['message'] = "error";
	}

	$this->response($response);
}

public function getBidsByLawyer_post(){

	$response = array();
	//$case_id = $this->input->post('case_id');
	$lawyer_id = $this->input->post('lawyer_id');
	
	$data = $this->Api_user_model->getBidsByLawyer($lawyer_id);

	if(sizeof($data)>0){
		$response['status'] = true;
		$response['response'] =$data;
		$response['message'] = "success";
	}else{
		$response['status'] = false;
		$response['response'] = new stdClass();
		$response['message'] = "error";
	}

	$this->response($response);
}


public function acceptBid_post(){

	$response = array();
	$case_id = $this->input->post('case_id');
	//$lawyer_id = $this->input->post('lawyer_id');
	$bid_id = $this->input->post('bid_id');
	
	$acceptStatus = $this->Api_user_model->acceptCase($case_id);
	if($acceptStatus){
		$data = $this->Api_user_model->acceptBid($bid_id);
		$data1 = $this->Api_user_model->getcasedetailsById($case_id);
		$title = "Accepted";
		$message = "your bid has been accepted. Case No . ".$data1['case_number'];
		$data1 = $this->Api_user_model->getBidsByBidId($bid_id);
		//echo $data1['lawyer_id'];
		$this->Api_user_model->pushNotificationForlawyer($data1['lawyer_id'],$title,$message,"ACTIVITY_BID_ACCEPTED");

		$response['status'] = true;
		$response['response'] =new stdClass();
		$response['message'] = "success";

	}else{
		$response['status'] = false;
		$response['response'] =new stdClass();
		$response['message'] = "Case already accepted";
	}
	

	$this->response($response);
}

public function resetpassword_post(){

	$response = array();
	$user_id = $this->input->post('user_id');
	$old_password = md5($this->input->post('old_password'));
	$new_password = md5($this->input->post('new_password'));
	
	$status = $this->Api_user_model->resetpassword($user_id,$old_password,$new_password);
	if($status){
		$response['status'] = true;
		$response['response'] =new stdClass();
		$response['message'] = "success";

	}else{
		$response['status'] = false;
		$response['response'] =new stdClass();
		$response['message'] = "error";
	}
	
	

	

	$this->response($response);
}


public function setViewed_post(){

	$response = array();
	$user_id = $this->input->post('user_id');
	$case_id = $this->input->post('case_id');
	
	$status = $this->Api_user_model->setViewed($user_id,$case_id);
	if($status){
		$response['status'] = true;
		$response['response'] =new stdClass();
		$response['message'] = "success";

	}else{
		$response['status'] = false;
		$response['response'] =new stdClass();
		$response['message'] = "error";
	}
	

	$this->response($response);
}


public function rate_post(){

	$response = array();
	$lawyer_id = $this->input->post('lawyer_id');
	$case_id = $this->input->post('case_id');
	$bid_id = $this->input->post('bid_id');
	$rating = $this->input->post('rating');
	$description = $this->input->post('description');
	$user_id = $this->input->post('user_id');
	
	$status = $this->Api_user_model->rate($lawyer_id,$case_id,$bid_id,$rating,$description,$user_id );
	if($status){
		$response['status'] = true;
		$response['response'] =new stdClass();
		$response['message'] = "success";

	}else{
		$response['status'] = false;
		$response['response'] =new stdClass();
		$response['message'] = "error";
	}
	

	$this->response($response);
}


public function forgotpassword_post() {

	$response = array();
	$email = $this->input->post('email');
	$user_type = $this->input->post('user_type');

	if($this->Api_user_model->isEmailExistForgotPassword($email,$user_type)){
		$milliseconds = round(microtime(true) * 1000);
		$milliseconds = $milliseconds+1000*60*60*12;
		$randNum = md5(uniqid(rand(), true));


		$otp = rand ( 1000 , 9999 );
		if($this->Api_user_model->sendforgotpasswordlink($email,$randNum,$milliseconds,$user_type)){
			$this->load->library('email');
			$config['protocol']    = 'smtp';
			$config['smtp_host']    = 'ssl://smtp.gmail.com';
			$config['smtp_port']    = '465';
			$config['smtp_timeout'] = '7';
			$config['smtp_user']    = 'buddytraffic@gmail.com';
			$config['smtp_pass']    = 'Traffic@1234';
			$config['charset']    = 'utf-8';
			$config['newline']    = "\r\n";
		$config['mailtype'] = 'html'; // or html
		$config['validation'] = TRUE; // bool whether to validate email or not      
		$this->email->initialize($config);


		$this->email->from('buddytraffic@gmail.com', 'Traffic Buddy');
		$this->email->to($email); 

		$urllink = base_url().'admin/cityadmin/forgotpassword/'.$randNum;

		//$message = $urllink;
		$message = '<!DOCTYPE html>
		<table width="650" bgcolor="#f2f2f2" cellpadding="0" cellspacing="0" border="0"  style="font-family: "Arial", sans-serif; padding: 30px;">
			<tbody style="font-family: "Arial", sans-serif; border:1px solid #000;">
				<tr>
					<td valign="top" align="center" style="font-size: 30px; line-height: 36px; color: #0d81d0; text-transform: capitalize; padding: 30px 30px 20px;">
						Reset your password
					</td>
				</tr>
				<tr>
					<td valign="top" align="center" style="font-size: 14px; line-height: 20px; color: #333; padding: 0px 30px 40px;">
						Circus Avenue Southern Flank is closed in between Karaya Road and Beckbagan Row to facilitate construction work of west bound ramp of Maa Flyover.
					</td>
				</tr>
				<tr>
					<td valign="top" align="center" style="padding: 0px 30px;">
						<a href="'.$urllink.'" style="font-size: 16px; line-height: 22px; color: #fff; background-color: #0d81d0; padding: 10px 30px; text-transform: uppercase; text-decoration: none; display: inline-block; vertical-align: top;">Reset Password</a>
					</td>
				</tr>
				<tr>
					<td valign="top" align="center" style="font-size: 12px; line-height: 18px; color: #666; padding: 20px 30px 0px;">
						To contact us please visit
					</td>
				</tr>
				<tr>
					<td valign="top" align="center" style="padding: 0px 30px 30px;">
						<a href="javascript:void(0)" style="font-size: 12px; line-height: 18px; color: #0d81d0;">support.trafficbuddy.com</a>
					</td>
				</tr>
			</tbody>
		</table>
		';
		$this->email->subject('Trafic Buddy OTP Validation');

		$this->email->message($message);  

		$this->email->send();

		$response['status'] = true;
		$response['response'] = new stdClass();
		$response['expire'] = $milliseconds;
		$response['message'] = "success";
	}else{
		$response['status'] = false;
		$response['response'] = new stdClass();
		$response['message'] = "error";
	}
}else{
	$response['status'] = false;
	$response['response'] = new stdClass();
	$response['message'] = "email does not exist";
}




$this->response($response);


}



public function testmail1_post() {

	$response = array();
	$email = 'yaju.rcc@gmail.com';
	//$email = 'sirsendu.96@gmail.com';

	$milliseconds = round(microtime(true) * 1000);
	$milliseconds = $milliseconds+1000*60*60*12;
	$randNum = md5(uniqid(rand(), true));


	$otp = rand ( 1000 , 9999 );
	$this->load->library('email');
	$config['protocol']    = 'smtp';
	$config['smtp_host']    = 'ssl://smtp.gmail.com';
	$config['smtp_port']    = '465';
	$config['smtp_timeout'] = '7';
	$config['smtp_user']    = 'buddytraffic@gmail.com';
	$config['smtp_pass']    = 'Traffic@1234';
	$config['charset']    = 'utf-8';
	$config['newline']    = "\r\n";
		$config['mailtype'] = 'html'; // or html
		$config['validation'] = TRUE; // bool whether to validate email or not      
		$this->email->initialize($config);


		$this->email->from('buddytraffic@gmail.com', 'Traffic Buddy');
		$this->email->to($email); 

		$urllink = base_url().'admin/cityadmin/forgotpassword/'.$randNum;

		$message = '<!DOCTYPE html>
		<table width="650" bgcolor="#f2f2f2" cellpadding="0" cellspacing="0" border="0"  style="font-family: "Arial", sans-serif; padding: 30px;">
			<tbody style="font-family: "Arial", sans-serif; border:1px solid #000;">
				<tr>
					<td valign="top" align="center" style="font-size: 30px; line-height: 36px; color: #0d81d0; text-transform: capitalize; padding: 30px 30px 20px;">
						active your account
					</td>
				</tr>
				<tr>
					<td valign="top" align="center" style="font-size: 14px; line-height: 20px; color: #333; padding: 0px 30px 40px;">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptas culpa, perferendis enim reprehenderit, amet cum! Doloribus esse dolore soluta reiciendis placeat ratione fugiat nulla! Distinctio iste qui cum reprehenderit totam.
					</td>
				</tr>
				<tr>
					<td valign="top" align="center" style="padding: 0px 30px;">
						<a href="http://www.google.com" style="font-size: 16px; line-height: 22px; color: #fff; background-color: #0d81d0; padding: 10px 30px; text-transform: uppercase; text-decoration: none; display: inline-block; vertical-align: top;">active now</a>
					</td>
				</tr>
				<tr>
					<td valign="top" align="center" style="font-size: 12px; line-height: 18px; color: #666; padding: 20px 30px 0px;">
						to contact us please visit
					</td>
				</tr>
				<tr>
					<td valign="top" align="center" style="padding: 0px 30px;">
						<a href="javascript:void(0)" style="font-size: 12px; line-height: 18px; color: #0d81d0;">support.trafficbuddy.com</a>
					</td>
				</tr>
			</tbody>
		</table>
		';
		$this->email->subject('Trafic Buddy Test mail');

		$this->email->message($message);  

		$this->email->send();

		$response['status'] = true;
		$response['response'] = new stdClass();
		$response['expire'] = $milliseconds;
		$response['message'] = "success";





		$this->response($response);


	}


	public function isCaseOpen_post(){

	$response = array();
	$case_id = $this->input->post('case_id');
	$acceptStatus = $this->Api_user_model->isCaseOpen($case_id);
	if($acceptStatus){
		$response['status'] = true;
		$response['response'] =new stdClass();
		$response['message'] = "success";
	}else{
		$response['status'] = false;
		$response['response'] =new stdClass();
		$response['message'] = "Case already accepted";
	}
	
	$this->response($response);
}


}