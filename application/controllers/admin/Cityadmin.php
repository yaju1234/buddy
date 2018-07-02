<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cityadmin extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model(array('admin_model','Api_user_model'));
		$this->load->helper(array('url', 'form'));
	}
	
	public function index() {
		if(!$this->isLoggedIn()){
			redirect('login/logout', 'refresh');
		}
		$data = array();
		$data['title'] = 'City Admin';
		$data['country_list'] = $this->Api_user_model->getCountry();
		$data1['state_list'] = $this->Api_user_model->getState();
		$data1['city_admin'] = $this->admin_model->getCityAdmin();
		//echo "<pre />";
		//print_r($data1);exit;
		$this->load->view('template/header.php', $data);
		$this->load->view('admin/admin_view', $data1);
		$this->load->view('template/footer.php');
	}


	public function addCityAdmin()
	{
		if(!$this->isLoggedIn()){
			redirect('login/logout', 'refresh');
		}

		$data = array();

		
		$state = $this->input->post('state');
		$data['state'] = $state;

		$city = $this->input->post('city');
		$data['city'] = $city;

		$display_name = $this->input->post('display_name');
		$data['display_name'] = $display_name;

		$email = $this->input->post('email');
		$data['email'] = $email;

		$password = $this->input->post('password');
		$data['password'] = $password;
		
	
		$isEmailExists=$this->admin_model->checkCityAdminEmailExists($email);

		$msg="";
		if($isEmailExists!=null){
 		$msg=$email." email id already exists";

 		$this->session->set_flashdata('error', $msg);
		redirect('/admin/cityadmin', 'refresh');

		}else{
			$msg="Sucessfully added";
			$cityAdmin = $this->admin_model->addCityAdmin($data);
		 $this->session->set_flashdata('sucess', $msg);

		redirect('/admin/cityadmin', 'refresh');
		}
		


	}


	public function deleteCityAdmin()
	{
		if(!$this->isLoggedIn()){
			redirect('login/logout', 'refresh');
		}

		
			$id = $this->input->post('id');
			$st = $this->admin_model->deleteCityAdmin($id);
			
			if ($st == '1') {
                echo 1;
            } else { 
             echo 0;
            }

	}

	public function changeStatusCityAdmin(){
		if(!$this->isLoggedIn()){
			redirect('login/logout', 'refresh');
		}

			$data = array();
			$id = $this->input->post('id');
			$is_active = $this->input->post('is_active');
			$data['is_active'] = $is_active;
			$st = $this->admin_model->changeStatusCityAdmin($id,$data);
			
			if ($st == '1') {
                echo 1;
            } else { 
             echo 0;
            }
	}
	

}
