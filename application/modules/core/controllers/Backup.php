<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Backup extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model("backup_model", "adm_backup");
	}
	function index(){
		$this->core_layout->setPageTitle("Configuration - Backup Databse");
		$this->core_layout->setBodyClass("configuration backup");
		$this->core_layout->setPrivilegeName("backup");
		
		$this->load->view("core/templates/header");
		$this->load->view("core/backup/index");
		$this->load->view("core/templates/footer");
	}
	function create_backup(){
		$resultset = $this->adm_backup->createBackup();
		
		$this->output
        ->set_content_type('json')
        ->set_output(json_encode($resultset));
	}
	function download_backup($id=null){
		$fileToDownload = $this->adm_backup->downloadBackup($id);
		if($fileToDownload){ force_download($fileToDownload, null); }
	}
	
	function get_collection(){
		$resultset = $this->adm_backup->getBackupDatabseList();
		
		$this->output
        ->set_content_type('json')
        ->set_output(json_encode($resultset));
	}
	
	function create_backup_stocks(){
		$resultset = $this->adm_backup->createBackupStocks();
		
		$this->output
        ->set_content_type('json')
        ->set_output(json_encode($resultset));
	}
}