<?php
class Cloudinarystorage extends CI_model{

	protected $CI;

	function __construct() {
		parent::__construct();
		$this->CI =& get_instance();
		$this->load->library("cloudinarylib");
	}


	public function simplefileupload($filee){
		
		$data =  \Cloudinary\Uploader::upload($filee,array("folder" => "uploads/profile_image/27/","resource_type"=>"auto"));
		
		 return $data;

		
	}
	public function moveFileToCloudinary($fileName,$fileContent,$path, $is_image = 0){
		$fileName = explode('.', $fileName);
		$fileName = $fileName[0];
		if($is_image == 1){
			$data = \Cloudinary\Uploader::upload($fileContent,array("public_id" => $path.$fileName,"resource_type"=>"auto","overwrite"=>TRUE));	
		}else{
			$data = \Cloudinary\Uploader::upload_large($fileContent,array("public_id" => $path.$fileName,"resource_type"=>"video","overwrite"=>TRUE));
			$data['original_filename'] = explode('/', $data['public_id']);
			$data['original_filename'] = end($data['original_filename']);
			
		}
		//print_r($data); die();
		return $data;
	}

	public function deleteFileFromCloudinary($public_id, $options = array()){
		$data = \Cloudinary\Uploader::destroy($public_id,$options);
		return $data;
	}

	public function deleteResourcesByPrefix($prefix){
		$api = new \Cloudinary\Api();
		/*print_r($api->delete_resources_by_prefix($prefix,array("invalidate" => TRUE)));
		\Cloudinary\Uploader::destroy('zombie', array("invalidate" => TRUE));
		exit;*/
		if($api->delete_resources_by_prefix($prefix,array("invalidate" => TRUE)))
			return true;
		return false;
	}

	public function deletePhotoInstancesByName($name, $keep_original = false){
		$api = new \Cloudinary\Api();
		//if($return = $api->delete_resources(array($name),array("keep_original" => $keep_original)) ){
		if($return = $api->delete_resources(array($name),array("keep_original" => $keep_original)) ){
			print_r($return);
			return true;
		}
			
		return false;
	}



	public function getResourcesFromCloudinary(){
		$api = new \Cloudinary\Api();
		$arr['public_ids'] = 'uploads/accounts/19a74935-9a88-11e6-bc05-0233682bb7a7/tags/19a7649b-9a88-11e6-bc05-0233682bb7a7/profile/photos/ddjjoy9078cswlon5.jpg';
		$data = $api->resources($arr);
		return $data;
	}

	/*public function isFileExistsInAws($uri){
		$bucket = 'livingtagsdev';
		$response = false;
		//check the file
		$response = $this->s3->getObjectInfo($bucket, $uri);
	    return $response;
	}*/

}
