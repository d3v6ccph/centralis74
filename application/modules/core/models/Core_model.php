<?php defined('BASEPATH') OR exit('No direct script access allowed');

    class Core_model extends CI_Model {
        private $jsList = array(), $cssList = array();
        private $title, $headerTitle, $bodyClass, $privilegeName;
		protected $emailProtocolTable = "email_settings";

        private $userdata = array();

        function __construct() {
            parent::__construct();
            $this->load->model("core/access_control_model", "acl_model");
            $this->load->helper('file');

            if ($this->session->userdata("logged_in")) {
                $this->userdata = $this->session->userdata("logged_in");
            }
        }

        function logNotification($notification = null, $status = "success", $module = "portal",$type="system") {
            if ($notification) {
                $data = array();
                $data["module"] = $module;
                $data["notification"] = $notification;
                $data["status"] = $status;
                $data["type"] = $type;
                $data["ip_address"] = $_SERVER["REMOTE_ADDR"];

                $save = $this->db->insert("log_notification", $data);
				return $save ? true : false;
            } else {
                return false;
            }
        }

        function getLogNotification($module = null) {
            $this->db->from("log_notification");
            if ($module) {
                $this->db->where("module", $module);
            }

            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
        }

        function addJs($path = null) {
            if ($path) {
                $this->jsList[] = $path;
                return $this;
            }
        }

        function addCss($path = null) {
            if ($path) {
                $this->cssList[] = $path;
                return $this;
            }
        }

        function getStoredJs() {
            $html = "";
            if ($this->jsList) {
                foreach ($this->jsList as $list) {
                    $filePath = realpath("./assets/{$list}");
                    if (file_exists($filePath)) {
                        $currentUrl = base_url("assets/{$list}");
                        $html .= "<script src='{$currentUrl}'></script>";
                    }
                }
            }
            return $html;
        }

        function getStoredCss() {
            $html = "";
            if ($this->cssList) {
                foreach ($this->cssList as $list) {
                    $filePath = realpath("./assets/{$list}");
                    if (file_exists($filePath)) {
                        $currentUrl = base_url("assets/{$list}");
                        $html .= "<link href='{$currentUrl}' rel='stylesheet' type='text/css' />";
                    }
                }
            }
            return $html;
        }

        function setPageTitle($title = null) {
            if ($title) {
                $this->title = $title;
                return $this;
            }
        }

        function getPageTitle() {
            $html = "";
            if ($this->title) {
                $html .= $this->title;
            }
            return $html;
        }

        function setHeaderTitle($title = null) {
            if ($title) {
                $this->headerTitle = $title;
                return $this;
            }
        }

        function getHeaderTitle() {
            $html = "";
            if ($this->headerTitle) {
                $html .= $this->headerTitle;
            }
            return $html;
        }

        function setBodyClass($class = null) {
            if ($class) {
                $this->bodyClass = $class;
                return $this;
            }
        }

        function getBodyClass() {
            $html = "";
            if ($this->bodyClass) {
                $html .= $this->bodyClass;
            }
            return $html;
        }

        function hasBodyClass() {
            return ($this->bodyClass !== "") ? true : false;
        }

        function setPrivilegeName($name = null) {
            if ($name) {
                $this->privilegeName = $name;
                return $this;
            }
        }

        function getPrivilegeName() {
            if (!$this->privilegeName) return false;
            return $this->privilegeName;
        }

        function getSidebarNavigation($includes = array(), $isActive = 0) {
            $menuItems = array();
            $arrData = $this->acl_model->getAccessControlMenu($includes, $isActive);
            $menuItems["aclMenu"] = $arrData;
            $menuItems["roleResource"] = $this->authenticate->getRoleResource();

            return $this->load->view("core/access_control/html/side_nav", $menuItems, true);
        }

        function generatePrivileges() {
            $arrData = array();
            $id = $this->authenticate->getRoleId();
            $query = $this->db->get_where("user_role_acl", array("role_id" => $id));
            if ($query->num_rows() > 0) {
                $row = $query->row();

                $privilege = unserialize($row->privilege_resource);
                if ($privilege) {
                    foreach ($privilege as $vv) {
                        $isNode = strpos($vv, "-");
                        if ($isNode == true) {
                            $dd = explode("-", $vv);
                            if (count($dd) == 2) {
                                $aclId = $dd[0];
                                $privilegeId = $dd[1];

                                $acl = $this->db->get_where("access_control_list", array("id" => $aclId));
                                if ($acl->num_rows() == 1) {
                                    $rowAcl = $acl->row();

                                    $privilegeData = $this->db->get_where("privilege_list", array("id" => $privilegeId));
                                    if ($privilegeData->num_rows() == 1) {
                                        $rowPriv = $privilegeData->row();
                                        $privName = strtolower($rowPriv->name);
                                        $arrData[$rowAcl->name][] = $privName;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return $arrData;
        }

        function generatePrivilegeAction() {
            $arrData = array();
            $id = $this->authenticate->getRoleId();
            $query = $this->db->get_where("user_role_acl", array("role_id" => $id));
            if ($query->num_rows() > 0) {
                $row = $query->row();

                $privilege = unserialize($row->privilege_resource);
                if ($privilege) {
                    foreach ($privilege as $vv) {
                        $isNode = strpos($vv, "-");
                        if ($isNode == true) {
                            $dd = explode("-", $vv);
                            if (count($dd) == 2) {
                                $aclId = $dd[0];
                                $privilegeId = $dd[1];

                                $acl = $this->db->get_where("access_control_list", array("id" => $aclId));
                                if ($acl->num_rows() == 1) {
                                    $rowAcl = $acl->row();

                                    $privilegeData = $this->db->get_where("privilege_list", array("id" => $privilegeId));
                                    if ($privilegeData->num_rows() == 1) {
                                        $rowPriv = $privilegeData->row();
                                        $privName = ucwords(strtolower($rowPriv->name));
                                        $arrData[$rowAcl->name][] = "btn{$privName}";
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return $arrData;
        }

        function getCurrentActions() {
            $actions = array();
            $currentActions = $this->generatePrivileges();
            $privilegeName = $this->getPrivilegeName();
            if ($privilegeName) {
                if (isset($currentActions[$privilegeName]) && $currentActions[$privilegeName]) {
                    $actions = $currentActions[$privilegeName];
                }

            }

            if (isset($currentActions["global_privileges"]) && $currentActions["global_privileges"]) {
                foreach ($currentActions["global_privileges"] as $privilege) {
                    if (!in_array($privilege, $actions)) {
                        $actions[] = $privilege;
                    }
                }
            }

            $defaultPrivileges = $this->core_layout->listXml();
            if ($defaultPrivileges) {
                foreach ($defaultPrivileges as $privilege) {
                    if (!in_array($privilege, $actions)) {
                        $actions[] = $privilege;
                    }
                }
            }

            return $actions;
        }

        function listXml() {
            $arrData = array();
            $RoleId = $this->authenticate->getRoleId();
            if ($RoleId && $RoleId == 1) {
                $xmlFile = realpath('assets/static/xml/privileges/admin_privilege.xml');
                if (file_exists($xmlFile)) {
                    $xmlstr = file_get_contents($xmlFile);
                    $sitemap = new SimpleXMLElement($xmlstr);
                    $privileges = $sitemap->privilege->data;
                    if ($privileges) {
                        $arrData = (array)$privileges;
                    }
                }
            }

            return $arrData;
        }

        function getCurrentSession() {
            if (!$this->userdata) return false;
            return $this->userdata;
        }

        function getUserLoggedIn() {
            if (!$this->userdata) return false;

            $loggedIn = $this->userdata;
            if ($loggedIn) {
                $lastname = (isset($loggedIn["lastname"]) && $loggedIn["lastname"]) ? $loggedIn["lastname"] : "";
                $firstname = (isset($loggedIn["firstname"]) && $loggedIn["firstname"]) ? $loggedIn["firstname"] : "";
                $middlename = (isset($loggedIn["middlename"]) && $loggedIn["middlename"]) ? $loggedIn["middlename"] : "";

                $arrData = array();
                $arrData["id"] = $loggedIn["id"];
                $arrData["employee_id"] = $loggedIn["emp_id"];
                $arrData["username"] = $loggedIn["username"];
                $arrData["display_name"] = "{$firstname} {$lastname}";
                $arrData["email"] = $loggedIn["email"];
                $arrData["company"] = $loggedIn["company"];
                $arrData["department"] = $loggedIn["department"];
                $arrData["group_id"] = $loggedIn["group_id"];

                return $arrData;
            }
        }

        /*** new function User Data ***/
        public function getUserData($id = null) {
            if (!$this->userdata) return false;

            $session = $this->userdata;
            $userId = ($id) ? $id : $session["id"];
            if ($userId) {
                $arrData = array();
                $tableEmployees = "tblemployees a";
                $tableUsers = "tblusers b";

                $this->db->select("a.id, a.biometricno, a.idno, a.lastname, a.firstname, a.middlename, a.suffix, a.employee_status, b.group_id, b.email, b.username, b.reset_pin, b.is_suspended");
                $this->db->from($tableEmployees);
                $this->db->join($tableUsers, "b.emp_id = a.id", "left");
                $this->db->where("a.id", $userId);
                $query = $this->db->get();
                if ($query->num_rows() == 1) {
                    $row = $query->row_array();
                    $data = $this->getDisplayName($row);
                    if ($data) {
                        foreach ($data as $key => $value) {
                            $row[$key] = $value;
                        }
                    }

                    return $row;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        public function getDisplayName($arrData = array()) {
            if ($arrData) {
                $lastname = $arrData["lastname"];
                $firstname = $arrData["firstname"];
                $middlename = $arrData["middlename"];
                $suffix = $arrData["suffix"];

                $nSuffix = "";
                $nMiddleName = "";

                if ($suffix !== "" && ($suffix !== "N/A" && $suffix !== "NONE")) {
                    $nSuffix = $suffix;
                }
                if ($middlename !== "" && ($middlename !== "N/A" && $middlename !== "NONE")) {
                    $nMiddleName = $middlename;
                }

                $displayName1 = "";
                $displayName2 = "";

                $nMiddleName = trim($nMiddleName);
                $nMiddleName = substr($nMiddleName, 0, 1);
                $nMiddleName = ($nMiddleName) ? "{$nMiddleName}." : "";
                if ($nMiddleName && $nSuffix) {
                    $displayName1 = "{$lastname}, {$firstname} {$nMiddleName}, {$nSuffix}";
                    $displayName2 = "{$firstname} {$nMiddleName} {$lastname} {$nSuffix}";
                } else if ($nSuffix) {
                    $displayName1 = "{$lastname}, {$firstname}, {$nSuffix}";
                    $displayName2 = "{$firstname} {$lastname} {$nSuffix}";
                } else if ($nMiddleName) {
                    $displayName1 = "{$lastname}, {$firstname} {$nMiddleName}";
                    $displayName2 = "{$firstname} {$nMiddleName} {$lastname}";
                } else {
                    $displayName1 = "{$lastname}, {$firstname}";
                    $displayName2 = "{$firstname} {$lastname}";
                }

                $displayName1 = strtoupper($displayName1);
                $displayName2 = strtoupper($displayName2);

                $data = array();
                $data["display_name_0"] = $displayName1;
                $data["display_name_1"] = $displayName2;

                return $data;
            } else {
                return false;
            }
        }

        /*** new function User Data ***/

        function generateCode($length = 13) {
            $str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            return substr(str_shuffle($str), 0, $length);
        }

        /*** email function ***/
        public function send_email($module, $email_title, $content_title, $content, $overrideMailer = array()) {
            $email_module = $this->getEmailModule($module);
            if ($email_module) {
                $sendTo = unserialize($email_module->send_to);
                $sendCc = unserialize($email_module->cc_to);
                $sendBcc = unserialize($email_module->bcc_to);

                $sendTo = (isset($overrideMailer["send_to"]) && $overrideMailer["send_to"]) ? $overrideMailer["send_to"] : $sendTo;
                $sendCc = (isset($overrideMailer["send_cc"]) && $overrideMailer["send_cc"]) ? $overrideMailer["send_cc"] : $sendCc;
                $sendBcc = (isset($overrideMailer["send_bcc"]) && $overrideMailer["send_bcc"]) ? $overrideMailer["send_bcc"] : $sendBcc;

                $sendToData = ($sendTo && is_array($sendTo)) ? implode(",", $sendTo) : "";
                $ccToData = ($sendCc && is_array($sendCc)) ? implode(",", $sendCc) : "";
                $bccToData = ($sendBcc && is_array($sendBcc)) ? implode(",", $sendBcc) : "";

                $sendToData = ($sendToData) ? $sendToData : "jp03@gccph.com";

                $emailSender = $this->doMailer($email_title, $overrideMailer);
                if ($emailSender) {
                    $emailSender->to($sendToData);
                    if ($ccToData) {
                        $emailSender->cc($ccToData);
                    }
                    if ($bccToData) {
                        $emailSender->bcc($bccToData);
                    }

                    $content_title = ($content_title) ? $content_title : "This is a sample title";
                    $content = ($content) ? $content : "This is a sample Content";
                    $emailSender->subject($content_title);
                    $emailSender->message($content);
                    $sent = $emailSender->send();
					return $sent ? true : false;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        private function doMailer($email_title = null) {
			$qTemp = $this->db->get_where($this->emailProtocolTable, array("id"=>1));
			if($qTemp->num_rows() == 1){
				$tempRow = $qTemp->row();
				$smtpUser = $tempRow->smtp_user;
                $smtpPassword = $tempRow->smtp_pass;

				/*** protocol: [ smtp ], mail ***/
				/*** smtp_host: [ smtp.googlemail.com ] ***/
				/*** smtp_port: [ 587 ], 465 ***/
				/*** smtp_crypto: [ tls ], ssl ***/
				$config = Array(
					'protocol' => $tempRow->protocol,
					'smtp_host' => $tempRow->smtp_host,
					'smtp_port' => intval($tempRow->smtp_port),
					'smtp_crypto' => $tempRow->smtp_crypto,
					'smtp_user' => $smtpUser,
					'smtp_pass' => $smtpPassword,
					'smtp_mailtype' => 'html',
					'charset' => 'utf-8',
					'wordwrap' => TRUE,
					'validate' => FALSE
				);

				if ($this->email->initialize($config)) {
					$this->email->set_newline("\r\n");
					$this->email->set_mailtype("html");
					$this->email->from($smtpUser, $email_title);

					return $this->email;
				} else {
					return false;
				}
			}else{
				return false;
			}
        }

        private function getEmailModule($module = null) {
            if ($module) {
                $this->db->from("email_template");
                $this->db->where('name', $module);
                $query = $this->db->get();
                if ($query->num_rows() == 1) {
                    return $query->row();
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        function getTimeAgo($timestamp) {
            $time_ago = strtotime($timestamp);
            $current_time = time();
            $time_difference = $current_time - $time_ago;
            $seconds = $time_difference;

            $minutes = round($seconds / 60); // value 60 is seconds
            $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec
            $days = round($seconds / 86400); //86400 = 24 * 60 * 60;
            $weeks = round($seconds / 604800); // 7*24*60*60;
            $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60
            $years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60

            if ($seconds <= 60) {
                return "Just Now";
            } else if ($minutes <= 60) {
                if ($minutes == 1) {
                    return "one minute ago";
                } else {
                    return "$minutes minutes ago";
                }
            } else if ($hours <= 24) {
                if ($hours == 1) {
                    return "an hour ago";
                } else {
                    return "$hours hrs ago";
                }
            } else if ($days <= 7) {
                if ($days == 1) {
                    return "yesterday";
                } else {
                    return "$days days ago";
                }
            } else if ($weeks <= 4.3) {
                if ($weeks == 1) {
                    return "a week ago";
                } else {
                    return "$weeks weeks ago";
                }
            } else if ($months <= 12) {
                if ($months == 1) {
                    return "a month ago";
                } else {
                    return "$months months ago";
                }
            } else {
                if ($years == 1) {
                    return "one year ago";
                } else {
                    return "$years years ago";
                }
            }
        }

        /*** email function ***/

        function saveLog($action, $user_employee_name) {
            $text = date("h:i:s A") . "||";
            $text .= strtoupper($user_employee_name) . "||";
            $text .= strtoupper($action);
            $text .= "\n";

            $filename = date("Ymd") . ".txt";
            $path = "./uploads/access_log";
            $full_path = $path . "/" . $filename;

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            return !write_file($full_path, $text, "a+");
        }

		function updatePassword(){
			$result = array();
			$post = $this->input->post();
			$userData = $this->userdata;
			$current_password = $this->getPasswordData($userData["id"])["password"];

			if($post):
				if($current_password != md5($post["current_password"])):
					if($post["new_password"] == $post["confirm_password"]):
						$this->db->update('tblusers', array("password"=>md5($post["new_password"])), array('id' => $userData["id"]));

						$trailAction = "Password has been updated.";
						$this->saveLog($trailAction, $this->loggedUserName);

						$result["msg"] = "Password has been updated.";
						$result["status"] = TRUE;
					else:
						$result["msg"] = "New password doesn't match.";
						$result["status"] = FALSE;
					endif;
				else:
					$result["msg"] = "Current Password doesn't match.";
					$result["status"] = FALSE;
				endif;
			else:
				$result["msg"] = "No post data.";
				$result["status"] = FALSE;
			endif;

			return $result;
		}

		private function getPasswordData($id = null){
			$this->db->select("*");
			$this->db->from("tblusers");
			$this->db->where("id", $id);
			$query = $this->db->get();
			return $query->row_array();
		}

		public function getPendingCancellationCount($type = null){
			$this->db->select("id");
			$this->db->from($type);
			$this->db->where("status",3);
			$query = $this->db->get();
			return $query->num_rows();
		}

    }

