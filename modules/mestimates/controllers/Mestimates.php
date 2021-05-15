<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mestimates extends AdminController
{
    private $CI;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mestimates_model');
        $this->load->model('mestimates_detail_model');
        $this->load->model('clients_model');
        $this->load->model('contracts_model');
        $this->CI = &get_instance();

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

    public function mestimate($id = '', $client_id = null)
    {
        if (!has_permission('mestimates', '', 'view')) {
            access_denied('mestimates');
        }
        $data['details'] = [];
        if ($this->input->post()) {
            var_dump($this->input->post('detail'));die();
            if ($id == '') {
                if (!has_permission('mestimates', '', 'create')) {
                    access_denied('mestimates');
                }
                $id = $this->mestimates_model->add($this->input->post());
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('mestimate')));
                    redirect(admin_url('mestimates/mestimate/' . $id));
                }
            } else {
                if (!has_permission('mestimates', '', 'edit')) {
                    access_denied('mestimates');
                }
                $success = $this->mestimates_model->update($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('mestimate')));
                }
                redirect(admin_url('mestimates/mestimate/' . $id));
            }
        }
        if ($id == '' || $id == 0) {
            $title = _l('add_new', _l('mestimate_lowercase'));
        } else {
            $data['mestimate'] = $this->mestimates_model->get($id);
            $title = _l('edit', _l('mestimate_lowercase'));
            $this->CI->load->helper('mestimates_helper');
            $data = array();
            $data['client'] = $this->clients_model->get($data['mestimate']->client_id);
            $contactId = null;
            $contacts = $this->clients_model->get_contacts($data['mestimate']['client_id'], ['active' => 1, 'is_primary' => 1, 'userid' => $data['mestimate']->client_id]);
            if (count($contacts) > 0) {
                $contactObj = (object)$contacts[0];
                $contactId = $contactObj->id;
                $data["contact"] = $contactObj;
            }

            $contacts = $this->clients_model->get_contacts($this->input->post("userid"), ['active' => 1, 'is_primary' => 1, 'userid' => $data['mestimate']->client_id]);
            if (count($contacts) > 0) {
                $contactObj = (object)$contacts[0];
                $contactId = $contactObj->id;
                $data["contact"] = $contactObj;
            }
            $data['details'] = $this->mestimates_detail_model->getByMestimate($id);
        }
        if ($client_id !== null) {
            $this->CI->load->helper('mestimates_helper');
            $data = array();
            $data['client'] = $this->clients_model->get($client_id);
            $contactId = null;
            $contacts = $this->clients_model->get_contacts($client_id, ['active' => 1, 'is_primary' => 1, 'userid' => $client_id]);
            if (count($contacts) > 0) {
                $contactObj = (object)$contacts[0];
                $contactId = $contactObj->id;
                $data["contact"] = $contactObj;
            }
            $data['client_id'] = $client_id;
        }

        $data['files'] = $this->mestimates_model->get_files($id, get_staff_user_id(), $client_id);
        $this->load->model('staff_model');
        $data['members'] = $this->staff_model->get('', ['is_not_staff' => 0, 'active' => 1]);

        $this->load->model('contracts_model');
        $data['contract_types'] = $this->contracts_model->get_contract_types();
        $data['title'] = $title;

        $data['groups'] = $this->clients_model->get_groups();
        $clients = $this->clients_model->get();
        $data['clients'] = $clients;

        $this->app_scripts->add('circle-progress-js', 'assets/plugins/jquery-circle-progress/circle-progress.min.js');
        $this->load->view('mestimate', $data);
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
        $data_view = $this->load->view('mestimates/_info_company', $data, TRUE);
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
        $this->load->view('mestimates/_file', $data);
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
        }
    }

    public function upload_file($mestimate_id = 0)
    {
        if ($this->input->post('client_id') == null) {
            set_alert('warning', _l('select_client'));
        }

        $this->CI->load->helper('mestimates_helper');
        $result = handle_mestimate_file_uploads($mestimate_id, $this->input->post('client_id'));
        header('Location: ' . $_SERVER['REQUEST_URI']);
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

    public function remove_file($mestimate_id, $id, $client_id)
    {
        $this->mestimates_model->remove_file($id);
        redirect(admin_url('mestimates/mestimate/' . $mestimate_id . '/' . $client_id));
    }

}
