<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meeting extends CI_Controller {

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
        $this->load->model('meeting_model');
        $this->load->library('form_validation');
    }

    public function attendance()
    {
        $data['current_date'] = date('Y-m-d');

        if ($_POST) {
            $this->form_validation->set_rules('date_of_meeting', 'date_of_meeting', 'required');
            $this->form_validation->set_rules('oic', 'oic', 'required|numeric|min_length[2]|max_length[3]');
            $this->form_validation->set_rules('recorder', 'recorder', 'required|numeric|min_length[2]|max_length[3]');
            $this->form_validation->set_rules('remarks', 'remarks', 'required');

            if ($this->form_validation->run() == false){
                $this->session->set_flashdata('alert_type', 'danger');
                $this->session->set_flashdata('message', 'Error saving. Please input all required fields.');
            }
            else {
                //custom checking
                //Process Members attended
                $data = array(
                    'date_of_meeting' => $this->input->post('date_of_meeting'),
                    'activity' => 'GM MEETING',
                    'venue' => 'BRAVO',
                    'oic' => $this->input->post('oic'),
                    'recorder' => $this->input->post('recorder'),
                    'remarks' => $this->input->post('remarks'),
                );

                $flag_exist = true;
                if ($_POST['member_counter'] > 0) {
                    for ($x=0; $x<$_POST['member_counter']; $x++){
                        if ($this->meeting_model->check_unit_exist($_POST['member'.$x]) == false){
                            $mem = $_POST['member'.$x];
                            $flag_exist = false;
                            break;
                        }
                    }

                    if ($flag_exist == false){
                        $this->session->set_flashdata('alert_type', 'danger');
                        $this->session->set_flashdata('message', 'Error members added doesn\'t exist. Unit:'.$mem);
                    }
                    else {
                        //check OIC
                        if ($this->meeting_model->check_unit_exist($data['oic']) == false){
                            $this->session->set_flashdata('alert_type', 'danger');
                            $this->session->set_flashdata('message', 'Error. Chief Officer not found. Please check unit number.');
                        }
                        else {
                            if ($this->meeting_model->check_unit_exist($data['recorder']) == false){
                                $this->session->set_flashdata('alert_type', 'danger');
                                $this->session->set_flashdata('message', 'Error. Recorder not found. Please check unit number.');
                            }
                            else {
                                //save meeting activity
                                if ($this->meeting_model->check_meeting_exist($data) == false){
                                    $meeting_id = $this->meeting_model->save_meeting($data);
                                    //save members
                                    $this->meeting_model->save_members($meeting_id, $_POST);

                                    $this->session->set_flashdata('alert_type', 'success');
                                    $this->session->set_flashdata('message', 'Successfully Saved Activity on '.$this->input->post('date_of_meeting').' with Total members attended: '.$this->input->post('member_counter'));
                                    redirect('meeting/attendance', 'refresh');
                                }
                                else {
                                    $this->session->set_flashdata('alert_type', 'danger');
                                    $this->session->set_flashdata('message', 'Error. meeting already exist on db. Please check your data.');
                                }
                            }
                        }
                    }
                }
                else {
                    $this->session->set_flashdata('alert_type', 'danger');
                    $this->session->set_flashdata('message', 'Error saving. Please input members attended.');
                }
            }

            $data['current_date'] = $this->input->post('date_of_meeting');
        }

        $this->load->view('meeting_attendance', $data);
    }

    public function lists()
    {
        $data['selected_title'] = '';
        $data['from_date'] = date('Y-m-01');
        $d = new DateTime( date('Y-m-d') );
        $data['to_date'] = $d->format( 'Y-m-t' );
        $data['meeting_list'] = [];

        if ($_POST) {
            if (isset($_POST['prev'])) {
                $data['from_date'] = date('Y-m-01', strtotime($this->input->post('from_date')." -1 month"));
                $d = new DateTime( date('Y-m-d', strtotime($this->input->post('from_date')." -1 month")) );
                $data['to_date'] = $d->format( 'Y-m-t' );
            }
            else if (isset($_POST['next'])) {
                $data['from_date'] = date('Y-m-01', strtotime($this->input->post('from_date')." +1 month"));
                $d = new DateTime( date('Y-m-d', strtotime($this->input->post('from_date')." +1 month")) );
                $data['to_date'] = $d->format( 'Y-m-t' );
            }
            else {
                $data['from_date'] = $this->input->post('from_date');
                $data['to_date'] = $this->input->post('to_date');
            }

            $this->form_validation->set_rules('from_date', 'from_date', 'required');
            $this->form_validation->set_rules('to_date', 'to_date', 'required');

            if ($this->form_validation->run() != false){
                $data['meeting_list'] = $this->meeting_model->get_meeting_list_range($data['from_date'], $data['to_date']);
            }
        }
        else {
            $data['meeting_list'] = $this->meeting_model->get_meeting_list_range($data['from_date'], $data['to_date']);
        }

        $this->load->view('meeting_lists', $data);
    }

    public function meeting_details($meeting_id)
    {
        $data['meeting_data'] = $this->meeting_model->get_meeting_data($meeting_id);
        $data['meeting_attendance'] = $this->meeting_model->get_meeting_attendance($meeting_id);
        $this->load->view('meeting_details', $data);
    }

    public function approve($unit, $meeting_id) {
        if ($this->meeting_model->check_unit_exist($unit) == false){
            echo false;
        }
        else {
            $data = array('approved_by' => $unit);
            $this->meeting_model->update_meeting($data, $meeting_id);
            echo true;
        }
    }

    public function meeting_email() {
        if (isset($_POST['email']) && isset($_POST['meeting_id'])){
            $meeting_id = $_POST['meeting_id'];

            $data = $this->meeting_model->get_meeting_data($meeting_id);
            $members = $this->meeting_model->get_meeting_attendance($meeting_id);
            $mem = '';
            $total = 0;
            if ($data){
                foreach ($members as $key => $value2) {
                    $total += 1;
                    $mem .= $value2->unit.', ';
                }
                //Do Email here
                $to       = $_POST['email'];
                $subject  = '[CFCVFB Attendance Tracker] Review Meeting Attendance on '.$data[0]->date_of_meeting;
                $message  = 'Greetings!<br><br>';
                $message  .= 'You just received an email to review on Meeting Attendance last '.date('l, M d, Y',strtotime($data[0]->date_of_meeting));
                $message  .= '<br><br>Here are the details of the activity:';
                $message  .= '<br><br>Meeting Activity: '.$data[0]->activity;
                $message  .= '<br>Venue: '.$data[0]->venue;
                $message  .= '<br>OIC: '.$data[0]->oic;
                $message  .= '<br>Members Present: '.$mem.' (Total of '.$total.')';
                $message  .= '<br>Remarks: '.$data[0]->remarks;
                $message  .= '<br>Recorded by: '.$data[0]->recorder;
                $message  .= '<br><br>';
                $message  .= 'Thank you!';
                $message  .= '<br><br>';
                $message  .= '*** Please reply to cfcvfb888@gmail.com. ***';
                $headers  = 'From: cfcvfb888@gmail.com' . "\r\n" .
                    'MIME-Version: 1.0' . "\r\n" .
                    'Content-type: text/html; charset=utf-8';
                if(mail($to, $subject, $message, $headers)) {
                    //update Sent field to 1
                    $data = array('sent' => 1);
                    $this->meeting_model->update_meeting($data, $meeting_id);
                    echo true;
                } else {
                    echo false;
                }
            }
            else echo false;
        }
        else echo false;
    }

}
