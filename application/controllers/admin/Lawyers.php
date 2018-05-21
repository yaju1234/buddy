<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lawyers extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model(array('admin_model'));
		$this->load->helper(array('url', 'form'));
	}
	
	public function index() {
        $data = array();
        $data['title'] = 'Lawyers Management';
        $data['lawyers_list'] = $this->admin_model->getLawyers();
		/*echo "<pre />";
		print_r($data['client_list']);exit;*/
		$this->load->view('template/header.php', $data);
        $this->load->view('admin/lawyers_view', $data);
		$this->load->view('template/footer.php');
    }
}
