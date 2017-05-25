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
        $attendance_month= ($this->input->post('attendance_month'));
        $final = array();
        $data['monthly'] = array();
        $data['summary'] = '';
        $data['attendance_month'] = $attendance_month;
        $counter = 0;
        $data['selected_month'] = '';

        if ($attendance_month){
            $all_units = $this->main_model->get_all_units('active');
            $selected_month = (date_format($date=date_create($attendance_month.'-01'),'F Y'));
            foreach ($all_units as $key => $value) {
                $final[$value->unit] = $this->main_model->get_unit_by_category($value->unit, $attendance_month);
                $counter += 1;
            }

            $data['monthly']= $final;
            $data['selected_month']= $selected_month;

        }

        $this->load->view('monthly_reports', $data);
    }

    function yearly_reports($action=NULL)
    {
        $attendance_year = ($this->input->post('attendance_year'));
        $final = array();
        $data['yearly'] = array();
        $data['summary'] = '';
        $data['attendance_year'] = $attendance_year;
        $counter = 0;
        $data['selected_year'] = '';

        if ($attendance_year){
            $all_units = $this->main_model->get_all_units('active');
            $selected_year = (date_format($date=date_create($attendance_year.'-01-01'),'Y'));
            foreach ($all_units as $key => $value) {
                $final[$value->unit] = [
                    'Duty'=> $this->main_model->get_unit_by_category_yearly($value->unit, 'Duty', $attendance_year),
                    'Fire Response'=> $this->main_model->get_unit_by_category_yearly($value->unit, 'Fire Response', $attendance_year),
                    'Training'=> $this->main_model->get_unit_by_category_yearly($value->unit, 'Training', $attendance_year),
                    'Meeting'=> $this->main_model->get_unit_by_category_yearly($value->unit, 'Meeting', $attendance_year),
                    'Special Activity'=> $this->main_model->get_unit_by_category_yearly($value->unit, 'Special Activity', $attendance_year),
                    'Total'=> 'NA'
                ];
                $counter += 1;
            }

            $data['yearly']= $final;
            $data['selected_year']= $selected_year;
        }

        $this->load->view('yearly_reports', $data);
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
            '.$data.'
          ]
        }';
        echo $whatever;

    }

    function category_reports()
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

        if ($_POST) {
            $from_date = $data['from_date'] = $this->input->post('from_date');
            $to_date = $data['to_date'] = $this->input->post('to_date');

            $this->load->model('training_model');
            $this->load->model('fire_model');
            $this->load->model('meeting_model');
            $this->load->model('duty_model');
            $this->load->model('special_model');

            $data['training'] = $this->training_model->get_top_20($from_date, $to_date);
            $data['fire'] = $this->fire_model->get_top_20($from_date, $to_date);
            $data['duty'] = $this->duty_model->get_top_20($from_date, $to_date);
            $data['meeting'] = $this->meeting_model->get_top_20($from_date, $to_date);
            $data['special'] = $this->special_model->get_top_20($from_date, $to_date);
        }

        $this->load->view('category_reports', $data);
    }

}
