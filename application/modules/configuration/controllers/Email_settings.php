<?php defined('BASEPATH') OR exit('No direct script access allowed');

    class Email_settings extends MY_Controller {
        public function __construct() {
            parent::__construct();
            $this->authenticate->doRedirect();
            $this->core_layout->setPageTitle("Configuration - Email Settings");
            $this->core_layout->setBodyClass("configuration email_settings");
            $this->core_layout->setPrivilegeName("email_settings");

            $this->load->model("Email_settings_model", "email_mod");

            date_default_timezone_set('Asia/Manila');
        }

        public function index() {
            $this->load->view('core/templates/header');
            $this->load->view("vw_email_settings");
            $this->load->view('core/templates/footer');
        }

        function get_email_settings() {
            echo json_encode($this->email_mod->getEmailSettings());
        }

        function update_email_setting() {
            echo json_encode($this->email_mod->updateEmailSetting());
        }

        function create_email_setting() {
            echo json_encode($this->email_mod->createEmailSetting());
        }

        function archive_email_setting($id, $status) {
            echo json_encode($this->email_mod->archive_email_setting($id, $status));
        }

        function get_archived_email_settings() {
            echo json_encode($this->email_mod->getEmailSettings(1));
        }
    }

    /* End of file Email_settings.php */