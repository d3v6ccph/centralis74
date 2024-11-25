<?php defined('BASEPATH') OR exit('No direct script access allowed');
Class Login_m extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function login($username, $password){ 
		$this->db->select('tblusers.*,tblemployees.id as emp_id ,tblemployees.firstname,tblemployees.lastname, tblemployees.middlename, tblemployees.suffix, tblemployees.company_id, tblemployees.department_id');
		$this->db->from('tblusers');
		$this->db->join('tblemployees','tblemployees.id = tblusers.emp_id');
		$this->db->where('tblusers.username', $username);
		$this->db->where('tblusers.password', MD5($password));
		$this->db->limit(1);

		$query = $this->db->get();
		
		if($query->num_rows() == 1){
		  return $query->result();
		}else{
		  return false;
		}
	}

	function update_log($where, $data){
		$this->db->update('tblusers', $data, $where);
		return $this->db->affected_rows();
	}

	function get_privileges_by_id($id){
		$this->db->select('tblprivilegeusers.privilege_id, tblprivileges.privilege_name');
		$this->db->from('tblprivilegeusers');
		$this->db->join('tblprivileges','tblprivileges.id = tblprivilegeusers.privilege_id');
		$this->db->where('tblprivilegeusers.user_id',$id);
		$this->db->order_by('tblprivilegeusers.privilege_id','asc');
		$query = $this->db->get();
		
		return $query->result();
	}

	public function get_by_uname($uname){
		$this->db->from('tblusers');
		$this->db->where('username',$uname);
		$query = $this->db->get();
		return $query->row();
	}

    function direct_login($username, $password){
        $this->db->select('gccmaster.tblusers.*,gccmaster.tblemployees.id as emp_id ,gccmaster.tblemployees.firstname, 
			gccmaster.tblemployees.lastname, gccmaster.tblemployees.middlename, gccmaster.tblemployees.suffix, 
			gccmaster.tblemployees.company_id, gccmaster.tblemployees.department_id');

        $this->db->from('gccmaster.tblusers');
        $this->db->join('gccmaster.tblemployees','gccmaster.tblemployees.id = gccmaster.tblusers.emp_id');
        $this->db->where('username', $username);
        $this->db->where('password', $password);

        $query = $this->db->get();
        if($query->num_rows() == 1){
            return $query->row();
        }else{
            return false;
        }
    }
}