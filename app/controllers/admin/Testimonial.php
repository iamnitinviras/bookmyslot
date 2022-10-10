<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Testimonial extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('model_dashboard');
        set_time_zone();
        if (get_site_setting('enable_testimonial') == 'N' || $this->login_type == 'V'):
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/dashboard', 'redirect');
        endif;
    }

    //show home page
    public function index() {
        $event = $this->model_dashboard->getData('app_testimonial', '*');
        $data['testimonial_data'] = $event;
        $data['title'] = translate('manage') . " " . translate('testimonial');
        $this->load->view('admin/master/manage-testimonial', $data);
    }

    //show add event form
    public function add_testimonial() {
        $data['title'] = translate('add') . " " . translate('testimonial');
        $this->load->view('admin/master/add-update-testimonial', $data);
    }

    //show edit event form
    public function update_testimonial($id) {
        $cond = 'id=' . $id;
        $event = $this->model_dashboard->getData("app_testimonial", "*", $cond);
        if (isset($event[0]) && !empty($event[0])) {
            $data['app_testimonial'] = $event[0];
            $data['title'] = translate('update') . " " . translate('testimonial');
            $this->load->view('admin/master/add-update-testimonial', $data);
        } else {
            $this->session->set_flashdata('msg', translate('invalid_request'));
            $this->session->set_flashdata('msg_class', 'failure');
            redirect('admin/testimonial', 'redirect');
        }
    }

    //add/edit an event
    public function save_testimonial() {
        $id = (int) $this->input->post('id', true);
        $this->form_validation->set_rules('name', 'title', 'trim|required');
        $this->form_validation->set_rules('details', '', 'trim|required');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == false) {
            if ($id > 0) {
                $this->update_testimonial();
            } else {
                $this->add_testimonial();
            }
        } else {
            $hidden_main_image = $this->input->post('hidden_testimonial_image', true);
            $data['name'] = $this->input->post('name', true);
            $data['details'] = $this->input->post('details', true);
            $data['status'] = $this->input->post('status', true);
            $data['created_by'] = $this->login_id;


            $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/category';

            if (isset($_FILES['image']["name"]) && $_FILES['image']["name"] != "") {
                $tmp_name = $_FILES["image"]["tmp_name"];
                $temp = explode(".", $_FILES["image"]["name"]);
                $newfilename = (uniqid()) . '.' . end($temp);
                move_uploaded_file($tmp_name, "$uploadPath/$newfilename");
                $data['image'] = $newfilename;

                if (isset($hidden_main_image) && $hidden_main_image != "" && file_exists(FCPATH . uploads_path . '/category/' . $hidden_main_image)) {
                    @unlink($uploadPath . "/" . $hidden_main_image);
                }
            }

            if ($id > 0) {
                $this->model_dashboard->update('app_testimonial', $data, "id=$id");
                $this->session->set_flashdata('msg', translate('testimonial_update'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $data['created_date'] = date("Y-m-d H:i:s");
                $this->model_dashboard->insert('app_testimonial', $data);
                $this->session->set_flashdata('msg', translate('testimonial_insert'));
                $this->session->set_flashdata('msg_class', 'success');
            }
            redirect('admin/testimonial', 'redirect');
        }
    }

    //delete an event
    public function delete_testimonial($id) {
        $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/category';
        $id = (int) $id;
        if ($id > 0) {
            $cond = 'id=' . $id;
            $event = $this->model_dashboard->getData("app_testimonial", "*", $cond);

            if (count($event) > 0) {
                $image = isset($event[0]['image']) ? $event[0]['image'] : "";

                if (isset($image) && $image != "") {
                    if (file_exists(FCPATH . uploads_path . '/category/' . $image)) {
                        @unlink($uploadPath . "/" . $image);
                    }
                }

                $this->model_dashboard->delete('app_testimonial', $id);
                $this->session->set_flashdata('msg', translate('testimonial_delete'));
                $this->session->set_flashdata('msg_class', 'success');
                echo "true";
                exit;
            } else {
                $this->session->set_flashdata('msg', translate('invalid_request'));
                $this->session->set_flashdata('msg_class', 'failure');
                echo "false";
                exit;
            }
        } else {
            $this->session->set_flashdata('msg', translate('invalid_request'));
            $this->session->set_flashdata('msg_class', 'failure');
            echo "false";
            exit;
        }
    }

}

?>