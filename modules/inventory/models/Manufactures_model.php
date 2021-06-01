<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Manufactures_model extends App_Model{
	public function __construct(){
		parent::__construct();
	}


	public function get($id = '', $exclude_notified = false){
		$this->db->where('id', $id);

		$result = $this->db->get(db_prefix() . 'manufactures')->row();
		//print_r($this->db->last_query());
		return $result;
	}

	public function updateManufacture($dataupdate, $id){

		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'manufactures', $dataupdate);

		//print_r($this->db->last_query());

		if ($this->db->affected_rows() > 0) {
			log_activity('Hanufacture Updated [ID:' . $id . ']');

			return true;
		}

		return false;
	}

	public function get_all_manufactures(){
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'desc');
		$manufactures = $this->db->get(db_prefix() . 'manufactures')->result_array();
		return array_values($manufactures);
	}

	public function addManufacture($data){
		$this->db->insert(db_prefix() . 'manufactures', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function delete($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'manufactures');
		if ($this->db->affected_rows() > 0) {
			log_activity('Manufacture Deleted [ID:' . $id . ']');

			return true;
		}

		return false;
	}

	public function get_staff_manufactures($staff_id, $exclude_notified = true){
		$this->db->where('staff_id', $staff_id);

		if ($exclude_notified) {
			$this->db->where('notified', 0);
		}

		$this->db->order_by('due_date', 'asc');
		$manufactures = $this->db->get(db_prefix() . 'manufactures')->result_array();

		return $manufactures;
	}

}


