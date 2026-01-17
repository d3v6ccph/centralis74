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
            $post = $this->input->post();

            $this->db->select('
                a.sku,
                a.inventory_sku,
                a.name,
                a.category_name,
                a.qty_in,
                a.qty_out,
                a.running_bal,
                a.site_id,
                b.name as warehouse,
                a.image_path,
                a.image
            ');
            $this->db->from($this->table.' as a');
            $this->db->join($this->companyTable.' as b', 'b.id = a.site_id', 'LEFT');

            // SKU filter (optional)
            if (!empty($post['sku'])) {
                $this->db->where_in('a.sku', array_map('strtolower', $post['sku']));
            }

            // Warehouse filter (ONLY if payload exists)
            if (!empty($post['warehouse'])) {
                $this->db->where_in('a.site_id', $post['warehouse']);
            }

            // Always exclude zero stock
            $this->db->where('a.running_bal >', 0);

            $query = $this->db->get();

            $items = [];

            foreach ($query->result() as $row) {
                if (!isset($items[$row->sku])) {
                    $items[$row->sku] = [
                        'sku'           => $row->sku,
                        'inventory_sku' => $row->inventory_sku != '' ? $row->inventory_sku : ' - - - ',
                        'name'          => $row->name,
                        'category_name' => $row->category_name,
                        'image_path'    => $row->image_path,
                        'image'         => $row->image,
                        'total_qty_in'  => 0,
                        'total_qty_out' => 0,
                        'total_balance' => 0,
                        'warehouses'    => []
                    ];
                }

                // totals per SKU
                $items[$row->sku]['total_qty_in']  += $row->qty_in;
                $items[$row->sku]['total_qty_out'] += $row->qty_out;
                $items[$row->sku]['total_balance'] += $row->running_bal;

                // warehouse list
                $items[$row->sku]['warehouses'][] = [
                    'site_id'     => $row->site_id,
                    'warehouse'   => $row->warehouse,
                    'qty_in'      => number_format($row->qty_in, 2),
                    'qty_out'     => number_format($row->qty_out, 2),
                    'running_bal' => number_format($row->running_bal, 2)
                ];
            }

            // format totals
            foreach ($items as &$item) {
                $item['total_qty_in']  = number_format($item['total_qty_in'], 2);
                $item['total_qty_out'] = number_format($item['total_qty_out'], 2);
                $item['total_balance'] = number_format($item['total_balance'], 2);
            }

            return [
                'data' => array_values($items),
                'data_for_export' => $this->get_item_for_export($post),
            ];
        }

        function get_item_for_export($post) {
            $this->db->select('
                a.sku,
                a.inventory_sku,
                a.name,
                a.running_bal,
                a.site_id,
                b.name as warehouse
            ');
            $this->db->from($this->table . ' as a');
            $this->db->join($this->companyTable . ' as b', 'b.id = a.site_id', 'LEFT');

            // SKU filter (optional)
            if (!empty($post['sku'])) {
                $this->db->where_in('a.sku', array_map('strtolower', $post['sku']));
            }

            // Warehouse filter (optional)
            if (!empty($post['warehouse'])) {
                $this->db->where_in('a.site_id', $post['warehouse']);
            }

            // Always exclude zero stock
            $this->db->where('a.running_bal >', 0);

            $query = $this->db->get();

            $items = [];

            foreach ($query->result() as $row) {

                if (!isset($items[$row->sku])) {
                    $items[$row->sku] = [
                        'sku'           => $row->sku,
                        'name'          => $row->name,
                        'inventory_sku' => $row->inventory_sku,
                        'warehouses'    => [],
                        'total'         => 0
                    ];
                }

                // per-warehouse data
                $items[$row->sku]['warehouses'][] = [
                    'warehouse'   => strtoupper($row->warehouse),
                    'running_bal' => number_format($row->running_bal, 2)
                ];

                // total per SKU (raw number, formatted later if needed)
                $items[$row->sku]['total'] += $row->running_bal;
            }

            // format totals
            foreach ($items as &$item) {
                $item['total'] = (int) $item['total']; 
                // or use number_format($item['total'], 2) if you want decimals
            }

            return array_values($items);
        }

        function get_item_old(){
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

        function get_last_sync_date() {
            $this->db->select('MAX(sync_update_date) as last_sync_date');
            $this->db->from($this->table);
            $query = $this->db->get();

            if ($query->num_rows() > 0 && $query->row()->last_sync_date) {
                return [
                    'state' => true,
                    'last_sync_date' => $query->row()->last_sync_date
                ];
            }

            return [
                'state' => false,
                'last_sync_date' => null
            ];
        }
    }