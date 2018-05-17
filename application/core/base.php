<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base extends CI_Controller {

    public function __construct() {

		parent::__construct();

        $usd = $this->session->userdata('logged_user');
        
        if(empty($usd)){
            redirect('login/logout','refresh');
            }
         
		
	} 

        
}

