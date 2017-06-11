<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Duty extends CI_Controller {

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
        $this->load->model('duty_model');
        $this->load->library('form_validation');
    }

    public function schedule()
    {
        $schedule = ($this->input->post('schedule'));
        $unit_number= ($this->input->post('unit'));
        $data = '';
        $sched_data = array();
        $data['selected_version'] = '';
        $data['version'] = $this->duty_model->get_duty_versions();

        if ($_POST){
            //means that user is asking to display the duty schedule
            if (isset($_POST['duty_version'])) {
                $data['selected_version'] = $this->input->post('duty_version');

                $data['Monday'] = $this->duty_model->get_duty_schedule('Monday', $this->input->post('duty_version'));
                $data['Tuesday'] = $this->duty_model->get_duty_schedule('Tuesday', $this->input->post('duty_version'));
                $data['Wednesday'] = $this->duty_model->get_duty_schedule('Wednesday', $this->input->post('duty_version'));
                $data['Thursday'] = $this->duty_model->get_duty_schedule('Thursday', $this->input->post('duty_version'));
                $data['Friday'] = $this->duty_model->get_duty_schedule('Friday', $this->input->post('duty_version'));
                $data['Saturday'] = $this->duty_model->get_duty_schedule('Saturday', $this->input->post('duty_version'));
                $data['Sunday'] = $this->duty_model->get_duty_schedule('Sunday', $this->input->post('duty_version'));

            }
            else{
                $this->form_validation->set_rules('schedule', 'schedule', 'required');
                $this->form_validation->set_rules('unit', 'unit', 'required');

                if ($this->form_validation->run() == false){
                    $this->session->set_flashdata('alert_type', 'danger');
                    $this->session->set_flashdata('message', 'Error saving. Please input required field.');
                }
                else {
                    if ($this->duty_model->duty_personnel($unit_number) == true){
                        $sched_data = array(
                            'schedule' => $schedule,
                            'unit' => $unit_number,
                            'version' => 1 //Todo
                        );
                        $this->duty_model->add_schedule($sched_data);
                        $this->session->set_flashdata('alert_type', 'success');
                        $this->session->set_flashdata('message', 'Schedule saved!');
                        redirect("/duty/schedule", 'refresh');
                    }
                    else {
                        $this->session->set_flashdata('alert_type', 'danger');
                        $this->session->set_flashdata('message', 'Unit does not exist.');
                    }
                }
            }

        }


        $this->load->view('duty_schedule', $data);
    }

    public function delete_schedule($unit, $schedule, $version)
    {
        $this->db->delete('duty_schedule', array('unit' => $unit, 'schedule' => $schedule, 'version' => $version));
        redirect("/duty/schedule", 'refresh');
    }

    function attendance() {
        $data['selected_title'] = '';
        $data['current_date'] = date('Y-m-d');
        $data['selected_version'] = '';
        $data['duty_version'] = $this->duty_model->get_duty_versions();

        if ($_POST) {
            $this->form_validation->set_rules('date_of_duty', 'date_of_duty', 'required');
            $this->form_validation->set_rules('time_r_hour', 'time_r_hour', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_r_min', 'time_r_min', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_r_period', 'time_r_period', 'required');
            $this->form_validation->set_rules('time_c_hour', 'time_c_hour', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_c_min', 'time_c_min', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_c_period', 'time_c_period', 'required');
            $this->form_validation->set_rules('unit', 'unit', 'required');
            $this->form_validation->set_rules('duty_version', 'duty_version', 'required');

            $duty = array(
                'attendance_date' => $this->input->post('date_of_duty'),
                'time_in' => $this->input->post('time_r_hour').':'.$this->input->post('time_r_min').' '.$this->input->post('time_r_period'),
                'time_out' => $this->input->post('time_c_hour').':'.$this->input->post('time_c_min').' '.$this->input->post('time_c_period'),
                'schedule' => date('l',strtotime($this->input->post('date_of_duty'))),
                'unit' => strtoupper($this->input->post('unit')),
                'duty_version' => $this->input->post('duty_version')
            );

            if ($this->form_validation->run() == false){
                $this->session->set_flashdata('alert_type', 'danger');
                $this->session->set_flashdata('message', 'Error saving. Please input required field.');
            }
            else {
                if ($this->duty_model->check_unit_exist($duty['unit']) == false){
                    $this->session->set_flashdata('alert_type', 'danger');
                    $this->session->set_flashdata('message', 'Error! Unit does not exist on the database. Please check again.');
                }
                else {
                    //check for duplicate
                    if ($this->duty_model->check_duty_data($duty) == true){
                        $this->session->set_flashdata('alert_type', 'danger');
                        $this->session->set_flashdata('message', 'You already have logged same date as before! Please check date and unit no.');
                    }
                    else {
                        $dutyRes = $this->duty_model->add_duty_attendance($duty);
                        $this->session->set_flashdata('alert_type', 'success');
                        $this->session->set_flashdata('message', 'Successfully Saved ! '.$duty['attendance_date'].' ('.$duty['time_in'].' TO '.$duty['time_out'].') of '.$duty['unit']);
                    }
                }

            }

            $data['current_date'] = $this->input->post('date_of_duty');
            $data['selected_version'] = $this->input->post('duty_version');
        }
        $this->load->view('duty_attendance', $data);
    }

    function review() {
        $data['current_date'] = date('Y-m-d');
        $data['selected_week'] = $selected_week = date('W');

        if ($_POST){
            if ($_POST['date_of_duty'] != '') { $data['selected_week'] = $selected_week = date('W',strtotime($_POST['date_of_duty'])); }
            if (isset($_POST['prev'])) $data['selected_week'] = $selected_week = $_POST['prev'];
            if (isset($_POST['next'])) $data['selected_week'] = $selected_week = $_POST['next'];
        }

        $dt = new DateTime(strtotime($selected_week));
        // create DateTime object with current time
        $dt->setISODate($dt->format('o'),$selected_week);
        $periods = new DatePeriod($dt, new DateInterval('P1D'), 6);
        $days = iterator_to_array($periods);
        // convert DatePeriod object to array

        $data['Monday'] = $this->duty_model->get_duties($days[0]->format('Y-m-d'));
        $data['Tuesday'] = $this->duty_model->get_duties($days[1]->format('Y-m-d'));
        $data['Wednesday'] = $this->duty_model->get_duties($days[2]->format('Y-m-d'));
        $data['Thursday'] = $this->duty_model->get_duties($days[3]->format('Y-m-d'));
        $data['Friday'] = $this->duty_model->get_duties($days[4]->format('Y-m-d'));
        $data['Saturday'] = $this->duty_model->get_duties($days[5]->format('Y-m-d'));
        $data['Sunday'] = $this->duty_model->get_duties($days[6]->format('Y-m-d'));
        $data['total_duties_for_the_week'] = $this->duty_model->get_total_duties_week($days[0]->format('Y-m-d'), $days[6]->format('Y-m-d'));
        $data['personel_duties_for_the_week'] = $this->duty_model->get_personel_duties_week($days[0]->format('Y-m-d'), $days[6]->format('Y-m-d'));
        $data['days'] = $days;

        $this->load->view('duty_review', $data);
    }

    function unit_review() {
        $this->load->model('personnel_model');

        $data['selected_from'] = date('Y-m-d');
        $data['selected_to'] = date('Y-m-d');
        $data['selected_unit'] = '';
        $data['unit_list'] = $this->personnel_model->get_user_list();
        $data['duty_list'] = [];

        if ($_POST){
            $this->form_validation->set_rules('unit', 'unit', 'required');
            $this->form_validation->set_rules('select_from', 'select_from', 'required');
            $this->form_validation->set_rules('select_to', 'select_to', 'required');

            $data['selected_unit'] =  $this->input->post('unit');
            $data['selected_from'] = $this->input->post('select_from');
            $data['selected_to'] = $this->input->post('select_to');

            if ($this->form_validation->run() == false){
                $this->session->set_flashdata('alert_type', 'danger');
                $this->session->set_flashdata('message', 'Please input required search field.');
            }
            else {
                $duty = array(
                    'select_from' => $this->input->post('select_from'),
                    'select_to' => $this->input->post('select_to'),
                    'unit' => strtoupper($this->input->post('unit'))
                );
                $data['duty_list'] = $this->duty_model->get_unit_duties($duty);
            }
        }

        $this->load->view('duty_unit_review', $data);
    }

    public function save()
    {

    }

    public function delete_duty($unit, $date, $schedule, $duty_version)
    {
        $this->db->delete('duty_attendance', array('unit' => $unit, 'attendance_date' => $date, 'schedule' => $schedule, 'duty_version' => $duty_version));
        $this->session->set_flashdata('alert_type', 'success');
        $this->session->set_flashdata('message', 'Successfully deleted : '.$unit.' on '.$schedule.' '.$date);
        redirect("/duty/review", 'refresh');
    }
    public function delete_unit_duty($unit, $date, $schedule, $duty_version)
    {
        $this->db->delete('duty_attendance', array('unit' => $unit, 'attendance_date' => $date, 'schedule' => $schedule, 'duty_version' => $duty_version));
        $this->session->set_flashdata('alert_type', 'success');
        $this->session->set_flashdata('message', 'Successfully deleted : '.$unit.' on '.$schedule.' '.$date);
        redirect("/duty/unit_review", 'refresh');
    }
}
