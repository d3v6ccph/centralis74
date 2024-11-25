<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Curl_request extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model("upload_model", "file_upload");
	}
	
	function remote_database(){
		$post = $this->input->post();
		$data = array();
		
		if(isset($post["remote_key"]) && $post["remote_key"] == md5("direct_access")){
			$filePath = './uploads/files/databases/uploaded/sql/';
			
			$config = array();
			$config['upload_path'] = $filePath;
			$config['allowed_types'] = '*';
			
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('file_contents')){
				$data["response"] = false;
				$data["state"] = "error";
				$data["message"] = $this->upload->display_errors();
			}else{
				$uploaded = $this->upload->data();
				$fileName = $uploaded["file_name"];
				$file = realpath($filePath.$fileName);
				if (file_exists($file)){
					$lines = file($file);
					$statement = "";
					
					$this->db->query("SET FOREIGN_KEY_CHECKS=0;");
						foreach ($lines as $line){
							$statement .= $line;
							if (substr(trim($line), -1) === ';'){
								$this->db->simple_query($statement);
								$statement = "";
							}
						}
					$this->db->query("SET FOREIGN_KEY_CHECKS=1;");
				}
				
				$data["response"] = true;
				$data["state"] = "success";
				$data["upload_data"] = $uploaded;
			}			
		}else{
			$data["response"] = false;
			$data["state"] = "error";
			$data["message"] = "no data found!";
		}
		
		return $data;
	}
}