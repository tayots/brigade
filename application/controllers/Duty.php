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

        if ($_POST){
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
                        'unit' => $unit_number
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

        $data['Monday'] = $this->duty_model->get_duty_schedule('Monday');
        $data['Tuesday'] = $this->duty_model->get_duty_schedule('Tuesday');
        $data['Wednesday'] = $this->duty_model->get_duty_schedule('Wednesday');
        $data['Thursday'] = $this->duty_model->get_duty_schedule('Thursday');
        $data['Friday'] = $this->duty_model->get_duty_schedule('Friday');
        $data['Saturday'] = $this->duty_model->get_duty_schedule('Saturday');
        $data['Sunday'] = $this->duty_model->get_duty_schedule('Sunday');
        $this->load->view('duty_schedule', $data);
    }

    public function delete_schedule($unit, $schedule)
    {
        $this->db->delete('duty_schedule', array('unit' => $unit, 'schedule' => $schedule));
        redirect("/duty/schedule", 'refresh');
    }

    function attendance() {
        $data['selected_title'] = '';
        $data['current_date'] = date('Y-m-d');
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


        $data['Monday'] = $this->duty_model->get_duties('Monday', $selected_week);
        $data['Tuesday'] = $this->duty_model->get_duties('Tuesday', $selected_week);
        $data['Wednesday'] = $this->duty_model->get_duties('Wednesday', $selected_week);
        $data['Thursday'] = $this->duty_model->get_duties('Thursday', $selected_week);
        $data['Friday'] = $this->duty_model->get_duties('Friday', $selected_week);
        $data['Saturday'] = $this->duty_model->get_duties('Saturday', $selected_week);
        $data['Sunday'] = $this->duty_model->get_duties('Sunday', $selected_week);
        $data['total_duties_for_the_week'] = $this->duty_model->get_total_duties_week($selected_week);
        $data['personel_duties_for_the_week'] = $this->duty_model->get_personel_duties_week($selected_week);

        $this->load->view('duty_review', $data);
    }

    public function save()
    {
        $data = [];
        if ($_POST) {
            $this->form_validation->set_rules('date_of_duty', 'date_of_duty', 'required');
            $this->form_validation->set_rules('time_r_hour', 'time_r_hour', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_r_min', 'time_r_min', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_r_period', 'time_r_period', 'required');
            $this->form_validation->set_rules('time_c_hour', 'time_c_hour', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_c_min', 'time_c_min', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_c_period', 'time_c_period', 'required');
            $this->form_validation->set_rules('unit', 'unit', 'required');

            $duty = array(
                'attendance_date' => $this->input->post('date_of_duty'),
                'time_in' => $this->input->post('time_r_hour').':'.$this->input->post('time_r_min').' '.$this->input->post('time_r_period'),
                'time_out' => $this->input->post('time_c_hour').':'.$this->input->post('time_c_min').' '.$this->input->post('time_c_period'),
                'schedule' => date('l',strtotime($this->input->post('date_of_duty'))),
                'unit' => $this->input->post('unit')
            );

            if ($this->form_validation->run() == false){
                $this->session->set_flashdata('alert_type', 'danger');
                $this->session->set_flashdata('message', 'Error saving. Please input required field.');
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
                    redirect("/duty/attendance", 'refresh');
                }
            }

            $data['current_date'] = $this->input->post('date_of_fire');
        }
        $this->load->view('duty_attendance', $data);
    }

    public function delete_duty($unit, $date, $schedule)
    {
        $this->db->delete('duty_attendance', array('unit' => $unit, 'attendance_date' => $date, 'schedule' => $schedule));
        $this->session->set_flashdata('alert_type', 'success');
        $this->session->set_flashdata('message', 'Successfully deleted : '.$unit.' on '.$schedule.' '.$date);
        redirect("/duty/review", 'refresh');
    }
}
