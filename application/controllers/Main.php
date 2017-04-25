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

    public function delete_personnel($id)
    {
        $this->db->delete('personnel', array('id' => $id));
        $data['message'] = 'Successfully deleted Personnel '.$id;
        redirect("/main/personnel", 'refresh');
    }

    public function personnel()
    {
        $first_name= ($this->input->post('first_name'));
        $last_name= ($this->input->post('last_name'));
        $unit_number= ($this->input->post('unit_number'));
        $data['alert_type'] =  "success";

        if ($_POST){
            if ($first_name != '' && $last_name != '' && $unit_number != ''){
                $personnel = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'unit' => $unit_number,
                    'status' => 'active'
                );
                if ($this->main_model->check_personnel($unit_number) == true){
                    $data['alert_type'] =  "danger";
                    $data['message'] =  "User already exist! Must not be duplicate. ------> $first_name $last_name #$unit_number";
                }
                else{
                    if ($this->main_model->save_personnel($personnel)) {
                        $data['message'] =  "Personnel added! ------>  $first_name $last_name #$unit_number";
                    }
                }
            }
            else {
                $data['alert_type'] =  "danger";
                $data['message'] = 'Error adding personnel. Please check required field.';
            }
        }


        $data['information'] = $this->main_model->get_user_list();
        $this->load->view('main_personnel', $data);
    }

    public function duty_schedule()
    {
        $schedule = ($this->input->post('schedule'));
        $unit_number= ($this->input->post('unit'));
        $data = '';
        $sched_data = array();

        if ($_POST){
            $this->form_validation->set_rules('schedule', 'schedule', 'required');
            $this->form_validation->set_rules('unit', 'unit', 'required');

            if ($this->form_validation->run() == false){
                $this->session->set_flashdata('alert_type', 'danger');
                $this->session->set_flashdata('message', 'Error saving. Please input required field.');
            }
            else {
                if ($this->main_model->check_personnel($unit_number) == true){
                    $sched_data = array(
                        'schedule' => $schedule,
                        'unit' => $unit_number
                    );
                    $this->main_model->add_schedule($sched_data);
                    $this->session->set_flashdata('alert_type', 'success');
                    $this->session->set_flashdata('message', 'Schedule saved!');
                }
                else {
                    $this->session->set_flashdata('alert_type', 'danger');
                    $this->session->set_flashdata('message', 'Unit does not exist.');
                }
            }
        }

        $data['Monday'] = $this->main_model->get_duty_schedule('Monday');
        $data['Tuesday'] = $this->main_model->get_duty_schedule('Tuesday');
        $data['Wednesday'] = $this->main_model->get_duty_schedule('Wednesday');
        $data['Thursday'] = $this->main_model->get_duty_schedule('Thursday');
        $data['Friday'] = $this->main_model->get_duty_schedule('Friday');
        $data['Saturday'] = $this->main_model->get_duty_schedule('Saturday');
        $data['Sunday'] = $this->main_model->get_duty_schedule('Sunday');
        $this->load->view('main_duty_schedule', $data);
    }

    public function delete_duty_schedule($unit, $schedule)
    {
        $this->db->delete('duty_schedule', array('unit' => $unit, 'schedule' => $schedule));
        redirect("/main/duty_schedule", 'refresh');
    }


    public function fire_attendance()
	{
        $data['selected_title'] = '';
        $attendance_date = date('Y-m-d');

        if ($_POST) {
            //validation
            $this->form_validation->set_rules('title', 'title', 'required');
            $this->form_validation->set_rules('unit', 'unit', 'required');

            if ($this->form_validation->run() == false){
                $this->session->set_flashdata('alert_type', 'danger');
                $this->session->set_flashdata('message', 'Error saving. Please input required field.');
            }
            else {
                $title = explode("|", $this->input->post('title'));
                $personnel = array(
                    'unit' => $this->input->post('unit'),
                    'fire_data_id' => $title[0],
                    'attendance_date' => $title[1],
                    'status' => 'active'
                );

                if ($this->main_model->check_fire_attendance($personnel) == true){
                    $this->session->set_flashdata('alert_type', 'danger');
                    $this->session->set_flashdata('message', "User already existed on said event. Please check ------>  ".$personnel['unit']." on ".$personnel['attendance_date']);
                }
                else{
                    $personnelId = $this->main_model->add_fire_attendance($personnel);
                    $this->session->set_flashdata('alert_type', 'success');
                    $this->session->set_flashdata('message', "Successfully added ------>  ".$personnel['unit']." on ".$personnel['attendance_date']);
                }

                $attendance_date = $personnel['attendance_date'];
            }

            $data['selected_title'] = $this->input->post('title');
        }

        $data['information'] = $this->main_model->get_fire_attendance($attendance_date);
        $data['fire_list'] = $this->main_model->get_fire_list_range(30); //last 30 days
		$this->load->view('fire_attendance', $data);
	}

	public function fire_review_attendance()
	{
        $title= ($this->input->post('title'));
        $category= ($this->input->post('category'));
        $data['information'] = [];

        if ($title != '' && $category != '') {
            $data['information'] = $this->main_model->get_event_info($title, $category);
        }

        $data['category_list'] = '';
        $data['title_list'] = '';
        $data['title'] = $title;
        $data['category'] = $category;
		$this->load->view('fire_review_attendance', $data);
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

	public function delete_unit($id, $attendance_date, $goback)
	{
        $this->db->delete('fire_attendance', array('id' => $id));
        $data['message'] = 'Successfully deleted '.$id;
        $data['information'] = $this->main_model->get_fire_attendance($attendance_date);
        $this->session->set_flashdata('alert_type', 'success');
        $this->session->set_flashdata('message', "Successfully deleted unit");
        redirect("/main/fire_attendance", 'refresh');
    }

    public function save()
    {
        $unit_number= ($this->input->post('unit_number'));
        $category= ($this->input->post('category'));
        $attendance_date= ($this->input->post('attendance_date'));
        $title= ($this->input->post('title'));

        $data['message'] =  "";
        $data['attendance_date'] =  $attendance_date;
        $data['category'] = $category;
        $data['title'] = $title;
        $data['alert_type'] =  "success";

        $personnel = array(
            'unit' => $unit_number,
            'attendance_date' => $attendance_date,
            'category' => $category,
            'title' => $title,
            'status' => 'active'
        );

        if ($this->input->post('unit_number') && $this->input->post('category') &&
            $this->input->post('attendance_date') && $this->input->post('title'))
        {
            //check if already existing
            if ($this->main_model->check_attendance($unit_number,$category, $attendance_date, $title) == true){
                $data['alert_type'] =  "danger";
                $data['message'] =  "User already exist! Must not be duplicate. ------>  $unit_number on $attendance_date";;
            }
            else{
                $personnelId = $this->main_model->addattendance($personnel);
                if($personnelId){
                    $data['message'] =  "Attendance Successfully Saved ! ------>  $unit_number on $attendance_date";
                } else {
                    $data['alert_type'] =  "danger";
                    $data['message'] =  "Error saving.";
                }
            }
        }
        else {
            $data['alert_type'] =  "danger";
            $data['message'] =  "Error saving. Please input required field";
        }

        $data['information'] = $this->main_model->userInformation($attendance_date);
        $this->load->view('main_add', $data);
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

    function fire_data() {
        $data = array();

        if ($_POST) {
            $this->form_validation->set_rules('date_of_fire', 'date_of_fire', 'required');
            $this->form_validation->set_rules('location', 'location', 'required');
            $this->form_validation->set_rules('classification', 'classification', 'required');
            $this->form_validation->set_rules('caller', 'caller', 'required');
            $this->form_validation->set_rules('time_r_hour', 'time_r_hour', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_r_min', 'time_r_min', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_r_period', 'time_r_period', 'required');
            $this->form_validation->set_rules('time_c_hour', 'time_c_hour', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_c_min', 'time_c_min', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_c_period', 'time_c_period', 'required');
            $this->form_validation->set_rules('water_used', 'water_used', 'required|numeric');
            $this->form_validation->set_rules('status', 'status', 'required');
            $this->form_validation->set_rules('unit', 'unit', 'required');

            $fire = array(
                'date_of_fire' => $this->input->post('date_of_fire'),
                'location' => $this->input->post('location'),
                'classification' => $this->input->post('classification'),
                'caller' => $this->input->post('caller'),
                'contact_number' => $this->input->post('contact_number'),
                'time_received' => $this->input->post('time_r_hour').':'.$this->input->post('time_r_min').' '.$this->input->post('time_r_period'),
                'time_controlled' => $this->input->post('time_c_hour').':'.$this->input->post('time_c_min').' '.$this->input->post('time_c_period'),
                'water_used' => $this->input->post('water_used'),
                'unit' => $this->input->post('unit'),
                'status' => $this->input->post('status')
            );

            if ($this->form_validation->run() == false){
                $this->session->set_flashdata('alert_type', 'danger');
                $this->session->set_flashdata('message', 'Error saving. Please input required field.');
            }
            else {
                //check for duplicate
                if ($this->main_model->check_fire_data($fire) == true){
                    $this->session->set_flashdata('alert_type', 'danger');
                    $this->session->set_flashdata('message', 'Possible duplicate fire data already exist!');
                }
                else {
                    $fireId = $this->main_model->add_fire_data($fire);
                    $this->session->set_flashdata('alert_type', 'success');
                    $this->session->set_flashdata('message', '10-70 Successfully Saved ! '.$fire['date_of_fire'].'@'.$fire['location']);
                    redirect("/main/fire_data", 'refresh');
                }
            }
        }
        $data['information'] = $this->main_model->get_fire_list();
        $data['summary'] = $this->main_model->get_fire_summary();
        $this->load->view('fire_log', $data);
    }

}
