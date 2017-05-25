<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Frederick
 * Date: 11/1/16
 * Time: 10:53 PM
 * To change this template use File | Settings | File Templates.
 */
class Meeting_model extends CI_Model {

    private $meeting = 'meeting';
    private $meeting_attendance = 'meeting_attendance';
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

    public function check_meeting_exist($data) {
        $this->db->where('date_of_meeting',$data['date_of_meeting']);
        $this->db->where('oic',$data['oic']);
        $this->db->where('recorder',$data['recorder']);
        $query = $this->db->get($this->meeting);
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function save_meeting($meeting=NULL)
    {
        $this->db->insert($this->meeting, $meeting);
        return $this->db->insert_id();
    }

    public function save_members($meeting_id, $post)
    {
        $data = [];
        for($x=0; $x<$post['member_counter']; $x++)
        {
            $data[$x] = array(
                'meeting_id' => $meeting_id,
                'unit' => strtoupper($post['member'.$x]),
                'attendance_date' => $post['date_of_meeting']
            );
        }

        return $this->db->insert_batch($this->meeting_attendance, $data);
    }

    public function get_meeting_list_range($from_date, $to_date)
    {
        $query = "SELECT t.*, count(*) AS 'total' FROM `meeting` t
            LEFT JOIN meeting_attendance ta ON ta.meeting_id = t.id
            WHERE `date_of_meeting` >= '$from_date'
            AND `date_of_meeting` <= '$to_date'
            GROUP BY ta.meeting_id
            ORDER BY `date_of_meeting` DESC";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    function get_meeting_data($meeting_id)
    {
        $this->db->where('id', $meeting_id);
        $query = $this->db->get($this->meeting);
        return $query->result();
    }

    function get_meeting_attendance($meeting_id)
    {
        $this->db->where('meeting_id', $meeting_id);
        $query = $this->db->get($this->meeting_attendance);
        return $query->result();
    }

    function update_meeting($data, $meeting_id)
    {
        $this->db->where('id', $meeting_id);
        $this->db->update($this->meeting, $data);
    }

    function get_summary_count($from, $to)
    {
        $this->db->select('count(*)');
        $this->db->from($this->meeting);
        $this->db->where('date_of_meeting >=', $from);
        $this->db->where('date_of_meeting <=', $to);
        return $this->db->count_all_results();
    }

    function get_top_20($from_date, $to_date)
    {
        $query = "SELECT ta.unit, count(*) as total
                FROM ".$this->meeting_attendance." ta
                WHERE attendance_date >= '$from_date' AND attendance_date <= '$to_date'
                GROUP BY ta.unit
                ORDER BY total dESC";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

}