<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Manufactures extends AdminController{
	public $id;

	public function __construct(){
		parent::__construct();
		$this->load->model('manufactures_model');
	}

	public function index(){
		if (!has_permission('inventory', '', 'view')) {
			access_denied('inventory');
		}
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path('inventory', 'manufactures/table'));
		}
		$this->app_scripts->add('circle-progress-js', 'assets/plugins/jquery-circle-progress/circle-progress.min.js');
		$data['title'] = _l('manufactures_tracking');
		$this->load->view('manufactures/manage', $data);
	}

	public function create_view($id = null){
		if (!has_permission('manufactures', '', 'create')) {
			access_denied('manufactures');
		}
		if (isset($id) && $id != '' && is_int($id) && $id > 0) {
			$this->id = $id;
			$data['manufacture'] = $this->manufactures_model->get($this->id);
		}
		if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] == 'json') {
			$data['errorCode'] = 'SUCCESS';
			$data['data_template'] = $this->load->view('manufactures/includes/create_form_view', $data, true);
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

			if ($data['name'] == '') {
				$data['errorCode'] = 'ACTION_ERROR';
				$data['errorMessage'] = _l('Name is required!');
				echo json_encode($data);
				die();
			}

			$detail['name'] = $data['name'];
			$detail['description'] = $data['description'];
			$detail['status'] = isset($data['status']) ? $data['status'] : 'inactive';
			$detail['contract_type'] = 0;
			$detail['staff_id'] = 0;
			$insert_id = $this->manufactures_model->addManufacture($detail);
			if (isset($insert_id) && $insert_id != '') {
				$data['errorCode'] = 'SUCCESS';
				$data['errorMessage'] = _l('Create View Susccess');
			} else {
				$data['errorCode'] = 'ACTION_ERROR';
				$data['errorMessage'] = _l('Error Create Manufacture, please contact admin');
			}

			$data['errorMessage'] = _l('Create View Susccess');

			echo json_encode($data);
		}

	}

	public function edit_view($id = null){
		if (!has_permission('inventory', '', 'edit')) {
			access_denied('inventory');
		}
		if ($this->input->post('id') != null) {
			$this->id = $this->input->post('id');
			$data['manufacture'] = $this->manufactures_model->get($this->id);
		}
		if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] == 'json') {
			$data['errorCode'] = 'SUCCESS';
			$data['data_template'] = $this->load->view('manufactures/includes/create_form_view', $data, true);
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

			if ($data['name'] == '') {
				$data['errorCode'] = 'ACTION_ERROR';
				$data['errorMessage'] = _l('Name is required!');
				echo json_encode($data);
				die();
			}
			$detail['name'] = $data['name'];
			$detail['description'] = $data['description'];
			$detail['status'] = isset($data['status']) ? $data['status'] : 'inactive';
			$detail['contract_type'] = 0;
			$detail['staff_id'] = 0;
			$insert_id = $this->manufactures_model->updateManufacture($detail, $this->input->post('id'));
			if (isset($insert_id) && $insert_id != '') {
				$data['errorCode'] = 'SUCCESS';
				$data['errorMessage'] = _l('Update View Susccess');
			} else {
				$data['errorCode'] = 'ACTION_ERROR';
				$data['errorMessage'] = _l('Error Create Manufacture, please contact admin');
			}

			echo json_encode($data);
		}

	}

	public function delete_view(){
		if (!has_permission('inventory', '', 'delete')) {
			access_denied('inventory');
		}
		if ($this->input->post('id') != null) {
			$this->id = $this->input->post('id');
			$data['manufacture'] = $this->manufactures_model->get($this->id);
		}
		if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] == 'json') {
			$data['errorCode'] = 'SUCCESS';
			$data['data_template'] = $this->load->view('manufactures/includes/delete_form_view', $data, true);
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
				$manufacture = $this->manufactures_model->get($this->id);
				if ($manufacture) {
					$this->manufactures_model->delete($this->id);
					$data['errorCode'] = 'SUCCESS';
					$data['errorMessage'] = _l('Delete Susccess');
				} else {
					$data['errorCode'] = 'ACTION_ERROR';
					$data['errorMessage'] = _l('Error Create Manufacture, please contact admin');
				}
			} else {
				$data['errorCode'] = 'ACTION_ERROR';
				$data['errorMessage'] = _l('Error Create Manufacture, please contact admin');
			}
			echo json_encode($data);
		}

	}
}
