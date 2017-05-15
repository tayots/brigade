<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Frederick
 * Date: 11/1/16
 * Time: 10:53 PM
 * To change this template use File | Settings | File Templates.
 */
class Training_model extends CI_Model {

    private $training = 'training';
    private $training_attendance = 'training_attendance';
    private $personnel = 'personnel';

    public function __construct()
    {
        parent::__construct();
    }

    public function check_unit_exist($unit_number) {
        $this->db->where('unit',$unit_number);
        $query = $this->db->get($this->personnel);
        //var_dump($this->db->last_query());
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function check_training_exist($data) {
        $this->db->where('date_of_training',$data['date_of_training']);
        $this->db->where('oic',$data['oic']);
        $this->db->where('recorder',$data['recorder']);
        $query = $this->db->get($this->training);
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function save_training($training=NULL)
    {
        $this->db->insert($this->training, $training);
        return $this->db->insert_id();
    }

    public function save_members($training_id, $post)
    {
        $data = [];
        for($x=0; $x<$post['member_counter']; $x++)
        {
            $data[$x] = array(
                'training_id' => $training_id,
                'unit' => $post['member'.$x]
            );
        }

        return $this->db->insert_batch($this->training_attendance, $data);
    }

    public function get_training_list_range($from_date, $to_date)
    {
        $query = "SELECT t.*, count(*) AS 'total' FROM `training` t
            LEFT JOIN training_attendance ta ON ta.training_id = t.id
            WHERE `date_of_training` >= '$from_date'
            AND `date_of_training` <= '$to_date'
            GROUP BY ta.training_id
            ORDER BY `date_of_training` DESC";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    function get_training_data($training_id)
    {
        $this->db->where('id', $training_id);
        $query = $this->db->get($this->training);
        return $query->result();
    }

    function get_training_attendance($training_id)
    {
        $this->db->where('training_id', $training_id);
        $query = $this->db->get($this->training_attendance);
        return $query->result();
    }

    function update_training($data, $training_id)
    {
        $this->db->where('id', $training_id);
        $this->db->update($this->training, $data);
    }

}