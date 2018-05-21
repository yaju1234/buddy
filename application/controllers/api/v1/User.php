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
			$last_inserted_id = $this->Api_user_model->register($first_name,$last_name,$email,$phone,$password,$user_type,$otp,$country,$state,$city);
			$data['user_id'] = $last_inserted_id;
			$data['phone'] = $phone;
			$this->Api_user_model->sendOTP($phone,$otp,$last_inserted_id);
			$response['status'] = true;
			$response['response'] = $data;
			$response['message'] = "register successfully";

		}

		

		
		$this->response($response);


	}

	public function validate_otp_post(){
		$response = array();

		$user_id = $this->input->post('user_id');
		$otp = $this->input->post('otp');
		if($this->Api_user_model->validateOTP($user_id,$otp)){
			$user_date = array();
			$user_date = $this->Api_user_model->getUser($user_id);
			if($user_date['status'] == '0'){
				$response['status'] = false;
				$response['response'] = new stdClass();
				$response['message'] = "user status false";
			}else if($user_date['is_active'] == '0'){
				$response['status'] = false;
				$response['response'] = new stdClass();
				$response['message'] = $user_date['admin_message'];
			}else if($user_date['is_phone_verified'] == '0'){
				$response['status'] = false;
				$response['response'] = new stdClass();
				$response['message'] = "phone number not verified";
			}else{
				$response['status'] = true;
				$response['response'] = $user_date;
				$response['message'] = "success";
			}
			
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

	public function send_mail_post() {



		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 587,
			'smtp_user' => 'homdevelopers@gmail.com',
			'smtp_pass' => 'Hom@1234',
			'mailtype'  => 'html', 
			'charset' => 'utf-8',
			'newline' => "\r\n",
			'wordwrap' => TRUE
			);
		
		$this->load->library('email',$config);
		$this->email->from('homdevelopers@gmail.com', 'Support');
		$this->email->to('yaju.rcc@gmail.com');
		$this->email->cc('');
		$this->email->bcc('');
		
		$this->email->subject('Test');
		$this->email->message('This is a test email');
		
		if ( ! $this->email->send())
		{
    // Generate error
			echo "Email is not sent!!";
		}
		
		echo $this->email->print_debugger();
	}

// 5001 -> email not verified
// 5002 -> phone not verified
// 5003 -> both email and phone not verified
// 5004 -> email already exist
// 5005 -> email id or password does not match


}
