<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Api_user_model extends CI_Model

{

  public function registration($user){
    

   $this->db->insert('users',$user);
   return $this->db->insert_id();
 }

 public function registrationDevice($user){

 
  $rows = $this->db->select('count(*) AS count')->where("token",$user['token'])->where("user_id",$user['user_id'])->get('device_token')->row_array();
    if($rows['count']==0){

         $rows2 = $this->db->select('count(*) AS count')->where("token",$user['token'])->get('device_token')->row_array();

        if($rows2['count']==0){
               $this->db->insert('device_token',$user);
               return $this->db->insert_id();
        }else{
              $data['user_id']= $user['user_id'];
              $this->db->where('token',$user['token'])->update('device_token',$data);
        }

    }
}

 public function login($email,$phone,$password){

  $rows = array();
  $result = array();
 

  if($phone != ''){
    
    $rows= $this->db->select('id,title,residence_address,first_name,middile_name,last_name,email,phone,zipcode,gender,id_type,id_number,country,state,city,country_code,career,work,work_title,membership,image,is_email_verified,is_phone_verified,user_online_status')
    ->where("phone",$phone)
    ->where("password",md5($password))
    ->get('users')->row_array();
  }else{
    
    $rows= $this->db->select('id,title,residence_address,first_name,middile_name,last_name,email,phone,zipcode,gender,id_type,id_number,country,state,city,country_code,career,work,work_title,membership,image,is_email_verified,is_phone_verified,user_online_status')
    ->where("email",$email)
    ->where("password",md5($password))
    ->get('users')->row_array();
  }



  if(count($rows) > 0){

  $data  = array();
  $data['last_login_time']= date('Y-m-d H:i:s');
  $data['user_online_status']= 'ONLINE';
  $this->db->where('id',$rows['id'])->update('users',$data);

  foreach($rows as $key => $value){
      $result[$key] = (string)$value;
   }
}
 return $result;
  
  
}


public function getUser($user_id){

 $rows = array();
 $result = array();

 $rows= $this->db->select('id,title,residence_address,first_name,middile_name,last_name,email,phone,zipcode,gender,id_type,id_number,country,state,city,country_code,career,work,work_title,membership,image,is_email_verified,is_phone_verified,user_online_status')->where("id",$user_id)->get('users')->row_array();

  if(count($rows) > 0){
   foreach($rows as $key => $value){
      $result[$key] = (string)$value;
   }
}
 return $result;
}


public function getUserByPhone($phone){

 $rows = array();

 $rows= $this->db->select('id,title,residence_address,first_name,middile_name,last_name,email,phone,zipcode,gender,id_type,id_number,country,career,work,work_title,membership,image')->where("phone",$phone)->get('users')->row_array();

 return $rows;
}

public function getUserByEmail($email){

 $rows = array();

 $rows= $this->db->select('id,title,residence_address,first_name,middile_name,last_name,email,phone,zipcode,gender,id_type,id_number,country,career,work,work_title,membership,image')->where("email",$email)->get('users')->row_array();

 return $rows;
}


public function isEmailExist($email,$user_id=''){

 $rows = array();
 if($user_id != ''){

    $rows= $this->db->select('count(*) AS count')->where("email",$email)->where("id != ".$user_id)->get('users')->row_array();
 
 }else{
    $rows= $this->db->select('count(*) AS count')->where("email",$email)->get('users')->row_array();
 }


 return $rows['count']>0 ? true : false;
}

public function isPhoneExist($phone){

 $rows = array();
 $rows= $this->db->select('count(*) AS count')->where("phone",$phone)->get('users')->row_array();
 return $rows['count']>0 ? true : false;
}

public function isValidOTP($user_id,$otp){

 $rows = array();

 $rows= $this->db->select('count(*) AS count')->where("id",$user_id)->where("otp",$otp)->get('users')->row_array();


 if($rows['count']>0){
  $data  = array();
  $data['is_phone_verified']= '1';
  $this->db->where('id',$user_id)->update('users',$data);

}
return $rows['count']>0 ? true : false;
}

public function isEmailValidOTP($user,$otp){

 $rows = array();
 $rows= $this->db->select('count(*) AS count')->where("id",$user)->where("email_otp",$otp)->get('users')->row_array();
 if($rows['count']>0){
  $data  = array();
  $data['is_email_verified']= '1';
  $this->db->where('id',$user)->update('users',$data);

}
return $rows['count']>0 ? true : false;
}

public function resendOTP($user_id,$otp){

 $rows = array();
 $rows= $this->db->select('count(*) AS count')->where("otp",$otp)->get('users')->row_array();
 if($rows['count']>0){
  $data  = array();
  $data['is_phone_verified']= '1';
  $this->db->where('id',$user_id)->update('users',$data);
  
}
return $rows['count']>0 ? true : false;
}


public function sendOTP($phone){
  //send SMS
  return '0000';
}

public function sendEmailOTP($email,$user_id){
  //send SMS


  return (randomKey(20));
}


public function update_payment($data,$id){
  $this->db->where('id',$id)->update('users',$data);
  return true;
}


public function updateOTP($user_id,$otp){
  $data  = array();
  $data['otp']= $otp;
  $this->db->where('id',$user_id)->update('users',$data);
}

public function updateEmailOTP($user_id,$otp,$email){
  $data  = array();
  $data['email_otp']= $otp;
  $data['email']= $email;
  $this->db->where('id',$user_id)->update('users',$data);
}

public function updateProfile($user_id,$data){

  $this->db->where('id',$user_id)->update('users',$data);
  $rows= $this->db->select('id,title,residence_address,first_name,middile_name,last_name,email,phone,zipcode,gender,id_type,id_number,country,state,city,country_code,career,work,work_title,membership,image,is_email_verified,is_phone_verified,user_online_status')->where("id",$user_id)->get('users')->row_array();
  foreach($rows as $key => $value){
      $result[$key] = (string)$value;
   }

 return $result;
}

public function forgetpassword($user_id){
  $data  = array();
   
  $time = date('Y-m-d H:i:s');
  $time = strtotime($time) + 3600; // Add 1 hour
  $time = date('Y-m-d H:i:s', $time); // Back to string
 
  $data['reset_password_token']= randomKey(20);
  $data['reset_password_valid_time']= $time;
  $this->db->where('id',$user_id)->update('users',$data);
  return $data['reset_password_token'];
}


public function validate_email_otp($user_id,$otp){
   $rows = array();
   $rows= $this->db->select('count(*) AS count')->where("otp",$otp)->where("id",$user_id)->get('users')->row_array();
   if($rows['count']>0){
    $data  = array();
    $data['is_phone_verified']= '1';
    $this->db->where('id',$user_id)->update('users',$data);
    
  }
  return $rows['count']>0 ? true : false;
}


public function getusetbyToken($token){
  $rows = array();

 $rows= $this->db->select('id,password,reset_password_token,reset_password_valid_time')->where("reset_password_token",$token)->get('users')->row_array();

 return $rows;
}


public function updatepassword($password,$user_id){
    $data['password'] = md5($password);
    $data['reset_password_token'] = '';
    $this->db->where('id',$user_id)->update('users',$data);
}


public function userprofileDetails($userId){

  $rows = array();
  $result = array();
  
    $rows= $this->db->select('id,title,residence_address,first_name,middile_name,last_name,email,phone,zipcode,gender,id_type,id_number,country,state,city,country_code,career,work,work_title,membership,image,is_email_verified,is_phone_verified,user_online_status')
    ->where("id",$userId)
    ->get('users')->row_array();
    
  if(count($rows) > 0){

      foreach($rows as $key => $value){
          $result[$key] = (string)$value;
       }
        $result['profile_image_path'] =  "uploads/profile_image/".$userId."/";;
  }
  return $result;
  }

  public function upadateOnlinestatus($user){

    $data['user_online_status'] = $user['online_status'];
    $this->db->where('id',$user['user_id'])->update('users',$data);

  }


  public function upadatePassword($user){
    $data['password'] = $user['password'];
    $this->db->where('id',$user['user_id'])->update('users',$data);
  }





}

?>
