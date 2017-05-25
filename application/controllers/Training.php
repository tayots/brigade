<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Training extends CI_Controller {

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
        $this->load->model('training_model');
        $this->load->library('form_validation');
    }

    public function attendance()
    {
        $data['current_date'] = date('Y-m-d');

        if ($_POST) {
            $this->form_validation->set_rules('date_of_training', 'date_of_training', 'required');
            $this->form_validation->set_rules('activity', 'activity', 'required');
            $this->form_validation->set_rules('venue', 'venue', 'required');
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
                    'date_of_training' => $this->input->post('date_of_training'),
                    'activity' => $this->input->post('activity'),
                    'venue' => $this->input->post('venue'),
                    'oic' => $this->input->post('oic'),
                    'recorder' => $this->input->post('recorder'),
                    'remarks' => $this->input->post('remarks'),
                );

                $flag_exist = true;
                if ($_POST['member_counter'] > 0) {
                    for ($x=0; $x<$_POST['member_counter']; $x++){
                        if ($this->training_model->check_unit_exist($_POST['member'.$x]) == false){
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
                        if ($this->training_model->check_unit_exist($data['oic']) == false){
                            $this->session->set_flashdata('alert_type', 'danger');
                            $this->session->set_flashdata('message', 'Error. Chief Officer not found. Please check unit number.');
                        }
                        else {
                            if ($this->training_model->check_unit_exist($data['recorder']) == false){
                                $this->session->set_flashdata('alert_type', 'danger');
                                $this->session->set_flashdata('message', 'Error. Recorder not found. Please check unit number.');
                            }
                            else {
                                //save training activity
                                if ($this->training_model->check_training_exist($data) == false){
                                    $training_id = $this->training_model->save_training($data);
                                    //save members
                                    $this->training_model->save_members($training_id, $_POST);

                                    $this->session->set_flashdata('alert_type', 'success');
                                    $this->session->set_flashdata('message', 'Successfully Saved Activity on '.$this->input->post('date_of_training').' with Total members attended: '.$this->input->post('member_counter'));
                                    redirect('training/attendance', 'refresh');
                                }
                                else {
                                    $this->session->set_flashdata('alert_type', 'danger');
                                    $this->session->set_flashdata('message', 'Error. Training already exist on db. Please check your data.');
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

            $data['current_date'] = $this->input->post('date_of_training');
        }

        $this->load->view('training_attendance', $data);
    }

    public function lists()
    {
        $data['selected_title'] = '';
        $data['from_date'] = date('Y-m-01');
        $d = new DateTime( date('Y-m-d') );
        $data['to_date'] = $d->format( 'Y-m-t' );
        $data['training_list'] = [];

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
                $data['training_list'] = $this->training_model->get_training_list_range($data['from_date'], $data['to_date']);
            }
        }
        else {
            $data['training_list'] = $this->training_model->get_training_list_range($data['from_date'], $data['to_date']);
        }

        $this->load->view('training_lists', $data);
    }

    public function training_details($training_id)
    {
        $data['training_data'] = $this->training_model->get_training_data($training_id);
        $data['training_attendance'] = $this->training_model->get_training_attendance($training_id);
        $this->load->view('training_details', $data);
    }

    public function approve($unit, $training_id) {
        if ($this->training_model->check_unit_exist($unit) == false){
            echo false;
        }
        else {
            $data = array('approved_by' => $unit);
            $this->training_model->update_training($data, $training_id);
            echo true;
        }
    }

    public function training_email() {
        if (isset($_POST['email']) && isset($_POST['training_id'])){
            $training_id = $_POST['training_id'];

            $data = $this->training_model->get_training_data($training_id);
            $members = $this->training_model->get_training_attendance($training_id);
            $mem = '';
            $total = 0;
            if ($data){
                foreach ($members as $key => $value2) {
                    $total += 1;
                    $mem .= $value2->unit.', ';
                }
                //Do Email here
                $to       = $_POST['email'];
                $subject  = '[CFCVFB Attendance Tracker] Review Training Attendance on '.$data[0]->date_of_training;
                $message  = 'Greetings!<br><br>';
                $message  .= 'You just received an email to review on Training Attendance last '.date('l, M d, Y',strtotime($data[0]->date_of_training));
                $message  .= '<br><br>Here are the details of the training:';
                $message  .= '<br><br>Training Activity: '.$data[0]->activity;
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
                    $this->training_model->update_training($data, $training_id);
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
