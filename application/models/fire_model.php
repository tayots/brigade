<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Frederick
 * Date: 11/1/16
 * Time: 10:53 PM
 * To change this template use File | Settings | File Templates.
 */
class Fire_model extends CI_Model {

    private $fire_attendance = 'fire_attendance';
    private $fire_data = 'fire_data';
    private $fire_apparata = 'fire_apparata';
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

    public function add_fire_attendance($personnel=NULL)
    {
        $this->db->insert($this->fire_attendance, $personnel);
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

    function get_fire_attendance($attendance_date, $location_id=null)
    {
        $this->db->select('fire_attendance.*,fire_data.location');
        $this->db->where('attendance_date', $attendance_date);
        ($location_id != null) ? $this->db->where('fire_data_id', $location_id) : '';
        $this->db->join('fire_data', 'fire_data.id = fire_attendance.fire_data_id', 'LEFT');
        $this->db->order_by('location,unit,attendance_date asc');
        $query = $this->db->get($this->fire_attendance);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    function get_fire_attendance_location($location_id)
    {
        $this->db->where('fire_data_id', $location_id);
        $this->db->order_by('unit');
        $query = $this->db->get($this->fire_attendance);
        return $query->result();
    }

    function get_fire_data($location_id)
    {
        $this->db->where('id', $location_id);
        $query = $this->db->get($this->fire_data);
        return $query->result();
    }

    function get_fire_apparata($location_id)
    {
        $this->db->where('fire_data_id', $location_id);
        $query = $this->db->get($this->fire_apparata);
        return $query->result();
    }

    public function add_fire_data($fire=NULL)
    {
        $this->db->insert($this->fire_data, $fire);
        return $this->db->insert_id();
    }

    public function add_apparata_responded($data=NULL)
    {
        return $this->db->insert_batch($this->fire_apparata, $data);
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

    public function get_fire_list($date_of_fire)
    {
        $this->db->where('date_of_fire',$date_of_fire);
        $this->db->order_by('date_of_fire','desc');
        $query = $this->db->get($this->fire_data);
        return $query->result();
    }

    public function get_fire_list_range($from_date, $to_date, $dispatch)
    {
        $this->db->where('date_of_fire >=',$from_date);
        $this->db->where('date_of_fire <=',$to_date);
        if ($dispatch == 'No') $this->db->where('dispatch',$dispatch);
        if ($dispatch == 'Yes') $this->db->where('dispatch',$dispatch);
        $this->db->order_by('date_of_fire','desc');
        $query = $this->db->get($this->fire_data);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    public function get_fire_summary()
    {
        $sql = "SELECT YEAR(date_of_fire) as year, DATE_FORMAT(date_of_fire, '%M') as month, count(id) as total
            FROM `fire_data` WHERE status = 'Dispatch' group by YEAR(date_of_fire), DATE_FORMAT(date_of_fire, '%M')";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_summary_count($from, $to)
    {
        $this->db->select('count(*)');
        $this->db->from($this->fire_data);
        $this->db->where('date_of_fire >=', $from);
        $this->db->where('date_of_fire <=', $to);
        return $this->db->count_all_results();
    }

    function update_fire_data($data, $fire_data_id)
    {
        $this->db->where('id', $fire_data_id);
        $this->db->update($this->fire_data, $data);
    }

    function get_top($from_date, $to_date, $top_limit)
    {
        $query = "SELECT ta.unit, count(*) as total
                FROM ".$this->fire_attendance." ta
                WHERE attendance_date >= '$from_date' AND attendance_date <= '$to_date'
                GROUP BY ta.unit
                ORDER BY total dESC LIMIT $top_limit";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    function get_summary_count_by_dispatch($from, $to, $dispatch)
    {
        $this->db->select('count(*)');
        $this->db->from($this->fire_data);
        $this->db->where('date_of_fire >=', $from);
        $this->db->where('date_of_fire <=', $to);
        $this->db->where('dispatch', $dispatch);
        return $this->db->count_all_results();
    }

    function get_summary_water_used($from, $to)
    {
        $query = "SELECT SUM(water_used) as 'water_used'
            FROM ".$this->fire_data."
            WHERE date_of_fire >= '$from' AND date_of_fire <= '$to'
            AND dispatch = 'Yes'";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    function get_summary_fire_occurs($from, $to, $ampm)
    {
        $query = "SELECT COUNT(*) as 'count'
            FROM ".$this->fire_data."
            WHERE date_of_fire >= '$from' AND date_of_fire <= '$to'
            AND time_received like '%".$ampm."%'";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    function get_graph_water_used($from, $to)
    {
        $query = "SELECT DATE_FORMAT(date_of_fire, '%b-%Y') as 'month',SUM(water_used) as 'water_used', count(id) as 'fire'
            FROM ".$this->fire_data."
            WHERE date_of_fire >= '$from' AND date_of_fire <= '$to'
            AND dispatch = 'Yes'
            GROUP BY month ORDER BY date_of_fire";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    function get_graph_no_used($from, $to)
    {
        $query = "SELECT DATE_FORMAT(date_of_fire, '%b-%Y') as 'month', count(id) as 'fire'
            FROM ".$this->fire_data."
            WHERE date_of_fire >= '$from' AND date_of_fire <= '$to'
            AND dispatch = 'Yes'
            AND water_used <= 0
            GROUP BY month ORDER BY date_of_fire";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    function get_graph_fires($from, $to)
    {
        $query = "SELECT DATE_FORMAT(date_of_fire, '%b-%Y') as 'month', count(id) as 'fire'
            FROM ".$this->fire_data."
            WHERE date_of_fire >= '$from' AND date_of_fire <= '$to'
            GROUP BY month ORDER BY date_of_fire";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    function get_graph_fires_previous($from, $to)
    {
        $query = "SELECT DATE_FORMAT(date_of_fire, '%b-%Y') as 'month', count(id) as 'fire'
            FROM ".$this->fire_data."
            WHERE date_of_fire >= DATE_SUB('$from',INTERVAL 1 YEAR) AND date_of_fire <= DATE_SUB('$to',INTERVAL 1 YEAR)
            GROUP BY month ORDER BY date_of_fire";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

}