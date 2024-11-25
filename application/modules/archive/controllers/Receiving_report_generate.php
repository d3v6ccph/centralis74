
	

	<?php
	
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Receiving_report_generate extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->core_layout->addJs("plugins/daterange_picker/daterangepicker.min.js");
		$this->core_layout->addCss("plugins/daterange_picker/daterangepicker.css");

		$this->load->model("archive/Rr_head_m","Rr_head_m");
		$this->load->model("archive/Rr_body_m","Rr_body_m");
		
	}
	public function index(){
		$this->load->view('core/templates/header');
		$this->load->view('index');
		$this->load->view('core/templates/footer');
	}
	public function archive(){
		$this->core_layout->setPageTitle("Reports - Receiving Report Archive");
		$this->core_layout->setBodyClass("reports receiving_report archive");
		$this->core_layout->setPrivilegeName("archive_receiving_report");
		$this->load->view('core/templates/header');
		$this->load->view('views/index');
		$this->load->view('core/templates/footer');
	}
	public function ajax_list() 
	{
		
		$list = $this->Rr_head_m->get_datatables();
		$data = array();
		$temp=null;
		foreach ($list as $myList) {
			
			$row = array();
			$row['rn'] = $myList->reference_no;
			$row['company'] = $myList->compname;
			$row['supp'] = $myList->supplier;
			$row['dn'] = $myList->document_no;
			$row['dd'] = $myList->document_date;
			$row['po'] = $myList->po_number;			
			
			
			
			
			$data[] = $row;
		
		}

		//$row[] = $button;
		$output = array(
		 	"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}


	
  
}