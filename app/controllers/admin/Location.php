<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class location extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_location');
        set_time_zone();
    }
    //show home page
    public function index() {
        $join = array(
            array(
                "table" => "app_city",
                "condition" => "app_city.city_id=app_location.loc_city_id",
                "jointype" => "LEFT")
        );
        $cond = '';
        $event = $this->model_location->getData('', '*', $cond, $join);
        $data['loc_data'] = $event;
        $data['title'] = translate('manage') . " " . translate('location');
        $this->load->view('admin/master/location/index', $data);
    }

    //show add event form
    public function add_location() {
        $data['title'] = translate('add') . " " . translate('location');
        $city = $this->model_location->getData("app_city", "*", 'city_status = "A"');
        $data['city_list'] = $city;
        $this->load->view('admin/master/location/add_update', $data);
    }

    //show edit event form
    public function update_location($id) {
        $cond = 'loc_id=' . $id;
        if ($this->session->userdata('Type_Admin') != "A") {
            $cond .= 'AND loc_created_by=' . $this->login_id;
        }
        $event = $this->model_location->getData("app_location", "*", $cond);
        if (isset($event[0]) && !empty($event[0])) {
            $data['loc_data'] = $event[0];
            $city = $this->model_location->getData("app_city", "*", 'city_status = "A"');
            $data['city_list'] = $city;
            $data['title'] = translate('update') . " " . translate('location');
            $this->load->view('admin/master/location/add_update', $data);
        } else {
            show_404();
        }
    }

    //add/edit an event
    public function save_location() {
        $loc_id = (int) $this->input->post('id', true);
        $this->form_validation->set_rules('loc_title', 'title', 'trim|required|is_unique[app_location.loc_title.loc_id.' . $loc_id . ']');
        $this->form_validation->set_rules('loc_city_id', '', 'required');
        $this->form_validation->set_rules('loc_status', '', 'required');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == false) {
            if ($loc_id > 0) {
                $this->update_location($loc_id);
            } else {
                $this->add_location();
            }
        } else {
            $data['loc_title'] = $this->input->post('loc_title', true);
            $data['loc_city_id'] = $this->input->post('loc_city_id', true);
            $data['loc_status'] = $this->input->post('loc_status', true);
            $data['loc_created_by'] = $this->login_id;

            if ($loc_id > 0) {
                $data['loc_updated_on'] = date("Y-m-d H:i:s");
                $this->model_location->update('app_location', $data, "loc_id=$loc_id");
                $this->session->set_flashdata('msg', translate('location_update'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $data['loc_created_on'] = date("Y-m-d H:i:s");
                $this->model_location->insert('app_location', $data);
                $this->session->set_flashdata('msg', translate('location_insert'));
                $this->session->set_flashdata('msg_class', 'success');
            }
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/location', 'redirect');
        }
    }

    //delete an event
    public function delete_location($id) {
        $event_data = $this->model_location->getData('app_event', 'id', "location='$id'");
        if (isset($event_data) && count($event_data) > 0) {
            $this->session->set_flashdata('msg', translate('event_location_exist'));
            $this->session->set_flashdata('msg_class', 'failure');
            echo 'false';
            exit(0);
        } else {
            $this->model_location->delete('app_location', "loc_id='$id' AND loc_created_by='$this->login_id'");
            $this->session->set_flashdata('msg', translate('location_delete'));
            $this->session->set_flashdata('msg_class', 'success');
            echo "true";
            exit;
        }
    }

    public function check_location_title() {
        $id = (int) $this->input->post('id', true);
        $title = $this->input->post('loc_title');

        if (isset($id) && $id > 0) {
            $this->db->where('loc_id!=',$id);
        }

        $this->db->where('loc_title', $title);
        $this->db->from('app_location');
        $check_title=$this->db->count_all_results();

        if (isset($check_title) && ($check_title) > 0) {
            echo "false";
            exit;
        } else {
            echo "true";
            exit;
        }
    }

}

?>