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

        $this->schedule_version = $this->duty_model->get_active_duty_version(); //change this everytime new schedule is added
    }

    public function schedule()
    {
        $schedule = ($this->input->post('schedule'));
        $unit_number= ($this->input->post('unit'));
        $data = '';
        $sched_data = array();
        $data['select_schedule'] = 'Monday';
        $data['selected_version'] = '';
        $data['select_duty_version'] = '';
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
                $this->form_validation->set_rules('select_duty_version', 'select_duty_version', 'required');
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
                            'version' => $this->input->post('select_duty_version')
                        );
                        $this->duty_model->add_schedule($sched_data);
                        $this->session->set_flashdata('alert_type', 'success');
                        $this->session->set_flashdata('message', 'Schedule saved! '.$sched_data['schedule'].' - '.$sched_data['unit']);
                        //redirect("/duty/schedule", 'refresh');
                    }
                    else {
                        $this->session->set_flashdata('alert_type', 'danger');
                        $this->session->set_flashdata('message', 'Unit does not exist.');
                    }
                }


                $data['select_duty_version'] = $this->input->post('select_duty_version');
                $data['select_schedule'] = $this->input->post('schedule');
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
                        $_POST['time_r_hour'] = '';
                        $_POST['time_r_min'] = '';
                        $_POST['time_r_period'] = 'PM';
                        $this->session->set_flashdata('alert_type', 'success');
                        $this->session->set_flashdata('message', 'Successfully Saved ! ------> '.date('M d, Y - l ',strtotime($duty['attendance_date'])).' ('.$duty['time_in'].' TO '.$duty['time_out'].') of '.$duty['unit']);
                    }
                }

            }
            $data['duties'] = $this->duty_model->get_duties_details($this->input->post('date_of_duty'));
            $data['current_date'] = $this->input->post('date_of_duty');
            $data['selected_version'] = $this->input->post('duty_version');
        }

        $this->load->view('duty_attendance', $data);
    }

    function review() {
        $data['current_date'] = date('Y-\WW') ;
        $data['selected_week'] = $selected_week = date('W');
        $year = date('Y');

        if ($_POST){
            if (isset($_POST['prev'])) {
                $prev = strtotime($_POST['date_of_duty'] .' -1 week');
                $data['current_date'] = date('Y-\WW', $prev);
                $data['selected_week'] = $selected_week = date('W', $prev);
                $year = date('Y', $prev);
            }
            elseif (isset($_POST['next'])) {
                $next = strtotime($_POST['date_of_duty'] .' +1 week');
                $data['current_date'] = date('Y-\WW', $next);
                $data['selected_week'] = $selected_week = date('W', $next);
                $year = date('Y', $next);
            }
            else {
                if ($_POST['date_of_duty'] != '') {
                    $ddate = new DateTime($_POST['date_of_duty']);
                    $data['selected_week'] = $selected_week = $ddate->format("W");
                    $data['current_date'] = $_POST['date_of_duty'];
                    $year = date('Y', strtotime($_POST['date_of_duty']));
                }
            }
        }

        //print $year."-W".$selected_week;
        $dt = new DateTime(strtotime($selected_week));
        // create DateTime object with current time
        $dt->setISODate($year,$selected_week);
        $periods = new DatePeriod($dt, new DateInterval('P1D'), 6);
        $days = iterator_to_array($periods);

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

    function unit_review($s_unit='', $from='', $to='') {
        $this->load->model('personnel_model');

        $data['selected_from'] = date('Y-m-01');
        $data['selected_to'] = date('Y-m-d');
        $data['selected_unit'] = '';
        $data['unit_list'] = $this->personnel_model->get_user_list();
        $data['duty_list'] = [];
        $data['required_duty'] = 0;

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
                $data['required_duty'] = $this->duty_model->get_required_duties($duty, $this->schedule_version);
            }
        }
        else {
            if ($s_unit != '')
            {
                $duty = array(
                    'select_from' => $from,
                    'select_to' => $to,
                    'unit' => strtoupper($s_unit)
                );
                $data['duty_list'] = $this->duty_model->get_unit_duties($duty);
                $data['selected_unit'] =  $s_unit;
                $data['selected_from'] = $from;
                $data['selected_to'] = $to;

                $data['required_duty'] = $this->duty_model->get_required_duties($duty, $this->schedule_version);
            }
        }

        $this->load->view('duty_unit_review', $data);
    }

    public function duty_unit_edit($unit, $attendance_date, $schedule, $version, $selected_unit, $selected_from, $selected_to)
    {
        $data = [];
        $data['duty_version_list'] = $this->duty_model->get_duty_versions();
        $data['current_date'] = $attendance_date;
        $data['selected_version'] = $version;
        $data['selected_unit'] = $unit;
        $res = $this->duty_model->get_duty_unit_detail($unit, $attendance_date, $schedule, $version);

        $time_r = explode(" ",$res[0]->time_in);
        $time_r_hour = explode(":", $time_r[0] );
        $data['time_r_hour'] = $time_r_hour[0];
        $data['time_r_min'] = $time_r_hour[1];
        $data['selected_in_ampm'] = $time_r[1];

        $time_c = explode(" ",$res[0]->time_out);
        $time_c_hour = explode(":", $time_c[0] );
        $data['time_c_hour'] = $time_c_hour[0];
        $data['time_c_min'] = $time_c_hour[1];
        $data['selected_out_ampm'] = $time_c[1];

        $data['original_data'] = $res;
        $data['original_selected_unit'] = $selected_unit;
        $data['original_selected_from'] = $selected_from;
        $data['original_selected_to'] = $selected_to;
        $this->load->view('duty_unit_edit', $data);
    }

    public function update_duty_attendance()
    {
        if ($_POST)
        {
            $this->form_validation->set_rules('date_of_duty', 'date_of_duty', 'required');
            $this->form_validation->set_rules('time_r_hour', 'time_r_hour', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_r_min', 'time_r_min', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_r_period', 'time_r_period', 'required');
            $this->form_validation->set_rules('time_c_hour', 'time_c_hour', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_c_min', 'time_c_min', 'required|numeric|min_length[2]|max_length[2]');
            $this->form_validation->set_rules('time_c_period', 'time_c_period', 'required');
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
                $this->session->set_flashdata('message', 'Error updating. Please input required field.');
            }
            else {
                $dutyRes = $this->duty_model->update_duty_attendance($duty, $_POST['hidden_ids']);
                $this->session->set_flashdata('alert_type', 'success');
                $this->session->set_flashdata('message', 'Successfully Updated ! '.$duty['attendance_date'].' ('.$duty['time_in'].' TO '.$duty['time_out'].') of '.$duty['unit']);

                redirect("/duty/unit_review/".$_POST['hidden_selected'][0].'/'.$_POST['hidden_selected'][1].'/'.$_POST['hidden_selected'][2]);
            }
        }
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

    function get_tardiness()
    {
        $data = [];

        if ($_POST){
            $this->load->model('duty_model');

            $selected_from = date('Y-01-01');
            $d = new DateTime( date('Y-m-d') );
            $selected_to = $d->format( 'Y-m-t' );
            $unit = $this->input->post('unit');

            $tardiness = $this->duty_model->get_graph_tardiness($unit, $selected_from, $selected_to);
            array_push($data,['Months','Number of Tardiness']);

            foreach($tardiness as $key => $value){
                array_push($data,[$value->month,(int)$value->tardiness_count]);
            }

            echo json_encode($data);
            exit();
        }
        echo json_encode($data);
        exit();
    }
}
