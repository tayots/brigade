<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Frederick
 * Date: 11/1/16
 * Time: 10:53 PM
 * To change this template use File | Settings | File Templates.
 */
class Main_model extends CI_Model {

    private $fire_attendance = 'fire_attendance';
    private $fire_data = 'fire_data';
    private $personnel = 'personnel';
    private $duty_schedule = 'duty_schedule';
    private $duty_attendance = 'duty_attendance';
    private $training = 'training';

    public function __construct()
    {
        parent::__construct();
    }

    public function add_fire_attendance($personnel=NULL)
    {
        $this->db->insert($this->fire_attendance, $personnel);
        return $this->db->insert_id();
    }

    public function save_personnel($personnel=NULL)
    {
        $this->db->insert('personnel', $personnel);
        return $this->db->insert_id();
    }

    public function check_fire_attendance($personnel)
    {
        $this->db->where('unit', $personnel['unit']);
        $this->db->where('attendance_date', $personnel['attendance_date']);
        $this->db->where('fire_data_id', $personnel['fire_data_id']);
        $query = $this->db->get($this->fire_attendance);
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function check_personnel($unit_number)
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

    public function get_event_info($title=NULL, $category=NULL)
    {
        $this->db->select('*');
        $this->db->from($this->fire_attendance);
        $this->db->where('title', $title);
        $this->db->where('category', $category);
        $this->db->order_by('unit', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_distinct_column_event($column)
    {
        $this->db->distinct();
        $this->db->select($column);
        $this->db->from($this->fire_attendance);
        $this->db->select('attendance_date');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_distinct_column_category($column)
    {
        $this->db->distinct();
        $this->db->select($column);
        $this->db->from($this->fire_attendance);
        $query = $this->db->get();
        return $query->result();
    }

    function get_chart_data() {
        $query = $this->db->query("select year(attendance_date) as year, count(id) as counter, unit from attendance group by year,unit order by year asc");
        $results['chart_data'] = $query->result();
//        $this->db->select_min('performance_year');
//        $this->db->limit(1);
//        $query = $this->db->get($this->performance);
//        $results['min_year'] = $query->row()->performance_year;
//        $this->db->select_max('performance_year');
//        $this->db->limit(1);
//        $query = $this->db->get($this->performance);
//        $results['max_year'] = $query->row()->performance_year;
        return $results;
    }

    function get_fire_attendance($attendance_date)
    {
        $this->db->select('fire_attendance.*,fire_data.location');
        $this->db->where('attendance_date', $attendance_date);
        $this->db->join('fire_data', 'fire_data.id = fire_attendance.fire_data_id', 'LEFT');
        $this->db->order_by('unit,attendance_date asc');
        $query = $this->db->get($this->fire_attendance);
        return $query->result();
    }

    function get_monthly_summary($category, $month)
    {
        $query = $this->db->query("SELECT * FROM attendance WHERE attendance_date = '$attendance_date' order by unit, attendance_date asc;");
        return $query->result();
    }

    function get_user_list()
    {
        $query = $this->db->query("SELECT * FROM personnel order by unit");
        return $query->result();
    }

    function get_all_units($status)
    {
        $query = $this->db->query("SELECT * FROM personnel where status = '$status' order by unit");
        return $query->result();
    }

    function get_unit_by_category($unit, $attendance_month)
    {
        $from = $attendance_month.'-01';
        $to = $attendance_month.'-31';

        $query = $this->db->query("
            SELECT p.unit,
              count(da.unit) as 'duty',
              count(t.unit) as 'training',
              count(s.unit) as 'special',
              count(m.unit) as 'meeting',
              count(f.unit) as 'fire'
            FROM personnel p
            LEFT JOIN duty_attendance da ON da.unit = p.unit AND (da.attendance_date >= '$from' and da.attendance_date <= '$to')
            LEFT JOIN training_attendance t ON t.unit = p.unit AND (t.attendance_date >= '$from' and t.attendance_date <= '$to')
            LEFT JOIN special_activity_attendance s ON s.unit = p.unit AND (s.attendance_date >= '$from' and s.attendance_date <= '$to')
            LEFT JOIN meeting_attendance m ON m.unit = p.unit AND (m.attendance_date >= '$from' and m.attendance_date <= '$to')
            LEFT JOIN fire_attendance f ON f.unit = p.unit AND (f.attendance_date >= '$from' and f.attendance_date <= '$to')
            WHERE p.unit = '$unit'
            GROUP BY p.unit
            ");
        return $query->result();
    }

    function get_unit_by_category_yearly($unit, $cat, $attendance_year)
    {
        $from = $attendance_year.'-01-01';
        $to = $attendance_year.'-12-31';

        $query = $this->db->query("
            SELECT p.unit,count(p.unit) as 'count' FROM attendance a
            LEFT JOIN personnel p on p.unit = a.unit where category = '$cat'
            and (attendance_date >= '$from' and attendance_date <= '$to')
            and p.unit = '$unit'
            group by p.unit
            ");

        if ($query->num_rows() > 0){
            return $query->row()->count;
        }
        else{
            return 0;
        }
    }

    public function add_fire_data($fire=NULL)
    {
        $this->db->insert($this->fire_data, $fire);
        return $this->db->insert_id();
    }

    public function check_fire_data($fire)
    {
        $this->db->where('date_of_fire',$fire['date_of_fire']);
        $this->db->where('location',$fire['location']);
        $query = $this->db->get($this->fire_data);
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function get_fire_list()
    {
        $this->db->order_by('date_of_fire','desc');
        $query = $this->db->get($this->fire_data);
        return $query->result();
    }
    public function get_fire_list_range($days=30)
    {
        $sql = "SELECT * FROM `fire_data` WHERE status = 'Dispatch' and date_of_fire BETWEEN CURDATE() - INTERVAL $days DAY AND CURDATE()";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_fire_summary()
    {
        $sql = "SELECT YEAR(date_of_fire) as year, DATE_FORMAT(date_of_fire, '%M') as month, count(id) as total
            FROM `fire_data` WHERE status = 'Dispatch' group by YEAR(date_of_fire), DATE_FORMAT(date_of_fire, '%M')";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_count_members($status)
    {
        $this->db->from($this->personnel);
        $this->db->where('status', strtolower($status));
        return $this->db->count_all_results();
    }

    public function get_duty_month_percentage($interval)
    {
        $this->db->from($this->duty_schedule);
        $total_schedule = ($this->db->count_all_results())*4;

        $this->db->from($this->duty_attendance);
        $this->db->where('MONTHNAME(attendance_date)', date('F', strtotime("$interval month")));
        $rendered = $this->db->count_all_results();
        //var_dump($this->db->last_query());
        return round(($rendered / $total_schedule)*100,2) . '%';
    }

    function get_summary_highest_count($order)
    {
        $from = date('Y-01-01');
        $to = date('Y-12-31');
        $query = "SELECT date_of_training as 'month',count(ta.training_id) as 'total'
            FROM ".$this->training."
            LEFT JOIN training_attendance ta ON ta.training_id = training.id
            WHERE date_of_training >= '$from' AND date_of_training <= '$to'
            GROUP BY date_of_training ORDER BY date_of_training $order limit 1";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

}