<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class city extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('model_city');
        set_time_zone();
        if (isset($this->login_type) && $this->login_type == 'V') {
            $app_vendor_setting_data = app_vendor_setting();
            if (isset($app_vendor_setting_data['allow_city_location']) && $app_vendor_setting_data['allow_city_location'] == 'N'):
                redirect('vendor/dashboard');
            endif;
        }
    }

    //show home page
    public function index() {
        $event = $this->model_city->getData('', '*');
        $data['city_data'] = $event;
        $data['title'] = translate('manage') . " " . translate('city');
        $this->load->view('admin/master/city-list', $data);
    }

    //show add event form
    public function add_city() {
        $data['title'] = translate('add') . " " . translate('city');
        $this->load->view('admin/master/manage-city', $data);
    }

    //show edit event form
    public function update_city($id) {
        $cond = 'city_id=' . $id;
        if ($this->session->userdata('Type_Admin') != "A") {
            $cond .= 'AND city_created_by=' . $this->login_id;
        }
        $event = $this->model_city->getData("app_city", "*", $cond);
        if (isset($event[0]) && !empty($event[0])) {
            $data['city_data'] = $event[0];
            $data['title'] = translate('update') . " " . translate('city');
            $this->load->view('admin/master/manage-city', $data);
        } else {
            show_404();
        }
    }

    public function default_city($id) {
        $this->db->query("UPDATE app_city SET is_default=0 WHERE 1");
        //update status
        $data['is_default'] = 1;
        $this->model_city->update('app_city', $data, "city_id=$id");
    }

    //add/edit an event
    public function save_city() {
        $city_id = (int) $this->input->post('id', true);
        $this->form_validation->set_rules('city_title', 'title', 'trim|required|is_unique[app_city.city_title.city_id.' . $city_id . ']');
        $this->form_validation->set_rules('city_status', '', 'required');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == false) {
            if ($city_id > 0) {
                $this->update_city();
            } else {
                $this->add_city();
            }
        } else {
            $data['city_title'] = $this->input->post('city_title', true);
            $data['city_status'] = $this->input->post('city_status', true);
            $data['city_created_by'] = $this->login_id;

            if ($city_id > 0) {
                $data['city_updated_on'] = date("Y-m-d H:i:s");
                $this->model_city->update('app_city', $data, "city_id=$city_id");
                $this->session->set_flashdata('msg', translate('city_update'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $data['city_created_on'] = date("Y-m-d H:i:s");
                $this->model_city->insert('app_city', $data);
                $this->session->set_flashdata('msg', translate('city_insert'));
                $this->session->set_flashdata('msg_class', 'success');
            }
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/manage-city', 'redirect');
        }
    }

    //delete an event
    public function delete_city($id) {
        $event_data = $this->model_city->getData('app_event', 'id', "city='$id'");
        if (isset($event_data) && count($event_data) > 0) {
            $this->session->set_flashdata('msg', translate('event_city_exist'));
            $this->session->set_flashdata('msg_class', 'failure');
            echo 'false';
            exit(0);
        } else {
            $this->model_city->delete('app_city', "city_id='$id' AND city_created_by='$this->login_id'");
            $this->session->set_flashdata('msg', translate('city_delete'));
            $this->session->set_flashdata('msg_class', 'success');
            echo "true";
            exit;
        }
    }

    public function check_city_title() {
        $id = (int) $this->input->post('id', true);
        $title = trim($this->input->post('city_title'));

        if (isset($id) && $id > 0) {
            $this->db->where('city_id!=',$id);
        }

        $this->db->where('city_title', $title);
        $this->db->from('app_city');
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