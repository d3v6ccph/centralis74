<?php defined('BASEPATH') OR exit('No direct script access allowed');

    class Contractors_model extends CI_Model {
        protected $contractorTable = "contractors";
        protected $loggedUser;
        protected $loggedUserName;

        function __construct() {
            parent::__construct();
            $this->load->model("access_control_model", "acl_model");
            $this->load->model("datatable_model", "dt_model");
            $this->loggedUser = $this->session->userdata('logged_in');

            /**
             * Original Code
             * Issue : Throw session error when logging out because the session is null
             * Error Message : Trying to access array offset on value of type null
             * 
             * 
             * $this->loggedUserName = $this->loggedUser["firstname"] . " " . $this->loggedUser["lastname"]
             * 
             * 
             */
            
            // New Code Start
            // Put it on condition if the session has value or not
            ($this->loggedUserName != NULL) ? $this->loggedUserName = $this->loggedUser["firstname"] . " " . $this->loggedUser["lastname"] : '';
            // New Code End
        }

        function getContractorsList() {
            $post = $this->input->post();
            if ($post) {
                $columns = array("id", "name", "status");
                $dir = $post["order"][0]["dir"];
                $order = $columns[$post["order"][0]["column"]];
                $draw = (isset($post['draw']) && $post['draw']) ? $post['draw'] : 0;
                $start = (isset($post["start"]) && $post["start"]) ? $post["start"] : 0;
                $limit = (isset($post["length"]) && $post["length"]) ? $post["length"] : 0;
                $searchValue = (isset($post["search"]["value"]) && $post["search"]["value"]) ? $post["search"]["value"] : "";
                $dtInventoryItems = $this->dt_model->dataTable();
                $dtInventoryItems->setTable("contractors");

                $dtInventoryItems->setParameterFields($columns);

                $parameters = array();
                $parameters["status !="] = 2;

                $dtInventoryItems->setWhereParameters($parameters);

                $totalData = $dtInventoryItems->dtAllPostsCount();
                $totalFiltered = $totalData;

                if (empty($searchValue)) {
                    $posts = $dtInventoryItems->dtAllPosts($limit, $start, $order, $dir);
                } else {
                    $posts = $dtInventoryItems->dtSearch($limit, $start, $searchValue, $order, $dir);
                    $totalFiltered = $dtInventoryItems->dtPostSearchCount($searchValue);
                }

                $data = array();
                if (!empty($posts)) {
                    foreach ($posts as $pst) {
                        $status = "";
                        switch ($pst->status):
                            case 2:
                                $status = '<span class="btn btn-danger m-btn m-btn--icon m-btn--icon-only btn-sm"><i class="la la-user-times"></i></span>';
                                break;
                            case 1:
                                $status = '<span class="btn btn-info m-btn m-btn--icon m-btn--icon-only btn-sm"><i class="la la-user"></i></span>';
                                break;
                            default:
                                $status = '<span class="btn btn-danger m-btn m-btn--icon m-btn--icon-only btn-sm"><i class="la la-user"></i></span>';
                                break;
                        endswitch;

                        $nestedData['id'] = $pst->id;
                        $nestedData['name'] = $pst->name;
                        $nestedData['status'] = $status;
                        $data[] = $nestedData;
                    }
                }
                return  array(
                    "draw" => intval($draw),
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data" => $data,
                );

            } else {
                return array(
                    "draw" => 1,
                    "recordsTotal" => 0,
                    "recordsFiltered" => 0,
                    "data" => array(),
                );
            }
        }

        function archiveContractor() {
            $resultset = array();
            $post = $this->input->post();

            $contractor = $this->db->get_where($this->contractorTable, array("id" => $post["id"]))->row();

            if ($post) {
                $data = array();
                $data["status"] = 2;
                $archive = $this->db->update($this->contractorTable, $data, $post);
                if ($archive) {
                    $this->core_layout->saveLog("ARCHIVED CONTRACTOR WITH NAME:$contractor->name", $this->loggedUserName);
                    $resultset["response"] = true;
                    $resultset["toastr_msg"] = "Contractor has been removed.";
                } else {
                    $resultset["response"] = false;
                    $resultset["toastr_msg"] = "Failed to remove contractor!";
                }
            } else {
                $resultset["response"] = false;
                $resultset["toastr_msg"] = "Failed to remove contractor, no data found!";
            }
            return $resultset;
        }

        function updateContractor() {
            $resultset = array();
            $post = $this->input->post();
            $trailAction = "UPDATED CONTRACTOR ";
            $ctr = 1;
            $count = count($post) - 1;
            foreach ($post as $key => $value) {
                if ($key === "id") continue;
                $trailAction .= " " . strtoupper($key) . ":" . strtoupper($value) . (intval($ctr) < $count ? ", " : " ");
                $ctr++;
            }

            if ($post) {
                $where = array();
                $where["id"] = $post["id"];
                unset($post["id"]);

                $update = $this->db->update($this->contractorTable, $post, $where);
                if ($update) {
                    $this->core_layout->saveLog($trailAction, $this->loggedUserName);
                    $resultset["response"] = true;
                    $resultset["toastr_msg"] = "Contractor data has been updated.";
                } else {
                    $resultset["response"] = false;
                    $resultset["toastr_msg"] = "Failed to update contractor data!";
                }
            } else {
                $resultset["response"] = false;
                $resultset["toastr_msg"] = "No data found!";
            }

            return $resultset;
        }

        function addContractor() {
            $post = $this->input->post();
            $trailAction = "ADDED NEW CONTRACTOR ";
            $ctr = 1;
            $count = count($post);
            foreach ($post as $key => $value) {
                $trailAction .= " " . strtoupper($key) . ":" . strtoupper($value) . (intval($ctr) < $count ? ", " : " ");
                $ctr++;
            }

            $resultarray = array();
            if (!$this->checkDuplicate($post)) {
                $query = $this->db->insert("contractors", $post);
                if ($query) {
                    $this->core_layout->saveLog($trailAction, $this->loggedUserName);
                    $resultarray["response"] = TRUE;
                    $resultarray["toastr_msg"] = "Contractor successfully saved!";
                } else {
                    $resultarray["response"] = FALSE;
                    $resultarray["toastr_msg"] = "Error processing request.";
                }
            } else {
                $resultarray["response"] = FALSE;
                $resultarray["toastr_msg"] = "Duplicate Contractor name.";
            }

            return $resultarray;
        }

        function checkDuplicate($post) {
            $query = $this->db->get_where($this->contractorTable, $post);
            return $query->num_rows() == 1 ? true : false;
        }

        function getContractorData() {
            $resultset = array();
            $post = $this->input->post();
            if ($post) {
                $query = $this->db->get_where($this->contractorTable, $post);
                if ($query->num_rows() == 1) {
                    $row = $query->row();
                    $resultset["value"] = $row;
                    $resultset["response"] = true;
                } else {
                    $resultset["response"] = false;
                }
            } else {
                $resultset["response"] = false;
            }

            return $resultset;
        }

        function getContractorsName() {
            $query = $this->db->query("SELECT * FROM contractors");
            return $query;
        }

		function getContractorCollection(){
			$this->db->select("*");
			$this->db->from("contractors");
			$this->db->where("status", 1);
			return $this->db->get();
		}

    }
