<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Frederick
 * Date: 11/1/16
 * Time: 10:53 PM
 * To change this template use File | Settings | File Templates.
 */
class Special_model extends CI_Model {

    private $special_activity = 'special_activity';
    private $special_activity_attendance = 'special_activity_attendance';
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

    public function check_special_exist($data) {
        $this->db->where('date_of_special',$data['date_of_special']);
        $this->db->where('activity',$data['activity']);
        $this->db->where('oic',$data['oic']);
        $this->db->where('recorder',$data['recorder']);
        $query = $this->db->get($this->special_activity);
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function save_special($special=NULL)
    {
        $this->db->insert($this->special_activity, $special);
        return $this->db->insert_id();
    }

    public function save_members($special_id, $post)
    {
        $data = [];
        for($x=0; $x<$post['member_counter']; $x++)
        {
            $data[$x] = array(
                'special_id' => $special_id,
                'unit' => strtoupper($post['member'.$x]),
                'attendance_date' => $post['date_of_special']
            );
        }

        return $this->db->insert_batch($this->special_activity_attendance, $data);
    }

    public function get_special_list_range($from_date, $to_date)
    {
        $query = "SELECT t.*, count(*) AS 'total' FROM `special_activity` t
            LEFT JOIN special_activity_attendance ta ON ta.special_id = t.id
            WHERE `date_of_special` >= '$from_date'
            AND `date_of_special` <= '$to_date'
            GROUP BY ta.special_id
            ORDER BY `date_of_special` DESC";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    function get_special_data($special_id)
    {
        $this->db->where('id', $special_id);
        $query = $this->db->get($this->special_activity);
        return $query->result();
    }

    function get_special_attendance($special_id)
    {
        $this->db->where('special_id', $special_id);
        $query = $this->db->get($this->special_activity_attendance);
        return $query->result();
    }

    function update_special($data, $special_id)
    {
        $this->db->where('id', $special_id);
        $this->db->update($this->special_activity, $data);
    }

    function get_summary_count($from, $to)
    {
        $this->db->select('count(*)');
        $this->db->from($this->special_activity);
        $this->db->where('date_of_special >=', $from);
        $this->db->where('date_of_special <=', $to);
        return $this->db->count_all_results();
    }

    function get_top_20($from_date, $to_date)
    {
        $query = "SELECT ta.unit, count(*) as total
                FROM ".$this->special_activity_attendance." ta
                WHERE attendance_date >= '$from_date' AND attendance_date <= '$to_date'
                GROUP BY ta.unit
                ORDER BY total dESC";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

}