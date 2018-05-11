<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'libraries/REST_Controller.php';

class User extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('url', 'form'));
		$this->load->model(array('Api_user_model'));
	}

	

	public function register_post(){

		$response = array();

		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$gender = $this->input->post('gender');
		$password = md5($this->input->post('password'));
		$user_type = $this->input->post('user_type');

		$otp = '0000';

		if($this->Api_user_model->isEmailExist($email, $user_type)){
			$response['status'] = false;
			$response['response'] = array();
			$response['message'] = "email already exist";
		}else{
			$data = array();
			$last_inserted_id = $this->Api_user_model->register($first_name,$last_name,$email,$phone,$gender,$password,$user_type,$otp);
			$data['user_id'] = $last_inserted_id;
			$this->Api_user_model->sendOTP($phone,$otp);
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
				$response['response'] = array();
				$response['message'] = "user status false";
			}else if($user_date['is_active'] == '0'){
				$response['status'] = false;
				$response['response'] = array();
				$response['message'] = $user_date['admin_message'];
			}else if($user_date['is_phone_verified'] == '0'){
				$response['status'] = true;
				$response['response'] = array();
				$response['message'] = "phone numver not verified";
			}else{
				$response['status'] = true;
				$response['response'] = $user_date;
				$response['message'] = "success";
			}
			
		}else{
			$response['status'] = false;
			$response['response'] = array();
			$response['message'] = "otp does not match";
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
			$response['response'] = array();
			$response['message'] = "email or passowrd does not match";
		}else{
			$user_date = $this->Api_user_model->getUser($user_id);
			if($user_date['status'] == '0'){
				$response['status'] = false;
				$response['response'] = array();
				$response['message'] = "user status false";
			}else if($user_date['is_active'] == '0'){
				$response['status'] = false;
				$response['response'] = array();
				$response['message'] = $user_date['admin_message'];
			}else if($user_date['is_phone_verified'] == '0'){
				$response['status'] = true;
				$response['response'] = array();
				$response['message'] = "phone number not verified";
			}else if(count($user_date)>0){
				$response['status'] = true;
				$response['response'] = $user_date;
				$response['message'] = "login success";
			}else{
				$response['status'] = false;
				$response['response'] = array();
				$response['message'] = "Error occurred!";
			}
		}
		$this->response($response);
	}

}
