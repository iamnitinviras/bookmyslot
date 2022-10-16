<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Coupon extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_event');
        set_time_zone();
    }

    //show event page
    public function index() {
        $coupon_data = $this->model_event->getData('app_coupon', '*', "created_by=" . $this->login_id);
        $data['coupon_data'] = $coupon_data;
        $data['title'] = translate('manage') . " " . translate('coupon');
        $this->load->view('admin/service/coupon/index', $data);
    }

    //show add event form
    public function add_coupon() {
        $data['event_data'] = $this->model_event->getData('app_event', '*', "status='A' AND type='S' AND created_by=" . $this->login_id);
        $data['title'] = translate('add') . " " . translate('coupon');
        $this->load->view('admin/service/coupon/add_update', $data);
    }

    //show edit event form
    public function update_coupon($id) {
        $coupon = $this->model_event->getData("app_coupon", "*", "id='$id'");
        if (isset($coupon[0]) && !empty($coupon[0])) {
            $data['coupon_data'] = $coupon[0];
            $data['event_data'] = $this->model_event->getData('app_event', '*', "status='A' AND type='S' AND created_by=" . $this->login_id);
            $data['title'] = translate('update') . " " . translate('coupon');
            $this->load->view('admin/service/coupon/add_update', $data);
        } else {
            show_404();
        }
    }

    //add/edit an event
    public function save_coupon() {
        $id = (int) $this->input->post('id', true);
        $this->form_validation->set_rules('title', '', 'required');
        $this->form_validation->set_rules('valid_till', '', 'required');
        $this->form_validation->set_rules('event_id[]', '', 'required');
        $this->form_validation->set_rules('code', '', 'required|is_unique[app_coupon.code.id.' . $id . ']');
        $this->form_validation->set_rules('discount_type', '', 'required');
        $this->form_validation->set_rules('discount_value', '', 'required');
        $this->form_validation->set_rules('status', '', 'required');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
            if ($id > 0) {
                $this->update_coupon($id);
            } else {
                $this->add_coupon();
            }
        } else {

            $data['title'] = $this->input->post('title', true);
            $data['valid_till'] = $this->input->post('valid_till', true);
            $data['event_id'] = json_encode($this->input->post('event_id[]', true));
            $data['code'] = $this->input->post('code', true);
            $data['discount_type'] = $this->input->post('discount_type', true);
            $data['discount_value'] = $this->input->post('discount_value', true);
            $data['status'] = $this->input->post('status', true);

            if ($id > 0) {
                $id = $this->model_event->update('app_coupon', $data, "id=$id");
                $this->session->set_flashdata('msg', translate('coupon_update'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $data['created_by'] = $this->login_id;
                $data['created_date'] = date('Y-m-d H:i:s');
                $id = $this->model_event->insert('app_coupon', $data);
                $this->session->set_flashdata('msg', translate('coupon_insert'));
                $this->session->set_flashdata('msg_class', 'success');
            }
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/manage-coupon', 'redirect');
        }
    }

    //delete an event
    public function delete_coupon($id) {
        $this->model_event->delete('app_coupon', 'id=' . $id . " AND created_by=" . $this->login_id);
        $this->session->set_flashdata('msg', translate('coupon_delete'));
        $this->session->set_flashdata('msg_class', 'success');
        echo 'true';
        exit;
    }

}

?>