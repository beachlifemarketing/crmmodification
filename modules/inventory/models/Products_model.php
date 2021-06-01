<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Products_model extends App_Model{
	public function __construct(){
		parent::__construct();
	}


	public function get($id = '', $exclude_notified = false){
		$this->db->where('product_id', $id);

		$result = $this->db->get(db_prefix() . 'products')->row();
		//print_r($this->db->last_query());
		return $result;
	}

	public function updateProduct($dataupdate, $id){

		$this->db->where('product_id', $id);
		$this->db->update(db_prefix() . 'products', $dataupdate);

		//print_r($this->db->last_query());

		if ($this->db->affected_rows() > 0) {
			log_activity('Hanufacture Updated [ID:' . $id . ']');

			return true;
		}

		return false;
	}

	public function get_all_products(){
		$this->db->where('product_status', 'active');
		$this->db->order_by('product_name', 'desc');
		$products = $this->db->get(db_prefix() . 'products')->result_array();
		return array_values($products);
	}

	public function addProduct($data){
		$this->db->insert(db_prefix() . 'products', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function delete($id){
		$files = $this->get_files($id);
		foreach ($files as $file) {
			$this->remove_file($file->id);
		}
		$this->db->where('product_id', $id);
		$this->db->delete(db_prefix() . 'products');
		if ($this->db->affected_rows() > 0) {
			log_activity('Product Deleted [ID:' . $id . ']');

			return true;
		}

		return false;
	}

	public function get_staff_products($staff_id, $exclude_notified = true){
		$this->db->where('staff_id', $staff_id);

		if ($exclude_notified) {
			$this->db->where('notified', 0);
		}

		$this->db->order_by('due_date', 'asc');
		$products = $this->db->get(db_prefix() . 'products')->result_array();

		return $products;
	}

	public function get_files($product_id, $staffid = null){
		if ($product_id != null) {
			if (is_client_logged_in()) {
				$this->db->where('visible_to_customer', 1);
			}
			$this->db->where('product_id', $product_id);
			if (isset($staffid)) {
				$this->db->where('staffid', $staffid);
			}
			$data = $this->db->get(db_prefix() . 'product_files')->result_array();
			//print_r($this->db->last_query());
			return $data;
		} else {
			return array();
		}
	}

	public function remove_file($id, $logActivity = true){
		hooks()->do_action('before_remove_product_file', $id);
		$this->db->where('id', $id);
		$file = $this->db->get(db_prefix() . 'product_files')->row();
		if ($file) {
			if (empty($file->external)) {
				$path = get_upload_path_by_type('product') . $file->product_id . '/';
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
			$this->db->delete(db_prefix() . 'product_files');
			if (is_dir(get_upload_path_by_type('product') . $file->product_id)) {
				// Check if no attachments left, so we can delete the folder also
				$other_attachments = list_files(get_upload_path_by_type('product') . $file->product_id);
				if (count($other_attachments) == 0) {
					delete_dir(get_upload_path_by_type('product') . $file->product_id);
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
		$file = $this->db->get(db_prefix() . 'product_files')->row();

		return $file;
	}

	public function update_file_data($data){
		$this->db->where('id', $data['id']);
		unset($data['id']);
		$this->db->update(db_prefix() . 'product_files', $data);
	}

}


