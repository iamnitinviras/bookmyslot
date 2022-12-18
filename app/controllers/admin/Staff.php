<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Staff extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_customer');
        set_time_zone();
        if ($this->login_type != 'V' || $this->login_type != 'A'):

        endif;
    }

    //show staff list
    public function index() {
        $data['title'] = translate('manage') . " " . translate('staff');
        $created_by = $this->login_id;
        $order = "created_on DESC";
        $staff = $this->model_customer->getData("app_admin", "*", "created_by=" . $created_by . " AND type='S'", "", $order);
        $data['staff_data'] = $staff;
        $this->load->view('admin/staff/index', $data);
    }

    public function add_staff() {
        $data['title'] = translate('add') . " " . translate('staff');
        $this->load->view('admin/staff/add_update', $data);
    }

    public function update_staff($id) {
        $id = (int) $id;
        $cond = 'id=' . $id;
        $staff = $this->model_customer->getData("app_admin", "*", $cond);

        if (isset($staff) && count($staff) > 0) {
            $data['staff_data'] = $staff[0];
            $data['title'] = translate('update') . " " . translate('staff');
            $this->load->view('admin/staff/add_update', $data);
        } else {
            show_404();
        }
    }

    public function save_staff() {
        $created_by = $this->login_id;
        $user_id = (int) $this->input->post('staff_id');
        $hidden_profile_image = $this->input->post('hidden_profile_image');
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[app_admin.email.id.' . $user_id . ']');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|is_unique[app_admin.phone.id.' . $user_id . ']');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == false) {
            if ($user_id > 0) {
                $this->update_staff($user_id);
            } else {
                $this->add_staff();
            }
        } else {
            $this->load->helper('string');
            $password = random_string('alnum', 8);
            $data = array(
                'first_name' => trim($this->input->post('first_name')),
                'last_name' => trim($this->input->post('last_name')),
                'email' => trim($this->input->post('email')),
                'password' => md5(trim($password)),
                'designation' => $this->input->post('designation'),
                'phone' => $this->input->post('phone'),
                'type' => 'S',
                'profile_status' => 'V',
                'status' => trim($this->input->post('status')),
                'created_by' => $created_by,
                'created_on' => date("Y-m-d H:i:s")
            );

            $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/profiles';
            if (isset($_FILES['profile_image']["name"]) && $_FILES['profile_image']["name"] != "") {
                $tmp_name = $_FILES["profile_image"]["tmp_name"];
                $temp = explode(".", $_FILES["profile_image"]["name"]);
                $newfilename = (uniqid()) . '.' . end($temp);
                move_uploaded_file($tmp_name, "$uploadPath/$newfilename");
                $data['profile_image'] = $newfilename;
                if ($hidden_profile_image != "" && $hidden_profile_image != NULL) {
                    @unlink($uploadPath . "/" . $hidden_profile_image);
                }
            }

            if ($user_id > 0) {
                $this->model_customer->update('app_admin', $data, 'id=' . $user_id);
                $this->session->set_flashdata('msg', translate('staff_update'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $name = (trim($this->input->post('first_name'))) . " " . (trim($this->input->post('last_name')));
                $hidenuseremail = $this->input->post('email');
                $this->model_customer->insert('app_admin', $data);

                $subject = translate('staff') . " | " . translate('account_registration');
                $define_param['to_name'] = $name;
                $define_param['to_email'] = $hidenuseremail;

                $parameter['NAME'] = $name;
                $parameter['LOGIN_URL'] = base_url('staff/login');
                $parameter['EMAIL'] = $hidenuseremail;
                $parameter['PASSWORD'] = $password;

                $html = $this->load->view("email_template/registration_admin", $parameter, true);
                $send = $this->sendmail->send($define_param, $subject, $html);
                $this->session->set_flashdata('msg', translate('staff_insert'));
                $this->session->set_flashdata('msg_class', 'success');
            }
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/staff', 'redirect');
        }
    }

    //delete staff
    public function delete_staff($id = null) {
        if ($id == null) {
            $id = $this->uri->segment(2);
        }
        $staff_data = $this->model_customer->getData("app_admin", "*", "id=" . $id);
        if (isset($staff_data) && count($staff_data) > 0):
            $staff_check = $this->model_customer->getData("app_event_book", "*", "staff_id=" . $id);

            if (isset($staff_check) && count($staff_check) > 0) {
                $this->session->set_flashdata('msg', translate('staff_booked_no_delete'));
                $this->session->set_flashdata('msg_class', 'failure');
                echo 'false';
                exit;
            } else {
                $this->model_customer->delete('app_admin', 'id=' . $id);

                //delete images
                $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/profiles';
                $hidden_profile_image = $staff_data[0]['profile_image'];
                if ($hidden_profile_image != "" && $hidden_profile_image != NULL) {
                    @unlink($uploadPath . "/" . $hidden_profile_image);
                }

                $this->session->set_flashdata('msg', translate('staff_deleted'));
                $this->session->set_flashdata('msg_class', 'success');
                echo 'true';
                exit;
            }
        else:
            $this->session->set_flashdata('msg', translate('invalid_request'));
            $this->session->set_flashdata('msg_class', 'success');
            echo 'false';
            exit;
        endif;
    }

    //change status of staff
    public function change_staff_tatus($id = null) {
        if ($id == null) {
            $id = $this->uri->segment(2);
        }
        $status = $this->input->post('status', true);
        $update = array(
            'status' => $status
        );
        $this->model_customer->update('app_admin', $update, 'id=' . $id);
        $msg = isset($status) && $status == "A" ? "Active" : "Inactive";
        $this->session->set_flashdata('msg', translate('staff_status'));
        $this->session->set_flashdata('msg_class', 'success');
        echo 'true';
        exit;
    }

    public function staff_booking($id) {

        $data['title'] = translate('booking') . " " . translate('staff');

        $join = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_event.id=app_event_book.event_id',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_city',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_location',
                'condition' => 'app_location.loc_id=app_event.location',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_event_category',
                'condition' => 'app_event_category.id=app_event.category_id',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_admin',
                'condition' => 'app_admin.id=app_event_book.staff_id',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_admin',
                'condition' => 'app_admin.id=app_event.created_by',
                'jointype' => 'left'
            )
        );

        $s_condition = "app_event.type = 'S' AND app_event_book.staff_id=" . $id;
        $appointment = $this->model_customer->getData("app_event_book", "app_event_book.*,app_admin.id as aid ,app_event_book.price as final_price,app_admin.company_name,app_event.title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,app_admin.first_name,app_admin.last_name,app_admin.phone,app_event.price,app_admin.first_name,app_admin.last_name,app_admin.company_name,app_event.image,app_event.description as event_description, app_event.payment_type", $s_condition, $join);
        $data['service_appointment_data'] = $appointment;
        $e_condition = "app_event.type = 'E' AND app_event_book.staff_id=" . $id;
        $e_appointment = $this->model_customer->getData("app_event_book", "app_event_book.*,app_admin.id as aid ,app_event_book.price as final_price,app_admin.company_name,app_event.title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,app_admin.first_name,app_admin.last_name,app_admin.phone,app_event.price,app_admin.first_name,app_admin.last_name,app_admin.company_name,app_event.image,app_event.description as event_description, app_event.payment_type", $e_condition, $join);
        $data['event_appointment_data'] = $e_appointment;
        $this->load->view('admin/staff/staff-booking', $data);
    }

}

?>