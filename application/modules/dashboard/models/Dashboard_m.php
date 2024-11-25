<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Dashboard_m extends CI_Model {
        protected $table = 'tblsynced_data';
        protected $warehouseTable = 'tblwarehouse';

        function __construct() {
            parent::__construct();
        }

        function get_all_warehouse(){
            $result = array();

            $this->db->select('COUNT(a.site_id) as total_items, UPPER(b.name) as warehouse');
            $this->db->join($this->warehouseTable.' as b', 'b.id = a.site_id', 'LEFT');
            $this->db->group_by('a.site_id');
            $this->db->from($this->table.' as a');
            $query = $this->db->get();

            if($query->num_rows() > 0){
                $result = $query->result();
            }

            return array('data' => $result);
        }
    }