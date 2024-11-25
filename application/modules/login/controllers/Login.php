<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends MY_Controller {
	function __construct(){
		parent::__construct();
	}
	
	public function index(){
	    $this->load->helper(array('form'));
	    $this->load->view('login_v');
	}
	
	public function logout(){
		$logged = $this->session->userdata("logged_in");
		if($logged){
			$this->authenticate->destroy_cookie_uri();
			$this->session->sess_destroy();
			redirect(login_url(), "refresh");
		}
	}
}
