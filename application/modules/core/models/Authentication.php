<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Authentication extends CI_Model{
	protected $usersTable = "tblusers";
	protected $roleAclTable = "user_role_acl";
	private $user_data = array();
	protected $mdb;

	function __construct(){
		parent::__construct();
		$this->mdb = $this->load->database('master', true);
		$this->user_data = $this->session->userdata("logged_in");
	}
	public function doRedirect(){
		$loginUrl = login_url();
		if(!$this->user_data && $loginUrl){
			$uri = $this->uri->uri_string();
			
			if($uri){ $this->set_cookie_uri(base_url(), $uri); }
			else{ $this->destroy_cookie_uri(); }
			
			redirect($loginUrl, "refresh");
		}
	}

	public function set_cookie_uri($baseUrl=null, $uriString=null){
		if($uriString){
			$cookieUrl = array(
				'name'=>'base_url',
				'value'=>$baseUrl,
				'expire'=>10000,
			);
			$cookieUri= array(
				'name'=>'uri_string',
				'value'=>$uriString,
				'expire'=>10000
			);
			$this->input->set_cookie($cookieUrl);
			$this->input->set_cookie($cookieUri);
			return true;
		}else{
			return false;
		}
	}
	public function get_cookie_uri(){
	  $cookieUrl = $this->input->cookie('base_url',true);
	  $cookieUri = $this->input->cookie('uri_string',true);
		if($cookieUrl && $cookieUri){
			$url = $cookieUrl."".$cookieUri;
			return $url;
		}else{ 
			return false;
		}
	}
	public function destroy_cookie_uri(){
		 delete_cookie('base_url');
		 delete_cookie('uri_string');
		 return true;
	}
	
	public function getCurrentUser(){ return $this->user_data; }
	public function getUserData($include = array()){
		$currentUser = $this->getCurrentUser();
		if($currentUser){
			if($include){
				$selectQuery = implode(",",$include);
				$this->mdb->select($selectQuery);
			}
			$this->mdb->where("id", $currentUser["emp_id"]);
			$this->mdb->from("tblemployees");
			$query = $this->mdb->get();
			if($query->num_rows() > 0){
				return $query->row_array();
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function getUserProfile(){
		$include = array(
			"id", "idno", "biometricno", "lastname",
			"firstname", "middlename", "email", "position",
			"employee_status", "curr_addr", "str_addr", "city",
			"postal", "pic_filename"
		);
		$user = $this->getUserData($include);
		if($user){
			foreach($user as $key => $value){
				if($key == "pic_filename"){
					$findImage = realpath("../web/uploads/files/images/employee_files/empcode_{$user['id']}/{$value}");
					$nValue = ($value && file_exists($findImage))? $value: "defaultAvatar.png";
					$tempUrl = ($value && file_exists($findImage))?
						base_url("../web/uploads/files/images/employee_files/empcode_{$user['id']}/{$nValue}"): base_url("uploads/images/{$nValue}");
					$user[$key] = $tempUrl;
				}
			}
			$userName = $this->getUserName($user);
			$user["display_name"] = ($userName)? $userName: "No Assigned Name";
			return $user;
		}else{
			return false;
		}
	}
	
	private function getUserName($arrData=array()){
		if(isset($arrData["lastname"], $arrData["firstname"], $arrData["middlename"])){
			$lname = (isset($arrData["lastname"]) && $arrData["lastname"])? $arrData["lastname"]:"";
			$fname = (isset($arrData["firstname"]) && $arrData["firstname"])? $arrData["firstname"]:"";
			$mname = (isset($arrData["middlename"]) && $arrData["middlename"])? $arrData["middlename"]:"";
			$userName = "{$fname} {$mname} {$lname}";
			return ucwords(strtolower($userName));
		}else{
			return "No Assigned Name";
		}
	}
	
	public function getUserId(){
		$userData = $this->user_data;
		if(isset($userData["emp_id"]) && $userData["emp_id"]){
			return $userData["emp_id"];
		}else{
			return false;
		}
	}
	public function getRoleId(){
		$userId = $this->getUserId();
		if($userId){
			$where = array("emp_id"=>$userId);
			$query = $this->db->get_where($this->usersTable, $where);
			if($query->num_rows() == 1){
				$row = $query->row_array();
				return $row["role_id"];
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public function getRoleResource(){
		$roleId = $this->getRoleId();
		$arrIds = array();
		if($roleId || $roleId == "0"){
			$query = $this->db->get_where($this->roleAclTable, array("role_id"=>$roleId));
			if($query->num_rows() == 1){
				$row = $query->row_array();
				$roleResource = $row["role_resource"];
				if($roleResource){
					$arrIds = unserialize($roleResource);
				}
			}
		}
		return $arrIds;
	}
}
