<?php defined('BASEPATH') OR exit('No direct script access allowed');

    class Dashboard extends MY_Controller {
        protected $startWeek = "";
        protected $endWeek = "";
        protected $startMonth = "";
        protected $endMonth = "";

        function __construct() {
            parent::__construct();
            $this->authenticate->doRedirect();
            $this->core_layout->setBodyClass("dashboard");
            $this->core_layout->setPrivilegeName("dashboard");

            $this->startWeek = date("Y-m-d", strtotime("monday this week"));
            $this->endWeek = date("Y-m-d", strtotime("sunday this week"));

            $this->startMonth = date("Y-m-1");
            $this->endMonth = date("Y-m-t");

            $this->load->model('dashboard/Dashboard_m', 'dashboard');

            date_default_timezone_set('Asia/Manila');
        }

        public function index() {
            // start amcharts4
            $this->core_layout->addJs("plugins/amcharts4/core.js");
            $this->core_layout->addJs("plugins/amcharts4/charts.js");
            $this->core_layout->addJs("plugins/amcharts4/themes/material.js");
            $this->core_layout->addJs("plugins/amcharts4/themes/animated.js");
            $this->core_layout->addJs("plugins/amcharts4/themes/material.js");
            // end amcharts4

            // start datatable-button scripts
            $this->core_layout->addJs("plugins/datatable-buttons/dataTables.buttons.min.js");
            $this->core_layout->addJs("plugins/datatable-buttons/pdfmake.min.js");
            $this->core_layout->addJs("plugins/datatable-buttons/buttons.html5.min.js");
            $this->core_layout->addJs("plugins/datatable-buttons/jszip.min.js");
            $this->core_layout->addJs("plugins/datatable-buttons/vfs_fonts.js");
            // end datatable-button scripts

            $this->load->view('core/templates/header');
            $this->load->view('index');
            $this->load->view('core/templates/footer');
        }

        function get_all_warehouse(){
            $data = $this->dashboard->get_all_warehouse();
            $this->output
            ->set_content_type('json')
            ->set_output(json_encode($data));
        }
    }
