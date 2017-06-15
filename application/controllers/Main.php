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

            if ($this->input->post('unit') == 'all') {
                $all_units = $this->main_model->get_all_units('active');
            }
            else {
                $temp['unit'] = $this->input->post('unit');
                $all_units[] = (object) $temp;
            }

            foreach ($all_units as $key => $value) {
                $final[$value->unit] = $this->main_model->get_unit_by_category($value->unit, $selected_from, $selected_to);
                $counter += 1;
            }

            $data['monthly']= $final;
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
            '.$data.'
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

}
