<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends AdminController{
	public $id;
	public $CI;
	public $clients;
	public $products;
	public $client_id;
	public $status;

	public function __construct(){
		parent::__construct();
		$this->load->model('orders_model');
		$this->load->model('products_model');
		$this->load->model('clients_model');
		$this->CI =& get_instance();
	}

	public function index(){
		if (!has_permission('inventory', '', 'view')) {
			access_denied('inventory');
		}
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path('inventory', 'orders/table'));
		}
		$this->app_scripts->add('circle-progress-js', 'assets/plugins/jquery-circle-progress/circle-progress.min.js');
		$data['title'] = _l('orders_tracking');
		$this->load->view('orders/manage', $data);
	}

	public function prepareData(){
		$this->clients = $this->clients_model->get();
		$this->products = $this->products_model->get_all_products();
		$status = array();
		$status['inprocessing'] = "In Progresss";
		$status['cancel'] = "Cancel";
		$status['completed'] = "Completed";
		$this->status = $status;
	}

	public function create_view($id = null){
		if (!has_permission('inventory', '', 'create')) {
			access_denied('inventory');
		}
		if (isset($id) && $id != '' && is_int($id) && $id > 0) {
			$this->id = $id;
			$data['order'] = $this->orders_model->get($this->id);
		}
		$this->prepareData();
		$data['products'] = $this->products;
		$data['clients'] = $this->clients;
		$data['status'] = $this->status;
		if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] == 'json') {
			$data['errorCode'] = 'SUCCESS';
			$data['data_template'] = $this->load->view('orders/includes/create_form_view', $data, true);
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

			if ($data['order_client_id'] == '' || $data['order_quantity'] == '' || $data['order_product_id'] == '') {
				$data['errorCode'] = 'ACTION_ERROR';
				$data['errorMessage'] = _l('Customer , Quantity, Product is required!');
				echo json_encode($data);
				die();
			}

			$product_ids = explode(",", $data['order_product_id']);

			$detail['order_client_id'] = $data['order_client_id'];
			$detail['order_quantity'] = $data['order_quantity'];
			$detail['order_note'] = $data['order_note'];
			$detail['order_number'] = $data['order_number'];
			$detail['order_status'] = isset($data['order_status']) ? $data['order_status'] : 'inprocessing';
			$detail['create_order_date'] = _d(date('Y-m-d'));
			$detail['due_order_date'] = isset($data['due_order_date']) ? _d($data['due_order_date']) : _d(date('Y-m-d'));
			$detail['order_product_id'] = $data['order_product_id'];

			foreach ($product_ids as $product_id) {
				$product = $this->products_model->get($product_id);
				if ($product->quantity < $data['order_quantity']) {
					$data['errorCode'] = 'ACTION_ERROR';
					$data['errorMessage'] = _l('Quantity order can not > quantity of inventory');
					echo json_encode($data);
					die();
				}
			}
			$insert_id = $this->orders_model->addOrder($detail);
			if (isset($insert_id) && $insert_id != '') {
				foreach ($product_ids as $product_id) {
					$quantityUpdate = (int)$product_id;
					if ($data['order_status'] === 'inprocessing') {
						$quantityUpdate = (int)$product->quantity - (int)$data['order_quantity'];
						if ($quantityUpdate < 0) {
							$quantityUpdate = 0;
						}
					}
					if ($data['order_status'] !== 'inprocessing') {
						$quantityUpdate = (int)$product->quantity + (int)$data['order_quantity'];
					}
					$productUpdate['quantity'] = $quantityUpdate;
					$this->products_model->updateProduct($productUpdate, $data['order_product_id']);
				}
				$data['errorCode'] = 'SUCCESS';
				$data['errorMessage'] = _l('Create order Susccess');
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
			$data['order'] = $this->orders_model->get($this->id);
		}
		$this->prepareData();
		$data['products'] = $this->products;
		$data['clients'] = $this->clients;
		$data['status'] = $this->status;
		if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] == 'json') {
			$data['errorCode'] = 'SUCCESS';
			$data['data_template'] = $this->load->view('orders/includes/create_form_view', $data, true);
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
			$detail['order_client_id'] = $data['order_client_id'];
			$detail['order_quantity'] = $data['order_quantity'];
			$detail['order_note'] = $data['order_note'];
			$detail['order_number'] = $data['order_number'];
			$detail['order_status'] = isset($data['order_status']) ? $data['order_status'] : 'inprocessing';
			$detail['due_order_date'] = isset($data['due_order_date']) ? _d($data['due_order_date']) : _d(date('Y-m-d'));
			$detail['order_product_id'] = $data['order_product_id'];
			$this->orders_model->updateProduct($detail, $this->input->post('order_id'));
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
			$data['order'] = $this->orders_model->get($this->id);
		}
		if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] == 'json') {
			$data['errorCode'] = 'SUCCESS';
			$data['data_template'] = $this->load->view('orders/includes/delete_form_view', $data, true);
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
				$order = $this->orders_model->get($this->id);
				if ($order) {
					$this->orders_model->delete($this->id);
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
		if ($this->input->post('id') != null && $this->input->post('order_id') != null) {
			$id = $this->input->post('id');
			$order_id = $this->input->post('order_id');
			$this->id = $order_id;
			$file = $this->orders_model->get_file($id);
			if ($file) {
				$this->orders_model->remove_file($id);
				$data['order'] = $this->orders_model->get($order_id);
				$data['id'] = $data['order']->order_id;
				$result = handle_order_file_uploads($order_id);
				$data['errorCode'] = 'SUCCESS';
				$data['errorMessage'] = _l('Delete Images Successfully');
				$data["files"] = $this->orders_model->get_files($order_id, get_staff_user_id());
				$data['view_file'] = $this->load->view('orders/includes/order_file_data', $data, true);
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
		if ($this->input->post('order_id') != null) {
			$this->id = $this->input->post('order_id');
			$data['order'] = $this->orders_model->get($this->id);
			$data['id'] = $this->id;
			$result = handle_order_file_uploads($this->id);
			$data['errorCode'] = 'SUCCESS';
			$data['errorMessage'] = _l('Add Images Successfully');
			$data["files"] = $this->orders_model->get_files($this->id, get_staff_user_id());
			$data['view_file'] = $this->load->view('orders/includes/order_file_data', $data, true);
			echo json_encode($data);
		} else {
			$data['errorCode'] = 'ACTION_ERROR';
			$data['errorMessage'] = _l('Not found order to add images');
			echo json_encode($data);
		}

	}

}
