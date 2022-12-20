<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Package extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_package');
        $this->load->model('model_membership');
    }

    //show home page
    public function index() {
        $package = $this->model_package->getData('', '*');
        $data['package_data'] = $package;
        $data['title'] = translate('manage') . " " . translate('package');
        $this->load->view('admin/package/index', $data);
    }

    //show add package form
    public function add_package() {
        $data['title'] = translate('add') . " " . translate('package');
        $this->load->view('admin/package/add_update', $data);
    }

    //show edit package form
    public function update_package($id = null) {
        if ($id == null) {
            $id = $this->uri->segment(2);
        }
        $package = $this->model_package->getData("app_package", "*", "id='$id'");
        if (isset($package[0]) && !empty($package[0])) {
            $data['package_data'] = $package[0];
            $data['title'] = translate('update') . " " . translate('package');
            $this->load->view('admin/package/add_update', $data);
        } else {
            show_404();
        }
    }

    //add/edit an package
    public function save_package() {
        
        $package_id = (int) $this->input->post('id', true);
        $this->form_validation->set_rules('title', '', 'trim|required|is_unique[app_package.title.id.' . $package_id . ']');
        $this->form_validation->set_rules('status', '', 'required');
        $this->form_validation->set_rules('description', '', 'trim|required');
        $this->form_validation->set_rules('price', '', 'trim|required');
        $this->form_validation->set_rules('package_month', translate('validity'), 'trim|required|numeric|is_unique[app_package.package_month.id.' . $package_id . ']');
        $this->form_validation->set_message('required', $this->lang->line('Required_msg'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
            if ($package_id > 0) {
                $this->update_package($package_id);
            } else {
                $this->add_package();
            }
        } else {
            $data['title'] = $this->input->post('title', true);
            $data['price'] = $this->input->post('price', true);
            $data['description'] = $this->input->post('description', true);
            
            $data['package_month'] = $this->input->post('package_month', true);
            $data['status'] = $this->input->post('status', true);

            $this->model_package->update('app_package', $data, "id=$package_id");
            $this->session->set_flashdata('msg', translate('package_update'));
            $this->session->set_flashdata('msg_class', 'success');
            redirect('admin/package', 'redirect');
        }
    }

    //delete an package
    public function delete_package($id = null) {
        if ($id == null) {
            $id = (int) $this->uri->segment(2);
        }
        $this->model_package->delete('app_package', 'id=' . $id);
        $this->session->set_flashdata('msg', translate('package_delete'));
        $this->session->set_flashdata('msg_class', 'success');
        echo true;
        exit;
    }

    //package payment page
    public function package_payment() {
        $data['payment_history'] = $this->model_membership->get_package_history();
        $data['title'] = $this->lang->line('Package_Payment');
        $this->load->view('admin/package/payment', $data);
    }
}

?>