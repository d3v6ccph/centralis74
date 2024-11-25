<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_graph_setting_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function getEditModal($id)
    {
        $result = array();
        $data = $this->getInventoryGraphSettingInfo($id);
        $result["html"] = $this->load->view("modals/edit_graph_setting", $data, TRUE);
        return $result;
    }

    function getAddSettingModal()
    {
        $result = array();
        $data = array();
        $data["prev"] = $this->getSecondToTheLast();

        $result["html"] = $this->load->view("modals/new_graph_setting", $data, TRUE);
        return $result;
    }

    function getSecondToTheLast()
    {
        $this->db->where("non_conditional", 0);
        $this->db->order_by("order", "asc");
        $q = $this->db->get("setting_inventory_graph");
        $settings = $q->result();
        $count = $q->num_rows();

        if ($count <= 1) {
            return $settings[0];
        } else {
            $index = $count - 1;
            return $settings[$index];
        }
    }

    function getInventoryGraphSettingInfo($id)
    {
        $this->db->where("id", $id);
        return $this->db->get("setting_inventory_graph")->row();
    }

    function editGraphSetting()
    {
        $post = $this->input->post();
        $id = $post["id"];
        unset($post["id"]);
        $resultSet = array();

        $this->db->where("id", $id);
        $update = $this->db->update("setting_inventory_graph", $post);
        $resultSet["success"] = $update;
        return $resultSet;
    }

    function addGraphSetting()
    {
        $post = $this->input->post();
        $resultSet = array();

        $insert = $this->db->insert("setting_inventory_graph", $post);
        $resultSet["success"] = $insert;
        return $resultSet;
    }

    function deleteSetting($id)
    {
        $this->db->where("id", $id);
        $delete = $this->db->delete("setting_inventory_graph");
        $resultSet["success"] = $delete;
        return $resultSet;
    }
}
