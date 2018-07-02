<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downloadexcel extends MY_Controller {
	
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
        $this->load->view('admin/download_excel_view', $data);
		$this->load->view('template/footer.php');
    }


    public function exceldata(){
		
		$fromdate = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		echo $fromdate;
		die();
		
		/*$data['created'] = date('Y-m-d H:i:s');
		if($this->input->post('cid') && $this->input->post('cid') > 0){
			$st = $this->admin_model->updateBanner($data, $this->input->post('cid'));
		} else {
			$st = $this->admin_model->addNewBanner($data);
		}
		redirect('/admin/banners', 'refresh');*/
	}
	

}
