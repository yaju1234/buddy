<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'libraries/REST_Controller.php';

class User extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'form'));
		$this->load->library('twilio');
		$this->load->library('email');
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
			$data['user_id'] = $last_inserted_id;
			$data['phone'] = $phone;
			if($user_type == "CLIENT"){
				$this->Api_user_model->sendOTP($phone,$otp,$last_inserted_id);
			}
			
			$response['status'] = true;
			$response['response'] = $user_date;
			$response['message'] = "register successfully";

		}
		$this->response($response);
	}
	
	public function updateClientProfile_post(){

		$response = array();
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
		//if($st){
			$user_date = $this->Api_user_model->getUser($id);
			$response['status'] = true;
			$response['response'] = $user_date;
			$response['message'] = "Updated successfully";
		/*}else{
			$response['status'] = false;
			$response['response'] = new stdClass();
			$response['message'] = "Updated unsuccessful";
		}*/
		$this->response($response);
	}
	
	public function imageCreate($profile_image, $type='client_profile_image') {
		$file_name = uniqid();
		if(!is_file('./uploadImage/'.$type)){
			mkdir('./uploadImage/'.$type, '0777');
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

		$user_id = $this->Api_user_model->isLoginValid($email,$password,$user_type);
		if($user_id == '0'){
			$response['status'] = false;
			$response['response'] =new stdClass();
			$response['error_code'] = 5005;
			$response['message'] = "email or passowrd does not match";
		}else{
			$user_date = $this->Api_user_model->getUser($user_id);
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

		//$otp = '0000';
		$user_id = '';

		if(!$this->Api_user_model->isEmailExist($email, $user_type)){
			$user_id = $this->Api_user_model->register_facebook($first_name,$last_name,$email,$phone,$user_type,$image,$facebook_id,$country,$state,$city);
		}else{
			$user_id = $this->Api_user_model->userIdByEmail($email,$user_type);
		}


		$user_date = $this->Api_user_model->getUser($user_id);
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

		//$otp = '0000';
		$user_id = '';

		if(!$this->Api_user_model->isEmailExist($email, $user_type)){
			$user_id = $this->Api_user_model->register_google($first_name,$last_name,$email,$phone,$user_type,$image,$google_id,$country,$state,$city);
		}else{
			$user_id = $this->Api_user_model->userIdByEmail($email,$user_type);
		}


		$user_date = $this->Api_user_model->getUser($user_id);
		$response['status'] = true;
		$response['response'] = $user_date;
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



}
