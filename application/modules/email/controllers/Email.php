<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model("inventory/items_model");
		$this->load->model("inventory/category_model");
		$this->load->model("inventory/priority_model");
		$this->load->model("inventory/Inventory_checking_model", "inv_checking");
		$this->load->model("email_model", "email_template");
		date_default_timezone_set("Asia/Manila");
	}

	function index(){
		$this->core_layout->setPageTitle("Inventory - Email");
		$this->core_layout->setBodyClass("inventory email");
		$this->core_layout->setPrivilegeName("email");

		$this->load->view('core/templates/header');
		$this->load->view('index');
		$this->load->view('core/templates/footer');
	}

	function email_lookup(){
        $data = $this->email_config->emailLookup();
        $this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
    }

	function create_new(){
		$data = $this->email_template->createNew();
        $this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
	}

	function update_email(){
		$data = $this->email_template->updateEmail();
        $this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
	}

	function get_email_collection(){
		$data = $this->email_template->getEmailCollection();
        $this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
	}

	function get_email(){
		$data = $this->email_template->getEmail();
        $this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
	}

	function send_email(){
		$data = $this->email_template->sendEmail();
        $this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
	}

	function auto_send_mail(){
		$data = $this->email_template->autoSendEmail();
        $this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
	}

	function email_template_view(){
		$data["data"] = $this->email_template->getNoReOrderQtyLevelStocks();

		$this->load->view("email/email_template",$data);
	}

	function update_protocol(){
		$data = $this->email_template->updateProtocol();
        $this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
	}

	function get_protocol_setting(){
		$data = $this->email_template->getProtocolSetting();
        $this->output
        ->set_content_type('json')
        ->set_output(json_encode($data));
	}


}

