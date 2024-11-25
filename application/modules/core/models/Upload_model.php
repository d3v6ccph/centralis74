<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Upload_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	function uploadFile($config=array()){
		$resultset = array();
		$files = (isset($_FILES["files"]) && $_FILES["files"])? $_FILES["files"]: false;
		if($config){
			$this->upload->initialize($config);
			
			if($files){
				foreach($files["name"] as $key => $image){
					$_FILES["files"]["name"] = $files["name"][$key];
					$_FILES["files"]["type"] = $files["type"][$key];
					$_FILES["files"]["tmp_name"] = $files["tmp_name"][$key];
					$_FILES["files"]["error"] = $files["error"][$key];
					$_FILES["files"]["size"] = $files["size"][$key];
				}
				
				if (!$this->upload->do_upload('files')){
						$error = array('error' => $this->upload->display_errors());
						$resultset["response"] = false;
						$resultset["data"] = $error;
						$resultset["message"] = $this->upload->display_errors();
				}else{
					$data = $this->upload->data();
					if($data){
						$resultset["response"] = true;
						$resultset["files"][] = $data;
						$resultset["message"] = "upload file successful.";
					}else{
						$resultset["response"] = false;
						$resultset["message"] = "no data to upload!";
					}
				}
			}else{
				$resultset["response"] = false;				
				$resultset["message"] = "no file to upload!";
			}
		}else{
			$resultset["response"] = false;
			$$resultset["message"] = "upload configuration was not set!";
		}
		
		return $resultset;
	}
	
	function uploadFileCurl($id=null, $md5=null){
		if($id){
			$this->db->select("site_url");
			$this->db->limit(1);
			$query = $this->db->get("core_setup");
			
			if($query->num_rows() == 1){
				$siteUrl = $query->row()->site_url;
				/*** $dummyUrl = site_url("core/curl_request/remote_database"); ***/
				if($siteUrl){
					$dummyUrl = $siteUrl;
					$query = $this->db->get_where("backup_database", array("id"=>$id));
					if($query->num_rows() == 1){
						$row = $query->row();
						
						$fileName = $row->filename;
						$filePath = $row->filepath;
						
						$file = realpath("{$filePath}{$fileName}");
						
						$cFile = curl_file_create($file, 'text/plain');
						
						$postData = array();
						$postData["remote_key"] = $md5;
						$postData["file_contents"] = $cFile;
						
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $dummyUrl);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
						
						$result = curl_exec($ch);
						$curlinfo = curl_getinfo($ch);
						curl_close($ch);
						
						if($curlinfo["http_code"] == 200){
							return true;
						}else{
							return false;
						}
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;				
			}
		}else{
			return false;
		}
	}
}