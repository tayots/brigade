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
    private $duty_version = 'duty_version';
    private $personnel = 'personnel';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_active_duty_version(){
        $this->db->where('status','active');
        $query = $this->db->get($this->duty_version);
        $ret = $query->row();
        return $ret->id;
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
        $this->db->where('duty_version',$duty['duty_version']);
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

    public function get_duty_versions()
    {
        $this->db->select('*');
        $this->db->from($this->duty_version);
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

    public function get_duty_schedule($schedule, $version)
    {
        $this->db->select('*');
        $this->db->from($this->duty_schedule);
        $this->db->where('schedule', $schedule);
        $this->db->where('version', $version);
        $query = $this->db->get();
        return $query->result();
    }

    function get_top($from_date, $to_date, $top_limit)
    {
        $query = "SELECT ta.unit, count(*) as total
                FROM ".$this->duty_attendance." ta
                WHERE attendance_date >= '$from_date' AND attendance_date <= '$to_date'
                GROUP BY ta.unit
                ORDER BY total dESC LIMIT $top_limit";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    function get_unit_duties($data)
    {
        $query = "SELECT da.*,
                IF(ds.schedule IS NULL,'ADD','DUTY') aS 'remarks',
                dv.name as 'version_name'
                FROM `duty_attendance` da
                LEFT JOIN `duty_schedule` ds
                ON ds.unit = da.unit AND ds.schedule = da.schedule and ds.version = da.duty_version
                LEFT JOIN `duty_version` dv ON dv.id = da.duty_version
                WHERE da.`unit` = '".$data['unit']."'
                AND da.`attendance_date` >= '".$data['select_from']."'
                AND da.`attendance_date` <= '".$data['select_to']."'
                ORDER BY da.`attendance_date` ASC";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    function get_duty_unit_detail($unit, $attendance_date, $schedule, $version)
    {
        $this->db->from($this->duty_attendance);
        $this->db->where('unit', $unit);
        $this->db->where('attendance_date', $attendance_date);
        $this->db->where('schedule', $schedule);
        $this->db->where('duty_version', $version);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result();
    }

    function update_duty_attendance($data, $original)
    {
        $this->db->where('unit', $original[0]);
        $this->db->where('attendance_date', $original[1]);
        $this->db->where('schedule', $original[2]);
        $this->db->where('duty_version', $original[3]);
        unset($data['unit']);
        return $this->db->update($this->duty_attendance, $data);
    }

    function get_duties_summary($from, $to, $sort)
    {
        if ($sort == 1) $sortby =' total_rendered DESC';
        elseif ($sort == 2) $sortby =' total_add_late DESC';
        elseif ($sort == 3) $sortby =' total_duty_late DESC';
        elseif ($sort == 4) $sortby =' total_rendered ASC';
        elseif ($sort == 5) $sortby =' total_add_late ASC';
        elseif ($sort == 6) $sortby =' total_duty_late ASC';
        else $sortby = ' d.unit ASC';

        $query = "SELECT
            d.unit,
            COALESCE(f.total_rendered,0,f.total_rendered) as total_rendered,
            COALESCE(g.total_add,0,g.total_add) as total_add,
            COALESCE(j.total_late,0,j.total_late) as total_add_late,
            COALESCE(h.total_duty,0,h.total_duty) as total_duty,
            COALESCE(i.total_late,0,i.total_late) as total_duty_late
            FROM `duty_attendance` d
            LEFT JOiN (SELECT count(*) AS total_rendered,unit
            FROM duty_attendance where attendance_date >= '$from' AND attendance_date <= '$to' GROUP BY unit) as f ON f.unit = d.unit
            LEFT JOIN (SELECT da.unit,count(*) as total_add
                            FROM `duty_attendance` da
                            LEFT JOIN `duty_schedule` ds
                            ON ds.unit = da.unit AND ds.schedule = da.schedule and ds.version = da.duty_version
                            LEFT JOIN `duty_version` dv ON dv.id = da.duty_version
                            WHERE da.`attendance_date` >= '$from'
                            AND da.`attendance_date` <= '$to'
                            AND ds.schedule IS NULL
                            GROUP BY da.unit,ds.schedule IS NULL) g ON g.unit = d.unit
            LEFT JOIN (SELECT da.unit,count(*) as total_duty
                            FROM `duty_attendance` da
                            LEFT JOIN `duty_schedule` ds
                            ON ds.unit = da.unit AND ds.schedule = da.schedule and ds.version = da.duty_version
                            LEFT JOIN `duty_version` dv ON dv.id = da.duty_version
                            WHERE da.`attendance_date` >= '$from'
                            AND da.`attendance_date` <= '$to'
                            AND ds.schedule IS NOT NULL
                            GROUP BY da.unit,ds.schedule IS NOT NULL) h ON h.unit = d.unit
            LEFT JOIN (SELECT da.unit,count(*) as total_late
                            FROM `duty_attendance` da
                            LEFT JOIN `duty_schedule` ds
                            ON ds.unit = da.unit AND ds.schedule = da.schedule and ds.version = da.duty_version
                            LEFT JOIN `duty_version` dv ON dv.id = da.duty_version
                            WHERE da.`attendance_date` >= '$from'
                            AND da.`attendance_date` <= '$to'
                            AND ds.schedule IS NOT NULL
                            AND time_in > '09:30 PM'
                            GROUP BY da.unit,ds.schedule IS NOT NULL) i ON i.unit = d.unit
            LEFT JOIN (SELECT da.unit,count(*) as total_late
                            FROM `duty_attendance` da
                            LEFT JOIN `duty_schedule` ds
                            ON ds.unit = da.unit AND ds.schedule = da.schedule and ds.version = da.duty_version
                            LEFT JOIN `duty_version` dv ON dv.id = da.duty_version
                            WHERE da.`attendance_date` >= '$from'
                            AND da.`attendance_date` <= '$to'
                            AND ds.schedule IS NULL
                            AND time_in > '09:30 PM'
                            GROUP BY da.unit,ds.schedule IS NULL) j ON j.unit = d.unit
            group by d.unit
            order by $sortby";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    function get_required_duties($data, $version)
    {
        $begin = new DateTime( $data['select_from'] );
        $end = new DateTime( $data['select_to'] );
        $end->modify("+ 1 day");
        $total_count = 0;

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);
        $arrayDay = [];
        foreach($daterange as $date){
            $arrayDay[$date->format("l")] = $date->format("l");
        }
        $days = implode("','", $arrayDay);
        $query = "SELECT * FROM $this->duty_schedule WHERE unit = '".$data['unit']."'
                AND version = $version
                AND schedule IN ('$days')";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                foreach($daterange as $date){
                    if ($row->schedule ==$date->format("l")) $total_count+=1;
                }
            }
            return $total_count;
        }
        else return 0;
    }

    public function get_graph_tardiness($unit, $from, $to)
    {

        $query = "SELECT DATE_FORMAT(attendance_date, '%b-%Y') as 'month', count(*) as 'tardiness_count'
            FROM ".$this->duty_attendance."
            WHERE attendance_date >= '$from' AND attendance_date <= '$to'
            AND time_in > '09:30 PM'
            AND unit = '$unit'
            GROUP BY month ORDER BY attendance_date";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

    public function get_duties_details($date_)
    {
        $query = "SELECT da.*,
                IF(ds.schedule IS NULL,'ADD','DUTY') aS 'remarks',
                dv.name as 'version_name'
                FROM `duty_attendance` da
                LEFT JOIN `duty_schedule` ds
                ON ds.unit = da.unit AND ds.schedule = da.schedule and ds.version = da.duty_version
                LEFT JOIN `duty_version` dv ON dv.id = da.duty_version
                WHERE da.`attendance_date` >= '".$date_."'
                AND da.`attendance_date` <= '".$date_."'
                ORDER BY da.`attendance_date` ASC";
        $query = $this->db->query($query);
        //var_dump($this->db->last_query());
        return $query->result();
    }

}