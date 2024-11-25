<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Email_settings_model extends CI_Model {
        public function __construct() {
            $this->db->db_debug = false;
            parent::__construct();
        }

        function getEmailSettings($forArchived = 0) {
            $result = array();
            $order = $this->input->post("order");
            $columns = $this->input->post("columns");
            $search = $this->input->post("search");
            $offset = $this->input->post("start");
            $limit = $this->input->post("length");
            $draw = $this->input->post("draw");
            $searchValue = $search["value"];

            if ((int)$limit >= 1) {
                $this->db->limit($limit, $offset);
            }

            if (!empty($order)) {
                $colIndex = $order[0]["column"];
                $dir = $order[0]["dir"];
                $column = $columns[$colIndex]["data"];
                $this->db->order_by($column, $dir);
            }

            $this->db->where("archived", $forArchived);
            $this->db->like("CONCAT(`label`, IFNULL(`to`, ''), IFNULL(`cc`,''), IFNULL(`bcc`,''))", $searchValue, "both");
            $data = $this->db->get("email_settings")->result();

            return array(
                "data" => $data,
                "recordsTotal" => $this->getEmailSettingsCount($searchValue, $forArchived),
                "recordsFiltered" => $this->getEmailSettingsCount($searchValue, $forArchived),
                "draw" => $draw
            );
        }

        private function getEmailSettingsCount($searchValue, $forArchived) {
            $this->db->where("archived", $forArchived);
            $this->db->like("CONCAT(`label`,`to`,`cc`,`bcc`)", $searchValue, "both");
            return $this->db->count_all_results("email_settings");
        }

        function updateEmailSetting() {
            $post = $this->input->post();
            $id = $post["id"];
            $post["to"] = preg_replace("/\s+/", "", $post["to"]);
            $post["cc"] = preg_replace("/\s+/", "", $post["cc"]);
            $post["bcc"] = preg_replace("/\s+/", "", $post["bcc"]);
            unset($post["id"]);

            $this->db->where("id", $id);
            if ($this->db->update("email_settings", $post)) {
                return array("success" => true, "message" => "Email setting successfully updated.", "title" => "Email Setting Updated.");
            }

            return array("success" => false, "message" => $this->db->error()["message"], "title" => "DB Error");
        }

        function createEmailSetting() {
            $post = $this->input->post();
            $post["code"] = md5(microtime() . rand());
            $post["to"] = preg_replace("/\s+/", "", $post["to"]);
            $post["cc"] = preg_replace("/\s+/", "", $post["cc"]);
            $post["bcc"] = preg_replace("/\s+/", "", $post["bcc"]);

            if ($this->db->insert("email_settings", $post)) {
                return array("success" => true, "message" => "Email setting successfully saved.", "title" => "Email Setting Created.");
            }

            return array("success" => false, "message" => $this->db->error()["message"], "title" => "DB Error");
        }

        function archive_email_setting($id, $status) {
            $this->db->where("id", $id);
            $this->db->set("archived", $status);
            if ($this->db->update("email_settings")) {
                $statusStr = (intval($status) === 1 ? "Archived" : "Restored");
                return array("success" => true, "message" => "Email setting successfully $statusStr.", "title" => "Email Setting $statusStr.");
            }

            return array("success" => false, "message" => $this->db->error()["message"], "title" => "DB Error");
        }
    }

    /* End of file .php */