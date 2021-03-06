<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lawyers extends MY_Controller {
	
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
        $data['title'] = 'Lawyers Management';
        $data['client_list'] = $this->admin_model->getLawyers();
		/*echo "<pre />";
		print_r($data['client_list']);exit;*/
		$this->load->view('template/header.php', $data);
        $this->load->view('admin/lawyers_view', $data);
		$this->load->view('template/footer.php');
    }
	
	public function updateClient(){
		if(!$this->isLoggedIn()){
			redirect('login/logout', 'refresh');
		}
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
			redirect('/admin/clients/details/'.$id, 'refresh');
		} catch(Exception $e){
			$response['status'] = false;
			$response['response'] = new stdClass();
			$response['message'] = "error";
			redirect('/admin/clients', 'refresh');
		}
		
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


	public function details($client_id) {
		if(!$this->isLoggedIn()){
			redirect('login/logout', 'refresh');
		}
        $data = array();
        $data['title'] = 'Lawyer Management';
        $data['country_list'] = $this->Api_user_model->getCountry();
        $data['client_list'] = $this->admin_model->getClientDetails($client_id);
        $data['all_case_list'] = $this->admin_model->getCaseListOfLawyer($client_id, 'ALL');
        $data['open_case_list'] = $this->admin_model->getCaseListOfLawyer($client_id, 'PENDING');
		/*echo "<pre />";
		print_r($data);exit;*/
		$this->load->view('template/header.php', $data);
        $this->load->view('admin/lawyer_details_view', $data);
		$this->load->view('template/footer.php');
    }
	
	public function saveLawyerMsg() {
		if(!$this->isLoggedIn()){
			redirect('login/logout', 'refresh');
		}
        $data = array();
        $data['admin_message'] = $this->input->post('admin_message');
        $user_id = $this->input->post('user_id');
		
		$st = $this->admin_model->updateLawyerMsg($data, $user_id);
		
		if($st){
			redirect('admin/lawyers/details/'.$user_id, 'refresh');
		}
    }
}
