<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Faq extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_city');
        set_time_zone();
    }

    //show home page
    public function index() {
        $app_faq = $this->model_city->getData('app_faq', '*');
        $data['app_faq'] = $app_faq;
        $data['title'] = translate('manage') . " " . translate('faqs');
        $this->load->view('admin/master/faq/index', $data);
    }

    //show add event form
    public function add_faq() {
        $data['title'] = translate('add') . " " . translate('faqs');
        $this->load->view('admin/master/faq/add_update', $data);
    }

    //show edit event form
    public function update_faq($id) {
        $cond = 'id=' . $id;
        $app_faq = $this->model_city->getData("app_faq", "*", $cond);
        if (isset($app_faq[0]) && !empty($app_faq[0])) {
            $data['app_faq'] = $app_faq[0];
            $data['title'] = translate('update') . " " . translate('faqs');
            $this->load->view('admin/master/faq/add_update', $data);
        } else {
            show_404();
        }
    }

    //add/edit an event
    public function save_faq() {
        $id = (int) $this->input->post('id', true);
        $this->form_validation->set_rules('title', 'title', 'trim|required|is_unique[app_faq.title.id.' . $id . ']');
        $this->form_validation->set_rules('status', '', 'trim|required');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == false) {
            if ($id > 0) {
                $this->update_faq($id);
            } else {
                $this->add_faq();
            }
        } else {
            $data['title'] = $this->input->post('title', true);
            $data['description'] = $this->input->post('description', true);
            $data['status'] = $this->input->post('status', true);

            if ($id > 0) {
                $this->model_city->update('app_faq', $data, "id=$id");
                $this->session->set_flashdata('msg', translate('faq_update'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $data['created_on'] = date("Y-m-d H:i:s");
                $this->model_city->insert('app_faq', $data);
                $this->session->set_flashdata('msg', translate('faq_insert'));
                $this->session->set_flashdata('msg_class', 'success');
            }
            redirect('admin/faq', 'redirect');
        }
    }

    //delete an event
    public function delete_faq($id) {
        $this->model_city->delete('app_faq', "id=" . $id);
        $this->session->set_flashdata('msg', translate('faq_delete'));
        $this->session->set_flashdata('msg_class', 'success');
        echo "true";
        exit;
    }

}

?>