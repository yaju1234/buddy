<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {

		parent::__construct();

		$this->load->model(array('dashboard_model'));
		$this->load->helper(array('url', 'form'));
		
	} 

	public function index()
	{
	    $data   = array();
        $data['title']  = 'Dashboard';
		$this->load->view('dashboard/index_view', $data);

	}
    
    
}

