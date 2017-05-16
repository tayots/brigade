<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fire extends CI_Controller {

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
        $this->load->model('fire_model');
        $this->load->library('form_validation');
    }


    function data() {
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
            $this->form_validation->set_rules('dispatch', 'dispatch', 'required');
            $this->form_validation->set_rules('unit', 'unit', 'required');
            $this->form_validation->set_rules('oic', 'oic', 'required');

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
                'dispatch' => $this->input->post('dispatch'),
                'oic' => $this->input->post('oic'),
                'proceeding' => $this->input->post('proceeding'),
                'at_base' => $this->input->post('at_base')
            );

            if ($this->form_validation->run() == false){
                $this->session->set_flashdata('alert_type', 'danger');
                $this->session->set_flashdata('message', 'Error saving. Please input required field.');
            }
            else {
                //check for duplicate
                if ($this->fire_model->check_fire_data($fire) == true){
                    $this->session->set_flashdata('alert_type', 'danger');
                    $this->session->set_flashdata('message', 'Possible duplicate fire data already exist!');
                }
                else {
                    $fireId = $this->fire_model->add_fire_data($fire);
                    // fire apparata data
                    $this->save_fire_apparata($fireId, $_POST);

                    $this->session->set_flashdata('alert_type', 'success');
                    $this->session->set_flashdata('message', '10-70 Successfully Saved ! '.$fire['date_of_fire'].'@'.$fire['location']);
                    redirect("/fire/data", 'refresh');
                }
            }
        }

        $this->load->view('fire_data', $data);
    }

    private function save_fire_apparata($id, $post)
    {
        $data = [];
        for($x=0; $x<count($post['engine']); $x++)
        {
            $data[$x] = array(
                'fire_data_id' => $id,
                'engine' => strtoupper($post['engine'][$x]),
                'time_out' => $post['time_out'][$x],
                'fto_out' => strtoupper($post['fto_out'][$x]),
                'time_in' => $post['time_in'][$x],
                'fto_in' => strtoupper($post['fto_in'][$x]),
                'onboard' => $post['onboard'][$x]
            );
        }

        return $this->fire_model->add_apparata_responded($data);
    }

    public function attendance()
	{
        $data['selected_title'] = '';
        $data['current_date'] = date('Y-m-d');
        $attendance_date = date('Y-m-d');

        if ($_POST) {
            // loading of fire location
            if ($_POST['date_of_fire'] != '') {
                $data['fire_list'] = $this->fire_model->get_fire_list($this->input->post('date_of_fire'));
            }

            //validation
            $this->form_validation->set_rules('date_of_fire', 'date_of_fire', 'required');
            $this->form_validation->set_rules('location', 'location', 'required');
            $this->form_validation->set_rules('unit', 'unit', 'required');

            if ($this->form_validation->run() == false){
                $this->session->set_flashdata('alert_type', 'danger');
                $this->session->set_flashdata('message', 'Error saving. Please input required field.');
            }
            else {
                //check if unit exist
                if ($this->fire_model->check_unit_exist($this->input->post('unit')) == false) {
                    $this->session->set_flashdata('alert_type', 'danger');
                    $this->session->set_flashdata('message', "Error! Unit does not exist on database. Please check again." );
                }
                else {
                    $personnel = array(
                        'unit' => strtoupper($this->input->post('unit')),
                        'fire_data_id' => $this->input->post('location'),
                        'attendance_date' => $this->input->post('date_of_fire'),
                        'status' => 'active'
                    );

                    if ($this->fire_model->check_fire_attendance($personnel) == true){
                        $this->session->set_flashdata('alert_type', 'danger');
                        $this->session->set_flashdata('message', "User already existed on said event. Please check ------>  ".$personnel['unit']." on ".$personnel['attendance_date']);
                    }
                    else{
                        $personnelId = $this->fire_model->add_fire_attendance($personnel);
                        $this->session->set_flashdata('alert_type', 'success');
                        $this->session->set_flashdata('message', "Successfully added: ".$personnel['unit']." on ".$personnel['attendance_date']);
                    }
                }

            }
            $attendance_date = $this->input->post('date_of_fire');
            $data['current_date'] = $attendance_date;
            $data['selected_title'] = $this->input->post('location');
        }
        else {
            $data['fire_list'] = $this->fire_model->get_fire_list($data['current_date']);
        }

        $data['information'] = $this->fire_model->get_fire_attendance($attendance_date);
		$this->load->view('fire_attendance', $data);
	}

	public function review_attendance()
	{
        $data['selected_title'] = '';
        $data['current_date'] = date('Y-m-d');
        $data['fire_apparata'] = '';

        if ($_POST) {
            // loading of fire location
            if ($_POST['date_of_fire'] != '') {
                $data['fire_list'] = $this->fire_model->get_fire_list($this->input->post('date_of_fire'));
            }

            //validation
            $this->form_validation->set_rules('date_of_fire', 'date_of_fire', 'required');
            $this->form_validation->set_rules('location', 'location', 'required');

            if ($this->form_validation->run() != false){
                $data['information'] = $this->fire_model->get_fire_attendance($this->input->post('date_of_fire'), $this->input->post('location'));
                $data['fire_apparata'] = $this->fire_model->get_fire_apparata($this->input->post('location'));
                $data['fire_data'] = $this->fire_model->get_fire_data($this->input->post('location'));
            }

            $data['selected_title'] = $this->input->post('location');
        }
        else {
            $data['fire_list'] = $this->fire_model->get_fire_list($data['current_date']);
        }

		$this->load->view('fire_review_attendance', $data);
	}

    public function fire_details($location_id)
	{
        $data['information'] = $this->fire_model->get_fire_attendance_location($location_id);
        $data['fire_apparata'] = $this->fire_model->get_fire_apparata($location_id);
        $data['fire_data'] = $this->fire_model->get_fire_data($location_id);
        $this->load->view('fire_details', $data);
	}

    public function lists()
    {
        $data['selected_title'] = '';
        $data['from_date'] = date('Y-m-01');
        $d = new DateTime( date('Y-m-d') );
        $data['to_date'] = $d->format( 'Y-m-t' );
        $data['fire_list'] = [];

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
                $data['fire_list'] = $this->fire_model->get_fire_list_range($data['from_date'], $data['to_date']);
            }
        }
        else {
            $data['fire_list'] = $this->fire_model->get_fire_list_range($data['from_date'], $data['to_date']);
        }

        $this->load->view('fire_lists', $data);
    }


	public function delete_attendance($id, $attendance_date, $goback)
	{
        $this->db->delete('fire_attendance', array('id' => $id));
        $data['message'] = 'Successfully deleted '.$id;
        $data['information'] = $this->fire_model->get_fire_attendance($attendance_date);
        $this->session->set_flashdata('alert_type', 'success');
        $this->session->set_flashdata('message', "Successfully deleted unit");
        redirect("/fire/attendance", 'refresh');
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
            if ($this->fire_model->check_attendance($unit_number,$category, $attendance_date, $title) == true){
                $data['alert_type'] =  "danger";
                $data['message'] =  "User already exist! Must not be duplicate. ------>  $unit_number on $attendance_date";;
            }
            else{
                $personnelId = $this->fire_model->addattendance($personnel);
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

        $data['information'] = $this->fire_model->userInformation($attendance_date);
        $this->load->view('main_add', $data);
    }


}
