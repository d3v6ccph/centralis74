<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rr_head_m extends CI_Model {

	var $table = 'gccis.rr_head';
	var $table2 = 'gccis.warehouse_location';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}	

	function get_datatables()
	{
		$this->db->from($this->table);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->db->from($this->table);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}

	function get_query_builder_result($qry)
	{

		$this->db->select('rb.id as body_id,rb.*,rh.id as head_id,rh.*'); 
		$this->db->from('rr_head rh');
		$this->db->join('rr_body rb', 'rh.id = rb.rr_headid', 'left');
		$this->db->where($qry);
		$query = $this->db->get();
		return $query->result();
	}
	public function select()
	{
		$this->db->from($this->table2);
		$query = $this->db->get();
		return $query->result();
	}
}
