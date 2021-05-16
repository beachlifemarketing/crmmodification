<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mestimates_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @param integer (optional)
     * @return object
     * Get single mestimate
     */
    public function get($id = '', $exclude_notified = false)
    {
        $this->db->where('id', $id);

        $result = $this->db->get(db_prefix() . 'mestimates')->row();
        return $result;
    }


    public function get_all_mestimates()
    {
        $this->db->where('status', 'active');
        $this->db->order_by('due_date', 'desc');
        $mestimates = $this->db->get(db_prefix() . 'mestimates')->result_array();
        return array_values($mestimates);
    }

    public function get_all_template()
    {
        $this->db->where('status', 'template');
        $this->db->order_by('due_date', 'asc');
        $mestimates = $this->db->get(db_prefix() . 'mestimates')->result_array();
        return $mestimates;
    }

    /**
     * Add new mestimate
     * @param mixed $data All $_POST dat
     * @return mixed
     */
    public function addMestimate($data)
    {
        $this->db->insert(db_prefix() . 'mestimates', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Update mestimate
     * @param mixed $data All $_POST data
     * @param mixed $id mestimate id
     * @return boolean
     */
    public function updateMestimate($dataupdate, $id)
    {

        $dataupdate['date'] = to_sql_date($dataupdate['date']);
        $dataupdate['due_date'] = to_sql_date($dataupdate['due_date']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'mestimates', $dataupdate);
        if ($this->db->affected_rows() > 0) {
            log_activity('Mestimate Updated [ID:' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete mestimate
     * @param mixed $id mestimate id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'mestimates');
        if ($this->db->affected_rows() > 0) {
            log_activity('Mestimate Deleted [ID:' . $id . ']');

            return true;
        }

        return false;
    }

    public function mark_as_notified($id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'mestimates', [
            'notified' => 1,
        ]);
    }

    public function get_files($mestimate_id = 0, $staffid = null, $client_id = null)
    {
        if (is_client_logged_in()) {
            $this->db->where('visible_to_customer', 1);
        }
        $this->db->where('mestimate_id', $mestimate_id);
        if (isset($staffid)) {
            $this->db->where('staffid', $staffid);
        }
        if (isset($client_id)) {
            $this->db->where('contact_id', $client_id);
        }
        $data = $this->db->get(db_prefix() . 'mestimate_files')->result_array();
        //print_r($this->db->last_query());
        return $data;
    }

    public function update_mestimate_file($mestimate_id = 0, $file_ids = [])
    {
        foreach ($file_ids as $file_id) {
            $image = array('mestimate_id' => $mestimate_id);
            $this->db->where('id', $file_id);
            $this->db->update('mestimate_files', $image);
        }
    }


    public function get_file($id, $mestimate_id = false)
    {
        if (is_client_logged_in()) {
            $this->db->where('visible_to_customer', 1);
        }
        $this->db->where('id', $id);
        $file = $this->db->get(db_prefix() . 'mestimate_files')->row();

        if ($file && $mestimate_id) {
            if ($file->mestimate_id != $mestimate_id) {
                return false;
            }
        }

        return $file;
    }

    public function update_file_data($data)
    {
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update(db_prefix() . 'mestimate_files', $data);
    }


    public function change_file_visibility($id, $visible)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'mestimate_files', [
            'visible_to_customer' => $visible,
        ]);
    }

    public function change_activity_visibility($id, $visible)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'mestimate_activity', [
            'visible_to_customer' => $visible,
        ]);
    }

    public function remove_file($id, $logActivity = true)
    {
        hooks()->do_action('before_remove_mestimate_file', $id);

        $this->db->where('id', $id);
        $file = $this->db->get(db_prefix() . 'mestimate_files')->row();
        if ($file) {
            if (empty($file->external)) {
                $path = get_upload_path_by_type('mestimate') . $file->mestimate_id . '/';
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
            $this->db->delete(db_prefix() . 'mestimate_files');

            if (is_dir(get_upload_path_by_type('mestimate') . $file->mestimate_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('mestimate') . $file->mestimate_id);
                if (count($other_attachments) == 0) {
                    delete_dir(get_upload_path_by_type('mestimate') . $file->mestimate_id);
                }
            }

            return true;
        }

        return false;
    }

    function get_next_file($file_id)
    {
        $condition = '1=1';
        if (is_client_logged_in()) {
            $condition = 'visible_to_customer = 1';
        }
        $stmt = $this->db->query("SELECT * FROM " . db_prefix() . "mestimate_files 
        WHERE id > " . $file_id . " 
         AND " . $condition . "
        ORDER BY id DESC LIMIT 1;");
        $file = $stmt->row();
        if (isset($file)) {
            return $file;
        }
        return null;
    }

    function get_previous_file($file_id)
    {
        $condition = '1=1';
        if (is_client_logged_in()) {
            $condition = 'visible_to_customer = 1';
        }
        $stmt = $this->db->query("SELECT * FROM " . db_prefix() . "mestimate_files 
        WHERE id < " . $file_id . " 
         AND " . $condition . "
        ORDER BY id DESC LIMIT 1;");
        $file = $stmt->row();
        if (isset($file)) {
            return $file;
        }
        return null;
    }

    public function get_staff_mestimates($staff_id, $exclude_notified = true)
    {
        $this->db->where('staff_id', $staff_id);

        if ($exclude_notified) {
            $this->db->where('notified', 0);
        }

        $this->db->order_by('due_date', 'asc');
        $mestimates = $this->db->get(db_prefix() . 'mestimates')->result_array();

        return $mestimates;
    }

}


