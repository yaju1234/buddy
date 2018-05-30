<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banners extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model(array('admin_model','Api_user_model'));
		$this->load->helper(array('url', 'form'));
	}
	
	public function index() {
        $data = array();
        $data['title'] = 'Banner Management';
        $data['client_list'] = $this->admin_model->getBanners();
		$this->load->view('template/header.php', $data);
        $this->load->view('admin/banner_images_view', $data);
		$this->load->view('template/footer.php');
    }
	
	public function addUpdateBanner(){
		if(!empty($_FILES['banner_image']['name'])){
			$banner_image = $this->uploadImage('./uploadImage/banner_image/',  $_FILES['banner_image'],'banner_image');
			if(!empty($banner_image)){
				$data['banner_image'] = $banner_image;
			}
		}
		$description = $this->input->post('description');
		$data['description'] = $description;
		$data['created'] = date('Y-m-d H:i:s');
		if($this->input->post('cid') && $this->input->post('cid') > 0){
			$st = $this->admin_model->updateBanner($data, $this->input->post('cid'));
		} else {
			$st = $this->admin_model->addNewBanner($data);
		}
		redirect('/admin/banners', 'refresh');
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


	public function details() {
        $data = array();
        $data['title'] = 'Client Management';
        $data['client_list'] = $this->admin_model->getClients();
		/*echo "<pre />";
		print_r($data['client_list']);exit;*/
		$this->load->view('template/header.php', $data);
        $this->load->view('admin/client_details_view', $data);
		$this->load->view('template/footer.php');
    }
	
}
