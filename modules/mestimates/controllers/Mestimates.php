<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mestimates extends AdminController
{
    private $CI;
    public $estimate_id;
    public $client_id;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mestimates_model');
        $this->load->model('mestimates_detail_model');
        $this->load->model('clients_model');
        $this->CI = &get_instance();

        if ($this->input->post('mestimate_id') != null) {
            $this->estimate_id = $this->input->post('mestimate_id');
        }

        if ($this->input->post('client_id') != null) {
            $this->client_id = $this->input->post('client_id');
        }
    }

    /* List all announcements */
    public function index()
    {
        if (!has_permission('mestimates', '', 'view')) {
            access_denied('mestimates');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('mestimates', 'table'));
        }
        $this->app_scripts->add('circle-progress-js', 'assets/plugins/jquery-circle-progress/circle-progress.min.js');
        $data['title'] = _l('mestimates_tracking');
        $this->load->view('manage', $data);
    }


    public function mestimate()
    {
        if (!has_permission('mestimates', '', 'view')) {
            access_denied('mestimates');
        }

        $contactId = null;
        $data['details'] = [];
        if ($this->estimate_id == '' || $this->estimate_id == 0) {
            $title = _l('add_new', _l('mestimate_lowercase'));
        } else {
            $data['mestimate'] = $this->mestimates_model->get($this->estimate_id);
            $title = _l('edit', _l('mestimate_lowercase'));
            $this->CI->load->helper('mestimates_helper');
            $data = array();
            $data['client'] = $this->clients_model->get($data['mestimate']->client_id);
            $this->client_id = $data['mestimate']->client_id;
            $contacts = $this->clients_model->get_contacts($data['mestimate']['client_id'], ['active' => 1, 'is_primary' => 1, 'userid' => $data['mestimate']->client_id]);
            if (count($contacts) > 0) {
                $contactObj = (object)$contacts[0];
                $contactId = $contactObj->id;
                $data["contact"] = $contactObj;
            }

            $contacts = $this->clients_model->get_contacts($this->client_id, ['active' => 1, 'is_primary' => 1, 'userid' => $data['mestimate']->client_id]);
            if (count($contacts) > 0) {
                $contactObj = (object)$contacts[0];
                $contactId = $contactObj->id;
                $data["contact"] = $contactObj;
            }
            $data['details'] = $this->mestimates_detail_model->getByMestimate($this->estimate_id);
        }
        if ($this->client_id !== null) {
            $this->CI->load->helper('mestimates_helper');
            $data = array();
            $data['client'] = $this->clients_model->get($this->client_id);
            $contacts = $this->clients_model->get_contacts($this->client_id, ['active' => 1, 'is_primary' => 1, 'userid' => $this->client_id]);
            if (count($contacts) > 0) {
                $contactObj = (object)$contacts[0];
                $contactId = $contactObj->id;
                $data["contact"] = $contactObj;
            }
            $data['client_id'] = $this->client_id;
        }

        $data['files'] = $this->mestimates_model->get_files($this->estimate_id, get_staff_user_id(), $this->client_id);

        $data['title'] = $title;

        $data['groups'] = $this->clients_model->get_groups();
        $data['templates'] = $this->mestimates_model->get_all_template();
        $clients = $this->clients_model->get();
        $data['clients'] = $clients;
        $this->app_scripts->add('circle-progress-js', 'assets/plugins/jquery-circle-progress/circle-progress.min.js');
        if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] === 'json') {
            $data['errorCode'] = 'SUCCESS';
            $data['view_address'] = $this->load->view('mestimates/includes/info_company', $data, true);
            $data['view_file'] = $this->load->view('mestimates/includes/mestimate_files.php', $data, true);
            $data['errorMessage'] = _l('load_info_client_success');
            echo json_encode($data);
        } else {
            $this->load->view('mestimate', $data);
        }
    }

    public function estimate_do()
    {
        if ($this->input->post() != null && $this->input->post()['undefined']) {
            unset($this->input->post()['undefined']);
        }
        if (!has_permission('mestimates', '', 'create')) {
            access_denied('mestimates');
        }
        if ($this->input->post()) {
            if (!isset($_REQUEST['sat'])) {
                $sat = 'active';
            } else {
                $sat = $_REQUEST['sat'];
            }
            if ($this->client_id == null) {
                $data['errorCode'] = "ACTION_ERROR";
                $data['errorMessage'] = _l('select_client', _l('mestimate'));
                echo json_encode($data);
                die();
            } else {

                if ($this->estimate_id == '' || $this->estimate_id == 0) {
                    $mestimate_id = $this->build_base_mestimate($this->input->post(), $this->client_id, $this->estimate_id, $sat);
                    $this->build_base_detail($this->input->post(), $mestimate_id, $this->client_id);
                    if ($this->input->post('image_ids') != null) {
                        $this->update_mestimate_file($this->input->post('image_ids'), $this->estimate_id);
                    }

                    $data['errorCode'] = 'SUCCESS';
                    $data['errorMessage'] = _l('added_successfully', _l('mestimate'));
                    echo json_encode($data);
                } else {
                    if (!has_permission('mestimates', '', 'edit')) {
                        access_denied('mestimates');
                    }
                    $success = $this->build_base_mestimate($this->input->post(), $this->client_id, $this->estimate_id, $sat);
                    $this->build_base_detail($this->input->post(), $this->estimate_id, $this->client_id);
                    if ($this->input->post('image_ids') != null) {
                        $this->update_mestimate_file($this->input->post('image_ids'), $this->estimate_id);
                    }
                    $data['errorCode'] = "SUCCESS";
                    $data['errorMessage'] = _l('updated_successfully', _l('mestimate'));
                    echo json_encode($data);
                }
            }
        } else {
            $data['errorCode'] = 'ACTION_ERROR';
            $data['errorMessage'] = _l('access_denied');
            echo json_encode($data);
        }
    }

    /* Delete announcement from database */
    public function delete($id)
    {
        if (!has_permission('mestimates', '', 'delete')) {
            access_denied('mestimates');
        }
        if (!$id) {
            redirect(admin_url('mestimates'));
        }
        $response = $this->mestimates_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('mestimate')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('mestimate_lowercase')));
        }
        redirect(admin_url('mestimates'));
    }

    function build_base_mestimate($data, $client_id, $mestimate_id, $sat = 'active')
    {
        $dataupdate = [];
        $dataupdate['status'] = $sat;
        $dataupdate['contact_id'] = $client_id;
        $dataupdate['job_number'] = $data['job_number'];
        $dataupdate['customer_group_id'] = $data['group_id'];
        $dataupdate['claim_number'] = $data['claim_number'];
        $dataupdate['date'] = $data['date'];
        $dataupdate['lic_number'] = $data['lic_number'];
        $dataupdate['pymt_option'] = $data['pymt_option'];
        $dataupdate['group_id'] = $data['group_id'];
        $dataupdate['representative'] = $data['representative'];
        $dataupdate['paid_by_customer_text'] = $data['paid_by_customer_text'];
        $dataupdate['balance_due_val'] = $data['balance_due_val'];
        $dataupdate['balance_due_text'] = $data['balance_due_text'];
        $dataupdate['balance_due'] = $data['balance_due'];
        $dataupdate['paid_by_customer_percent'] = $data['paid_by_customer_percent'];
        $dataupdate['total'] = $data['total'];
        $dataupdate['discount_val'] = $data['discount_val'];
        $dataupdate['discount'] = $data['discount'];
        $dataupdate['sub_total'] = $data['sub_total'];

        $dataupdate['staff_id'] = get_staff_user_id();
        if ($mestimate_id > 0) {
            return $this->mestimates_model->updateMestimate($dataupdate, $mestimate_id);
        } else {
            return $this->mestimates_model->addMestimate($dataupdate);
        }
    }

    function build_base_detail($data, $mestimate_id, $client_id)
    {
        $data =
        $countEmelement = count($data['detail']['are']);

        for ($i = 0; $i < $countEmelement; $i++) {
            $detail['mestimate_id'] = $mestimate_id;
            $detail['contact_id'] = $client_id;
            $detail['area'] = $data['detail']['area'];
            $detail['size'] = $data['detail']['size'];
            $detail['qty'] = $data['detail']['qty'];
            $detail['qty_unit'] = $data['detail']['qty_unit'];
            $detail['px'] = $data['detail']['px'];
            $detail['px_unit'] = $data['detail']['px_unit'];
            $detail['duration'] = $data['detail']['duration'];
            $detail['duration_unit'] = $data['detail']['duration_unit'];
            $detail['status'] = 1;
            $this->mestimates_detail_model->deleteByMestimate($data['mestimate_id']);
            $this->mestimates_detail_model->add($detail);
        }


    }

    function update_mestimate_file($image_ids = [], $mestimate_id = 0)
    {
        if (count($image_ids) > 0) {
            $this->mestimates_model->update_mestimate_file($mestimate_id, $image_ids);
        }

    }

    public function loadContact()
    {
        if (!has_permission('mestimates', '', 'view')) {
            access_denied('mestimates');
        }
        $contactId = null;
        $data = array();
        $data['client'] = $this->clients_model->get($this->input->post("userid"));
        $contacts = $this->clients_model->get_contacts($this->input->post("userid"), ['active' => 1, 'is_primary' => 1, 'userid' => $this->input->post("userid")]);
        if (count($contacts) > 0) {
            $contactObj = (object)$contacts[0];
            $contactId = $contactObj->id;
            $data["contact"] = $contactObj;
        }
        $data['client_id'] = $this->input->post("userid");
        $data_view = $this->load->view('mestimates/includes/_info_company', $data, TRUE);
        $data['html'] = $data_view;
        $data['errorCode'] = 'SUCCESS';
        echo json_encode($data);
    }

    public function notify($id, $notify_type)
    {
        if (!has_permission('mestimates', '', 'edit') && !has_permission('mestimates', '', 'create')) {
            access_denied('mestimates');
        }
        if (!$id) {
            redirect(admin_url('mestimates'));
        }
        $success = $this->mestimates_model->notify_staff_members($id, $notify_type);
        if ($success) {
            set_alert('success', _l('mestimate_notify_staff_notified_manually_success'));
        } else {
            set_alert('warning', _l('mestimate_notify_staff_notified_manually_fail'));
        }
        redirect(admin_url('mestimates/mestimate/' . $id));
    }

    public function file($id, $mestimate_id)
    {
        $data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
        $data['current_user_is_admin'] = is_admin();

        $data['file'] = $this->mestimates_model->get_file($id, $mestimate_id);
        if (isset($data['file'])) {
            $data['file_next'] = $this->mestimates_model->get_next_file($id);
            $data['file_previous'] = $this->mestimates_model->get_previous_file($id);
        }

        if (!$data['file']) {
            header('HTTP/1.0 404 Not Found');
            die;
        }
        $this->load->view('mestimates/includes/_file', $data);
    }

    public function update_file_data()
    {
        if ($this->input->post()) {
            $this->mestimates_model->update_file_data($this->input->post());
        }
    }

    public function add_external_file()
    {
        if ($this->input->post()) {
            $data = [];
            $data['mestimate_id'] = $this->input->post('mestimate_id');
            $data['files'] = $this->input->post('files');
            $data['external'] = $this->input->post('external');
            $data['visible_to_customer'] = ($this->input->post('visible_to_customer') == 'true' ? 1 : 0);
            $data['staffid'] = get_staff_user_id();
            $this->mestimates_model->add_external_file($data);
            $data['errorCode'] = 'SUCCESS';
            $data['errorMessage'] = _l('delete_file_success');
        } else {
            $data['errorCode'] = 'ACTION_ERROR';
            $data['errorMessage'] = _l('select_file_to_remove');
        }
        echo json_encode($data);
    }

    public function upload_file()
    {
        if ($_REQUEST['client_id'] == null) {
            $data['errorCode'] = 'ACTION_ERROR';
            $data['errorMessage'] = _l('select_client');
            echo json_encode($data);
        } else {
            $this->CI->load->helper('mestimates_helper');
            $result = handle_mestimate_file_uploads($_REQUEST['mestimate_id'], $_REQUEST['client_id']);
            $this->mestimate();
        }
    }

    public function change_file_visibility($id, $visible)
    {
        if ($this->input->is_ajax_request()) {
            $this->mestimates_model->change_file_visibility($id, $visible);
        }
    }

    public function change_activity_visibility($id, $visible)
    {
        if (has_permission('mestimates', '', 'create')) {
            if ($this->input->is_ajax_request()) {
                $this->mestimates_model->change_activity_visibility($id, $visible);
            }
        }
    }

    public function remove_file()
    {
        if ($_REQUEST['id']) {
            $this->mestimates_model->remove_file($_REQUEST['id']);
            $this->mestimate();
        } else {
            $data['errorCode'] = 'ACTION_ERROR';
            $data['errorMessage'] = _l('select_file_to_remove');
            echo json_encode($data);
        }
    }

}
