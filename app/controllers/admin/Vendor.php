<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendor extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_vendor');
        check_mandatory();
        set_time_zone();
        if (get_site_setting('is_display_vendor') == 'N'):
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/dashboard', 'redirect');
        endif;
    }

    //show vendor list
    public function index() {
        $data['title'] = translate('manage') . " " . translate('vendor');
        $vendor = $this->model_vendor->getData("app_users", '*', "type='V'");
        $data['vendor_data'] = $vendor;
        $this->load->view('admin/vendor/index', $data);
    }

    //Show unverified vendor list
    public function unverified_vendor() {
        $data['title'] = translate('unverified') . " " . translate('vendor');
        $vendor = $this->model_vendor->getData("app_users", '*', "type='V' AND profile_status='N'");
        $data['vendor_data'] = $vendor;
        $this->load->view('admin/vendor/unverified_vendor', $data);
    }

    //delete vendor
    public function delete_vendor($id) {
        $event_data = $this->model_vendor->getData('app_event', 'id', "created_by='$id'");
        if (isset($event_data) && count($event_data) > 0) {
            $this->session->set_flashdata('msg', translate('event_vendor_exist'));
            $this->session->set_flashdata('msg_class', 'failure');
            echo 'false';
            exit(0);
        } else {
            $this->model_vendor->delete('app_users', 'id=' . $id);
            $this->session->set_flashdata('msg', translate('vendor_deleted'));
            $this->session->set_flashdata('msg_class', 'success');
            echo 'true';
            exit;
        }
    }

    //change status of vendor
    public function change_vendor_tatus($id) {
        $status = $this->input->post('status', true);
        $update = array(
            'status' => $status
        );
        $this->model_vendor->update('app_users', $update, 'id=' . $id);
        $this->session->set_flashdata('msg', translate('vendor_status'));
        $this->session->set_flashdata('msg_class', 'success');
        echo 'true';
        exit;
    }

    public function unverified_vendor_action() {
        $user_id = (int) $this->input->post('CustomerIDVal', true);
        if (isset($user_id) && $user_id > 0) {
            $status = trim($this->input->post('get_status', true));
            $update = array(
                'profile_status' => $status
            );

            $status_name = "";
            if ($status == 'V'):
                $status_name = translate('approved');
            endif;
            if ($status == 'R'):
                $status_name = translate('rejected');
            endif;

            $this->model_vendor->update('app_users', $update, 'id=' . $user_id);
            //Send email to vendor
            $vendor = $this->model_vendor->getData("app_users", '*', "id=" . $user_id)[0];
            $first_name = $vendor['first_name'];
            $last_name = $vendor['last_name'];
            $email = $vendor['email'];

            //Send email to vendor
            $subject = translate('profile') . " " . translate('verification') . " " . translate('update');
            $define_param['to_name'] = $first_name . " " . $last_name;
            $define_param['to_email'] = $email;

            $parameter['NAME'] = $first_name . " " . $last_name;
            $parameter['profile_verification_content'] = translate('profile_verification_content') . " <b>" . $status_name . "</b>";

            $html = $this->load->view("email_template/vendor_profile_verification", $parameter, true);
            $send = $this->sendmail->send($define_param, $subject, $html);

            $this->session->set_flashdata('msg', translate('vendor_status'));
            $this->session->set_flashdata('msg_class', 'success');

            redirect('admin/unverified-vendor');
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect('admin/unverified-vendor');
        }
    }

    public function vendor_payment() {
        $data['vendor_payment'] = $this->model_vendor->get_vendor_payment_list($this->login_id);
        $data['title'] = translate('vendor_Payment');
        $this->load->view('admin/vendor/vendor-payment-list', $data);
    }

    public function send_vendor_payment($id) {
        $this->model_vendor->update('app_appointment_payment', array('transfer_status' => 'S'), "id='$id'");
        $this->session->set_flashdata('msg', translate('vendor_payment_send'));
        $this->session->set_flashdata('msg_class', 'success');
        echo 'true';
        exit;
    }

    public function add_vendor() {
        $data['title'] = translate('add_vendor');
        $this->load->view('admin/vendor/add_update', $data);
    }

    public function update_vendor($id) {
        $cond = 'id=' . $id;

        $vendor = $this->model_vendor->getData("app_users", "*", $cond);
        if (isset($vendor[0]) && !empty($vendor[0])) {
            $data['vendor_data'] = $vendor[0];
            $data['title'] = translate('update') . " " . translate('vendor');
            $this->load->view('admin/vendor/add_update', $data);
        } else {
            show_404();
        }
    }

    public function save_vendor() {

        $user_id = (int) $this->input->post('vendor_id');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[app_users.email.id.' . $user_id . ']');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required|is_unique[app_users.phone.id.' . $user_id . ']');
        $this->form_validation->set_rules('company', 'Company', 'required');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == false) {
            if ($user_id > 0) {
                $this->update_vendor($user_id);
            } else {
                $this->add_vendor();
            }
        } else {
            $this->load->helper('string');
            $password = random_string('alnum', 8);
            $data = array(
                'first_name' => trim($this->input->post('first_name')),
                'last_name' => trim($this->input->post('last_name')),
                'email' => trim($this->input->post('email')),
                'password' => md5(trim($password)),
                'company_name' => trim($this->input->post('company')),
                'website' => trim($this->input->post('website')),
                'phone' => $this->input->post('phone'),
                'type' => 'V',
                'status' => 'A',
                'address' => trim($this->input->post('address')),
                'profile_status' => 'V',
                'created_on' => date("Y-m-d H:i:s")
            );
            if ($user_id > 0) {
                $this->model_vendor->update('app_users', $data, 'id=' . $user_id);
                $this->session->set_flashdata('msg', translate('vendor_update'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $name = (trim($this->input->post('first_name'))) . " " . (trim($this->input->post('last_name')));
                $hidenuseremail = $this->input->post('email');
                $this->model_vendor->insert('app_users', $data);

                //Send email to vendor
                $subject = translate('vendor') . " | " . translate('account_registration');
                $define_param['to_name'] = $name;
                $define_param['to_email'] = $hidenuseremail;

                $parameter['NAME'] = $name;
                $parameter['LOGIN_URL'] = base_url('vendor/login');
                $parameter['EMAIL'] = $hidenuseremail;
                $parameter['PASSWORD'] = $password;

                $html = $this->load->view("email_template/registration_admin", $parameter, true);
                $send = $this->sendmail->send($define_param, $subject, $html);

                $this->session->set_flashdata('msg', translate('vendor_insert'));
                $this->session->set_flashdata('msg_class', 'success');
            }
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/vendor', 'redirect');
        }
    }

}

?>