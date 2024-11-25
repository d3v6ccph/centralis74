<?php defined('BASEPATH') OR exit('No direct script access allowed');

    class Email_model extends CI_Model {
        protected $loggedUser;
        protected $loggedUserName;

        function __construct() {
            parent::__construct();
            $this->loggedUser = $this->session->userdata('logged_in');
            $this->loggedUserName = $this->loggedUser["firstname"] . " " . $this->loggedUser["lastname"];
        }

		function emailLookup(){
			$get = $this->input->get();
			$resultarray = array();
			if(isset($get['q'])){
				$query = $this->db->query("SELECT a.id, b.firstname, b.lastname, b.middlename, b.suffix, a.email FROM gccmaster.tblusers a, gccmaster.tblemployees b WHERE b.id=a.emp_id AND (a.email !='NO EMAIL ADDRESS' AND a.email !='NO EMAIL' AND a.email !='' AND b.employee_status = 'Active') AND (b.firstname LIKE '%{$get['q']}%' || b.lastname LIKE '%{$get['q']}%' || a.email LIKE '%{$get['q']}%') ORDER BY b.firstname ASC LIMIT 10");
			}else{
				$query = $this->db->query("SELECT a.id, b.firstname, b.lastname, b.middlename, b.suffix, a.email FROM gccmaster.tblusers a, gccmaster.tblemployees b WHERE b.id=a.emp_id AND (a.email !='NO EMAIL ADDRESS' AND a.email !='NO EMAIL' AND a.email !='' AND b.employee_status = 'Active') ORDER BY b.firstname ASC LIMIT 10");
			}
			if($query->num_rows() > 0){
				foreach($query->result_array() as $_query){
					$data = array();
					$tempRs = (array) $_query;
					$data["id"] = $_query["email"];
					$data["text"] =  $_query["email"]." | ".$_query["firstname"] . " " . $_query["lastname"];
					$resultarray[] = $data;
				}
			}
			return array("results"=>$resultarray);
		}

		function createNew(){
			$data = array();
			$result = array();
			$post = $this->input->post();
			$data["send_to"] = isset($post["send_to"]) ? serialize($post["send_to"]) : array();
			$data["cc_to"] = isset($post["cc_to"]) ? serialize($post["cc_to"]) : array();
			$data["bcc_to"] = isset($post["bcc_to"]) ? serialize($post["bcc_to"]) : array();
			$data["created_by"] = $this->loggedUser["emp_id"];
			$data["name"] = $post["name"];
			$data["description"] = $post["description"]; 
			$data["status"] = 1; 
			
			$query = $this->db->insert("email_template",$data);
			if($query){
				$this->core_layout->saveLog("Email template ".$data['name']." has been created", $this->loggedUserName);

				$result["response"] = true;
				$result["toastr_msg"] = "Email template has been saved.";
			}else{
				$result["response"] = false;
				$result["toastr_msg"] = "Error in saving email template!";
			}
			return $result;
			
		}

		function getEmailCollection(){
			$result = array();
			$this->db->select("*");
			$this->db->from("email_template");
			$this->db->where("status","1");
			$query = $this->db->get();

			if($query->num_rows() > 0){
				foreach($query->result_array() as $_query){
					$data = array();
					$data["id"] = $_query["id"];
					$data["name"] = $_query["name"];
					$data["description"] = $_query["description"];
					$data["send_to"] = isset($_query["send_to"]) ? unserialize($_query["send_to"]) : array();
					$data["cc_to"] = isset($_query["cc_to"]) ? unserialize($_query["cc_to"]) : array();
					$data["bcc_to"] = isset($_query["bcc_to"]) ? unserialize($_query["bcc_to"]) : array();
					$data["status"] = $_query["status"] = 1 ? '<span class="m-badge m-badge--success m-badge--wide">Active</span>' : '<span class="m-badge m-badge--danger m-badge--wide">Inactive
				</span>';
					$result[] = $data;
				}
			}

			return array("data"=>$result);
		}

		function explodeEmailArray($emails = array()){
			$email_count = count($emails);

			if($email_count > 1){
				$res = "";
				foreach($emails as $_emails):
					$res += $_emails.", ";
				endforeach;
			}
		}

		function getEmail(){
			$result = array();
			$post = $this->input->post();
			$this->db->select("*");
			$this->db->from("email_template");
			$this->db->where("id",$post["id"]);
			$query = $this->db->get();
			$rowdata = $query->row_array();

			$data = array();
			$data["id"] = $rowdata["id"];
			$data["name"] = $rowdata["name"];
			$data["description"] = $rowdata["description"];
			$data["status"] = $rowdata["status"];
			$data["send_to"] = unserialize($rowdata["send_to"]);
			$data["cc_to"] = unserialize($rowdata["cc_to"]);
			$data["bcc_to"] = unserialize($rowdata["bcc_to"]);

			if($query){
				$result["data"] = $data;
				$result["response"] = TRUE;
			}else{
				$result["response"] = FALSE;
			}
			return $result;
		}

		function updateEmail(){
			$post = $this->input->post();
			$data = array();
			$result = array();

			$data["send_to"] = isset($post["send_to"]) ? serialize($post["send_to"]) : array();
			$data["cc_to"] = isset($post["cc_to"]) ? serialize($post["cc_to"]) : array();
			$data["bcc_to"] = isset($post["bcc_to"]) ? serialize($post["bcc_to"]) : array();
			$data["updated_by"] = $this->loggedUser["emp_id"];
			$data["updated_at"] = date("Y-m-d h:m:s");
			$data["name"] = $post["name"];
			$data["description"] = $post["description"]; 
			$data["status"] = $post["status"]; 

			$update = $this->db->update("email_template", $data, array("id" => $post["id"]));

			if($update){
				$this->core_layout->saveLog("Email template ".$data['name']." has been updated", $this->loggedUserName);

				$result["response"] = true;
				$result["toastr_msg"] = "Email template has been updated.";
			}else{
				$result["response"] = false;
				$result["toastr_msg"] = "Error in updating email template!";
			}

			return $result;
		}

		function sendEmail(){
			$post = $this->input->post();
			$emailTemplateData = $this->getEmailTemplateData($post["id"]);
			if($emailTemplateData){
				return $this->email_send($emailTemplateData["name"]);
			}else{
				return array("status"=> FALSE,"toastr_msg"=>"Failed to send email!", "toastr_status" => "error");
			}
			
		}

		function getEmailTemplateData($id){
			$this->db->select("*");
			$this->db->from("email_template");
			$this->db->where("id",$id);
			$query = $this->db->get();
			return $query->num_rows() > 0 ? $query->row_array() : FALSE;
		}

		function getNoMinimumLevelStocks(){
			$this->db->select("items.id,items.sku,items.name,items.category_id,items.priority_id,category.name as c_name,priority.name as p_name");
			$this->db->from("items");
			$this->db->join("category","category.id = items.category_id");
			$this->db->join("priority","priority.id = items.priority_id");
			$this->db->where("items.min_qty",0);
			$this->db->where("items.status",1);
			$query = $this->db->get();
			return $query->num_rows() > 0 ? $query->result_array() : array();
		}

		function getNoReOrderQtyLevelStocks(){
			$this->db->select("items.id,items.sku,items.name,items.category_id,items.priority_id,category.name as c_name,priority.name as p_name");
			$this->db->from("items");
			$this->db->join("category","category.id = items.category_id");
			$this->db->join("priority","priority.id = items.priority_id");
			$this->db->where("items.reorder_qty_level",0);
			$this->db->or_where("items.reorder_qty_level",NULL);
			$this->db->where("items.status",1);
			$query = $this->db->get();
			return $query->num_rows() > 0 ? $query->result_array() : array();
		}

		function getBelowMinimumLevelStocks(){
			$this->db->select("items.id,items.sku,items.name,items.category_id,items.priority_id,category.name as c_name,priority.name as p_name");
			$this->db->from("items");
			$this->db->join("category","category.id = items.category_id");
			$this->db->join("priority","priority.id = items.priority_id");
			$this->db->where("items.min_qty <","qty");
			$this->db->where("items.status",1);
			$query = $this->db->get();
			return $query->num_rows() > 0 ? $query->result_array() : array();
		}

		function getBelowCriticalLevelStocks(){
			$data = array();
			$this->db->select("items.id,items.sku,items.name,items.category_id,items.priority_id,imtes.critical_level_percentage,items.min_qty,category.name as c_name,priority.name as p_name");
			$this->db->from("items");
			$this->db->join("category","category.id = items.category_id");
			$this->db->join("priority","priority.id = items.priority_id");
			$this->db->where("items.status",1);
			$query = $this->db->get();

			if($query->num_rows() > 0):
				foreach($query->result_array() as $_query):
					if($this->getCriticalLevel($_query["min_qty"], $_query["critical_level_percentage"]) < $_query["min_qty"]):
						$d = array();
						$d["name"] = $_query["name"];
						$d["sku"] = $_query["sku"];
						$d["c_name"] = $_query["c_name"];
						$d["p_name"] = $_query["p_name"];
					endif;
					$data[] = $d;
				endforeach;
			endif;

			return $data;

		}

		function getCriticalLevel($min_qty, $crit_percent){
			return $min_qty * ($crit_percent / 100);
		}

		function getEmailTemplatesCollection(){
			$this->db->select("*");
			$this->db->from("email_template");
			$this->db->where("status",1);
			$query = $this->db->get();
			return $query->num_rows() > 0 ? $query->result_array() : array();
		}

		function email_send($email_type = ""){
			$resultset = array();
			$emailTo = array();
			$message = "";


			switch($email_type){
				case "inventory_no_minimum_level_stocks":
					$email_data["data"] = $this->getNoMinimumLevelStocks();
					$email_data["email_title"] = "No minimum level stocks";
				break;
				case "inventory_no_reorder_level_stocks":
					$email_data["data"] = $this->getNoReOrderQtyLevelStocks();
					$email_data["email_title"] = "No minimum re-order level stocks";
				break;
				case "inventory_below_minimum_level":
					$email_data["data"] = $this->getBelowMinimumLevelStocks();
					$email_data["email_title"] = "Below minimum level stocks";
				break;
				default:
					$email_data["data"] = array();
				break;

			}
			

			$message .= $this->load->view("email/email_template", $email_data, true);
		 
			$module = $email_type;
			$email_title = "Inventory System";
			$content_title = $email_type;
			$content = $message;
			
			$overrideMailer = array();
			/*** $overrideMailer["email_user"] = "gccphtest@gmail.com";
			$overrideMailer["email_pass"] = "developer"; ***/
			if($emailTo){ $overrideMailer["send_to"] = $emailTo; }
			
			$sent = $this->core_layout->send_email($module, $email_title, $content_title, $content, $overrideMailer);
			if($sent){
				$resultset["status"] = true;				
				$resultset["toastr_msg"] = $email_title." , email has been sent";
				$resultset["toastr_status"] = "success";
				
				$this->core_layout->logNotification($email_title ." , email has been sent", "success");
			}else{
				$resultset["status"] = false;
				$resultset["toastr_msg"] = $email_title .", failed to send email!";
				$resultset["toastr_status"] = "error";
				
				$this->core_layout->logNotification($email_title .", failed to send email!", "error");
			}
			return $resultset;			
		}

		function getCoreSetup(){
			$this->db->select("*");
			$this->db->from("core_setup");
			$query = $this->db->get();

			return $query->row_array();
		}

		function updateProtocol(){
			$post = $this->input->post();
			$post["updated_by"] = $this->loggedUser["emp_id"];
			if($this->checkIfProtocolExist() > 0){
				$query = $this->db->update("email_settings", $post, array("id" => $post["id"]));
			}else{
				$query = $this->db->insert("email_settings",$post);
			}

			if($query){
				$result["data"] = true;
				$result["response"] = true;
				$result["toastr_msg"] = "Email Protocol Setting has been updated.";
			}else{
				$result["response"] = false;
				$result["toastr_msg"] = "Error in updating email protocol setting!";
			}

			return $result;
		}

		private function checkIfProtocolExist(){
			$query = $this->db->query('SELECT * FROM email_settings');
			return $query->num_rows();
		}

		function getProtocolSetting(){
			$query = $this->db->query("SELECT * FROM email_settings LIMIT 1");
			return $query->row_array();
		}

	}
