<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    class Items_model extends CI_Model {

        protected $table = 'tblsynced_data';
        protected $companyTable = 'tblwarehouse';

        function __construct(){
            parent::__construct();

            $this->loggedUser = $this->session->userdata('logged_in');
            $this->loggedUserName = $this->loggedUser["firstname"] . " " . $this->loggedUser["lastname"];
            $this->session = $this->core_layout->getCurrentSession();
        }

        function get_all_items($id = 0){
            $result = array();
            $get = $this->input->get();

            $this->db->select('sku as id, CONCAT(sku, " | ", name) as text');

            if(isset($get['term']) && $get['term']){
                $this->db->like('sku', $get['term'], 'both');
                $this->db->or_like('inventory_sku', $get['term'], 'both');
                $this->db->or_like('name', $get['term'], 'both');
            }else{
                $this->db->limit(10);
            }

            if($id){
                $this->db->where('site_id', $id);
            }else{
                $this->db->group_by(array('sku', 'name'));
            }

            $this->db->from($this->table);
            $query = $this->db->get();

            if($query->num_rows() > 0){
                $result = $query->result();
            }

            return array('results' => $result);
        }

        function get_item(){
            $result = array();
            $post = $this->input->post();

            $this->db->select('a.sku, a.inventory_sku, a.name, a.category_name, a.qty_in, a.qty_out, a.running_bal, b.name as warehouse, a.image_path, a.image');
            $this->db->join($this->companyTable.' as b', 'b.id = a.site_id', 'LEFT');

            if(isset($post['sku']) && $post['sku']){
                $this->db->where('sku', strtolower($post['sku']));
            }

            if(isset($post['warehouse']) && $post['warehouse']){
                $this->db->where('a.site_id', $post['warehouse']);
            }
            
            $this->db->from($this->table.' as a');
            $query = $this->db->get();

            $arrData = array();
            if($query->num_rows() > 0){
                foreach($query->result() as $key => $rs){
                    $rs->qty_in = number_format($rs->qty_in, 2);
                    $rs->qty_out = number_format($rs->qty_out, 2);
                    $rs->running_bal = number_format($rs->running_bal, 2);

                    $arrData[$key] = $rs;
                }

                foreach ($arrData as $k => $v) {
                    $result[] = $v;
                }
            }

            return array('data' => $result);
        }

        function get_all_warehouses(){
            $result = array();
            $get = $this->input->get();

            $this->db->select("id, CONCAT(code, ' - ', name) as text");

            if(isset($get['term']) && $get['term']){
                $this->db->like('code', $get['term'], 'both');
                $this->db->or_like('name', $get['term'], 'both');
            }else{
                $this->db->limit(10);
            }

            $this->db->from($this->companyTable);
            $query = $this->db->get();

            if($query->num_rows() > 0){
                $result = $query->result();
            }

            return array('results' => $result);
        }
    }