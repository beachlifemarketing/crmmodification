<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Orders_model extends App_Model{
	public function __construct(){
		parent::__construct();
	}


	public function get($id = '', $exclude_notified = false){
		$this->db->where('order_id', $id);

		$result = $this->db->get(db_prefix() . 'orders')->row();
		//print_r($this->db->last_query());
		return $result;
	}

	public function updateOrder($dataupdate, $id){

		$this->db->where('order_id', $id);
		$this->db->update(db_prefix() . 'orders', $dataupdate);

		//print_r($this->db->last_query());

		if ($this->db->affected_rows() > 0) {
			log_activity('Order Updated [ID:' . $id . ']');

			return true;
		}

		return false;
	}

	public function get_all_orders(){
		$this->db->where('status', 'active');
		$this->db->order_by('name', 'desc');
		$orders = $this->db->get(db_prefix() . 'orders')->result_array();
		return array_values($orders);
	}

	public function addOrder($data){
		$this->db->insert(db_prefix() . 'orders', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function delete($id){
		$this->db->where('order_id', $id);
		$this->db->delete(db_prefix() . 'orders');
		if ($this->db->affected_rows() > 0) {
			log_activity('Order Deleted [ID:' . $id . ']');

			return true;
		}

		return false;
	}

	public function get_staff_orders($staff_id, $exclude_notified = true){
		$this->db->where('staff_id', $staff_id);

		if ($exclude_notified) {
			$this->db->where('notified', 0);
		}

		$this->db->order_by('due_date', 'asc');
		$orders = $this->db->get(db_prefix() . 'orders')->result_array();

		return $orders;
	}

	public function get_files($order_id, $staffid = null){
		if ($order_id != null) {
			if (is_client_logged_in()) {
				$this->db->where('visible_to_customer', 1);
			}
			$this->db->where('order_id', $order_id);
			if (isset($staffid)) {
				$this->db->where('staffid', $staffid);
			}
			$data = $this->db->get(db_prefix() . 'order_files')->result_array();
			//print_r($this->db->last_query());
			return $data;
		} else {
			return array();
		}
	}

	public function remove_file($id, $logActivity = true){
		hooks()->do_action('before_remove_order_file', $id);
		$this->db->where('id', $id);
		$file = $this->db->get(db_prefix() . 'order_files')->row();
		if ($file) {
			if (empty($file->external)) {
				$path = get_upload_path_by_type('order') . $file->order_id . '/';
				$fullPath = $path . $file->file_name;
				if (file_exists($fullPath)) {
					unlink($fullPath);
					$fname = pathinfo($fullPath, PATHINFO_FILENAME);
					$fext = pathinfo($fullPath, PATHINFO_EXTENSION);
					$thumbPath = $path . $fname . '_thumb.' . $fext;

					if (file_exists($thumbPath)) {
						unlink($thumbPath);
					}
				}
			}
			$this->db->where('id', $id);
			$this->db->delete(db_prefix() . 'order_files');
			if (is_dir(get_upload_path_by_type('order') . $file->order_id)) {
				// Check if no attachments left, so we can delete the folder also
				$other_attachments = list_files(get_upload_path_by_type('order') . $file->order_id);
				if (count($other_attachments) == 0) {
					delete_dir(get_upload_path_by_type('order') . $file->order_id);
				}
			}
			return true;
		}

		return false;
	}


	public function get_file($id){
		if (is_client_logged_in()) {
			$this->db->where('visible_to_customer', 1);
		}
		$this->db->where('id', $id);
		$file = $this->db->get(db_prefix() . 'order_files')->row();

		return $file;
	}

	public function update_file_data($data){
		$this->db->where('id', $data['id']);
		unset($data['id']);
		$this->db->update(db_prefix() . 'order_files', $data);
	}

}


