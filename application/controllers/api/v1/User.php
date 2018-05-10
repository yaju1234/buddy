<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'libraries/REST_Controller.php';

class User extends REST_Controller {

	public function __construct() {
		parent::__construct();
		    $this->load->helper(array('url', 'form'));
    		$this->load->model(array('Api_user_model'));
	}

	/* function name: registration_basic_post
	*  purpose: gold registration
	*  Author: Somwrita Debnath
	*/
   

public function test11_post(){

		//$country = $this->Api_user_model->getCountrylist();
	
		$response['status'] = true;
		$response['response'] = array();
		$response['message'] = "Data fetched successfully";
		
		$this->response($response);


	}

}
