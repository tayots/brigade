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
    }

	public function index()
	{
		$this->load->view('main_index');
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
                $final[$value->unit] = [
                    'Duty'=> $this->main_model->get_unit_by_category($value->unit, 'Duty', $attendance_month),
                    'Fire Response'=> $this->main_model->get_unit_by_category($value->unit, 'Fire Response', $attendance_month),
                    'Training'=> $this->main_model->get_unit_by_category($value->unit, 'Training', $attendance_month),
                    'Meeting'=> $this->main_model->get_unit_by_category($value->unit, 'Meeting', $attendance_month),
                    'Special Activity'=> $this->main_model->get_unit_by_category($value->unit, 'Special Activity', $attendance_month),
                    'Total'=> 'NA'
                ];
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

}
