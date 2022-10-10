<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Currency extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_slider');
        set_time_zone();
    }

    //show home page
    public function index() {
        $currency = $this->model_slider->getData('app_currency', '*','display_status="A"','','id desc');
        $data['currency_data'] = $currency;
        $data['title'] = translate('manage') . " " . translate('currency');
        $this->load->view('admin/currency/currency-list', $data);
    }

    //show add currency form
    public function add_currency() {
        $data['title'] = translate('add') . " " . translate('currency');
        $this->load->view('admin/currency/add-currency', $data);
    }

    //show edit currency form
    public function update_currency($id) {
        $id=(int)$id;
        if($id>25){
            $currency = $this->model_slider->getData("app_currency", "*", "id='$id'");
            if (isset($currency[0]) && !empty($currency[0])) {
                $data['currency_data'] = $currency[0];
                $data['title'] = translate('update') . " " . translate('currency');
                $this->load->view('admin/currency/add-currency', $data);
            } else {
                redirect('admin/currency', 'redirect');
            }
        }else{
            $this->session->set_flashdata('msg',"You are not allowed to update default currency.");
            $this->session->set_flashdata('msg_class', 'info');
            redirect('admin/currency', 'redirect');
        }
        
    }

    //add/edit an currency
    public function save_currency() {

        $currency_id = (int) $this->input->post('id', true);
        $this->form_validation->set_rules('title', 'Title', 'trim|required|is_unique[app_currency.title.id.' . $currency_id . ']');
        $this->form_validation->set_rules('code', 'Code', 'trim|required|is_unique[app_currency.code.id.' . $currency_id . ']');
        $this->form_validation->set_rules('currency_code', 'Currency Code', 'trim|required');

        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
            if ($currency_id > 0) {
                $this->update_currency($currency_id);
            } else {
                $this->add_currency();
            }
        } else {
            $data['title'] = $this->input->post('title', true);
            $data['code'] = $this->input->post('code', true);
            $data['display_status'] ='A';
            $data['currency_code'] = $this->input->post('currency_code', true);

            if ($currency_id > 0) {

                if($currency_id>25){
                    $id = $this->model_slider->update('app_currency', $data, "id=$currency_id");
                    $this->session->set_flashdata('msg',"Currency has been updated.");
                    $this->session->set_flashdata('msg_class', 'success');
                }else{
                    
                    $this->session->set_flashdata('msg',"You are not allowed to update default currency.");
                    $this->session->set_flashdata('msg_class', 'info');
                    redirect('admin/currency', 'redirect');
                }
            } else {
                $id = $this->model_slider->insert('app_currency', $data);
                $this->session->set_flashdata('msg',"Currency has been added successfully.");
                $this->session->set_flashdata('msg_class', 'success');
            }
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect('admin/currency', 'redirect');
        }
    }
}

?>