<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('insert')) {
    function insert($table_name, $insert_data)
    {
        $CI =& get_instance();
        return $CI->db->insert($table_name, $insert_data);
    }
}
/**
 *  Get customer attachment
 * @param mixed $id customer id
 * @return  array
 */
function get_all_mestimates_attachments($id)
{
    $CI = &get_instance();

    $attachments = [];
    $attachments['mestimates'] = [];

    $permission_estimates_view = has_permission('mestimates', '', 'view');
    $permission_estimates_own = has_permission('mestimates', '', 'view_own');

    if ($permission_estimates_view || $permission_estimates_own || get_option('allow_staff_view_proposals_assigned') == 1) {
        $noPermissionQuery = get_estimates_where_sql_for_staff(get_staff_user_id());
        // Estimates
        $CI->db->select('userid,id');
        $CI->db->where('userid', $id);
        if (!$permission_estimates_view) {
            $CI->db->where($noPermissionQuery);
        }
        $CI->db->from(db_prefix() . 'mestimates');
        $estimates = $CI->db->get()->result_array();

        $ids = array_column($estimates, 'id');
        if (count($ids) > 0) {
            $CI->db->where_in('rel_id', $ids);
            $CI->db->where('rel_type', 'mestimates');
            $_attachments = $CI->db->get(db_prefix() . 'files')->result_array();

            foreach ($_attachments as $_att) {
                array_push($attachments['mestimates'], $_att);
            }
        }
    }

    return hooks()->apply_filters('all_client_attachments', $attachments, $id);
}

function get_mestimate_discussions_language_array()
{
    $lang = [
        'discussion_add_comment' => _l('discussion_add_comment'),
        'discussion_newest' => _l('discussion_newest'),
        'discussion_oldest' => _l('discussion_oldest'),
        'discussion_attachments' => _l('discussion_attachments'),
        'discussion_send' => _l('discussion_send'),
        'discussion_reply' => _l('discussion_reply'),
        'discussion_edit' => _l('discussion_edit'),
        'discussion_edited' => _l('discussion_edited'),
        'discussion_you' => _l('discussion_you'),
        'discussion_save' => _l('discussion_save'),
        'discussion_delete' => _l('discussion_delete'),
        'discussion_view_all_replies' => _l('discussion_view_all_replies'),
        'discussion_hide_replies' => _l('discussion_hide_replies'),
        'discussion_no_comments' => _l('discussion_no_comments'),
        'discussion_no_attachments' => _l('discussion_no_attachments'),
        'discussion_attachments_drop' => _l('discussion_attachments_drop'),
    ];

    return $lang;
}

function handle_mestimate_file_uploads($mestimate_id = 0, $client_id = null)
{
    $CI = &get_instance();
    $filesIDS = [];
    $errors = [];

    if (isset($_FILES['file']['name'])
        && ($_FILES['file']['name'] != '' || is_array($_FILES['file']['name']) && count($_FILES['file']['name']) > 0)) {
        hooks()->do_action('before_upload_mestimate_attachment', $mestimate_id);

        if (!is_array($_FILES['file']['name'])) {
            $_FILES['file']['name'] = [$_FILES['file']['name']];
            $_FILES['file']['type'] = [$_FILES['file']['type']];
            $_FILES['file']['tmp_name'] = [$_FILES['file']['tmp_name']];
            $_FILES['file']['error'] = [$_FILES['file']['error']];
            $_FILES['file']['size'] = [$_FILES['file']['size']];
        }

        $path = FCPATH . 'uploads/mestimates' . '/' . $mestimate_id . '/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
            if (_perfex_upload_error($_FILES['file']['error'][$i])) {
                $errors[$_FILES['file']['name'][$i]] = _perfex_upload_error($_FILES['file']['error'][$i]);

                continue;
            }

            // Get the temp file path
            $tmpFilePath = $_FILES['file']['tmp_name'][$i];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                _maybe_create_upload_path($path);
                $filename = unique_filename($path, $_FILES['file']['name'][$i]);

                // In case client side validation is bypassed
                if (!_upload_extension_allowed($filename)) {
                    continue;
                }

                $newFilePath = $path . $filename;
                // Upload the file into the company uploads dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $CI = &get_instance();
                    if (is_client_logged_in()) {
                        $contact_id = get_contact_user_id();
                        $staffid = 0;
                    } else {
                        $staffid = get_staff_user_id();
                        $contact_id = 0;
                    }
                    if (isset($client_id)) {
                        $contact_id = $client_id;
                    }
                    $data = [
                        'mestimate_id' => $mestimate_id,
                        'file_name' => $filename,
                        'filetype' => $_FILES['file']['type'][$i],
                        'dateadded' => date('Y-m-d H:i:s'),
                        'staffid' => $staffid,
                        'contact_id' => $contact_id,
                        'subject' => $filename,
                    ];
                    if (is_client_logged_in()) {
                        $data['visible_to_customer'] = 1;
                    } else {
                        $data['visible_to_customer'] = ($CI->input->post('visible_to_customer') == 'true' ? 1 : 0);
                    }
                    $CI->db->insert(db_prefix() . 'mestimate_files', $data);

                    $insert_id = $CI->db->insert_id();
                    if ($insert_id) {
                        if (is_image($newFilePath)) {
                            create_img_thumb($path, $filename);
                        }
                        array_push($filesIDS, $insert_id);
                    } else {
                        unlink($newFilePath);

                        return false;
                    }
                }
            }
        }
    }

    if (count($filesIDS) > 0) {
        $CI->load->model('mestimates_model');
        end($filesIDS);
        $lastFileID = key($filesIDS);
        //$CI->mestimates_model->new_mestimate_file_notification($filesIDS[$lastFileID], $mestimate_id);
    }

    if (count($errors) > 0) {
        $message = '';
        foreach ($errors as $filename => $error_message) {
            $message .= $filename . ' - ' . $error_message . '<br />';
        }
        header('HTTP/1.0 400 Bad error');
        echo $message;
        die;
    }

    if (count($filesIDS) > 0) {
        return true;
    }

    return false;
}

function mestimate_file_url($file, $preview = false)
{
    $path = 'uploads/mestimates/' . $file['mestimate_id'] . '/';
    $fullPath = FCPATH . $path . $file['file_name'];
    $url = base_url($path . $file['file_name']);
    if (!empty($file['external']) && !empty($file['thumbnail_link'])) {
        $url = $file['thumbnail_link'];
    } else {
        if ($preview) {
            $fname = pathinfo($fullPath, PATHINFO_FILENAME);
            $fext = pathinfo($fullPath, PATHINFO_EXTENSION);
            $thumbPath = pathinfo($fullPath, PATHINFO_DIRNAME) . '/' . $fname . '_thumb.' . $fext;
            if (file_exists($thumbPath)) {
                $url = base_url('uploads/mestimates/' . $file['mestimate_id'] . '/' . $fname . '_thumb.' . $fext);
            }
        }
    }

    return $url;
}
