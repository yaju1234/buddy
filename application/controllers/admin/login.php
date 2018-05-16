<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {

		parent::__construct();

		$this->load->model(array('login_model'));
		$this->load->helper(array('url', 'form'));
		
	} 

	public function index()
	{
	  
	    $data   = array();
        $data['title']  = 'Login';
		$this->load->view('login/index_view', $data);
	}
    
    	public function script()
	{
         
	    $this->login_model->script();
	}
    
    
    public function dologin(){
       
        if($this->session->userdata('logged_user')){ 

             redirect('dashboard/', 'refresh');

        }else{    
        
        $data['title']  = 'Easy Appo - Login';
        
        if (!$this->input->post('remember_me')) {
            
                $this->session->sess_expiration = 7200;
                $this->session->sess_expire_on_close = TRUE;
            }
            if($this->input->post(null)){
              
                $valid  = $this->login_model->check_credentials();
                
                if($valid == '1'){
                    
                    redirect('dashboard/', 'refresh');
                 }
                
                else{
                     $this->session->set_flashdata('active_account', "Sorry, Either your e-mail or password are incorrect.");
                     redirect('login/', 'refresh');
                }
               } 
            }
            
    }
        
 	public function logout() {
 	  
    	$this->session->sess_destroy();
    	redirect('login/', 'refresh');
	
	}   
        
}

