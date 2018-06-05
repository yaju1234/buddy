<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->model(array('login_model'));
		$this->load->helper(array('url', 'form'));
	} 

	public function index()
	{
		if($this->isLoggedIn()){
			redirect('admin/clients', 'refresh');
		} else {
			$data = array();
			$data['title']  = 'Login';
			$this->load->view('template/header_outer.php', $data);
			$this->load->view('login/index_view');
			$this->load->view('template/footer_outer.php');
		}
	}
	
    public function dologin(){
		if($this->input->post(null)){
			$valid  = $this->login_model->check_credentials();
			die(json_encode(array('status'=>$valid)));
			/*if($valid){
				redirect('admin/clients/', 'refresh');
			}else{
				$this->session->set_flashdata('active_account', "Sorry, Either your e-mail or password are incorrect.");
				redirect('login/', 'refresh');
			}*/
		}
    }
	
 	public function logout() {
    	$this->session->sess_destroy();
    	redirect('/', 'refresh');
	}
}