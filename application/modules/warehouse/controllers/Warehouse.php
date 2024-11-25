<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Warehouse extends MY_Controller {
        public function __construct() {
            parent::__construct();
            $this->load->model("warehouse/Warehouse_m", 'warehouse');
        }

        public function index(){
            $this->core_layout->setPageTitle("Masterfile - Warehouse");
            $this->core_layout->setBodyClass("masterfile Warehouse");
            $this->core_layout->setPrivilegeName("warehouse_is");

            $this->load->view('core/templates/header');
            $this->load->view("index");
            $this->load->view('core/templates/footer');
        }

        function get_datatable_list(){
            $data = $this->warehouse->get_datatable_list();
            $this->output
            ->set_content_type('json')
            ->set_output(json_encode($data));
        }

        public function add_warehouse(){
            $data = $this->warehouse->add_warehouse();
            $this->output
            ->set_content_type('json')
            ->set_output(json_encode($data));
        }
        
        public function get_warehouse_data($id){
            $data = $this->warehouse->get_warehouse_data($id);
            $this->output
            ->set_content_type('json')
            ->set_output(json_encode($data));
        }

        public function edit_warehouse(){
            $data = $this->warehouse->edit_warehouse();
            $this->output
            ->set_content_type('json')
            ->set_output(json_encode($data));
        }

        public function check_available(){
            $post = $this->input->post();
            $result = array();

            $data = $this->warehouse->get_warehouse_data($post['id']);

            if($data['state']){
                $id = $post['id'];
                $date = date('Ymd');
                $token = MD5("direct_access-$date");

                $temp = $data['data'];

                $url = $temp->path."/core/sync/check_sync/$token";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $head = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                $_temp = json_decode($head);

                if(isset($_temp->response) && $_temp->response){
                    $result['state'] = true;
                }else{
                    $result['state'] = false;
                }
            }else{
                $result['state'] = false;
            }

            $this->output
            ->set_content_type('json')
            ->set_output(json_encode($result));
        }

        public function sync_warehouse_data($id){
            $result = array();
            $data = $this->warehouse->get_warehouse_data($id);
            $temp = $data['data'];

            $getData = $temp->path."/core/sync/get_company_items";

            $_ch = curl_init();
            curl_setopt($_ch, CURLOPT_URL, $getData);
            curl_setopt($_ch, CURLOPT_RETURNTRANSFER, TRUE);
            $data = curl_exec($_ch);
            $httpCode = curl_getinfo($_ch, CURLINFO_HTTP_CODE);
            curl_close($_ch);

            $_temp = json_decode($data);
            if(isset($_temp->data) && $_temp->data){
                $save_sync = $this->warehouse->save_sync($id, $_temp->data, $temp->name);
                $result = $save_sync;
            }else{
                $result['state'] = false;
                $result['msg'] = 'Failed to sync Company Items.';
            }

            $this->output
            ->set_content_type('json')
            ->set_output(json_encode($result));
        }
    }