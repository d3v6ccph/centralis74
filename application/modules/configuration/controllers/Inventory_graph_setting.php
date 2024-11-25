<?php defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_graph_setting extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->authenticate->doRedirect();
        $this->core_layout->setBodyClass("configuration");
        $this->core_layout->setPrivilegeName("configuration");

        $this->load->model("Inventory_graph_setting_model", "inv_sett_model");

        date_default_timezone_set('Asia/Manila');
    }

    function index()
    {
        $this->core_layout->addJs("plugins/spectrum/spectrum.min.js");
        $this->core_layout->addCss("plugins/spectrum/spectrum.min.css");

        $this->load->view('core/templates/header');
        $this->load->view("vw_inventory_graph_setting");
        $this->load->view('core/templates/footer');
    }

    function get_inventory_graph_setting_list()
    {
        $data = $this->db->get("setting_inventory_graph")->result();
        echo json_encode(array("data" => $data));
    }

    function get_edit_modal($id)
    {
        $data = $this->inv_sett_model->getEditModal($id);
        echo json_encode($data);
    }

    function get_add_setting_modal()
    {
        $data = $this->inv_sett_model->getAddSettingModal();
        echo json_encode($data);
    }

    function edit_graph_setting()
    {
        $data = $this->inv_sett_model->editGraphSetting();
        echo json_encode($data);
    }

    function add_graph_setting() {
        $data = $this->inv_sett_model->addGraphSetting();
        echo json_encode($data);
    }

    function delete_setting($id) {
        $data = $this->inv_sett_model->deleteSetting($id);
        echo json_encode($data);
    }
}
