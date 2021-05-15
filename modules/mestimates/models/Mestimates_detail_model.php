<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mestimates_detail_model extends App_Model
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
    public function getByMestimate($mestimate_id = '')
    {
        $this->db->where('mestimate_id', $mestimate_id);
        $result = $this->db->get(db_prefix() . 'mestimates_detail')->result_array();
        return $result;
    }

}


