<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Warehouse_m extends CI_Model {
        protected $table = 'tblwarehouse';
        protected $syncTable = 'tblsynced_data';

        public function __construct() {
            parent::__construct();

            $this->loggedUser = $this->session->userdata('logged_in');
            $this->loggedUserName = $this->loggedUser["firstname"] . " " . $this->loggedUser["lastname"];
            $this->session = $this->core_layout->getCurrentSession();
        }

        private function getLoggedInUser() {
            $userdata = $this->session->all_userdata();
            return $userdata['logged_in'];
        }

        function get_datatable_list(){
            $resultset = array();
            $post = $this->input->post();
            $order_val = array(array("column"=>"0", "dir"=>"desc"));
            $search = (isset($post["search"]['value']) && $post["search"]['value']) ? $post["search"]['value'] : false;
            $limit = (isset($post["length"]) && $post["length"]) ? $post["length"] : 10;
            $offset = (isset($post["start"]) && $post["start"]) ? $post["start"] : 0;
            $sortBy =  (isset($post["columns"]) && $post["columns"])? $post["columns"]: 1;
            $sortOrder = (isset($post["order"]) && $post["order"]) ? $post["order"] : $order_val;

            $rowCount = 0;
            $rowData = array();

            $rowData = $this->get_list($limit, $offset, $sortBy, $sortOrder, $search);
            $rowCount = $this->get_list_count($search);

            $resultset["recordsTotal"] = $rowCount;
            $resultset["recordsFiltered"] = $rowCount;
            $resultset["data"] = $rowData;

            return $resultset;
        }

        function get_list($limit, $offset, $sortBy, $sortOrder, $search = null){
            $filterFields = array('name', 'code', 'path');
            $resultset = array();

            $this->db->from($this->table);
            $this->db->where('is_archived', 0);
            
            if($search){
                $this->db->group_start();
                foreach ($filterFields as $key => $field) {
                    if ($key == 0) {
                        $this->db->like($field, $search, "both");
                    } else {
                        $this->db->or_like($field, $search, "both");
                    }
                }
                $this->db->group_end();
            }

            if($limit != -1){
                $this->db->limit($limit, $offset);
            }

            $i = $sortOrder[0]['column'];
            $this->db->order_by($sortBy[$i]['data'], $sortOrder[0]['dir']);
            $query = $this->db->get();

            if($query->num_rows() > 0){
                $resultset = $query->result();
            }

            return $resultset;
        }

        function get_list_count($search = null){
            $filterFields = array('name', 'code', 'path');

            $this->db->from($this->table);
            $this->db->where('is_archived', 0);
            
            if($search){
                $this->db->group_start();
                foreach ($filterFields as $key => $field) {
                    if ($key == 0) {
                        $this->db->like($field, $search, "both");
                    } else {
                        $this->db->or_like($field, $search, "both");
                    }
                }
                $this->db->group_end();
            }

            $query = $this->db->get();
            return $query->num_rows();
        }

        function add_warehouse(){
            $post = $this->input->post();
            $resultset = array();

            if(isset($post) && $post){
                $check = $this->db->get_where($this->table, array('UPPER(code)' => strtoupper($post['code'])));

                if($check->num_rows() > 0){
                    $resultset['state'] = false;
                    $resultset['msg'] = 'Warehouse code already exists.';
                }else{
                    $data = array(
                        'name' => trim($post['name']),
                        'code' => trim($post['code']),
                        'path' => trim($post['path']),
                        'added_by' => $this->session["emp_id"],
                        'added_date' => date('Y-m-d H:i:s'),
                    );
    
                    $query = $this->db->insert($this->table, $data);
    
                    if($query){
                        $resultset['state'] = true;
                        $resultset['msg'] = 'Successfully Added new Warehouse';
    
                        $this->core_layout->saveLog('Added new Warehouse', $this->loggedUserName);
                    }else{
                        $resultset['state'] = false;
                        $resultset['msg'] = 'Failed to add new Warehouse';
    
                        $this->core_layout->saveLog('Failed to add new Warehouse', $this->loggedUserName);
                    }
                }
            }

            return $resultset;
        }

        function get_warehouse_data($id){
            $resultset = array();

            $this->db->from($this->table);
            $this->db->where('id', $id);
            $query = $this->db->get();

            if($query->num_rows() > 0){
                $resultset['state'] = true;
                $resultset['data'] = $query->row();
            }else{
                $resultset['state'] = false;
            }

            return $resultset;
        }

        function edit_warehouse(){
            $post = $this->input->post();
            $resultset = array();

            if(isset($post) && $post){
                $data = array(
                    'name' => trim($post['name']),
                    'code' => trim($post['code']),
                    'path' => trim($post['path']),
                    'status' => $post['status'],
                    'updated_by' => $this->session["emp_id"],
                    'updated_date' => date('Y-m-d H:i:s'),
                );

                $this->db->where('id', $post['id']);
                $query = $this->db->update($this->table, $data);

                if($query){
                    $resultset['state'] = true;
                    $resultset['msg'] = 'Successfully updated Warehouse';

                    $this->core_layout->saveLog('Successfully updated Warehouse', $this->loggedUserName);
                }else{
                    $resultset['state'] = false;
                    $resultset['msg'] = 'Failed to update Warehouse';

                    $this->core_layout->saveLog('Failed to update Warehouse', $this->loggedUserName);
                }
            }

            return $resultset;
        }

        function save_sync($id, $arr = array(), $warehouse){
            $result = array();

            $date = date('Y-m-d H:i:s');
            $count = count($arr);

            $this->db->where('site_id', $id);
            $this->db->from($this->syncTable);
            $query = $this->db->get();

            if($query->num_rows() > 0){
                foreach($arr as $items){
                    $data = array(
                        'name' => $items->name,
                        'sku' => $items->sku,
                        'inventory_sku' => (isset($items->inventory_sku) && $items->inventory_sku) ? $items->inventory_sku : NULL,
                        'category_name' => $items->category_name,
                        'qty' => $items->qty,
                        'qty_in' => $items->in,
                        'qty_out' => $items->out,
                        'running_bal' => $items->running_balance,
                        'image' => $items->image,
                        'image_path' => $items->image_path, 
                        'sync_update_by' => $this->session["emp_id"],
                        'sync_update_date' => $date
                    );

                    $this->db->where('item_id', $items->id);
                    $this->db->where('site_id', $id);
                    $update = $this->db->update($this->syncTable, $data);

                    if($update){
                        $result['state'] = true;
                        $result['msg'] = "Warehouse `".strtoupper($warehouse)."` successfully synced and updated its data.";
                        $this->core_layout->saveLog("Warehouse `".strtoupper($warehouse)."` successfully synced and updated its data.", $this->loggedUserName);
                    }else{
                        $result['state'] = false;
                        $result['msg'] = "Warehouse `".strtoupper($warehouse)."` failed to sync and update its data.";

                        $this->core_layout->saveLog("Warehouse `".strtoupper($warehouse)."` failed to sync and update its data.", $this->loggedUserName);
                    }
                }
            }else{
                foreach($arr as $items){
                    $this->db->where('sku', $items->sku);
                    $this->db->where('inventory_sku', $items->inventory_sku);
                    $this->db->from($this->syncTable);
                    $q = $this->db->get();

                    if($q->num_rows() == 0){
                        $data = array(
                            'site_id' => $id,
                            'item_id' => $items->id,
                            'name' => $items->name,
                            'sku' => $items->sku,
                            'inventory_sku' => (isset($items->inventory_sku) && $items->inventory_sku) ? $items->inventory_sku : NULL,
                            'category_name' => $items->category_name,
                            'qty' => $items->qty,
                            'qty_in' => $items->in,
                            'qty_out' => $items->out,
                            'running_bal' => $items->running_balance,
                            'image' => $items->image,
                            'image_path' => $items->image_path, 
                            'sync_by' => $this->session["emp_id"],
                            'sync_date' => $date
                        );

                        $save = $this->db->insert($this->syncTable, $data);

                        if($save){
                            $result['state'] = true;
                            $result['msg'] = "Warehouse `".strtoupper($warehouse)."` with `$count items` successfully synced and saved its data.";

                            $this->core_layout->saveLog("Company `".strtoupper($warehouse)."` with `$count items` successfully synced and saved its data.", $this->loggedUserName);
                        }else{
                            $result['state'] = false;
                            $result['msg'] = "Warehouse `".strtoupper($warehouse)."` failed to sync and save its data.";

                            $this->core_layout->saveLog("Warehouse `".strtoupper($warehouse)."` failed to sync and save its data.", $this->loggedUserName);
                        }
                    }
                }
            }

            return $result;
        }

        function get_all_warehouse() {
            $this->db->select('id, name, code, path');
            $this->db->from($this->table);
            $this->db->where('status', 1);
            $query = $this->db->get();
            return $query->result_array();
        }
    }