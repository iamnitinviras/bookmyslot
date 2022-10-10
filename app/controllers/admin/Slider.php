<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Slider extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_slider');
        set_time_zone();
    }

    //show home page
    public function index() {
        $slider = $this->model_slider->getData('', '*', "created_by='$this->login_id'");
        $data['slider_data'] = $slider;
        $data['title'] = translate('manage') . " " . translate('gallery_image');
        $this->load->view('admin/master/slider-list', $data);
    }

    //show add slider form
    public function add_slider() {
        $data['title'] = translate('add') . " " . translate('gallery_image');
        $this->load->view('admin/master/manage-slider', $data);
    }

    //show edit slider form
    public function update_slider($id) {
        $slider = $this->model_slider->getData("app_slider", "*", "id='$id'");
        if (isset($slider[0]) && !empty($slider[0])) {
            $data['slider_data'] = $slider[0];
            $data['title'] = translate('update') . " " . translate('gallery_image');
            $this->load->view('admin/master/manage-slider', $data);
        } else {
            show_404();
        }
    }

    //add/edit an slider
    public function save_slider() {

        $slider_id = (int) $this->input->post('id', true);
        $old_slider_image = $this->input->post('old_slider_image', true);
        $this->form_validation->set_rules('status', '', 'required');

        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
            if ($slider_id > 0) {
                $this->update_slider();
            } else {
                $this->add_slider();
            }
        } else {
            $data['status'] = $this->input->post('status', true);
            $data['created_by'] = $this->login_id;

            if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
                $filesCount = count($_FILES['image']['name']);

                $_FILES['userFile']['name'] = $_FILES['image']['name'];
                $_FILES['userFile']['type'] = $_FILES['image']['type'];
                $_FILES['userFile']['tmp_name'] = $_FILES['image']['tmp_name'];
                $_FILES['userFile']['error'] = $_FILES['image']['error'];
                $_FILES['userFile']['size'] = $_FILES['image']['size'];

                $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/slider';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $temp = explode(".", $_FILES["userFile"]["name"]);
                $name = uniqid();
                $new_name = $name . '.' . end($temp);
                $config['file_name'] = $new_name;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('userFile')) {
                    $fileData = $this->upload->data();
                    $data['image'] = $fileData['file_name'];
                }
                if (isset($old_slider_image) && $old_slider_image != null && $old_slider_image != "") {
                    $slider_image = FCPATH . uploads_path . "/slider/" . $old_slider_image;
                    if (file_exists($slider_image)) {
                        unlink($slider_image);
                    }
                }
            }
            if ($slider_id > 0) {
                $data['updated_on'] = date("Y-m-d h:i:s");
                $id = $this->model_slider->update('app_slider', $data, "id=$slider_id");
                $this->session->set_flashdata('msg', translate('gallery_update'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $data['created_on'] = date("Y-m-d h:i:s");
                $id = $this->model_slider->insert('app_slider', $data);
                $this->session->set_flashdata('msg', translate('gallery_insert'));
                $this->session->set_flashdata('msg_class', 'success');
            }
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/manage-slider', 'redirect');
        }
    }

    //delete an slider
    public function delete_slider($id) {

        //delete existing slider simage
        $slider = $this->model_slider->getData("app_slider", "*", "id='$id'");
        if (count($slider) > 0) {

            $slider_image = FCPATH . uploads_path . "/slider/" . $slider[0]['image'];
            if (file_exists($slider_image)) {
                unlink($slider_image);
            }

            $this->model_slider->delete('app_slider', 'id=' . $id);
            $this->session->set_flashdata('msg', translate('gallery_delete'));
            $this->session->set_flashdata('msg_class', 'success');
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            echo 'true';
            exit;
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));

            echo "false";
            exit;
        }
    }

}

?>