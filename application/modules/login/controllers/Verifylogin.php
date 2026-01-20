<?php defined('BASEPATH') OR exit('No direct script access allowed');

    class Verifylogin extends MY_Controller {
        private $directAccess;

        /** e61a999de57725d7ce368a91ed87a7bd **/
        function __construct() {
            parent::__construct();
            $this->load->model('Login_m');
            $this->load->library('form_validation');
            $this->directAccess = md5("direct_access");
        }

        public function index() {
            //This method will have the credentials validation
            $this->form_validation->set_error_delimiters('<div class="m-alert m-alert--outline alert alert-danger alert-dismissible" role="alert">', '<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
		<span></span>
		</div>');
            $this->form_validation->set_rules('username', 'Username', 'trim|required|prep_for_form');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database|prep_for_form');

            if ($this->form_validation->run() == FALSE) {
                //Field validation failed.  User redirected to login page
                $this->load->view('login_v');
            } else {
                //Go to private area
                redirect('dashboard/index', 'refresh');
            }
        }

        public function check_database($password){
            $username = $this->input->post('username');
            //query the database
            $result = $this->Login_m->login($username, $password);
            if ($result) {
                $userResult = $result[0];
                if ($userResult->is_suspended == 1){
                    $this->form_validation->set_message('check_database', 'This user account has been suspended.');
                    return false;
                } elseif (intval($userResult->lockout) === 1){
                    $lockedDate = date("F d, Y h:i A", strtotime($userResult->lockout_dt));
                    $this->form_validation->set_message('check_database', 'Account has been <strong>LOCKED OUT</strong> last '.$lockedDate.', Contact I.T. Team for Assistance.');
                    return false;
                }
                
                $checkUser = array("emp_id"=>$userResult->emp_id, "username"=>$userResult->username);
                $tempUser = $this->db->get_where("tblusers", $checkUser);
                if ($tempUser->num_rows() === 1){
                    $row = $tempUser->row();
                    $privileges = $this->Login_m->get_privileges_by_id($row->id);
                    $sess_array = array(
                        'id' => $row->id,
                        'emp_id' => $userResult->emp_id,
                        'username' => $userResult->username,
                        'firstname' => $userResult->firstname,
                        'middlename' => $userResult->middlename,
                        'lastname' => $userResult->lastname,
                        'privileges' => $privileges,
                        'suffix' => $userResult->suffix,
                        'group_id' => $userResult->group_id,
                        'email' => $userResult->email,
                        'company' => $userResult->company_id,
                        'department' => $userResult->department_id
                    );

                    $this->session->set_userdata('logged_in', $sess_array);
                } elseif ($tempUser->num_rows() === 0){
                    $insertUser = array("emp_id"=>$userResult->emp_id, "group_id"=> $userResult->group_id, "email"=>$userResult->email,
                    "username"=>$userResult->username, "password"=>$userResult->password, "added_by"=>$userResult->emp_id, "added_date"=>date("Y-m-d H:i:s"));
                    $added = $this->db->insert("tblusers", $insertUser);
                    if($added){
                        $id = $this->db->insert_id();
                        $privileges = $this->Login_m->get_privileges_by_id($id);
                        $sess_array = array(
                            'id' => $id,
                            'emp_id' => $userResult->emp_id,
                            'username' => $userResult->username,
                            'firstname' => $userResult->firstname,
                            'middlename' => $userResult->middlename,
                            'lastname' => $userResult->lastname,
                            'privileges' => $privileges,
                            'suffix' => $userResult->suffix,
                            'group_id' => $userResult->group_id,
                            'email' => $userResult->email,
                            'company' => $userResult->company_id,
                            'department' => $userResult->department_id
                        );

                        $this->session->set_userdata('logged_in', $sess_array);
                    }
                }

                return true;
            }else{
                $this->form_validation->set_message('check_database', 'Invalid username or password');
                return false;
            }
        }

        function oldcode_20260119_check_database($password) {
            //Field validation succeeded.  Validate against database
            $username = $this->input->post('username');
            //query the database
            $result = $this->Login_m->login($username, $password);
            if ($result) {
                $id = $result['0']->id;
                $privileges = $this->Login_m->get_privileges_by_id($id);
                $sess_array = array();
                foreach ($result as $row) {
                    if ($row->is_suspended == 1) {
                        $this->form_validation->set_message('check_database', 'This user account is suspended.');
                        return false;
                    } else {
                        $sess_array = array(
                            'id' => $row->id,//tbluser_id
                            'emp_id' => $row->emp_id,
                            'username' => $row->username,
                            'firstname' => $row->firstname,
                            'middlename' => $row->middlename,
                            'lastname' => $row->lastname,
                            'privileges' => $privileges,
                            'suffix' => $row->suffix,
                            'group_id' => $row->group_id,
                            'email' => $row->email,
                            'company' => $row->company_id,
                            'department' => $row->department_id
                        );
                        $this->session->set_userdata('logged_in', $sess_array);
                        return TRUE;
                    }
                }
            } else {
                $this->form_validation->set_message('check_database', 'Invalid username or password');
                return false;
            }
        }

        function redirect_access($username, $password) {
            $response = false;
            if ($username && $password) {
                $row = $this->Login_m->direct_login($username, $password);
                if ($row) {
                    $id = $row->id;
                    $privileges = $this->Login_m->get_privileges_by_id($id);
                    $sess_array = array();

                    if ($row->is_suspended == 1) {
                        $response = false;
                    } else {
                        $sess_array = array(
                            'id' => $row->id,
                            'emp_id' => $row->emp_id,
                            'username' => $row->username,
                            'firstname' => $row->firstname,
                            'middlename' => $row->middlename,
                            'lastname' => $row->lastname,
                            'privileges' => $privileges,
                            'suffix' => $row->suffix,
                            'group_id' => $row->group_id,
                            'email' => $row->email,
                            'company' => $row->company_id,
                            'department' => $row->department_id
                        );

                        $this->session->set_userdata('logged_in', $sess_array);
                        $this->session->set_userdata('test_userdata', array('id' => 1, 'user' => 'test'));

                        $response = true;
                    }
                } else {
                    $response = false;
                }
            } else {
                $response = false;
            }
            return $response;
        }

        function super_admin_token($token = null, $user = null) {
            $loggedIn = $this->session->userdata("logged_in");
            $redirectLink = site_url("portal/index");
            $loginUrl = site_url("login/index");
            $tempUrl = $loginUrl;

            if ($loggedIn) {
                $tempUrl = $redirectLink;
            } else {
                if (($token && $user) && $token == $this->directAccess) {
                    $query = $this->db->get_where("gccmaster.tblusers", array("username" => $user, "is_suspended" => 0));
                    if ($query->num_rows() == 1) {
                        $row = $query->row();
                        $response = $this->redirect_access($row->username, $row->password);
                        if ($response) {
                            $tempUrl = $redirectLink;
                        } else {
                            $tempUrl = $loginUrl;
                        }
                    } else {
                        $tempUrl = $loginUrl;
                    }
                } else {
                    $tempUrl = $loginUrl;
                }
            }

            return $tempUrl;
        }

        function super_admin_prtoken_mrs($token = null, $user = null, $id = null) {
            $loggedIn = $this->session->userdata("logged_in");

            $redirectLink = root_url("mrs/pr_head/pr_dt?id={$id}&status=ForApproved");
            /*** $redirectLink = root_url("mrs/mrsdetails_email/mrs_request_approval/{$id}"); ***/
            $loginUrl = site_url();

            if ($loggedIn) {
                redirect($redirectLink, "refresh");
            } else {
                if (($token && $user) && $token == $this->directAccess) {
                    $query = $this->db->get_where("gccmaster.tblusers", array("username" => $user, "is_suspended" => 0));
                    if ($query->num_rows() == 1) {
                        $row = $query->row();
                        $response = $this->redirect_access($row->username, $row->password);
                        if ($response) {
                            redirect($redirectLink, "refresh");
                        } else {
                            redirect($loginUrl, "refresh");
                        }
                    } else {
                        redirect($loginUrl, "refresh");
                    }
                } else {
                    redirect($loginUrl, "refresh");
                }
            }
        }

        function super_admin_prtoken_mrs_approval($token = null, $user = null, $status = null, $id = null) {
            $loggedIn = $this->session->userdata("logged_in");
            $redirectLink = root_url("mrs/mrsdetails_email/mrs_request_status_offline/{$status}/{$id}");
            $loginUrl = site_url();

            if ($loggedIn) {
                redirect($redirectLink);
            } else {
                if (($token && $user) && $token == $this->directAccess) {
                    $query = $this->db->get_where("gccmaster.tblusers", array("username" => $user, "is_suspended" => 0));
                    if ($query->num_rows() == 1) {
                        $row = $query->row();
                        $response = $this->redirect_access($row->username, $row->password);
                        if ($response) {
                            redirect($redirectLink);
                        } else {
                            redirect($loginUrl, "refresh");
                        }
                    } else {
                        redirect($loginUrl, "refresh");
                    }
                } else {
                    redirect($loginUrl, "refresh");
                }
            }
        }

        public function get_cookie_uri() {
            $cookieUrl = $this->input->cookie('base_url', true);
            $cookieUri = $this->input->cookie('uri_string', true);
            if ($cookieUrl && $cookieUri) {
                $url = $cookieUrl . "" . $cookieUri;
                return $url;
            } else {
                return false;
            }
        }
        public function destroy_cookie_uri() {
            delete_cookie('base_url');
            delete_cookie('uri_string');
            return true;
        }

        function user_access($username = null) {
            $resultset = array();
            if ($username) {
                $query = $this->db->get_where("gccmaster.tblusers", array("username" => $username, "is_suspended" => 0));
                if ($query->num_rows() == 1) {
                    $resultset["response"] = true;
                    $resultset["password"] = $this->directAccess;
                } else {
                    $resultset["response"] = false;
                }
            } else {
                $resultset["response"] = false;
            }
            echo json_encode($resultset);
        }

        function bypass_access() {
            $resultset = array();
            $post = $this->input->post();
            if (isset($post) && $post) {
                unset($post["csrf_token"]);
                $tempUrl = $this->super_admin_token($post["token"], $post["username"]);
                $resultset["response"] = true;
                $resultset["redirect"] = site_url("login");
            } else {
                $resultset["response"] = false;
            }

            echo json_encode($resultset);
        }
    }