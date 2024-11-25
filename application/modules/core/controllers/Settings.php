<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Settings extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->authenticate->doRedirect();
		$this->load->model("settings_model", "settings");
		$this->load->model("datatable_model","dt_model");
		
		$this->core_layout->setPageTitle("Configuration - Settings");
		$this->core_layout->setBodyClass("configuration settings");
		$this->core_layout->setPrivilegeName("settings");
	}
	
	function index(){
		$data = array();
		$data["items"] = $this->settings->getSettingsData();
		
		$this->load->view('core/templates/header');
		$this->load->view('core/settings/index', $data);
		$this->load->view('core/templates/footer');
	}
	
	function update_settings(){
		$data = $this->settings->updateSettings();
		$this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
	}
}