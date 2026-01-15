<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    class Items extends MY_Controller {

        function __construct(){
            parent::__construct();

            $this->load->model('items_model', 'items');
        }

        function index(){
            $this->core_layout->addJs("plugins/datatable-buttons/dataTables.buttons.min.js");
            $this->core_layout->addJs("plugins/datatable-buttons/pdfmake.min.js");
            $this->core_layout->addJs("plugins/datatable-buttons/buttons.html5.min.js");
            $this->core_layout->addJs("js/dataTables.rowGroup.min.js");

            $this->core_layout->addJs("js/xlsx.full.min.js");

            $this->core_layout->addCss('plugins/swal/sweetalert2.min.css', true);
            $this->core_layout->addJs('plugins/swal/sweetalert2.all.min.js', true);

            $this->core_layout->setPageTitle("Masterfile - Items");
            $this->core_layout->setBodyClass("masterfile Items");
            $this->core_layout->setPrivilegeName("items");

            $this->load->view('core/templates/header');
            $this->load->view("index");
            $this->load->view('core/templates/footer');
        }

        function get_datatable_list(){
            $data = $this->items->get_datatable_list();
            $this->output
            ->set_content_type('json')
            ->set_output(json_encode($data));
        }

        function get_all_items($id = 0){
            $data = $this->items->get_all_items($id);
            $this->output
            ->set_content_type('json')
            ->set_output(json_encode($data));
        }
        
        function get_item(){
            $data = $this->items->get_item();
            $this->output
            ->set_content_type('json')
            ->set_output(json_encode($data));
        }

        function get_all_warehouses(){
            $data = $this->items->get_all_warehouses();
            $this->output
            ->set_content_type('json')
            ->set_output(json_encode($data));
        }
    }