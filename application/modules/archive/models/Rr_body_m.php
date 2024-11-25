<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rr_body_m extends CI_Model {

	var $table = 'gccis.rr_body';

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
	function get_datatables2($id)
	{
		$this->db->from($this->table);
		$this->db->where('rr_headid',$id);
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

	public function get_by_rr_head($id)
	{
		$this->db->select('id, item_code, qty,uom,unit_cost,po_qty,total_amount,inventoryCode,item_barcode,description'); 
		$this->db->from($this->table);
		$this->db->where('rr_headid',$id);
		$query = $this->db->get();
		return $query->result();
	}

	/*public function get_by_rr_head2($id)
	{
		$this->db->select('rr.id, rr.item_code, rr.qty,rr.uom,rr.unit_cost,rr.po_qty,rr.total_amount,items.item_description,rr.description as rd,rr.item_barcode,rr.inventoryCode'); 
		$this->db->from('rr_body rr');
		$this->db->join('items items', 'rr.inventoryCode = items.inventoryCode', 'left');
		$this->db->where('rr_headid',$id);
		$query = $this->db->get();
		return $query->result();
	}*/

	public function get_by_rr_head2($id)
	{
		$this->db->select('rr.id, rr.item_code, rr.qty,rr.uom,rr.unit_cost,rr.po_qty,rr.total_amount,rr.description as rd,rr.item_barcode,rr.inventoryCode'); 
		$this->db->from('rr_body rr');
		$this->db->where('rr.rr_headid',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function autocomplete2($itemcode,$warehouse)
	{		
		$this->db->select('repo.itemcode as itemcode,repo.balance as balance, items.description as description');
		$this->db->from('repository repo');
		$this->db->join('gccmaster.items items', 'repo.itemcode = items.stockCode', 'left');
		$this->db->like('repo.itemcode',$itemcode);
		$this->db->where('repo.balance >=',1);
		$this->db->where('repo.warehouse',$warehouse);
		$this->db->group_by('repo.itemcode','ASC'); 	
		$query = $this->db->get();
		echo json_encode($query->result_array());
	}

	/*
	public function autocomplete2($itemcode,$warehouse)
	{		
		$this->db->select('repo.itemcode as itemcode,repo.balance as balance, items.description as description');
		$this->db->from('repository repo');
		$this->db->join('gccmaster.items items', 'repo.itemcode = items.barcode', 'left');
		$this->db->like('repo.itemcode',$itemcode);
		$this->db->where('repo.balance >=',1);
		$this->db->where('repo.warehouse',$warehouse);
		$this->db->group_by('repo.itemcode','ASC'); 	
		$query = $this->db->get();
		echo json_encode($query->result_array());
	}*/

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function save_asset($data)
	{
		$this->db->insert('gccasset.gccis_repo', $data);
		return $this->db->insert_id();
	}

	public function get_asset($id)
	{
		$this->db->select('assetacode'); 
		$this->db->from('gccasset.assets');
		$this->db->where('assetacode',$id);
		$query = $this->db->get();
		return $query->result();
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

	public function delete_by_headid($id)
	{
		$this->db->where('rr_headid', $id);
		$this->db->delete($this->table);
	}

	public function total_cost($id)
    {
    	
		$this->db->select('sum(total_amount) as total',false);  
		$this->db->from($this->table);
		$this->db->where('rr_headid',$id);
		$query = $this->db->get();		
	 	return $query->result();	
    }    

}
