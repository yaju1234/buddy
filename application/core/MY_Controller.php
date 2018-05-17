<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class MY_Controller extends CI_Controller{
	
	function __construct(){
		parent::__construct();
	}

	/**
	* @param return bool
	**/
	protected function isLoggedIn(){
		if($this->session->userdata('logged_user')){
			return true;
		}else{
			return false;
		}
	}

	protected function redirectIfNotLoggedIn(){
		if(!$this->session->userdata('logged_user')){
		   redirect('login/', 'refresh');
		}
	}

	protected function redirectToHome(){
		if($this->session->userdata('logged_user')){
		   redirect('dashboard/', 'refresh');
		}
	}
        
    protected function send_mail($data_info,$main_user_email){
        /*$to_email = $data_info['email'];
        $subject = $data_info['subject'];
        $data_message = $data_info['message'];*/
        
        //$to_email = 'das.prasenjit55@gmail.com';
        
        /*$from_email = "homdevelopers@gmail.com";
        $to_email = 'cool_prasen@rediffmail.com';
        $subject = 'Email Test';
        $data_message = 'Hello Prasenjit';
        
        $config = Array(
            'protocol' => 'mail',
           //'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => 'homdevelopers@gmail.com',
            'smtp_pass' => 'hom123456',
            'mailtype'  => 'html', 
            'charset'   => 'iso-8859-1',
            'wordwrap' => TRUE
        );
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        
        $this->email->from($from_email, 'HOM');
        $this->email->to($to_email); 

        $this->email->subject($subject);
        
        $data = array();
        
        $data['userName']= 'Prasenjit';
        $data['data_message']= $data_message;
             
        $body = $this->load->view('emails/email_template',$data);
        $this->email->message($body);

        $this->email->send();
        
        echo $this->email->print_debugger(); */
     
       $this->load->library('email');
       $this->email->initialize(array(
          'protocol' => 'smtp',
          'smtp_host' => 'smtp.sendgrid.net',
          'smtp_user' => 'hom.developer',
          'smtp_pass' => 'hom123456',
          'smtp_port' => 587,
          'crlf' => "\r\n",
          'newline' => "\r\n",
          'charset' => 'utf-8', 
          'wordwrap' => true, 
          'mailtype' => 'html'
            
        ));
       
        $name = $data_info['name'];
        $to_email = $data_info['email'];
        $subject = $data_info['subject'];
        $message = $data_info['message'];
        
        $this->email->from('homdevelopers@gmail.com', 'Health On Mobile');
        $this->email->to($to_email);
        if(!empty($main_user_email)){
        $this->email->cc($main_user_email);
        }
        //$this->email->bcc('them@their-example.com');
        $this->email->subject($subject);
        
        $data = array();
        $data_message = $message;
        $data['userName']= $name;
        $data['data_message']= $data_message;
             
        $body = $this->load->view('emails/email_template',$data,true);
        $this->email->message($body);
        
        $this->email->send();
        return 1;
        //echo $this->email->print_debugger();
      
    }
        
}
