<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Personnel extends CI_Controller {

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
        $this->load->model('personnel_model');
        $this->load->library('form_validation');
    }

    public function delete($id)
    {
        $this->db->delete('personnel', array('id' => $id));
        $data['message'] = 'Successfully deleted Personnel '.$id;
        redirect("/personnel", 'refresh');
    }

    public function status($id, $status)
    {
        $data['status'] = $status;
        $this->db->where('id',$id);
        $this->db->update('personnel', $data);
        $data['message'] = 'Successfully set to '.$status.': '.$id;
        redirect("/personnel", 'refresh');
    }

    public function index()
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
                if ($this->personnel_model->check_personnel($unit_number) == true){
                    $data['alert_type'] =  "danger";
                    $data['message'] =  "User already exist! Must not be duplicate. ------> $first_name $last_name #$unit_number";
                }
                else{
                    if ($this->personnel_model->save_personnel($personnel)) {
                        $data['message'] =  "Personnel added! ------>  $first_name $last_name #$unit_number";
                    }
                }
            }
            else {
                $data['alert_type'] =  "danger";
                $data['message'] = 'Error adding personnel. Please check required field.';
            }
        }


        $data['information'] = $this->personnel_model->get_user_list();
        $this->load->view('personnel', $data);
    }

}
