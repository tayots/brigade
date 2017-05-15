<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Frederick
 * Date: 11/1/16
 * Time: 10:53 PM
 * To change this template use File | Settings | File Templates.
 */
class Personnel_model extends CI_Model {

    private $personnel = 'personnel';

    public function __construct()
    {
        parent::__construct();
    }

    public function save_personnel($personnel=NULL)
    {
        $this->db->insert($this->personnel, $personnel);
        return $this->db->insert_id();
    }

    public function check_personnel($unit_number)
    {
        $this->db->where('unit',$unit_number);
        $query = $this->db->get($this->personnel);
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function get_user_list()
    {
        $query = $this->db->query("SELECT * FROM ".$this->personnel." order by unit");
        return $query->result();
    }

    function get_all_units($status)
    {
        $query = $this->db->query("SELECT * FROM ".$this->personnel." where status = '$status' order by unit");
        return $query->result();
    }

}