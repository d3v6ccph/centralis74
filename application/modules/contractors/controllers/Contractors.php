<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Contractors extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model("contractors/contractors_model","contractors");
		$this->load->model("core/settings_model", "settings");
		
		date_default_timezone_set('Asia/Manila');
	}
	

	function index(){
		$this->core_layout->setPageTitle("Materfiles - Contractors");
		$this->core_layout->setBodyClass("masterfiles contractor");
		$this->core_layout->setPrivilegeName("contractor");
		
		$this->load->view('core/templates/header');
		$this->load->view('index');
		$this->load->view('core/templates/footer');
	}

	function get_contractors_list(){
		$post = $this->input->post();
		$data = $this->contractors->getContractorsList($post);
		$this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
	}
	function archive_contractor(){
		$data = $this->contractors->archiveContractor();
		$this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
	}
	function update_contractor(){
		$data = $this->contractors->updateContractor();
		$this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
	}
	function add_contractor(){
		$data = $this->contractors->addContractor();
		$this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
	}
	
	function get_contractors_data(){
		$data = $this->contractors->getContractorData();
		$this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
	}

}