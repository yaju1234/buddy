<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

class Admin_model extends CI_Model{
	function getClients(){
		$userData = $this->session->userdata['logged_user'];
		$role = $userData['role'];
		if($role=="SUPER_ADMIN"){
			$res = $this->db->select('*')->where('user_type','CLIENT')/*->where('status','1')*/->order_by('created','desc')->get('traffic_users')->result_array();
			return $res;
		}else{
			$city = $userData['city'];

			$res = $this->db->select('*')->where('user_type','CLIENT')/*->where('status','1')*/->where('city',$city)->order_by('created','desc')->get('traffic_users')->result_array();
			return $res;
		}
		
	}
	function getClientDetails($userid){
		$rows = array();
		$rows= $this->db->select('id,first_name, last_name, email, phone, IF(LOCATE("http", profile_image) > 0, profile_image, IF(profile_image = "", "", IF(user_type = "CLIENT", CONCAT("uploadImage/client_profile_image/",profile_image), CONCAT("uploadImage/lawyer_profile_image/",profile_image)))) as profile_image, IF(license_image = "", "", CONCAT("uploadImage/client_license_image/",license_image)) as license_image, is_phone_verified, is_email_verified,is_active,admin_message, status,country, state, city, status')->where("id",$userid)->get('traffic_users')->row_array();
		return $rows;
	}
	function getCaseList($user_id, $status='ALL'){
		$rows = array();
		$query = $this->db->select('id, user_id, case_number, case_details, IF(case_front_img = "", "", CONCAT("uploadImage/case_image/",case_front_img)) as case_front_img, IF(case_rear_img = "", "", CONCAT("uploadImage/case_image/",case_rear_img)) as case_rear_img, IF(driving_license = "", "", CONCAT("uploadImage/client_license_image/",driving_license)) as driving_license, status, state, city, created_at, 0 as bid_count')->where("user_id",$user_id);
		if($status != 'ALL'){
			$query->where("status",$status);
		}
		$rows = $query->get('traffic_cases')->result_array();
		return $rows;
	}
	function getCaseDetails($user_id, $case_id){
		$rows = array();
		$query = $this->db->select('id, user_id, case_number, case_details, IF(case_front_img = "", "", CONCAT("uploadImage/case_image/",case_front_img)) as case_front_img, IF(case_rear_img = "", "", CONCAT("uploadImage/case_image/",case_rear_img)) as case_rear_img, IF(driving_license = "", "", CONCAT("uploadImage/client_license_image/",driving_license)) as driving_license, status, state, city, created_at, 0 as bid_count')->where("user_id",$user_id)->where("id",$case_id);
		$rows = $query->get('traffic_cases')->row_array();
		return $rows;
	}
	function getLawyers(){
		$userData = $this->session->userdata['logged_user'];
		$role = $userData['role'];
		if($role=="SUPER_ADMIN"){

			$res = $this->db->select('*')->where('user_type','LAWYER')/*->where('status','1')*/->order_by('created','desc')->get('traffic_users')->result_array();
			foreach($res as $ky=>$rslt){
				$res[$ky]['degree'] = $this->db->select('*')->where('user_id',$rslt['id'])->get('traffic_degree')->result_array();
			}
			return $res;
		}else{
			$city = $userData['city'];
			$res = $this->db->select('*')->where('user_type','LAWYER')/*->where('status','1')*/->where('city',$city)->order_by('created','desc')->get('traffic_users')->result_array();
			foreach($res as $ky=>$rslt){
				$res[$ky]['degree'] = $this->db->select('*')->where('user_id',$rslt['id'])->get('traffic_degree')->result_array();
			}
		}
	}
	function getCaseListOfLawyer($user_id, $status='ALL'){
		$rows = array();
		$query = $this->db->select('TC.id, TC.user_id, TC.case_number, TC.case_details, IF(TC.case_front_img = "", "", CONCAT("uploadImage/case_image/",TC.case_front_img)) as case_front_img, IF(TC.case_rear_img = "", "", CONCAT("uploadImage/case_image/",TC.case_rear_img)) as case_rear_img, IF(TC.driving_license = "", "", CONCAT("uploadImage/client_license_image/",TC.driving_license)) as driving_license, TC.status, TC.state, TC.city, TC.created_at, count(TB.id) as bid_count, IF(TB.is_accepted = "1", TB.lawyer_id, 0) as accepted_lawyer_id')
		->where("TCN.lawyer_id",$user_id);
		if($status != 'ALL'){
			$query->where("TC.status",$status);
		}
		$query->join('traffic_case_notifications TCN', 'TCN.case_id = TC.id');
		$query->join('traffic_bids TB', 'TB.case_id = TC.id');
		$rows = $query->get('traffic_cases TC')->result_array();
		return $rows;
	}
	function addNewBanner($data){
		$res = $this->db->insert('traffic_banners', $data);
		return $res;
	}
	function updateLawyerMsg($data, $user_id){
		$res = $this->db->where('id', $user_id)->update('traffic_users', $data);
		return $res;
	}
	function updateBanner($data, $id){
		$res = $this->db->where('id', $id)->update('traffic_banners', $data);
		return $res;
	}
	function getBanners(){
		$res = $this->db->select('*')->get('traffic_banners')->result_array();
		return $res;
	}
	function getBids($case_id){
		$rows = array();
		$rows= $this->db
		->select('BIDS.id,BIDS.client_id,BIDS.lawyer_id,BIDS.case_id,BIDS.bid_amount,BIDS.bid_text,BIDS.created_at, IF(BIDS.is_accepted = "1", "ACCEPTED", "") as status, BIDS.status as status1, BIDS.created_at,BIDS.accepted_at,TU.first_name as lawyer_first_name, TU.last_name as lawyer_last_name, TU.email as lawyer_email, TU.phone as lawyer_phone, IF(LOCATE("http", TU.profile_image) > 0, TU.profile_image, IF(TU.profile_image = "", "", CONCAT("uploadImage/lawyer_profile_image/",TU.profile_image))) as lawyer_profile_image')
		->JOIN('traffic_users TU', 'TU.id = BIDS.lawyer_id', 'INNER')
		->where("BIDS.case_id",$case_id)
		->order_by("BIDS.id", "DESC")
		->get('traffic_bids BIDS')
		->result_array();
		return $rows;


	}

	//add cityAdmin
	//
	function addCityAdmin($data){
		$res = $this->db->insert('traffic_admin', $data);
		return $res;
	}


	function getCityAdmin(){
		$res = $this->db->select('*')->where('role', "CITY_ADMIN")/*->where('status','1')*/->order_by('id','desc')->get('traffic_admin')->result_array();
		return $res;

	}

	//delete city admin
	function deleteCityAdmin($id){
		
		return $this->db->where("id",$id)->delete('traffic_admin');
	}

	

function checkCityAdminEmailExists($email){
		
		$res = $this->db->select('*')->where('email', $email)->get('traffic_admin')->row_array();
		return $res;
	}
function changeStatusCityAdmin($id,$data){
		
		$res = $this->db->where('id', $id)->update('traffic_admin', $data);
		return 1;
		}

	
	
}