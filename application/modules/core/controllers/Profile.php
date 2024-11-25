<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->authenticate->doRedirect();
		$this->core_layout->setBodyClass("dashboard");
	}
	function index(){
		$arrData = array();
		$userProfile = $this->authenticate->getUserProfile();
		if($userProfile){
			$arrData["userProfile"] = $userProfile;
		}
		if($this->session->userdata('logged_in')){
			$this->load->view('core/templates/header');
			$this->load->view('core/profile/index', $arrData);
			$this->load->view('templates/footer');					
		}else{
			redirect('login_controller', 'refresh');
		}
	}

	function update_password(){
		$data = $this->core_layout->updatePassword();
		$this->output
			->set_content_type("json")
			->set_output(json_encode($data));
	}
}
