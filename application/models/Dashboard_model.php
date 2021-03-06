<?php if (!defined('BASEPATH'))exit('No direct script access allowed');



class Dashboard_model extends CI_Model

{
  
//-------------------------------
// EMAIL EXISTS (true or false)
//---------------------------------
private function email_exists($email)
{
	$this->db->where('email', $email);
	$query = $this->db->get('users');
	if( $query->num_rows() > 0 ){ return TRUE; } else { return FALSE; }
}
 
//---------------------------------
// AJAX REQUEST, IF EMAIL EXISTS
//---------------------------------
function register_email_exists()
{
	if (array_key_exists('email',$_POST)) {
		if ( $this->email_exists($this->input->post('email')) == TRUE ) {
			echo json_encode(FALSE);
		} else {
			echo json_encode(TRUE);
		}
	}
}


private function forget_email_exists($email)
{
	$this->db->where('email', $email);
	$query = $this->db->get('users');
	if( $query->num_rows() > 0 ){ return TRUE; } else { return FALSE; }
}
 
//---------------------------------
// AJAX REQUEST, IF EMAIL EXISTS
//---------------------------------
function forget_pass_email_exists()
{
	if (array_key_exists('email',$_POST)) {
		if ( $this->forget_email_exists($this->input->post('email')) == TRUE ) {
			echo json_encode(TRUE);
		} else {
			echo json_encode(FALSE);
		}
	}
}

public function purchase($data)
{
    //session_start();
	//return (print_r($data['name_first']));
    
    if(strtolower($data['code']) == strtolower($_SESSION['random_number']))
	{
		
		// insert your name , email and text message to your table in db
		
		echo 1;// submitted 
		
	}
	else
	{
		echo 0; // invalid code
       //echo $_REQUEST['name'];
	}
    
}

public function purchase_package($id,$tran_id)
{
	//print_r($data_purchase);
    
    //echo $data_purchase['name_first'];
    $data = array();
    $usd = $this->session->userdata('logged_user');
    
    $rs  = $this->db->select('id,number,allow_hour')->where('id',$id)->get('pitch_master')->row_array();
    
    $data['user_id'] = $usd['id'];
    $data['pitch_use_total'] = $rs['number'];
    $data['pitchit_master_id'] = $id;
    $data['allow_hour'] = $rs['allow_hour'];
    $data['reference'] = $tran_id;
    $data['status'] = 'in';
    $data['created_date'] = date("Y-m-d h:i:s");
    
    $this->db->insert('pitch_transaction', $data);
    
   
}

   function get_twit_user($twid)
    {
        $data=array();
         
         $data  = $this->db->select('*')->where('social_id', $twid)->get('users')->row_array();
      
      
          $temp  = $this->db->select('*')->where('social_id',$twid)->get('users')->row_array();
          $this->session->set_userdata('logged_user', $temp);
      
        return $data;
     
    }
    
    function get_twit_user_pass_blank($twid)
    {
        $data=array();
         
         $data  = $this->db->select('count(*) as count')->where('social_id', $twid)->where('password', '')->get('users')->row_array();
      
      //echo $this->db->last_query();die;
      
          $temp  = $this->db->select('*')->where('social_id',$twid)->get('users')->row_array();
          $this->session->set_userdata('logged_user', $temp);
      
        return $data;
     
    }
    
    function get_twit_user_byemail($twemail)
    {
        $data=array();
         
         $data  = $this->db->select('count(*) as count,user_type,password,email')->where('email', $twemail)->get('users')->row_array();
         //echo $this->db->last_query();die;
      
         $temp  = $this->db->select('*')->where('email',$twemail)->get('users')->row_array();
         $this->session->set_userdata('logged_user', $temp);
      
        return $data;
     
    }
    
    function get_google_user($twid)
    {
        $data=array();
         
         $data  = $this->db->select('count(*) as count,user_type')->where('social_id', $twid)->where('social_source', 'googleplus')->get('users')->row_array();
      
      
          $temp  = $this->db->select('*')->where('social_id',$twid)->where('social_source', 'googleplus')->get('users')->row_array();
          $this->session->set_userdata('logged_user', $temp);
      
        return $data;
        //echo $this->db->last_query();
     
    }

    public function social_register()
    {
        $data   = array();
        $data22   = array();
        //$data   = $this->input->post(null);
        //$data['social_id'] = $this->input->post('social_id');
        $socialid = $this->input->post('social_id');
        
        
        //$data['password'] = md5($this->input->post('password'));
        
        //unset($data['con_password']);
        
        //$update_account=$this->db->where('user_guid',$unqid)->update('users',$data);
        
        $chkRs  = $this->db->select('count(*) as count')->where('social_id',$socialid)->get('users')->row_array();
        
        
        //echo $this->db->last_query();die;
        
        if($chkRs['count'] == 0){
            
            $data22['social_id'] = $this->input->post('social_id');
            $data22['social_source'] = $this->input->post('social_source');
            $data22['social_image'] = $this->input->post('social_image');
            $data22['name_first'] = $this->input->post('name_first');
            //$data['name_middle'] = $this->input->post('mname');
            $data22['name_last'] = $this->input->post('name_last');
            $data22['email'] = $this->input->post('social_email');
            
            $utype = $this->input->post('type');
            
            if($utype == '1')
            {
               $data22['user_type'] = '1';
               $data22['user_category'] = 'writer';
            }
            elseif($utype == '2')
            {
               $data22['user_type'] = '2'; 
               $data22['user_category'] = 'publisher';
            }
            elseif($utype == '3')
            {
               $data22['user_type'] = '3';
               $data22['user_category'] = 'agent';
            }
            elseif($utype == '4')
            {
               $data22['user_type'] = '4'; 
               $data22['user_category'] = 'editor';
            }
            else
            {
                $data22['user_type'] = '0';
                $data22['user_category'] = 'none';
            }
            
            
            $data22['status_id'] = '1'; 
            
            
            $data22['created'] = date("Y-m-d h:i:s");
            $data22['modified_date'] = date("Y-m-d h:i:s");
            $this->db->insert('users', $data22);
            $lastid = $this->db->insert_id();
            
            $temp  = $this->db->select('*')->where('social_id',$socialid)->get('users')->row_array();
            $this->session->set_userdata('logged_user', $temp);
            
            
            $data['profile_writer_id'] = $lastid;
            $data['url'] = $this->input->post('social_ownid');
            $data['create_date'] = date("Y-m-d h:i:s");
            $data['modified_date'] = date("Y-m-d h:i:s");
            
            if($temp['social_source'] == 'twitter')
            {
            $data['link_type_id'] = '2';
            $data['description'] = 'Twitter';
            }
            if($temp['social_source'] == 'facebook')
            {
            $data['link_type_id'] = '1';
            $data['description'] = 'Facebook';
            }
            if($temp['social_source'] == 'googleplus')
            {
            $data['link_type_id'] = '3';
            $data['description'] = 'Googleplus';
            }
            if($temp['social_source'] == 'linkedin')
            {
            $data['link_type_id'] = '5';
            $data['description'] = 'Linkedin';
            }
            $this->db->insert('profile_links', $data);
            }
         //echo "sssssss";die;
       return 1;
        
       
    }

    function randomByte($min, $max) {
        $range = $max - $min;
        if ($range < 0)
            return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }
    
    function forget_password(){
        $data   = array();

        $codeAlphabet = "123456789@#$?!ABCDEFabcdef";
        for ($i = 0; $i < 10; $i++) {
            $gen_pass .= $codeAlphabet[$this->randomByte(0, strlen($codeAlphabet))];
        }
        $password = $gen_pass;
        $email = $this->input->post('email');
        
        $get_user = $this->db->select("id,name_first,name_middle,name_last,user_type")->where('email',$this->input->post('email'))->where("status_id","1")->limit(1)->get("users")->row_array();
        
        $fullname = ucfirst($get_user['name_first']); //.' '.$get_user['name_middle'].' '.$get_user['name_last']
        
        $data['password'] = md5($password);
        
        $this->db->where('email',$this->input->post('email'))->update('users', $data);
        
        $this->send_mail_to_registered_user_forgetpassword($email, $fullname, $password);
         //echo "sssssss";die;
       return 1;
    }
    
   function send_mail_to_registered_user_forgetpassword($email ,$name ,$pass){
       
        $sub    = 'Inkubate Password Change';
        $str    = '<div style="width:750px; padding:0px 0 0 0; margin:20px auto; font-family:Verdana, Geneva, sans-serif; font-size:13px;background-color: #39302c;">

   <table width="100%" border="0" cellpadding="8" cellspacing="0" style="color:#39302c; padding:10px 0 0px 0px; border:#ccc solid 1px;">
   
   <tr>
   
     <td height="78"><a href="" style="padding-bottom:0px;"><img src="http://billbahadur.com/demo/inkubate/images/logo.png" alt="" /></a></td>
     
   
   </tr>
   <tr>&nbsp;</tr>
  <tr height="114">
    <td width="27%"><strong style="color: white;">Dear '.$name.',<br/>

    <p>This is your new temporary password '.$pass.' to enter Inkubate.Please <a style="color:#FFF;" href="'.base_url("home/login").'" target="_blank">click here</a> , to log back into Inkubate. Make sure to update your account with a new password again within your profile after you log back into Inkubate.</p>
    <p>&nbsp;</p>
    <p>Regards,</p>
    <p>The Inkubate Customer Service Team</p>

  </tr>
  <tr>&nbsp;</tr>
   <tr>
   <td colspan="2" bgcolor="#ddd"><p><strong>&copy; '.date('Y').' Inkubate. All rights reserved. </p></strong></td>
  
   </tr>
</table></div>';
        
       /* $headers = "From: inkubate@inkubate.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n"; 
        $headers .= "Content-Type: text/HTML; charset=ISO-8859-1\r\n";
        @mail($email, $sub, stripslashes($str), $headers);
        return 1; */
        
        $this->load->library('email');
        

        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        
        $this->email->initialize($config);
        
        $this->email->from('team@inkubate.com', 'Inkubate Team');
        $this->email->to($email); 
        
        
        $this->email->subject($sub);
        $this->email->message(stripslashes($str));
        
        return $this->email->send();
        
    } 
    
    function register(){
        $data   = array();
        $dataBookshelf   = array();
        //$data   = $this->input->post(null);
        $unqid = md5(uniqid(rand(), true));
        
        $maxid  = $this->db->select_max('id')->get('users')->row_array();
        //echo $this->db->last_query();die;
        $mid = $maxid['id']+1;
        
        $data['id'] = $mid;
        //echo $data['id'];die;
        $data['name_first'] = $this->input->post('name_first');
        $data['name_last'] = $this->input->post('name_last');
        $data['username'] = $this->input->post('email');
        $data['email'] = $this->input->post('email');
        $data['password'] = md5($this->input->post('password'));
        $data['user_guid'] = md5(uniqid(rand(), true));
      
        unset($data['con_password']);
       
        $getInvite = $this->db->select("*")->where('friend_email',$data['email'])->where("is_deleted","0")->where("status","1")->order_by('created',"asc")->limit(1)->get("invites")->row_array();
        //print_r($getInvite);
        //exit;
        if(count($getInvite) > 0)
        {
        	
        	
        	$data['invite_id'] = $getInvite['id'];
        	$data['invite_by'] = $getInvite['user_id'];
        }
        $data['created']    = date("Y-m-d h:i:s");
        $data['modified_date']    = date("Y-m-d h:i:s");
        
        $this->db->trans_start();
	$this->db->insert('users', $data);
    
	$insert_id = $this->db->insert_id();
        if(count($getInvite) > 0)
        {
        	$address['user_id'] = $getInvite['user_id'];
        	$address['address_user_id'] = $insert_id;
        	$address['status'] = "1";
        	$address['is_deleted'] = "0";
        	$this->db->insert('author_address_books', $address);
        }
        $this->db->trans_complete();
       // echo "sssssss";die;
        //$last_id=$this->db->insert_id();
       
       
      /* $getBookshelf = $this->db->select("count(*) as count")->where('user_id',$insert_id)->where("is_status","0")->get("bookshelfs")->row_array(); 
        if($data['user_type'] == 2 || $data['user_type']==3 || $getBookshelf['count'] == 0)
        {
        	$dataBookshelf['user_id'] = $insert_id;
        	$dataBookshelf['name'] = $insert_id.'DeafaultBookshelf';
        	$dataBookshelf['protected'] = "1";
            $dataBookshelf['default_list'] = "1";
            $dataBookshelf['create_date'] = date('Y-m-d h:i:s');
            $dataBookshelf['modified_date'] = date('Y-m-d h:i:s');
        	$dataBookshelf['is_status'] = "0";
        	$this->db->insert('bookshelfs', $dataBookshelf);
        }
        
        $this->send_mail_to_registered_user($data['email'], $data['user_guid'],$data['user_type']); */
         //echo "sssssss";die;
       return 1;
    }
    
    function register2(){
        $data   = array();
        $data22   = array();
        //$data   = $this->input->post(null);
        $email = $this->input->post('email');
        
        $data['user_type'] = $this->input->post('user_type');
        
        $update_account=$this->db->where('email',$email)->update('users',$data);
        
          $chkRs  = $this->db->select('*')->where('email',$email)->get('users');
        
      
        
        
        //echo $this->db->last_query();die;
        
        if($chkRs->num_rows() > 0){
            $temp   = array();
            $temp   = $chkRs->row_array();
            
            
        $getBookshelf = $this->db->select("count(*) as count")->where('user_id',$temp['id'])->where("is_status","0")->get("bookshelfs")->row_array(); 
        if($data['user_type'] == 2 || $data['user_type'] == 3 || $getBookshelf['count'] == 0)
        {
        	$dataBookshelf['user_id'] = $temp['id'];
        	$dataBookshelf['name'] = $temp['id'].'DeafaultBookshelf';
        	$dataBookshelf['protected'] = "1";
            $dataBookshelf['default_list'] = "1";
            $dataBookshelf['create_date'] = date('Y-m-d h:i:s');
            $dataBookshelf['modified_date'] = date('Y-m-d h:i:s');
        	$dataBookshelf['is_status'] = "0";
        	$this->db->insert('bookshelfs', $dataBookshelf);
        }
        
        $this->send_mail_to_registered_user($email, $temp['user_guid'],$data['user_type']);
        
            
            
            //$this->session->set_userdata('logged_user', $temp);
             
            $data22['user_id'] = $temp['id'];
            $data22['status_id'] = '1';
            $data22['description'] = 'profile';
            $data22['create_date'] = date("Y-m-d h:i:s");
            $data22['modified_date'] = date("Y-m-d h:i:s");
            $this->db->insert('assets', $data22);
            }
         //echo "sssssss";die;
       return 1;
    }
    
    function success_sign($unqid){
        $data   = array();
        $data22   = array();
        
        $data['status_id'] = '1';
      
        $update_account=$this->db->where('user_guid',$unqid)->update('users',$data);
        
        $chkRs  = $this->db->select('*')->where('user_guid',$unqid)->get('users');
        
        
        //echo $this->db->last_query();die;
        
        if($chkRs->num_rows() > 0){
            $temp   = array();
            $temp   = $chkRs->row_array();
            
            $this->session->set_userdata('logged_user', $temp);
             
            $data22['user_id'] = $temp['id'];
            $data22['status_id'] = '1';
            $data22['description'] = 'profile';
            $data22['create_date'] = date("Y-m-d h:i:s");
            $data22['modified_date'] = date("Y-m-d h:i:s");
            $this->db->insert('assets', $data22);
            }
         //echo "sssssss";die;
       return 1;
    }
    
    function register2_old(){
        $data   = array();
        $data22   = array();
        //$data   = $this->input->post(null);
        $unqid = $this->input->post('unqid');
        
        $data['name_first'] = $this->input->post('fname');
        $data['name_middle'] = $this->input->post('mname');
        $data['name_last'] = $this->input->post('lname');
        $data['date_of_birth'] = $this->input->post('dob');
        $data['postal_code'] = $this->input->post('zip');
        $data['status_id'] = '1';
        $data['password'] = md5($this->input->post('password'));
        
        unset($data['con_password']);
        
        $update_account=$this->db->where('user_guid',$unqid)->update('users',$data);
        
        $chkRs  = $this->db->select('*')->where('user_guid',$unqid)->get('users');
        
        
        //echo $this->db->last_query();die;
        
        if($chkRs->num_rows() > 0){
            $temp   = array();
            $temp   = $chkRs->row_array();
            
            $this->session->set_userdata('logged_user', $temp);
             
            $data22['user_id'] = $temp['id'];
            $data22['status_id'] = '1';
            $data22['description'] = 'profile';
            $data22['create_date'] = date("Y-m-d h:i:s");
            $data22['modified_date'] = date("Y-m-d h:i:s");
            $this->db->insert('assets', $data22);
            }
         //echo "sssssss";die;
       return 1;
    }
    
   function send_mail_to_registered_user($email ,$unqid,$usertype){
        $verified   = md5($email);
        if($usertype == 1)
        {
            $type = 'Writer';
        }
        if($usertype == 2)
        {
            $type = 'Publisher';
        }
        if($usertype == 3)
        {
            $type = 'Agent';
        }
        if($usertype == 4)
        {
            $type = 'Editor';
        }
        //echo "email : ".$email." : name : ".$name." : password :".$password."<br>";
        $sub    = 'Your Invitation to Inkubate';
        $str    = '<div style="width:750px; padding:0px 0 0 0; margin:40px auto; font-family:Verdana, Geneva, sans-serif; font-size:13px;background-color: #39302c;">

   <table width="100%" border="0" cellpadding="8" cellspacing="0" style="color:#39302c; padding:10px 0 0px 0px; border:#ccc solid 1px;">
   
   <tr>
   
     <td height="168"><a href="" style="padding-bottom:0px;"><img src="http://inkubate.com/application/images/logo.png" alt="" /></a></td>
     
   
   </tr>
  <tr>
    <td width="27%"><strong style="color: white;">Dear '.$type.',<br/>

<p>Your request for an invitation to Inkubate has been approved. Please <a href="http://billbahadur.com/demo/inkubate/home/success_sign/'.$unqid.'"> click here </a> to finish the registration process. *If this link does not work, copy and paste the URL into your browser.</p>

<p>Becoming a part of Inkubate will allow you to post your work in a highly organized, searchable portfolio that only vetted publishers and agents will be able to view. Heres how it works:</p>

<p>Sign on and post your work.</p>
<p>Create your profile.</p>
<p>Build your literary brand.</p>
<p>Its that simple. When we invite Publishers and agents to join Inkubate, they will be searching the works you have posted.</p>

<p>Once you have posted work, you can invite your writing friends and colleagues to join you on Inkubate!</p></strong></td>
    
  </tr>
  
   <tr>
   <td colspan="2" bgcolor="#ddd"><p><strong>&#169 '.date("Y").' Inkubate. All rights reserved. </p></strong></td>
  
   </tr>
</table></div>';
        //die($str);
        //die();
        //$headers  = "From: Admin <das.prasenjit55@gmail.com>\n";
        //$headers = "From: inkubate@inkubate.com\r\n";
        //$headers .= "MIME-Version: 1.0\r\n"; 
        //$headers .= "Content-Type: text/HTML; charset=ISO-8859-1\r\n";
        //@mail($email, $sub, stripslashes($str), $headers);
        
                
        $this->load->library('email');
        

        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        
        $this->email->initialize($config);
        
        $this->email->from('team@inkubate.com', 'Inkubate Team');
        $this->email->to($email); 
        
        
        $this->email->subject($sub);
        $this->email->message(stripslashes($str));
        
        return $this->email->send();
        
        
        
       // return 1;
    }
    
 function check_credentials(){
        
        $email  = $this->input->post('email');
        $password  = $this->input->post('email');
        
        $options = array("cost" => 11);
        $pass = password_hash($password, PASSWORD_BCRYPT, $options);
        $pass_verify = password_verify($password, $pass);
        
        if($pass_verify == 1)
        {
        $chkRs  = $this->db->select('*')->where('email', $email)->get('appo_admin');
        
        //echo $this->db->last_query();die;
        
        if($chkRs->num_rows() > 0){
            $temp   = array();
            $temp   = $chkRs->row_array();
            
            $this->session->set_userdata('logged_user', $temp);
            
              return 1;  
            
        }else{
            return 0;
        }
       }
       else
       {
           return 0;
       } 
    }   

}
