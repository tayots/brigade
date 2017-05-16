<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Frederick
 * Date: 11/1/16
 * Time: 10:53 PM
 * To change this template use File | Settings | File Templates.
 */
class Duty_model extends CI_Model {

    private $duty_attendance = 'duty_attendance';
    private $duty_schedule = 'duty_schedule';
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

    public function check_duty_data($duty)
    {
        $this->db->where('attendance_date',$duty['attendance_date']);
        $this->db->where('unit',$duty['unit']);
        $this->db->where('schedule',$duty['schedule']);
        $query = $this->db->get($this->duty_attendance);
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function add_duty_attendance($duty)
    {
        return $this->db->insert($this->duty_attendance, $duty);
    }

    public function get_duties($day)
    {
        $this->db->select('*');
        $this->db->from($this->duty_attendance);
        $this->db->where('attendance_date', $day);
        $this->db->order_by('time_in');
        $query = $this->db->get();
        //print_r($this->db->last_query());
        return $query->result();
    }

    public function get_total_duties_week($start, $end)
    {
        $this->db->select('count(*)');
        $this->db->from($this->duty_attendance);
        $this->db->where('attendance_date >=', $start);
        $this->db->where('attendance_date <=', $end);
        return $this->db->count_all_results();
    }

    public function get_personel_duties_week($start, $end)
    {
        $query = 'SELECT unit, count(*) as total FROM `duty_attendance` WHERE attendance_date >= "'.$start.'" AND attendance_date <= "'.$end.'" GROUP BY unit';
        return $this->db->query($query);
    }

    public function duty_personnel($unit_number)
    {
        $this->db->where('unit',$unit_number);
        $query = $this->db->get('personnel');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function add_schedule($schedule)
    {
        $this->db->insert($this->duty_schedule, $schedule);
        return $this->db->insert_id();
    }

    public function get_duty_schedule($schedule)
    {
        $this->db->select('*');
        $this->db->from($this->duty_schedule);
        $this->db->where('schedule', $schedule);
        $query = $this->db->get();
        return $query->result();
    }

}