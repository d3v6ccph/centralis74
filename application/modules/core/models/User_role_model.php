<?php defined('BASEPATH') OR exit('No direct script access allowed');
class User_role_model extends CI_Model{
	protected $roleTable = "user_role";
	protected $roleAclTable = "user_role_acl";
	protected $aclTable = "access_control_list";
	protected $aclPrivTable = "acl_privilege";
	protected $privilegeListTable = "privilege_list";
	
	function __construct(){
		parent::__construct();
		$this->load->model("access_control_model", "acl_model");
		$this->load->model("datatable_model","dt_model");
	}
	function addUserRole(){
		$post = $this->input->post();
		if($post){
			$aclName = $this->checkAclName($post);
			if($aclName){
				if(!isset($post["role_code"])){ $post["role_code"] = $this->core_layout->generateCode(); }
				$insert = $this->db->insert($this->roleTable, $post);
				if($insert){
					$result["response"] = true; 
					$result["toastr_msg"] = "User role has been saved.";
				}else{
					$result["response"] = false;
					$result["toastr_msg"] = "Error in saving user role!";
				}
			}else{
				$result["response"] = false;
				$result["toastr_msg"] = "User role name already exist!";
			}
		}else{
			$result["response"] = false;
			$result["toastr_msg"] = "No available data for saving!";
		}
		
		return $result;
	}
	
	function updateUserRole(){
		$post = $this->input->post();
		$result = array();
		if(isset($post["id"]) && $post["id"]){
			$id = $post["id"]; unset($post["id"]);
			$update = $this->db->update($this->roleTable, $post, array("id"=>$id));
			if($update){
				$result["response"] = true; 
				$result["toastr_msg"] = "User role has been updated.";
			}else{
				$result["response"] = false;
				$result["toastr_msg"] = "Error in updating user role!";
			}
		}else{
			$result["response"] = false;
			$result["toastr_msg"] = "No available data for saving!";
		}
		
		return $result;
	}
	
	function removeUserRole(){
		$post = $this->input->post();
		$result = array();
		if(isset($post["id"]) && $post["id"]){
			$this->db->delete($this->roleTable, array('id'=>$post['id']));
			if (!$this->db->affected_rows()) {
				$result["response"] = false;
				$result["toastr_msg"] = "Error! ID [{$post['id']}] not found";
			} else {
				$result["response"] = true;
				$result["toastr_msg"] = "User role has been removed.";
			}
		}else{
			$result["response"] = false;
			$result["toastr_msg"] = "No available data for removal!";
		}
		
		return $result;
	}
	
	function getUserRoleList(){
		$post = $this->input->post();
		if($post){
			$columns = array("id", "name", "description", "status");
			$dir = $post["order"][0]["dir"];
			$order = $columns[$post["order"][0]["column"]];
			$draw = (isset($post['draw']) && $post['draw'])? $post['draw']: 0;
			$start = (isset($post["start"]) && $post["start"])? $post["start"]: 0;
			$limit = (isset($post["length"]) && $post["length"])? $post["length"]: 0;
			$searchValue = (isset($post["search"]["value"]) && $post["search"]["value"])? $post["search"]["value"]: "";
			$dtAccessControl = $this->dt_model->dataTable();
			$dtAccessControl->setTable($this->roleTable);
			$dtAccessControl->setParameterFields($columns);
			
			$totalData = $dtAccessControl->dtAllPostsCount();
			$totalFiltered = $totalData;
			
			if(empty($searchValue)){            
				$posts = $dtAccessControl->dtAllPosts($limit, $start, $order, $dir);
			}else {
				$posts = $dtAccessControl->dtSearch($limit, $start, $searchValue, $order, $dir);
				$totalFiltered = $dtAccessControl->dtPostSearchCount($searchValue);
			}
			
			$data = array();
			if(!empty($posts)){
				foreach ($posts as $pst){
					$nestedData['id'] = $pst->id;
					$nestedData['name'] = $pst->name;
					$nestedData['description'] = $pst->description;
					$nestedData['status'] = $pst->status;
					$data[] = $nestedData;
				}
			}
			$json_data = array(
                    "draw" => intval($draw),  
                    "recordsTotal" => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data,   
                    );
            
			return $json_data;
		}else{
			return array(
				"draw"=>1,
				"recordsTotal"=>0,
				"recordsFiltered"=>0,
				"data"=>array(),
				);
		}
	}
	function getRoleUpdate(){
		$post = $this->input->post();
		$resultset = array();
		if(isset($post["id"]) && $post["id"]){
			$query = $this->db->get_where($this->roleTable, $post);
			if($query->num_rows() == 1){
				$resultset["response"] = true;
				$resultset["value"] = $query->row_array();
			}else{
				$resultset["response"] = false;
			}
		}else{
			$resultset["response"] = false;
		}
		return $resultset;
	}
	function getAssignedRole(){
		$post = $this->input->post();
		$resultset = array();
		if(isset($post["id"]) && $post["id"] || $post["id"] == "0"){
			$query = $this->db->get_where($this->roleTable, $post);
			if($query->num_rows() > 0){
				$includes = array("id", "name", "label", "url", "icon", "identifier");
				$arrData["row"] = $query->row_array();
				
				$roles = $this->getAssignedUserRoleList($post["id"]);
				$aclData = $this->acl_model->accessControlJson();
				$html = $this->load->view("core/roles/modal_content/list", $arrData, true);
				
				$resultset["html"] = $html;
				$resultset["data"] = $aclData;
				$resultset["role_id"] = $roles;
				$resultset["response"] = true;
			}else{
				$resultset["response"] = false;
			}
		}else{
			$resultset["response"] = false;			
		}
		
		return $resultset;
	}	
	function setAssignedUserRole(){
		$post = $this->input->post();
		$resultset = array();
		if(isset($post["nodes"], $post["id"]) && $post["nodes"] && $post["id"] || $post["id"] == "0"){
			$idx = $post["id"];
			$nodes = json_decode($post["nodes"], true);
			$ids = array();
			foreach($nodes as $key => $value){
				$selected = $value["state"]["selected"];
				$parentId = intval($value["parent"]);
				$id = intval($value["id"]);
				
				if($parentId !== 0 && $selected && !in_array($parentId, $ids)){ $ids[] = $parentId; }
				if($selected){ $ids[] = $id; }
			}
			
			$ids = array_unique($ids);
			
			$setRole = $this->setUserRoleAcl($idx, $ids);
			if($setRole){
				$resultset["toastr_msg"] = "Success";
				$resultset["response"] = true;				
			}else{
				$resultset["toastr_msg"] = "Error";
				$resultset["response"] = false;	
			}
		}else{
			$resultset["toastr_msg"] = "Failed";
			$resultset["response"] = false;
		}
		
		return $resultset;
	}
	
	function setAssignedUserRoleActions(){
		$post = $this->input->post();
		$resultset = array();
		if(isset($post["nodes"], $post["id"]) && $post["nodes"] && ($post["id"] || $post["id"] == "0")){
			$idx = $post["id"];
			$nodes = json_decode($post["nodes"], true);
			$ids = array();
			foreach($nodes as $key => $value){
				$selected = $value["state"]["selected"];
				$parentId = intval($value["parent"]);
				$id = $value["id"];
				
				if($parentId !== 0 && $selected && !in_array($parentId, $ids)){ $ids[] = $parentId; }
				if($selected){ $ids[] = $id; }
			}
			
			$ids = array_unique($ids);
			
			$setRole = $this->setUserRoleAclActions($idx, $ids);
			if($setRole){
				$resultset["toastr_msg"] = "Success";
				$resultset["response"] = true;				
			}else{
				$resultset["toastr_msg"] = "Error";
				$resultset["response"] = false;	
			}
		}else{
			$resultset["toastr_msg"] = "Failed";
			$resultset["response"] = false;
		}
		
		return $resultset;
	}
	function setUserRoleAclActions($id=null, $ids=array()){
		if($id || $id=="0"){
			$query = $this->db->get_where($this->roleTable, array("id"=>$id, "status"=>1));
			if($query->num_rows() > 0){
				$row = $query->row_array();
				$arrData = array();
				$arrData["privilege_resource"] = serialize($ids);
				
				$whereData = array();
				$whereData["role_id"] = $row["id"];
				
				$update = $this->db->update($this->roleAclTable, $arrData, $whereData);
				if($update){ return true; }
				else{ return false; }
			}else{ return false; }	
		}else{ return false; }
	}
	
	function setUserRoleAcl($id=null, $ids=array()){
		if($id || $id == "0"){
			$query = $this->db->get_where($this->roleTable, array("id"=>$id, "status"=>1));
			if($query->num_rows() > 0){
				$row = $query->row_array();
				$arrData = array();
				$arrData["role_id"] = $row["id"];
				$arrData["name"] = $row["name"];
				$arrData["role_resource"] = serialize($ids);
				
				$querySearch = $this->db->get_where($this->roleAclTable, array("role_id"=>$row["id"]));
				if($querySearch->num_rows() == 1){
					$rowSearch = $querySearch->row_array();
					$where = array();
					$where["id"] = $rowSearch["id"];
					
					$nData = array();
					$nData["role_resource"] = $arrData["role_resource"];
					$update = $this->db->update($this->roleAclTable, $nData, $where);
					if($update){ return true; }
					else{ return false; }
				}else{
					$saved = $this->db->insert($this->roleAclTable, $arrData);
					if($saved){ return true; }
					else{ return false; }
				}
			}else{
				return false;
			}	
		}else{
			return false;
		}
	}
	
	function getRolePrivilegeData(){
		$post = $this->input->post();
		$resultset = array();
		if(isset($post["id"]) && ($post["id"] || $post["id"] == "0")){
			$query = $this->db->get_where($this->roleTable, array("id"=>$post["id"]));
			if($query->num_rows() == 1){
				$row = $query->row_array();
				if($row){
					$arrData = array();
					$arrData["row"] = $row;
					
					$html = $this->load->view("core/roles/modal_content/list_actions", $arrData, true);
					$data = $this->getRoleResource($post["id"]);
					$actions = $this->getAssignedUserRoleActionList($post["id"]);
					$resultset["html"] = $html;
					$resultset["data"] = $data;
					$resultset["role_id"] = $actions;
					$resultset["response"] = true;
				}else{					
					$resultset["response"] = false;
				}
			}else{
			$resultset["response"] = false;
			}
		}else{
			$resultset["response"] = false;
		}
		
		return $resultset;
	}
	
	function getRoleResource($id=null){
		$arrData = array();
		if($id || $id == "0"){
			$query = $this->db->get_where($this->roleAclTable, array("role_id"=>$id));
			if($query->num_rows() == 1){
				$row = $query->row_array();
				$roleResource = unserialize($row["role_resource"]);
				if($roleResource){
					
					foreach($roleResource as $id){
						$queryAcl = $this->db->get_where($this->aclTable, array("id"=>$id, "is_active"=>1));
						if($queryAcl->num_rows() == 1){
							$row = $queryAcl->row_array();
							
							
							$data = array();
							$data["id"] = $row["id"];
							$data["text"] = $row["label"];
							$data["parent"] = "#";
							$data["a_attr"]["class"] = "no_checkbox";
							
							$queryAclPriv = $this->db->get_where($this->aclPrivTable, array("acl_id"=>$row["id"]));
							if($queryAclPriv->num_rows() == 1){
								$rowAclPriv = $queryAclPriv->row_array();
								$aclResource = unserialize($rowAclPriv["acl_resource"]);
								if($aclResource){
									$arrData[] = $data;
									foreach($aclResource as $idx){
										$queryList = $this->db->get_where($this->privilegeListTable, array("id"=>$idx, "status"=>1));
										if($queryList->num_rows() == 1){
											$rowx = $queryList->row_array();
											$datax = array();
											
											$datax["id"] = $row["id"]."-".$rowx["id"];
											$datax["text"] = $rowx["label"];
											$datax["parent"] = $row["id"];
											$arrData[] = $datax;
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return $arrData;
	}
	
	function getAssignedUserRoleList($id=null){
		if($id || $id == "0"){
			$query = $this->db->get_where($this->roleAclTable, array("role_id"=>$id));
			if($query->num_rows() == 1){
				$row = $query->row_array();
				return unserialize($row["role_resource"]);
			}else{
				return array();
			}
		}else{
			return array();
		}
	}
	
	function getAssignedUserRoleActionList($id=null){
		if($id || $id=="0"){
			$query = $this->db->get_where($this->roleAclTable, array("role_id"=>$id));
			if($query->num_rows() == 1){
				$row = $query->row_array();
				return unserialize($row["privilege_resource"]);
			}else{
				return array();
			}
		}else{
			return array();
		}
	}
	private function checkAclName($data=array()){
		if($data){
			$query = $this->db->get_where($this->roleTable, array("name"=>$data["name"]));
			if($query->num_rows() == 0){ return true; }
			else{ return false; }
		}else{ return false; }
	}
}