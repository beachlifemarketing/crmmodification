<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mestimates extends AdminController
{
    private $CI;
    public $mestimate_id;
    public $client_id;
    public $mestimate_id_old;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mestimates_model');
        $this->load->model('mestimates_detail_model');
        $this->load->model('clients_model');
        $this->load->model('projects_model');
        $this->CI = &get_instance();


        if ($this->input->post('mestimate_id_old') != null) {
            $this->mestimate_id_old = $this->input->post('mestimate_id_old');
        }

        if ($this->input->post('mestimate_id') != null) {
            $this->mestimate_id = $this->input->post('mestimate_id');
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

    /* List all announcements */
    public function template()
    {
        if (!has_permission('mestimates', '', 'view')) {
            access_denied('mestimates');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('mestimates', 'table_template'));
        }
        $this->app_scripts->add('circle-progress-js', 'assets/plugins/jquery-circle-progress/circle-progress.min.js');
        $data['title'] = _l('mestimates_template');
        $this->load->view('manage_template', $data);
    }


    public function tmp($id = 0)
    {
        if (!has_permission('mestimates', '', 'view')) {
            access_denied('mestimates');
        }
        if (($id == null || $id == 0)) {
            access_denied('mestimates');
        }
        $data['tpl'] = $id;
        $this->load->view('mestimate', $data);
    }

    public function mestimate($id = 0)
    {
        $data = array();
        if (!has_permission('mestimates', '', 'view')) {
            access_denied('mestimates');
        }

        if (isset($_REQUEST['mestimate_id_view'])) {
            $id = $_REQUEST['mestimate_id_view'];
        }

        if (($id != null && $id > 0)) {
            $this->mestimate_id = $id;
            $this->mestimate_id_old = $id;
            $title = _l('edit', _l('mestimate_lowercase'));
            $data['is_edit'] = true;
        } else {
            $title = _l('add_new', _l('mestimate_lowercase'));
            $data['is_edit'] = false;
        }

        $contactId = null;
        $data['details'] = [];
        $mestimate = $this->mestimates_model->get($this->mestimate_id);
        $this->CI->load->helper('mestimates_helper');
        if (isset($mestimate) && isset($mestimate->client_id)) {
            $this->client_id = $mestimate->client_id;
        }
        if (isset($_REQUEST['change']) && $_REQUEST['change'] == 'client') {
            $this->client_id = $_REQUEST['client_id'];
        }
        if ($this->client_id !== null) {
            $this->CI->load->helper('mestimates_helper');
            $data = array();
            $client = $this->clients_model->get($this->client_id);
            $contacts = $this->clients_model->get_contacts($this->client_id, ['active' => 1, 'is_primary' => 1, 'userid' => $this->client_id]);
            if (count($contacts) > 0) {
                $contactObj = (object)$contacts[0];
                $contactId = $contactObj->id;
                $data["contact"] = $contactObj;
            }
            $data['client'] = $client;
            $data['files'] = $this->mestimates_model->get_files(get_staff_user_id(), $this->client_id);
            $data['projects'] = $this->projects_model->get_projects_for_ticket($this->client_id);
        }
        $data['mestimate'] = $mestimate;
        $data['details'] = $this->mestimates_detail_model->getByMestimate($this->mestimate_id);
        $fileMapResult = array();
        $fileMap = $this->mestimates_model->get_file_map($this->mestimate_id);
        foreach ($fileMap as $file) {
            $fileMapResult[$file['file_id']] = $file['mestimate_id'];
        }
        $data['fileMap'] = $fileMapResult;
        $data['client_id'] = $this->client_id;
        $data['mestimate_id'] = $this->mestimate_id;
        $data['mestimate_id_old'] = $this->mestimate_id_old;

        if (isset($mestimate_id_old) && $mestimate_id_old != 0 && $mestimate_id_old != '') {
            $title = _l('edit', _l('mestimate_lowercase'));
            $data['is_edit'] = true;
        }

        $data['title'] = $title;
        $data['groups'] = $this->clients_model->get_groups();

        $data['templates'] = $this->mestimates_model->get_all_template();
        $clients = $this->clients_model->get();
        $data['clients'] = $clients;
        $data['rtype'] = isset($_REQUEST['rtype']) ? $_REQUEST['rtype'] : '';
        if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] === 'json') {
            $data['errorCode'] = 'SUCCESS';
            if ($_REQUEST['mestimate_id_view']) {
                $data['data_template'] = $this->load->view('mestimates/mestimate_detail_data', $data, true);
                $data['errorMessage'] = _l('load_template_success');
            } elseif ($_REQUEST['load_model_send_email']) {
                $template_name = 'mestimate-send-to-client';
                $this->load->model('emails_model');
                $template_name = $template_name;
                $slug = $this->CI->app_mail_template->get_default_property_value('slug', $template_name);
                $template = $this->emails_model->get(['slug' => $slug, 'language' => 'english'], 'row');

                $data['template'] = $template;
                $data['template_name'] = $template_name;
                $data['template_disabled'] = $template->active == 0;
                $data['template_id'] = $template->emailtemplateid;
                $data['template_system_name'] = $template->name;
                $data['data_template'] = $this->load->view('mestimates/includes/send_email_modal_data', $data, true);
                $data['errorMessage'] = _l('load_template_success');
            } else {
                if (isset($_REQUEST['change']) && $_REQUEST['change'] == 'template') {
                    $data['data_template'] = $this->load->view('mestimates/includes/mestimate_data', $data, true);
                    $data['errorMessage'] = _l('load_template_success');
                } else {
                    $data['view_address'] = $this->load->view('mestimates/includes/info_company', $data, true);
                    $data['view_file'] = $this->load->view('mestimates/includes/mestimate_files', $data, true);
                    $data['errorMessage'] = _l('load_info_client_success');
                }
            }

            echo json_encode($data);
        } else {
            $this->load->view('mestimate', $data);
        }
    }

    public function estimate_do()
    {
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
                if ($this->mestimate_id_old == '' || $this->mestimate_id_old == 0 || $this->mestimate_id_old == null) {
                    $mestimate_id = $this->build_base_mestimate($this->input->post(), $this->client_id, null, $sat);
                    $this->build_base_detail($this->input->post('detail'), $mestimate_id, $this->client_id);
                    if ($this->input->post('image_ids') != null) {
                        $this->update_mestimate_file($this->input->post('image_ids'), $mestimate_id);
                    }
                    $data['errorCode'] = 'SUCCESS';
                    $data['mestimate_id'] = $mestimate_id;
                    $data['errorMessage'] = _l('added_successfully', _l('mestimate'));
                    $data['url_redirect'] = admin_url('mestimates/mestimate/' . $mestimate_id);
                    set_alert('success', 'Load M-Estimate #' . $mestimate_id . ' to update');
                    echo json_encode($data);
                    die();
                } else {
                    if (!has_permission('mestimates', '', 'edit')) {
                        access_denied('mestimates');
                    }
                    $mestimate_id = $this->build_base_mestimate($this->input->post(), $this->client_id, $this->mestimate_id_old, $sat);

                    if ($sat === 'template') {
                        $this->build_base_detail($this->input->post('detail'), $this->mestimate_id_old, $this->client_id);
                    } else {
                        $this->build_base_detail($this->input->post('detail'), $this->mestimate_id_old, $this->client_id);
                    }

                    if ($this->input->post('image_ids') != null) {
                        $this->update_mestimate_file($this->input->post('image_ids'), $this->mestimate_id_old);
                    }
                    $data['errorCode'] = "SUCCESS";
                    $data['errorMessage'] = _l('updated_successfully', _l('mestimate'));
                }
                if ($sat === 'template') {
                    if (isset($_REQUEST['rtype']) && $_REQUEST['rtype'] == 'json') {
                        $data['rtype'] = 'json';
                    }
                    $data['errorMessage'] = _l('create_template_success', _l('mestimate'));
                    $data['templates'] = $this->mestimates_model->get_all_template();
                    $data["mestimate_id"] = $mestimate_id;
                    $data["mestimate_id_old"] = $this->mestimate_id_old;
                    $data['html_template'] = $this->load->view('mestimates/includes/_template_list.php', $data, true);
                    $data['html_button'] = $this->load->view('mestimates/includes/_save_button.php', $data, true);
                }
                echo json_encode($data);
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


    public function show_detail($id = null)
    {
        if (!has_permission('mestimates', '', 'view') && !has_permission('mestimates', '', 'view_own') && get_option('allow_staff_view_mestimates_assigned') == '0') {
            echo _l('access_denied');
            die;
        }

        if ($_REQUEST['mestimate_id_view']) {
            if (!$id) {
                $id = $_REQUEST['mestimate_id_view'];
            }
        }

        if (!$id) {
            die('No mestimate found');
        }

        $mestimate = $this->mestimates_model->get($id);
        $mestimate->date = _d($mestimate->date);
        $mestimate->due_date = _d($mestimate->due_date);

        if ($mestimate->sent == 0) {
            $template_name = 'mestimate_send_to_customer';
        } else {
            $template_name = 'mestimate_send_to_customer_already_sent';
        }

        //$data['activity'] = $this->mestimates_model->get_estimate_activity($id);
        $data['mestimate'] = $mestimate;
        $data['members'] = $this->staff_model->get('', ['active' => 1]);

        $data['errorCode'] = 'SUCCESS';
        $data['html_detail'] = $this->load->view('mestimates/mestimate_detail_data', $data, true);
        $data['errorMessage'] = _l('load_template_success');
        echo json_encode($data);
    }


    public function send_expiry_reminder($id)
    {
        $canView = user_can_view_estimate($id);
        if (!$canView) {
            access_denied('Estimates');
        } else {
            if (!has_permission('estimates', '', 'view') && !has_permission('estimates', '', 'view_own') && $canView == false) {
                access_denied('Estimates');
            }
        }

        $success = $this->mestimates_model->send_expiry_reminder($id);
        if ($success) {
            set_alert('success', _l('sent_expiry_reminder_success'));
        } else {
            set_alert('danger', _l('sent_expiry_reminder_fail'));
        }
        if ($this->set_estimate_pipeline_autoload($id)) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(admin_url('estimates/list_estimates/' . $id));
        }
    }

    /* Send estimate to email */
    public function send_to_email($id)
    {
        $canView = user_can_view_estimate($id);
        if (!$canView) {
            access_denied('estimates');
        } else {
            if (!has_permission('estimates', '', 'view') && !has_permission('estimates', '', 'view_own') && $canView == false) {
                access_denied('estimates');
            }
        }

        try {
            $success = $this->estimates_model->send_estimate_to_client($id, '', $this->input->post('attach_pdf'), $this->input->post('cc'));
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        // In case client use another language
        load_admin_language();
        if ($success) {
            set_alert('success', _l('estimate_sent_to_client_success'));
        } else {
            set_alert('danger', _l('estimate_sent_to_client_fail'));
        }
        if ($this->set_estimate_pipeline_autoload($id)) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(admin_url('estimates/list_estimates/' . $id));
        }
    }

    /* Delete announcement from database */
    public function delete_template($id = null)
    {
        if (!has_permission('mestimates', '', 'delete')) {
            access_denied('mestimates');
        }
        if (!isset($id) || $id == '' || $id == 0) {
            if (isset($_REQUEST['url']) && $_REQUEST['url'] == 'mestimate' && isset($_REQUEST['mestimate_id'])) {
                if ($_REQUEST['rtype'] && $_REQUEST['rtype'] == 'json') {
                    $response = $this->mestimates_model->delete($_REQUEST['mestimate_id']);
                    $data['rtype'] = 'json';
                    $data['errorCode'] = 'SUCCESS';
                    $data['errorMessage'] = _l('delete_template_success');
                    $data['templates'] = $this->mestimates_model->get_all_template();
                    $data["mestimate_id"] = (isset($_REQUEST['mestimate_id']) ? $_REQUEST['mestimate_id'] : '');
                    $data["mestimate_id_old"] = (isset($_REQUEST['mestimate_id_old']) ? $_REQUEST['mestimate_id_old'] : '');
                    $data['html_template'] = $this->load->view('mestimates/includes/_template_list.php', $data, true);
                    $data["mestimate_id"] = '';
                    $data['html_button'] = $this->load->view('mestimates/includes/_save_button.php', $data, true);
                    echo json_encode($data);
                }
            }
        } else {
            $response = $this->mestimates_model->delete($id);
            if ($response == true) {
                set_alert('success', _l('deleted', _l('template_mestimate_lowercase')));
            } else {
                set_alert('warning', _l('problem_deleting', _l('template_mestimate_lowercase')));
            }
            redirect(admin_url('mestimates/template'));
        }
    }

    function build_base_mestimate($data, $client_id, $mestimate_id = 0, $sat = 'active')
    {
        $dataupdate = [];
        $dataupdate['status'] = $sat;
        $dataupdate['client_id'] = $client_id;
        $dataupdate['contact_id'] = $client_id;
        $dataupdate['job_number'] = $data['job_number'];
        $dataupdate['project_id'] = $data['project_id'];
        $dataupdate['customer_group_id'] = $data['group_id'];
        $dataupdate['claim_number'] = $data['claim_number'];
        $dataupdate['date'] = $data['date'];
        $dataupdate['due_date'] = $data['due_date'];
        $dataupdate['lic_number'] = $data['lic_number'];
        $dataupdate['pymt_option'] = $data['pymt_option'];
        $dataupdate['group_id'] = $data['group_id'];
        $dataupdate['representative'] = $data['representative'];
        $dataupdate['paid_by_customer_text'] = $data['paid_by_customer_text'];
        $dataupdate['balance_due_val'] = $data['balance_due_val'];
        $dataupdate['balance_due_text'] = $data['balance_due_text'];
        $dataupdate['balance_due'] = $data['balance_due'];
        $dataupdate['term_and_condition'] = $data['term_and_condition'];
        $dataupdate['client_note'] = $data['client_note'];

        $dataupdate['paid_by_customer_percent'] = $data['paid_by_customer_percent'];
        $dataupdate['total'] = $data['total'];
        $dataupdate['discount_val'] = $data['discount_val'];
        $dataupdate['discount'] = $data['discount'];
        $dataupdate['title'] = $data['title'];
        $dataupdate['adminnote'] = $data['adminnote'];
        $dataupdate['sub_total'] = $data['sub_total'];

        if ($sat === 'template') {
            $dataupdate['template_name'] = $data['template_name'];
            $mestimate_id = 0;
        }


        $dataupdate['staff_id'] = get_staff_user_id();
        if ($mestimate_id > 0) {
            return $this->mestimates_model->updateMestimate($dataupdate, $mestimate_id);
        } else {
            return $this->mestimates_model->addMestimate($dataupdate);
        }
    }

    function build_base_detail($data, $mestimate_id, $client_id)
    {
        $countEmelement = count($data['are']);
        $this->mestimates_detail_model->deleteByMestimate($mestimate_id);
        for ($i = 0; $i < $countEmelement; $i++) {
            $detail['mestimate_id'] = $mestimate_id;
            $detail['contact_id'] = $client_id;
            $detail['area'] = $data['are'][$i];
            $detail['amount'] = $data['amount'][$i];
            $detail['size'] = $data['size'][$i];
            $detail['description'] = $data['description'][$i];
            $detail['qty_unit'] = $data['qty_unit'][$i];
            $detail['px_unit'] = $data['px_unit'][$i];
            $detail['duration'] = $data['duration'][$i];
            $detail['status'] = 1;
            $this->mestimates_detail_model->add($detail);
        }
    }

    function update_mestimate_file($image_ids = [], $mestimate_id = 0)
    {
        $this->mestimates_model->deleteFileMap($mestimate_id);
        if (count($image_ids) > 0) {
            $this->mestimates_model->add_mestimate_file($mestimate_id, $image_ids);
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

    public function file($id)
    {
        $data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
        $data['current_user_is_admin'] = is_admin();

        $data['file'] = $this->mestimates_model->get_file($id);
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
            $result = handle_mestimate_file_uploads($_REQUEST['client_id']);
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


    public function loadFormSendEmail()
    {

    }

    /* Generates estimate PDF and senting to email  */
    public function pdf($id)
    {
        $canView = user_can_view_estimate($id);
        if (!$canView) {
            access_denied('Mestimates');
        } else {
            if (!has_permission('mestimates', '', 'view') && !has_permission('mestimates', '', 'view_own') && $canView == false) {
                access_denied('Mestimates');
            }
        }
        if (!$id) {
            redirect(admin_url('mestimates'));
        }
        $mestimate = $this->mestimates_model->get($id);

        try {
            $pdf = mestimate_pdf($mestimate);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $type = 'D';

        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }

        if ($this->input->get('print')) {
            $type = 'I';
        }

        $fileNameHookData = hooks()->apply_filters('mestimate_file_name_admin_area', [
            'file_name' => mb_strtoupper(slug_it($mestimate->id . '_' . date('Y-m-d H:i:s'))) . '.pdf',
            'mestimate' => $mestimate,
        ]);

        $pdf->Output($fileNameHookData['file_name'], $type);
    }

}
