<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Content extends CI_Controller {

    public function __construct() {
        parent::__construct();
        run_default_query();
        if (is_maintenance_mode() == 'Y') {
            redirect('maintenance');
            exit(0);
        }
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $this->load->model('model_customer');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        set_time_zone();
    }

    //show customer dashboard if authenticated
    public function index() {
        $this->authenticate->check();
        redirect('dashboard');
    }

    //get location
    public function get_location($city_id) {
        $location_data = $this->model_customer->getData('app_location', '*', "loc_city_id='$city_id' AND loc_status='A'");

        $html = '<option value="">' . translate('select_location') . '</option>';
        if (isset($location_data) && count($location_data) > 0) {
            foreach ($location_data as $value) {
                $html .= '<option value="' . $value['loc_id'] . '">' . $value['loc_title'] . '</option>';
            }
        }
        echo $html;
        exit;
    }

    //cutomer login
    public function login() {
        $next = $this->input->get('next');
        if (!$this->session->userdata('CUST_ID')) {
            $data['title'] = translate('login');
            $data['next'] = $next;
            $this->load->view('front/login', $data);
        } else {
            redirect('dashboard');
        }
    }

    //check authentication of cutomer when login
    public function login_action() {

        $username = $this->db->escape($this->input->post("username", true));
        $next = $this->input->post("next", true);
        $password = $this->input->post("password", true);
        $this->form_validation->set_rules('username', '', 'trim|required');
        $this->form_validation->set_rules('password', '', 'trim|required');
        $this->form_validation->set_message('required', translate('required_message'));
        if ($this->form_validation->run() == false) {
            $this->login();
        } else {
            $users = $this->model_customer->authenticate($username, $password);
            //Check for login
            if ($users['errorCode'] == 0) {
                $this->session->set_flashdata('msg', $users['errorMessage']);
                $this->session->set_flashdata('msg_class', 'failure');
                $this->login();
            } else {
                $this->session->set_flashdata('msg', translate('login_success'));
                $this->session->set_flashdata('msg_class', 'success');

                if (isset($next) && $next != "") {
                    redirect(base_url($next));
                } else {
                    redirect(base_url());
                }
            }
        }
    }

    //customer forgot password 
    public function forgot_password() {
        if (!$this->session->userdata('CUST_ID')) {
            $company_data = $this->model_customer->getData("app_site_setting", "*");
            $data['title'] = translate('forgot_password');
            $data['company_data'] = $company_data[0];
            $this->load->view('front/forgot_password', $data);
        } else {
            redirect(base_url("dashboard"));
        }
    }

    //authenticate email of customer and send mail
    public function forgot_password_action() {
        $email = $this->input->post("email", true);
        $rply = $this->model_customer->check_username($email);
        $this->load->helper('string');
        if ($rply['errorCode'] == 1) {
            $define_param['to_name'] = ($rply['Firstname']) . " " . ($rply['Lastname']);
            $define_param['to_email'] = $rply['Email'];
            $userid = $rply['ID'];
            $hidenuseremail = $rply['Email'];
            $hidenusername = ($rply['Firstname']) . " " . ($rply['Lastname']);
            //Encryprt data
            $encid = $this->general->encryptData($userid);
            $encemail = $this->general->encryptData($hidenuseremail);
            $url = base_url("reset-password/" . $encid . "/" . $encemail);

            $update['reset_password_check'] = 0;
            $update['reset_password_requested_on'] = date("Y-m-d H:i:S");
            $result = $this->model_customer->update("app_customer", $update, "ID='" . $userid . "'");

            //Send email
            $subject = translate('reset_password');
            $define_param['to_name'] = $hidenusername;
            $define_param['to_email'] = $hidenuseremail;

            $parameter['URL'] = $url;
            $html = $this->load->view("email_template/forgot_password", $parameter, true);
            $this->sendmail->send($define_param, $subject, $html);

            $this->session->set_flashdata('msg', $rply['errorMessage']);
            $this->session->set_flashdata('msg_class', 'success');
            redirect('login');
        } else {
            $this->session->set_flashdata('msg', $rply['errorMessage']);
            $this->session->set_flashdata('msg_class', 'failure');
            redirect('forgot-password');
        }
    }

    //show cutomer reset password form
    public function reset_password() {
        $id_ency = $this->uri->segment(2);
        $email_ency = $this->uri->segment(3);

        $id = (int) $this->general->decryptData($id_ency);
        $email = $this->general->decryptData($email_ency);
        $customer_data = $this->model_customer->getData("app_customer", "*", "id='" . $id . "' AND email='" . $email . "'");

        if (count($customer_data) > 0) {
            $add_min = date("Y-m-d H:i:s", strtotime($customer_data[0]['reset_password_requested_on'] . "+1 hour"));
            if ($add_min > date("Y-m-d H:i:s")) {
                if ($customer_data[0]['reset_password_check'] != 1) {
                    $content_data['title'] = translate('reset_password');
                    $content_data['id'] = $id;
                    $this->load->view('front/reset_password', $content_data);
                } else {
                    $this->session->set_flashdata('failure', translate('reset_failure'));
                    redirect('forgot_password');
                }
            } else {
                $this->session->set_flashdata('failure', translate('reset_failure'));
                redirect('forgot-password');
            }
        } else {
            $this->session->set_flashdata('failure', translate('invalid_request'));
            redirect('forgot-password');
        }
    }

    //reset password
    public function reset_password_action() {
        $password = $this->input->post('password');
        $id = $this->input->post('id');

        $this->form_validation->set_rules('password', '', 'trim|required');
        $this->form_validation->set_rules('Cpassword', '', 'trim|required');
        $this->form_validation->set_message('required', translate('required_message'));
        if ($this->form_validation->run() == false) {
            $content_data['id'] = $id;
            $this->load->view('front/reset_password', $content_data);
        } else {
            $update['reset_password_check'] = 1;
            $update['reset_password_requested_on'] = "0000-00-00 00:00:00";
            $update['password'] = md5($password);
            $this->model_customer->update("app_customer", $update, "id='" . $id . "'");
            $this->session->set_flashdata('msg', translate('reset_success'));
            $this->session->set_flashdata('msg_class', 'success');
            redirect('login');
        }
    }

    //show customer change password form
    public function update_password() {
        $this->authenticate->check();
        $data['title'] = translate('change_password');
        $city_join = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'inner'
            )
        );
        $top_cities = $this->model_customer->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');
        $data['topCity_List'] = $top_cities;
        $this->load->view('front/profile/change_password', $data);
    }

    //change password
    public function update_password_action() {
        $user_id = (int) $this->session->userdata('CUST_ID');
        $this->authenticate->check();
        $this->form_validation->set_rules('old_password', '', 'trim|required');
        $this->form_validation->set_rules('password', '', 'trim|required');
        $this->form_validation->set_rules('confirm_password', '', 'trim|required');
        $this->form_validation->set_message('required', translate('required_message'));
        if ($this->form_validation->run() == false) {
            $this->update_password();
        } else {
            $password = $this->input->post('old_password');
            $new_password = $this->input->post('password');
            $id = (int) $this->session->userdata("CUST_ID");
            $get_result = $this->model_customer->getData("app_customer", "*", "id='" . $id . "'");
            if (count($get_result) > 0) {
                $old_password = $get_result[0]['password'];
                if (isset($password) && $old_password == md5($password)) {
                    $update['default_password_changed'] = 1;
                    $update['password'] = md5($new_password);
                    $this->model_customer->update("app_customer", $update, "id='" . $id . "'");
                    $this->session->set_userdata("DefaultPassword", 1);
                    $this->session->set_flashdata('msg', translate('reset_success'));
                    $this->session->set_flashdata('msg_class', 'success');
                    redirect('change-password');
                } else {
                    $this->session->set_flashdata('msg', translate('current_password_failure'));
                    $this->session->set_flashdata('msg_class', 'failure');
                    redirect('change-password');
                }
            } else {
                $this->session->set_flashdata('msg', translate('invalid_request'));
                $this->session->set_flashdata('msg_class', 'failure');
                redirect('login');
            }
        }
    }

    //show customer profile
    public function profile() {
        $data['title'] = translate('customer_profile');
        $this->authenticate->check();
        $id = (int) $this->session->userdata('CUST_ID');
        if ($id > 0) {
            $customer_data = $this->model_customer->getData("app_customer", "*", "id=" . $id);
            if (isset($customer_data) && count($customer_data) > 0 && !empty($customer_data)) {
                $data['title'] = translate('profile');
                $data['customer_data'] = $customer_data[0];
                $city_join = array(
                    array(
                        'table' => 'app_event',
                        'condition' => 'app_city.city_id=app_event.city',
                        'jointype' => 'inner'
                    )
                );
                $top_cities = $this->model_customer->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');
                $data['topCity_List'] = $top_cities;
                $this->load->view('front/profile/profile', $data);
            } else {
                $this->session->set_flashdata('msg', translate('invalid_request'));
                $this->session->set_flashdata('msg_class', 'failure');
                show_404();
            }
        } else {
            $this->session->set_flashdata('msg', translate('invalid_request'));
            $this->session->set_flashdata('msg_class', 'failure');
            show_404();
        }
    }

    //update profile
    public function profile_save() {
        $user_id = (int) $this->session->userdata('CUST_ID');
        $this->authenticate->check();

        $this->form_validation->set_rules('first_name', '', 'trim|required');
        $this->form_validation->set_rules('last_name', '', 'trim|required');
        $this->form_validation->set_rules('email', '', 'trim|required|is_unique[app_customer.email.ID.' . $user_id . ']');
        $this->form_validation->set_rules('phone', '', 'trim|is_unique[app_customer.phone.ID.' . $user_id . ']');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class = "error"> ', '</div>');
        if ($this->form_validation->run() == false) {
            $this->profile();
        } else {
            $data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'updated_on' => date("Y-m-d H:i:s")
            );
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['name'] != '') {

                $uploadPath = uploads_path . '/profiles';
                $tmp_name = $_FILES["profile_image"]["tmp_name"];
                $temp = explode(".", $_FILES["profile_image"]["name"]);
                $newfilename = (uniqid()) . '.' . end($temp);
                move_uploaded_file($tmp_name, "$uploadPath/$newfilename");
                $data['profile_image'] = $newfilename;
            }

            $result = $this->model_customer->update("app_customer", $data, "id='" . $user_id . "'");
            $this->session->set_flashdata('msg', translate('profile_success'));
            $this->session->set_flashdata('msg_class', "success");
            redirect('profile');
        }
    }

    //show customer register form
    public function register() {
        $next = $this->input->get('next');
        $data['next'] = $next;
        $data['title'] = translate('register');
        $this->load->view('front/register', $data);
    }

    //customer registration
    public function register_save() {
        $next = $this->input->post("next", true);
        $this->form_validation->set_rules('first_name', '', 'required');
        $this->form_validation->set_rules('last_name', '', 'required');
        $this->form_validation->set_rules('email', '', 'required|is_unique[app_customer.email]');
        $this->form_validation->set_rules('password', '', 'required');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class = "error"> ', '</div>');
        if ($this->form_validation->run() == false) {
            $this->register();
        } else {
            $data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'password' => md5($this->input->post('password')),
                'status' => 'P',
                'created_on' => date("Y-m-d H:i:s")
            );

            $insert_id = $this->model_customer->insert("app_customer", $data);
            $this->customer_verify_resend($insert_id);
        }
    }

    //cutomer logout
    public function logout() {
        $this->session->unset_userdata('CUST_ID');
        $this->session->unset_userdata('DefaultPassword');
        $this->session->set_flashdata('msg', translate('logout_success'));
        $this->session->set_flashdata('msg_class', 'success');
        redirect(base_url());
    }

    //show customer register form
    public function vendor_register() {
        if ($this->session->userdata('Vendor_ID')) {
            $this->session->set_flashdata('msg_class', "failure");
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect('vendor/dashboard');
        }
        $data['title'] = translate('register');
        $package_data = $this->model_customer->getData("app_package", "*", "status='A'");
        $data['package_data'] = $package_data;
        $this->load->view('front/vendor_register', $data);
    }

    //customer registration
    public function vendor_register_save() {
        $this->form_validation->set_rules('first_name', '', 'required');
        $this->form_validation->set_rules('last_name', '', 'required');
        $this->form_validation->set_rules('email', '', 'required|is_unique[app_users.email]');
        $this->form_validation->set_rules('password', '', 'required');
        $this->form_validation->set_rules('company', '', 'required');
        $this->form_validation->set_rules('phone', '', 'required|is_unique[app_users.phone]');
        $this->form_validation->set_rules('address', '', 'required');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class = "error"> ', '</div>');
        if ($this->form_validation->run() == false) {
            $this->vendor_register();
        } else {
            $data = array(
                'first_name' => trim($this->input->post('first_name')),
                'last_name' => trim($this->input->post('last_name')),
                'email' => trim($this->input->post('email')),
                'password' => md5(trim($this->input->post('password'))),
                'company_name' => trim($this->input->post('company')),
                'website' => trim($this->input->post('website')),
                'phone' => $this->input->post('phone'),
                'type' => 'V',
                'status' => 'P',
                'address' => trim($this->input->post('address')),
                'created_on' => date("Y-m-d H:i:s")
            );

            $insert_id = $this->model_customer->insert("app_users", $data);
            $this->vendor_verify_resend($insert_id);
        }
    }

    public function verify_vendor($encid, $encemail) {
        $id = (int) $this->general->decryptData($encid);
        $email = $this->general->decryptData($encemail);
        $vendor_data = $this->model_customer->getData("app_users", "*", "id='" . $id . "' AND email='" . $email . "'");
        if (count($vendor_data) > 0) {
            if ($vendor_data[0]['status'] == 'P') {
                $this->model_customer->update('app_users', array('profile_status' => 'N', 'status' => 'A'), "id='$id'");
                $this->session->set_flashdata('msg_class', "success");
                $this->session->set_flashdata('msg', translate('account_verify_success'));
                redirect('vendor/login');
            } else {
                $this->session->set_flashdata('msg_class', "failure");
                $this->session->set_flashdata('msg', translate('already_vendor_verify'));
                redirect('vendor-register');
            }
        } else {
            $this->session->set_flashdata('msg_class', "failure");
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect('vendor-register');
        }
    }

    public function vendor_verify_resend($id) {
        $vendor_result = $this->model_customer->getData('app_users', '*', "id='$id' AND profile_status='N'");

        if (count($vendor_result) > 0) {

            $this->model_customer->update('app_users', array('created_on' => date("Y-m-d H:i:s")), "id='$id'");

            $encid = $this->general->encryptData($id);
            $encemail = $this->general->encryptData($vendor_result[0]['email']);
            $url = base_url('verify-vendor/' . $encid . "/" . $encemail);
            $name = ($vendor_result[0]['first_name']) . " " . ($vendor_result[0]['last_name']);
            $hidenuseremail = $vendor_result[0]['email'];
            // Send email
            $subject = translate('account_registration');
            $define_param['to_name'] = $name;
            $define_param['to_email'] = $hidenuseremail;

            $parameter['NAME'] = $name;
            $parameter['URL'] = $url;
            $html = $this->load->view("email_template/customer_register", $parameter, true);
            $send = $this->sendmail->send($define_param, $subject, $html);

            $this->session->set_flashdata('msg', translate('vendor_mail_success'));
            $this->session->set_flashdata('msg_class', "success");
            redirect('success');
        } else {
            $this->session->set_flashdata('msg_class', "failure");
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect('vendor');
        }
    }

    public function check_vendor_email() {
        $email = $this->input->post('email');
        $id = $this->input->post('id');
        if ($id && $id > 0) {
            $check_title = $this->model_customer->getData("app_users", "email", "email='$email' AND id != " . $id);
        } else {
            $check_title = $this->model_customer->getData("app_users", "email", "email='$email'");
        }
        if (isset($check_title) && count($check_title) > 0) {
            echo "false";
            exit;
        } else {
            echo "true";
            exit;
        }
    }

    public function check_vendor_phone() {
        $phone = $this->input->post('phone');
        $id = $this->input->post('id');
        if ($id && $id > 0) {
            $check_title = $this->model_customer->getData("app_users", "phone", "phone='$phone' AND id != " . $id);
        } else {
            $check_title = $this->model_customer->getData("app_users", "phone", "phone='$phone'");
        }
        if (isset($check_title) && count($check_title) > 0) {
            echo "false";
            exit;
        } else {
            echo "true";
            exit;
        }
    }

    public function check_customer_email() {
        $email = $this->input->post('email');
        $id = $this->input->post('id');
        if ($id && $id > 0) {
            $check_title = $this->model_customer->getData("app_customer", "email", "email='$email' AND id != " . $id);
        } else {
            $check_title = $this->model_customer->getData("app_customer", "email", "email='$email'");
        }
        if (isset($check_title) && count($check_title) > 0) {
            echo "false";
            exit;
        } else {
            echo "true";
            exit;
        }
    }

    public function check_customer_phone() {
        $phone = $this->input->post('phone');
        $id = $this->input->post('id');
        if ($id && $id > 0) {
            $check_title = $this->model_customer->getData("app_customer", "phone", "phone='$phone' AND id != " . $id);
        } else {
            $check_title = $this->model_customer->getData("app_customer", "phone", "phone='$phone'");
        }
        if (isset($check_title) && count($check_title) > 0) {
            echo "false";
            exit;
        } else {
            echo "true";
            exit;
        }
    }

    public function customer_verify_resend($id) {
        $customer_result = $this->model_customer->getData('app_customer', '*', "id='$id' AND status='P'");

        if (count($customer_result) > 0) {

            $this->model_customer->update('app_customer', array('created_on' => date("Y-m-d H:i:s")), "id='$id'");

            $encid = $this->general->encryptData($id);
            $encemail = $this->general->encryptData($customer_result[0]['email']);
            $url = base_url('verify-customer/' . $encid . "/" . $encemail);
            $name = ($customer_result[0]['first_name']) . " " . ($customer_result[0]['last_name']);
            $hidenuseremail = $customer_result[0]['email'];

            // Send email
            $subject = translate('account_registration');
            $define_param['to_name'] = $name;
            $define_param['to_email'] = $hidenuseremail;

            $parameter['NAME'] = $name;
            $parameter['URL'] = $url;
            $html = $this->load->view("email_template/customer_register", $parameter, true);
            $send = $this->sendmail->send($define_param, $subject, $html);

            $this->session->set_flashdata('msg', translate('vendor_mail_success'));
            $this->session->set_flashdata('msg_class', "success");
            redirect('success');
        } else {
            $this->session->set_flashdata('msg_class', "failure");
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect('login');
        }
    }

    public function verify_customer($encid, $encemail) {
        $id = (int) $this->general->decryptData($encid);
        $email = $this->general->decryptData($encemail);
        $cust_data = $this->model_customer->getData("app_customer", "*", "id='" . $id . "' AND email='" . $email . "'");
        if (count($cust_data) > 0) {
            if ($cust_data[0]['status'] == 'A') {
                $this->session->set_flashdata('msg_class', "failure");
                $this->session->set_flashdata('msg', translate('already_vendor_verify'));
                redirect('register');
            } else {
                $this->model_customer->update('app_customer', array('status' => 'A'), "id='$id'");
                $this->session->set_flashdata('msg_class', "success");
                $this->session->set_flashdata('msg', translate('account_verify_success'));
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('msg_class', "failure");
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect('register');
        }
    }

    public function get_service_slidepanel_details($id) {

        $result_data = get_full_event_service_data_by_booking_id($id);
        $data['title'] = translate('service') . " " . translate("booking") . " " . translate("details");
        $data['result_data'] = $result_data;
        $this->load->view('front/profile/get_service_slidepanel_details', $data);
    }

    public function get_event_slidepanel_details($id) {
        $result_data = get_full_event_service_data_by_booking_id($id);
        $data['title'] = translate('event') . " " . translate("booking") . " " . translate("details");
        $data['result_data'] = $result_data;
        $this->load->view('front/profile/get_event_slidepanel_details', $data);
    }

    /* Appointment Payment Details */

    public function appointment_payment_details($id = FALSE) {
        if ($id) {
            $fields = "";
            $fields .= "app_event_book.staff_id,app_event_book.event_booked_seat,app_event_book.addons_id,app_appointment_payment.*,CONCAT(app_users.first_name,' ',app_users.last_name) as vendor_name,app_users.company_name,app_event.type as event_type,app_event.title as event_name,CONCAT(app_customer.first_name,' ',app_customer.last_name) as customer_name";
            $join = array(
                array(
                    "table" => "app_users",
                    "condition" => "app_users.id=app_appointment_payment.vendor_id",
                    "jointype" => "INNER"),
                array(
                    "table" => "app_event",
                    "condition" => "(app_event.id=app_appointment_payment.event_id AND app_event.type='S')",
                    "jointype" => "INNER"),
                array(
                    "table" => "app_customer",
                    "condition" => "app_customer.id=app_appointment_payment.customer_id",
                    "jointype" => "INNER"),
                array(
                    "table" => "app_event_book",
                    "condition" => "app_event_book.id=app_appointment_payment.booking_id",
                    "jointype" => "INNER")
            );

            $payment_data = $this->model_customer->getData("app_appointment_payment", $fields, "app_appointment_payment.id=" . $id, $join, "id DESC")[0];

            $data['title'] = $page_title;
            $data['payment_data'] = $payment_data;
            $this->load->view('front/profile/event-payment-details', $data);
        }
    }

    public function event_payment_details($id = FALSE) {
        if ($id) {
            $fields = "";
            $fields .= "app_event_book.staff_id,app_event_book.event_booked_seat,app_event_book.addons_id,app_appointment_payment.*,CONCAT(app_users.first_name,' ',app_users.last_name) as vendor_name,app_users.company_name,app_event.type as event_type,app_event.title as event_name,CONCAT(app_customer.first_name,' ',app_customer.last_name) as customer_name";
            $join = array(
                array(
                    "table" => "app_users",
                    "condition" => "app_users.id=app_appointment_payment.vendor_id",
                    "jointype" => "INNER"),
                array(
                    "table" => "app_event",
                    "condition" => "(app_event.id=app_appointment_payment.event_id AND app_event.type='E')",
                    "jointype" => "INNER"),
                array(
                    "table" => "app_customer",
                    "condition" => "app_customer.id=app_appointment_payment.customer_id",
                    "jointype" => "INNER"),
                array(
                    "table" => "app_event_book",
                    "condition" => "app_event_book.id=app_appointment_payment.booking_id",
                    "jointype" => "INNER")
            );

            $payment_data = $this->model_customer->getData("app_appointment_payment", $fields, "app_appointment_payment.id=" . $id, $join, "id DESC")[0];

            $data['title'] = $page_title;
            $data['payment_data'] = $payment_data;
            $this->load->view('front/profile/event-payment-details', $data);
        }
    }

    public function payout_request_details($id) {
        $data['row'] = $this->model_customer->getData("app_payment_request", '*', "id=" . $id)[0];
        $this->load->view('vendor/payout_request', $data);
    }

    public function upload_summernote_image() {
        if ($_FILES['file']['name']) {
            if (!$_FILES['file']['error']) {
                $name = time();
                $ext = explode('.', $_FILES['file']['name']);
                $filename = $name . '.' . $ext[1];

                $destination = FCPATH . '/assets/uploads/event/' . $filename; //change this directory
                $location = $_FILES["file"]["tmp_name"];
                move_uploaded_file($location, $destination);
                echo base_url('assets/uploads/event/') . $filename; //change this URL
            } else {
                echo $message = 'Ooops!  Your upload triggered the following error:  ' . $_FILES['file']['error'];
            }
        }
    }

}
