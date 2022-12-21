<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customer extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_customer');
        set_time_zone();
    }

    //show customer list
    public function index() {
        $data['title'] = translate('manage') . " " . translate('customer');
        $order = "created_on DESC";
        $customer = $this->model_customer->getData("app_customer", "*", "", "", $order);
        $data['customer_data'] = $customer;
        $this->load->view('admin/customer/index', $data);
    }

    //delete customer
    public function delete_customer($id = null) {
        if ($id == null) {
            $id = $this->uri->segment(2);
        }
        $customer_check = $this->model_customer->getData("app_event_book", "*", "customer_id=" . $id);
        if (isset($customer_check) && count($customer_check) > 0) {
            $this->session->set_flashdata('msg', translate('customer_booked_no_delete'));
            $this->session->set_flashdata('msg_class', 'failure');
            echo 'false';
            exit;
        } else {
            $this->model_customer->delete('app_customer', 'id=' . $id);
            $this->session->set_flashdata('msg', translate('customer_deleted'));
            $this->session->set_flashdata('msg_class', 'success');
            echo 'true';
            exit;
        }
    }

    //change status of customer
    public function change_customer_tatus($id = null) {
        if ($id == null) {
            $id = $this->uri->segment(2);
        }
        $status = $this->input->post('status', true);
        $update = array(
            'status' => $status
        );
        $this->model_customer->update('app_customer', $update, 'id=' . $id);
        $msg = isset($status) && $status == "A" ? "Active" : "Inactive";
        $this->session->set_flashdata('msg', translate('customer_status'));
        $this->session->set_flashdata('msg_class', 'success');
        echo 'true';
        exit;
    }

    public function add_customer() {
        $data['title'] = translate('add_customer');
        $this->load->view('admin/customer/add_update', $data);
    }

    public function update_customer($id) {
        $cond = 'id=' . $id;

        $customer = $this->model_customer->getData("app_customer", "*", $cond);
        if (isset($customer[0]) && !empty($customer[0])) {
            $data['customer_data'] = $customer[0];
            $data['title'] = translate('update') . " " . translate('customer');
            $this->load->view('admin/customer/add_update', $data);
        } else {
            show_404();
        }
    }

    public function save_customer() {

        $user_id = (int) $this->input->post('customer_id');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[app_customer.email.id.' . $user_id . ']');
        $this->form_validation->set_rules('phone', 'Phone', 'is_unique[app_users.phone.id.' . $user_id . ']');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == false) {
            if ($user_id > 0) {
                $this->update_customer($user_id);
            } else {
                $this->add_customer();
            }
        } else {
            $this->load->helper('string');
            $password = random_string('alnum', 8);
            $data = array(
                'first_name' => trim($this->input->post('first_name')),
                'last_name' => trim($this->input->post('last_name')),
                'email' => trim($this->input->post('email')),
                'password' => md5(trim($password)),
                'phone' => $this->input->post('phone'),
                'status' => 'A',
                'created_on' => date("Y-m-d H:i:s")
            );
            if ($user_id > 0) {
                $this->model_customer->update('app_customer', $data, 'id=' . $user_id);
                $this->session->set_flashdata('msg', translate('customer_update'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $name = (trim($this->input->post('first_name'))) . " " . (trim($this->input->post('last_name')));
                $hidenuseremail = $this->input->post('email');
                $this->model_customer->insert('app_customer', $data);

                //Send email to customer
                $subject = translate('customer') . " | " . translate('account_registration');
                $define_param['to_name'] = $name;
                $define_param['to_email'] = $hidenuseremail;

                $parameter['NAME'] = $name;
                $parameter['LOGIN_URL'] = base_url('login');
                $parameter['EMAIL'] = $hidenuseremail;
                $parameter['PASSWORD'] = $password;

                $html = $this->load->view("email_template/registration_admin", $parameter, true);
                $send = $this->sendmail->send($define_param, $subject, $html);

                $this->session->set_flashdata('msg', translate('customer_insert'));
                $this->session->set_flashdata('msg_class', 'success');
            }
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/customer', 'redirect');
        }
    }

    public function customer_booking($id) {
        
        $data['title'] = translate('booking') . " " . translate('customer');

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
                'table' => 'app_customer',
                'condition' => 'app_customer.id=app_event_book.customer_id',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_users',
                'condition' => 'app_users.id=app_event.created_by',
                'jointype' => 'left'
            )
        );

        $s_condition = "app_event.type = 'S' AND app_event_book.customer_id=" . $id;
        $appointment = $this->model_customer->getData("app_event_book", "app_event_book.*,app_users.id as aid ,app_event_book.price as final_price,app_users.company_name,app_event.title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,app_customer.first_name,app_customer.last_name,app_customer.phone,app_event.price,app_users.first_name,app_users.last_name,app_users.company_name,app_event.image,app_event.description as event_description, app_event.payment_type", $s_condition, $join);
        $data['service_appointment_data'] = $appointment;
        $e_condition = "app_event.type = 'E' AND app_event_book.customer_id=" . $id;
        $e_appointment = $this->model_customer->getData("app_event_book", "app_event_book.*,app_users.id as aid ,app_event_book.price as final_price,app_users.company_name,app_event.title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,app_customer.first_name,app_customer.last_name,app_customer.phone,app_event.price,app_users.first_name,app_users.last_name,app_users.company_name,app_event.image,app_event.description as event_description, app_event.payment_type", $e_condition, $join);
        $data['event_appointment_data'] = $e_appointment;
        $this->load->view('admin/customer/booking', $data);
    }

    function view_event_booking_details($id) {
        $data['title'] = translate('view') . " " . ('booking');
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
                'table' => 'app_customer',
                'condition' => 'app_customer.id=app_event_book.customer_id',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_users',
                'condition' => 'app_users.id=app_event.created_by',
                'jointype' => 'left'
            )
        );

        $e_condition = "app_event_book.id=" . $id;
        $event_data = $this->model_customer->getData("app_event_book", "app_event_book.* ,app_event_book.price as final_price,app_event.title as Event_title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,CONCAT(app_customer.first_name,' ',app_customer.last_name) as Customer_name,app_customer.phone as Customer_phone,app_customer.email as Customer_email,app_event.price,app_users.company_name,app_event.description as Event_description, app_event.payment_type", $e_condition, $join);
        $data['event_data'] = $event_data;
        $this->load->view('admin/customer/view_booking_details', $data);
    }

}

?>