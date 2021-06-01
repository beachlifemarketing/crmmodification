<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Products extends AdminController{
	public $id;
	public $CI;
	public $manufactures;
	public $clients;
	public $files;
	public $client_id;

	public function __construct(){
		parent::__construct();
		$this->load->model('products_model');
		$this->load->model('manufactures_model');
		$this->load->model('clients_model');
		$this->CI =& get_instance();
	}

	public function index(){
		if (!has_permission('products', '', 'view')) {
			access_denied('products');
		}
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path('inventory', 'products/table'));
		}
		$this->app_scripts->add('circle-progress-js', 'assets/plugins/jquery-circle-progress/circle-progress.min.js');
		$data['title'] = _l('products_tracking');
		$this->load->view('products/manage', $data);
	}

	public function prepareData(){
		$this->manufactures = $this->manufactures_model->get_all_manufactures();
		if (isset($this->id) && $this->id != '') {
			$this->files = $this->products_model->get_files($this->id, get_staff_user_id());
		} else {
			$this->files = [];
		}


	}

	public function create_view($id = null){
		if (!has_permission('inventory', '', 'create')) {
			access_denied('inventory');
		}
		if (isset($id) && $id != '' && is_int($id) && $id > 0) {
			$this->id = $id;
			$data['product'] = $this->products_model->get($this->id);
		}
		$this->prepareData();
		$data['manufactures'] = $this->manufactures;
		$data['files'] = $this->files;
		$data['tab'] = 'product_info';
		if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] == 'json') {
			$data['errorCode'] = 'SUCCESS';
			$data['data_template'] = $this->load->view('products/includes/create_form_view', $data, true);
			$data['errorMessage'] = _l('Load Create View Susccess');
			echo json_encode($data);
		}

	}

	public function create(){
		if (!has_permission('inventory', '', 'create')) {
			access_denied('inventory');
		}

		if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] == 'json') {
			$data = $this->input->post();

			if ($data['product_name'] == '' || $data['quantity'] == '' || $data['manufacturer_id'] == '') {
				$data['errorCode'] = 'ACTION_ERROR';
				$data['errorMessage'] = _l('Name, quantity, Manufacturer is required!');
				echo json_encode($data);
				die();
			}


			$detail['product_name'] = $data['product_name'];
			$detail['product_code'] = $data['product_code'];
			$detail['product_description'] = $data['product_description'];
			$detail['product_status'] = isset($data['product_status']) ? $data['product_status'] : 'inactive';
			$detail['date_create'] = date('Y-m-d');
			$detail['quantity'] = $data['quantity'];
			$detail['manufacturer_id'] = $data['manufacturer_id'];
			$insert_id = $this->products_model->addProduct($detail);
			if (isset($insert_id) && $insert_id != '') {
				$data['product_id'] = $insert_id;
				$this->id = $insert_id;
				$this->prepareData();
				$data['manufactures'] = $this->manufactures;
				$data['product'] = $this->products_model->get($insert_id);
				$data['files'] = $this->files;
				$data['errorCode'] = 'SUCCESS';
				$data['errorMessage'] = _l('Create product Susccess, now! you can update images');
				$data['tab'] = 'product_images';
				$data['data_template'] = $this->load->view('products/includes/create_form_view', $data, true);
			} else {
				$data['errorCode'] = 'ACTION_ERROR';
				$data['errorMessage'] = _l('Error Create Product, please contact admin');
			}

			echo json_encode($data);
		}

	}

	public function edit_view($id = null){
		if (!has_permission('inventory', '', 'edit')) {
			access_denied('inventory');
		}
		if ($this->input->post('id') != null) {
			$this->id = $this->input->post('id');
			$data['product'] = $this->products_model->get($this->id);
		}
		$this->prepareData();
		$data['manufactures'] = $this->manufactures;
		$data['files'] = $this->files;
		$data['tab'] = 'product_info';
		if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] == 'json') {
			$data['errorCode'] = 'SUCCESS';
			$data['data_template'] = $this->load->view('products/includes/create_form_view', $data, true);
			$data['errorMessage'] = _l('Load Edit View Susccess');
			echo json_encode($data);
		}

	}

	public function edit(){
		if (!has_permission('inventory', '', 'edit')) {
			access_denied('inventory');
		}
		if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] == 'json') {
			$data = $this->input->post();

			if ($data['product_name'] == '' || $data['quantity'] == '' || $data['manufacturer_id'] == '') {
				$data['errorCode'] = 'ACTION_ERROR';
				$data['errorMessage'] = _l('Name, quantity, Manufacturer is required!');
				echo json_encode($data);
				die();
			}
			$detail['product_name'] = $data['product_name'];
			$detail['product_code'] = $data['product_code'];
			$detail['product_description'] = $data['product_description'];
			$detail['product_status'] = isset($data['product_status']) ? $data['product_status'] : 'inactive';
			$detail['date_create'] = date('Y-m-d');
			$detail['quantity'] = $data['quantity'];
			$detail['manufacturer_id'] = $data['manufacturer_id'];
			$this->products_model->updateProduct($detail, $this->input->post('product_id'));
			$data['errorCode'] = 'SUCCESS';
			$data['errorMessage'] = _l('Update Product Susccess');
		} else {
			$data['errorCode'] = 'ACTION_ERROR';
			$data['errorMessage'] = _l('Error Update Product, please contact admin');
		}
		echo json_encode($data);

	}

	public function delete_view(){
		if (!has_permission('inventory', '', 'delete')) {
			access_denied('inventory');
		}
		if ($this->input->post('id') != null) {
			$this->id = $this->input->post('id');
			$data['product'] = $this->products_model->get($this->id);
		}
		if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] == 'json') {
			$data['errorCode'] = 'SUCCESS';
			$data['data_template'] = $this->load->view('products/includes/delete_form_view', $data, true);
			$data['errorMessage'] = _l('Load Delete View Susccess');
			echo json_encode($data);
		}

	}

	public function delete(){
		if (!has_permission('inventory', '', 'delete')) {
			access_denied('inventory');
		}
		if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] == 'json') {
			$id = $this->input->post('id');
			if (isset($id) && $id != '') {
				$this->id = $id;
				$product = $this->products_model->get($this->id);
				if ($product) {
					$this->products_model->delete($this->id);
					$data['errorCode'] = 'SUCCESS';
					$data['errorMessage'] = _l('Delete Susccess');
				} else {
					$data['errorCode'] = 'ACTION_ERROR';
					$data['errorMessage'] = _l('Error Create Product, please contact admin');
				}
			} else {
				$data['errorCode'] = 'ACTION_ERROR';
				$data['errorMessage'] = _l('Error Create Product, please contact admin');
			}
			echo json_encode($data);
		}

	}

	public function remove_file(){
		if ($this->input->post('id') != null && $this->input->post('product_id') != null) {
			$id = $this->input->post('id');
			$product_id = $this->input->post('product_id');
			$this->id = $product_id;
			$file = $this->products_model->get_file($id);
			if ($file) {
				$this->products_model->remove_file($id);
				$data['product'] = $this->products_model->get($product_id);
				$data['id'] = $data['product']->product_id;
				$result = handle_product_file_uploads($product_id);
				$data['errorCode'] = 'SUCCESS';
				$data['errorMessage'] = _l('Delete Images Successfully');
				$data["files"] = $this->products_model->get_files($product_id, get_staff_user_id());
				$data['view_file'] = $this->load->view('products/includes/product_file_data', $data, true);
				echo json_encode($data);
			} else {
				$data['errorCode'] = 'ACTION_ERROR';
				$data['errorMessage'] = _l('Not found file to delete');
				echo json_encode($data);
			}
		} else {
			$data['errorCode'] = 'ACTION_ERROR';
			$data['errorMessage'] = _l('Not found file to delete');
			echo json_encode($data);
		}
	}

	public function upload_file(){
		$this->CI->load->helper('inventory_helper');
		if ($this->input->post('product_id') != null) {
			$this->id = $this->input->post('product_id');
			$data['product'] = $this->products_model->get($this->id);
			$data['id'] = $this->id;
			$result = handle_product_file_uploads($this->id);
			$data['errorCode'] = 'SUCCESS';
			$data['errorMessage'] = _l('Add Images Successfully');
			$data["files"] = $this->products_model->get_files($this->id, get_staff_user_id());
			$data['view_file'] = $this->load->view('products/includes/product_file_data', $data, true);
			echo json_encode($data);
		} else {
			$data['errorCode'] = 'ACTION_ERROR';
			$data['errorMessage'] = _l('Not found product to add images');
			echo json_encode($data);
		}

	}

}
