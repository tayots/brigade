<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
    function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('main_model');
        $this->load->library('form_validation');

        $this->activities = array('Duty','Training','Special Activity','Meeting','Fire Response');
    }

	public function index()
	{
        $data['current_year'] = date('Y');
        $data['active_members'] = $this->main_model->get_count_members('active');
        $data['inactive_members'] = $this->main_model->get_count_members('inactive');
        $data['duty_month'] = $this->main_model->get_duty_month_percentage($interval='+0');
        $data['duty_previous_month'] = $this->main_model->get_duty_month_percentage($interval='-1');
        $data['highest_training'] = $this->main_model->get_summary_highest_count('DESC');
        $data['lowest_training'] = $this->main_model->get_summary_highest_count('ASC');
        $this->load->view('main_index', $data);
	}

	public function report()
	{
        $results = $this->main_model->get_chart_data();
        $data['chart_data'] = $results['chart_data'];
		$this->load->view('main_report', $data);
	}

    function monthly_reports($action=NULL)
    {
        $this->load->model('personnel_model');

        $data['unit_list'] = $this->personnel_model->get_user_list();
        $data['selected_unit'] = 'all';
        $final = array();
        $data['monthly'] = array();
        $data['summary'] = '';
        $counter = 0;
        $data['selected_from'] = date('Y-m-01');
        $d = new DateTime( date('Y-m-d') );
        $data['selected_to'] = $d->format( 'Y-m-t' );
        $temp = [];

        $data['fire_summary'] = 0;
        $data['training_summary'] = 0;
        $data['meeting_summary'] = 0;
        $data['special_summary'] = 0;
        $data['duty_summary'] = 0;

        if ($_POST){
            $selected_from = $this->input->post('select_from');
            $selected_to = $this->input->post('select_to');
            $selected_unit = $this->input->post('unit');

            $data['monthly']= $this->main_model->get_unit_by_category($selected_unit, $selected_from, $selected_to);
            $data['selected_unit'] = $this->input->post('unit');
            $data['selected_from'] = $selected_from;
            $data['selected_to'] = $selected_to;

            $data['fire_summary'] = $this->main_model->get_summary_count_category('fire_data', 'date_of_fire', $selected_from, $selected_to);
            $data['training_summary'] = $this->main_model->get_summary_count_category('training', 'date_of_training', $selected_from, $selected_to);
            $data['meeting_summary'] = $this->main_model->get_summary_count_category('meeting', 'date_of_meeting', $selected_from, $selected_to);
            $data['special_summary'] = $this->main_model->get_summary_count_category('special_activity', 'date_of_special', $selected_from, $selected_to);
        }

        $this->load->view('monthly_reports', $data);
    }

    function get_activities()
    {
        $from_date = date('Y-01-01');
        $to_date = date('Y-12-31');

        $this->load->model('training_model');
        $this->load->model('fire_model');
        $this->load->model('special_model');
        $this->load->model('meeting_model');

        $fire = $this->fire_model->get_summary_count($from_date, $to_date);
        $training = $this->training_model->get_summary_count($from_date, $to_date);
        $meeting = $this->meeting_model->get_summary_count($from_date, $to_date);
        $special = $this->special_model->get_summary_count($from_date, $to_date);

        $whatever = '{
        "cols": [
            {"id":"","label":"Activities","pattern":"","type":"string"},
            {"id":"","label":"Activities","pattern":"","type":"number"}
          ],
        "rows": [
            {"c":[{"v":"Fire Alarms","f":null},{"v":'.$fire.',"f":null}]},
            {"c":[{"v":"Trainings","f":null},{"v":'.$training.',"f":null}]},
            {"c":[{"v":"Meetings","f":null},{"v":'.$meeting.',"f":null}]},
            {"c":[{"v":"Special Activities","f":null},{"v":'.$special.',"f":null}]}
          ]
        }';
        echo $whatever;

    }

    function get_trainings()
    {
        $from_date = date('Y-01-01');
        $to_date = date('Y-12-31');

        $this->load->model('training_model');

        $training = $this->training_model->get_summary_count_last_six();
        $data = '';
        foreach($training as $key => $value){
            $data .= '{"c":[{"v":"'.$value->month.'","f":null},{"v":'.$value->total.',"f":null}]},';
        }
        $whatever = '{
        "cols": [
            {"id":"","label":"Trainings","pattern":"","type":"string"},
            {"id":"","label":"Count","pattern":"","type":"number"}
          ],
        "rows": [
            '.rtrim($data,",").'
          ]
        }';
        echo $whatever;

    }

    function top_reports()
    {
        $data = [];
        $data['from_date'] = date('Y-m-01');
        $d = new DateTime( date('Y-m-d') );
        $data['to_date'] = $d->format( 'Y-m-t' );
        $data['training'] = [];
        $data['fire'] = [];
        $data['duty'] = [];
        $data['meeting'] = [];
        $data['special'] = [];
        $data['top_limit'] = 10;

        $data['fire_summary'] = 0;
        $data['training_summary'] = 0;
        $data['meeting_summary'] = 0;
        $data['special_summary'] = 0;
        $data['duty_summary'] = 0;

        if ($_POST) {
            $from_date = $data['from_date'] = $this->input->post('from_date');
            $to_date = $data['to_date'] = $this->input->post('to_date');
            $top_limit = $this->input->post('top_limit');

            $this->load->model('training_model');
            $this->load->model('fire_model');
            $this->load->model('meeting_model');
            $this->load->model('duty_model');
            $this->load->model('special_model');

            $data['training'] = $this->training_model->get_top($from_date, $to_date, $top_limit);
            $data['fire'] = $this->fire_model->get_top($from_date, $to_date, $top_limit);
            $data['duty'] = $this->duty_model->get_top($from_date, $to_date, $top_limit);
            $data['meeting'] = $this->meeting_model->get_top($from_date, $to_date, $top_limit);
            $data['special'] = $this->special_model->get_top($from_date, $to_date, $top_limit);

            $data['fire_summary'] = $this->main_model->get_summary_count_category('fire_data', 'date_of_fire', $from_date, $to_date);
            $data['training_summary'] = $this->main_model->get_summary_count_category('training', 'date_of_training', $from_date, $to_date);
            $data['meeting_summary'] = $this->main_model->get_summary_count_category('meeting', 'date_of_meeting', $from_date, $to_date);
            $data['special_summary'] = $this->main_model->get_summary_count_category('special_activity', 'date_of_special', $from_date, $to_date);
        }

        $this->load->view('top_reports', $data);
    }

    public function fire_reports()
    {
        $this->load->model('fire_model');

        $data['selected_from'] = date('Y-01-01');
        $d = new DateTime( date('Y-m-d') );
        $data['selected_to'] = $d->format( 'Y-m-t' );

        if ($_POST){
            $data['selected_from'] = $this->input->post('select_from');
            $data['selected_to'] = $this->input->post('select_to');
        }

        $data['responded'] = $this->fire_model->get_summary_count_by_dispatch($data['selected_from'], $data['selected_to'], 'Yes');
        $data['no_respond'] = $this->fire_model->get_summary_count_by_dispatch($data['selected_from'], $data['selected_to'], 'No');
        $data['total_water'] = $this->fire_model->get_summary_water_used($data['selected_from'], $data['selected_to'])[0]->water_used.' TONS';
        $data['total_fires'] = $data['responded'] + $data['no_respond'];

        $am_fires = $this->fire_model->get_summary_fire_occurs($data['selected_from'], $data['selected_to'], 'AM')[0]->count;
        $pm_fires = $this->fire_model->get_summary_fire_occurs($data['selected_from'], $data['selected_to'], 'PM')[0]->count;
        $data['am_fires'] = number_format(($am_fires / $data['total_fires'])*100,2);
        $data['pm_fires'] = number_format(($pm_fires / $data['total_fires'])*100,2);

        $this->load->view('fire_reports', $data);
    }

    public function get_fire_respond()
    {
        if ($_POST){
            $from_date = $this->input->post('select_from');
            $to_date = $this->input->post('select_to');

            $this->load->model('fire_model');

            $responded = $this->fire_model->get_summary_count_by_dispatch($from_date, $to_date, 'Yes');
            $no_responded = $this->fire_model->get_summary_count_by_dispatch($from_date, $to_date, 'No');

            $whatever = '{
                "cols": [
                    {"id":"","label":"Activities","pattern":"","type":"string"},
                    {"id":"","label":"Activities","pattern":"","type":"number"}
                  ],
                "rows": [
                    {"c":[{"v":"Responded","f":null},{"v":'.$responded.',"f":null}]},
                    {"c":[{"v":"No Respond","f":null},{"v":'.$no_responded.',"f":null}]}
                  ]
                }';

            echo $whatever;
        }
    }

    function get_water_used()
    {
        if ($_POST){
            $from_date = $this->input->post('select_from');
            $to_date = $this->input->post('select_to');

            $this->load->model('fire_model');

            $training = $this->fire_model->get_graph_water_used($from_date, $to_date);
            $data = '';
            foreach($training as $key => $value){
                $data .= '{"c":[{"v":"'.$value->month.'","f":null},{"v":'.$value->water_used.',"f":null},{"v":'.$value->fire.',"f":null}]},';
            }
            $whatever = '{
                "cols": [
                    {"id":"","label":"Trainings","pattern":"","type":"string"},
                    {"id":"","label":"Water Used","pattern":"","type":"number"},
                    {"id":"","label":"Fires Responded","pattern":"","type":"number"}
                  ],
                "rows": [
                    '.rtrim($data,",").'
                  ]
                }';
            echo $whatever;
        }
    }

    function get_no_used()
    {
        if ($_POST){
            $from_date = $this->input->post('select_from');
            $to_date = $this->input->post('select_to');

            $this->load->model('fire_model');

            $training = $this->fire_model->get_graph_no_used($from_date, $to_date);
            $data = '';
            foreach($training as $key => $value){
                $data .= '{"c":[{"v":"'.$value->month.'","f":null},{"v":'.$value->fire.',"f":null}]},';
            }
            $whatever = '{
                "cols": [
                    {"id":"","label":"Trainings","pattern":"","type":"string"},
                    {"id":"","label":"No Discharge","pattern":"","type":"number"}
                  ],
                "rows": [
                    '.rtrim($data,",").'
                  ]
                }';
            echo $whatever;
        }
    }

    function get_fires()
    {
        if ($_POST){
            $from_date = $this->input->post('select_from');
            $to_date = $this->input->post('select_to');

            $this->load->model('fire_model');

            $training = $this->fire_model->get_graph_fires($from_date, $to_date);
            $training2 = $this->fire_model->get_graph_fires_previous($from_date, $to_date);

            $data = [];
            array_push($data,['Months','This Year Fires', 'Last Year Fires']);
            foreach($training as $key => $value){
                if (isset($training2[$key])) array_push($data,[$value->month,$value->fire*1, $training2[$key]->fire*1]);
                else array_push($data,[$value->month,$value->fire*1, 0*1]);
            }
            echo json_encode($data);
            exit;
        }
    }

    public function duty_reports()
    {
        $this->load->model('duty_model');

        $data['select_from'] = date('Y-m-01');
        $d = new DateTime( date('Y-m-d') );
        $data['select_to'] = $d->format( 'Y-m-t' );
        $data['sort_by'] = 0; //default by Unit
        $ab = 0;
        if ($_POST){
            $data['select_from'] = $this->input->post('select_from');
            $data['select_to'] = $this->input->post('select_to');
            $data['sort_by'] = $this->input->post('sort_by');

            $result = $this->duty_model->get_duties_summary($data['select_from'], $data['select_to'], $data['sort_by']);
            $version = $this->duty_model->get_active_duty_version();

            foreach($result as $key => $value){
                $data['unit'] = $value->unit;
                $result[$key]->total_required = $this->duty_model->get_required_duties($data, $version);

                if ($value->total_required != 0){
                    $ab = number_format((($value->total_required-$value->total_duty)/$value->total_required)*100,0);
                    if ((int)$ab > 75) $ab = '<span style="color:red">'.$ab.'%</span>';
                    else $ab = $ab.'%';
                    $result[$key]->total_absences = $value->total_required-$value->total_duty.' ('.$ab.')';
                }
                else $result[$key]->total_absences = '0 (0%)';
            }
            $data['duties'] = $result;
        }

        $this->load->view('duty_reports', $data);
    }

    function monthly_fire_reports($action=NULL)
    {
        $this->load->model('personnel_model');

        $data['unit_list'] = $this->personnel_model->get_user_list();
        $final = array();
        $data['monthly'] = array();
        $data['summary'] = '';
        $counter = 0;
        $data['selected_from'] = date('Y-m-01');
        $d = new DateTime( date('Y-m-d') );
        $data['selected_to'] = $d->format( 'Y-m-t' );
        $temp = [];

        $data['fire_summary'] = 0;

        if ($_POST){
            $d = new DateTime( $this->input->post('select_from') );
            $selected_from = $d->format( 'Y-m' );
            $e = new DateTime( $this->input->post('select_to') );
            $selected_to = $e->format( 'Y-m' );

            $data['selected_from'] = $this->input->post('select_from');
            $data['selected_to'] = $this->input->post('select_to');

            //Get month interval from selected dates
            $date1 = new DateTime($this->input->post('select_from'));
            $date2 = new DateTime($this->input->post('select_to'));
            $diff = date_diff($date1, $date2);
            $interval = new DateInterval('P1M');
            $month_list = [$d->format( 'F Y' )]; //first month
            for ($x=0; $x<$diff->m; $x++) {
                $interval = new DateInterval('P1M');
                $d->add($interval);
                array_push($month_list, $d->format('F Y'));
            }

            $data['month_list']= $month_list;
            $result = $this->main_model->get_monthly_fire_count($selected_from, $selected_to);
            $sort_data = array();
            foreach ($result as $key => $val) {
                $sort_data[$val->unit][$val->month] = $val->total;
            }
            $data['fire_data'] = $sort_data;
        }

        $this->load->view('monthly_fire_reports', $data);
    }

    function monthly_training_reports($action=NULL)
    {
        $this->load->model('personnel_model');

        $data['unit_list'] = $this->personnel_model->get_user_list();
        $final = array();
        $data['monthly'] = array();
        $data['summary'] = '';
        $counter = 0;
        $data['selected_from'] = date('Y-m-01');
        $d = new DateTime( date('Y-m-d') );
        $data['selected_to'] = $d->format( 'Y-m-t' );
        $temp = [];

        $data['fire_summary'] = 0;

        if ($_POST){
            $d = new DateTime( $this->input->post('select_from') );
            $selected_from = $d->format( 'Y-m' );
            $e = new DateTime( $this->input->post('select_to') );
            $selected_to = $e->format( 'Y-m' );

            $data['selected_from'] = $this->input->post('select_from');
            $data['selected_to'] = $this->input->post('select_to');

            //Get month interval from selected dates
            $date1 = new DateTime($this->input->post('select_from'));
            $date2 = new DateTime($this->input->post('select_to'));
            $diff = date_diff($date1, $date2);
            $interval = new DateInterval('P1M');
            $month_list = [$d->format( 'F Y' )]; //first month
            for ($x=0; $x<$diff->m; $x++) {
                $interval = new DateInterval('P1M');
                $d->add($interval);
                array_push($month_list, $d->format('F Y'));
            }

            $data['month_list']= $month_list;
            $result = $this->main_model->get_monthly_training_count($selected_from, $selected_to);
            $sort_data = array();
            foreach ($result as $key => $val) {
                $sort_data[$val->unit][$val->month] = $val->total;
            }
            $data['training_data'] = $sort_data;
        }

        $this->load->view('monthly_training_reports', $data);
    }

    function monthly_meeting_reports($action=NULL)
    {
        $this->load->model('personnel_model');

        $data['unit_list'] = $this->personnel_model->get_user_list();
        $final = array();
        $data['monthly'] = array();
        $data['summary'] = '';
        $counter = 0;
        $data['selected_from'] = date('Y-m-01');
        $d = new DateTime( date('Y-m-d') );
        $data['selected_to'] = $d->format( 'Y-m-t' );
        $temp = [];

        $data['fire_summary'] = 0;

        if ($_POST){
            $d = new DateTime( $this->input->post('select_from') );
            $selected_from = $d->format( 'Y-m' );
            $e = new DateTime( $this->input->post('select_to') );
            $selected_to = $e->format( 'Y-m' );

            $data['selected_from'] = $this->input->post('select_from');
            $data['selected_to'] = $this->input->post('select_to');

            //Get month interval from selected dates
            $date1 = new DateTime($this->input->post('select_from'));
            $date2 = new DateTime($this->input->post('select_to'));
            $diff = date_diff($date1, $date2);
            $interval = new DateInterval('P1M');
            $month_list = [$d->format( 'F Y' )]; //first month
            for ($x=0; $x<$diff->m; $x++) {
                $interval = new DateInterval('P1M');
                $d->add($interval);
                array_push($month_list, $d->format('F Y'));
            }

            $data['month_list']= $month_list;
            $result = $this->main_model->get_monthly_meeting_count($selected_from, $selected_to);
            $sort_data = array();
            foreach ($result as $key => $val) {
                $sort_data[$val->unit][$val->month] = $val->total;
            }
            $data['meeting_data'] = $sort_data;
        }

        $this->load->view('monthly_meeting_reports', $data);
    }

    function monthly_special_reports($action=NULL)
    {
        $this->load->model('personnel_model');

        $data['unit_list'] = $this->personnel_model->get_user_list();
        $final = array();
        $data['monthly'] = array();
        $data['summary'] = '';
        $counter = 0;
        $data['selected_from'] = date('Y-m-01');
        $d = new DateTime( date('Y-m-d') );
        $data['selected_to'] = $d->format( 'Y-m-t' );
        $temp = [];

        $data['fire_summary'] = 0;

        if ($_POST){
            $d = new DateTime( $this->input->post('select_from') );
            $selected_from = $d->format( 'Y-m' );
            $e = new DateTime( $this->input->post('select_to') );
            $selected_to = $e->format( 'Y-m' );

            $data['selected_from'] = $this->input->post('select_from');
            $data['selected_to'] = $this->input->post('select_to');

            //Get month interval from selected dates
            $date1 = new DateTime($this->input->post('select_from'));
            $date2 = new DateTime($this->input->post('select_to'));
            $diff = date_diff($date1, $date2);
            $interval = new DateInterval('P1M');
            $month_list = [$d->format( 'F Y' )]; //first month
            for ($x=0; $x<$diff->m; $x++) {
                $interval = new DateInterval('P1M');
                $d->add($interval);
                array_push($month_list, $d->format('F Y'));
            }

            $data['month_list']= $month_list;
            $result = $this->main_model->get_monthly_special_count($selected_from, $selected_to);
            $sort_data = array();
            foreach ($result as $key => $val) {
                $sort_data[$val->unit][$val->month] = $val->total;
            }
            $data['meeting_data'] = $sort_data;
        }

        $this->load->view('monthly_special_reports', $data);
    }

}
