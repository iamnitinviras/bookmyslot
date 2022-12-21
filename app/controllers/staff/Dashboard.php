<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $this->load->model('model_staff');
        $this->load->model('model_dashboard');
        set_time_zone();
    }

    //show staff dashboard
    public function index() {
        $staff_id = (int) $this->session->userdata('staff_id');
        $data['title'] = translate('dashboard');

        $data['completed_appointment'] = $this->db->query("SELECT COUNT(id) as completed_appointment FROM app_event_book WHERE staff_id=" . $staff_id . " AND status='C'")->row('completed_appointment');
        $data['pending_appointment'] = $this->db->query("SELECT COUNT(id) as completed_appointment FROM app_event_book WHERE staff_id=" . $staff_id . " AND status='A'")->row('completed_appointment');
        $join = array(
            array(
                "table" => "app_customer",
                "condition" => "app_customer.id=app_event_book.customer_id",
                "jointype" => "LEFT"),
            array(
                "table" => "app_event",
                "condition" => "app_event.id=app_event_book.event_id",
                "jointype" => "LEFT")
        );
        $current_date = date('Y-m-d');
        $up_date = date('Y-m-d', strtotime(date('Y-m-d') . ' + 10 days'));
        $current_time = date('H:i:s');
        $cond = " app_event.type='S' AND app_event_book.status IN ('A') AND app_event_book.start_date >= '$current_date' AND app_event_book.start_date <= '$up_date' AND app_event_book.staff_id=" . $staff_id;
        $appointment = $this->model_dashboard->getData('app_event_book', 'app_event_book.*,app_customer.first_name,app_customer.last_name,app_event.title,app_event.payment_type,app_event.created_by', $cond, $join);


        $cond_pending = "app_event_book.status='P' AND app_event.type='S'  AND app_event_book.staff_id=" . $staff_id;
        $pending_appointment = $this->model_dashboard->getData('app_event_book', 'app_event_book.*,app_customer.first_name,app_customer.last_name,app_event.title,app_event.payment_type,app_event.created_by', $cond_pending, $join);
        $data['pending_appointment'] = $pending_appointment;

        $data['appointment_data'] = $appointment;
        $this->load->view('staff/dashboard', $data);
    }

    public function appointment() {
        $data['title'] = translate('appointment');

        $staff_id = (int) $this->session->userdata('staff_id');

        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : "";
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : "";
        $appointment_type = isset($_REQUEST['appointment_type']) ? $_REQUEST['appointment_type'] : "U";

        $cond = " app_event_book.event_id >0 AND app_event_book.type='S' AND app_event_book.payment_status!='IN'";

        $cond .= " AND app_event_book.staff_id=" . $staff_id;

        if (isset($status) && $status != "") {
            $cond .= " AND app_event_book.status='" . $status . "'";
        }
        if (isset($type) && $type != "") {
            $cond .= " AND app_event.payment_type='" . $type . "'";
        }
        $cur_date=date("Y-m-d");
        
        if(isset($appointment_type) && $appointment_type=='U'){
            $cond .= " AND app_event_book.start_date>='".$cur_date."' ";
        }else{
            $cond .= " AND app_event_book.start_date<'".$cur_date."'  ";
        }


        $join = array(
            array(
                "table" => "app_customer",
                "condition" => "app_customer.id=app_event_book.customer_id",
                "jointype" => "LEFT"),
            array(
                "table" => "app_event",
                "condition" => "app_event.id=app_event_book.event_id",
                "jointype" => "LEFT"),
            array(
                "table" => "app_users",
                "condition" => "app_event.created_by=app_users.id",
                "jointype" => "INNER")
        );

        $appointment = $this->model_dashboard->getData('app_event_book', 'app_event_book.*,app_users.company_name,app_customer.first_name,app_customer.last_name,app_event.title,app_event.created_by,app_event.payment_type', $cond, $join,"app_event_book.start_date ASC,app_event_book.start_time ASC");
        $data['appointment_data'] = $appointment;

        $this->load->view('staff/appointment', $data);
    }

    public function send_remainder() {

        $id = $this->input->post('event_book_id', true);
        $staff_id = (int) $this->session->userdata('staff_id');
        if ((int) $id > 0) {
            $cond = "app_event_book.id = '$id'";
            $join = array(
                array(
                    "table" => "app_customer",
                    "condition" => "app_customer.id=app_event_book.customer_id",
                    "jointype" => "LEFT"),
                array(
                    "table" => "app_event",
                    "condition" => "app_event.id=app_event_book.event_id",
                    "jointype" => "LEFT")
            );

            $res = $this->model_dashboard->getData('app_event_book', 'app_event_book.*,app_customer.first_name,app_customer.last_name,app_customer.email, app_event.title,app_event.description, app_event.created_by', $cond, $join)[0];

            $service_data = get_full_event_service_data($res['event_id']);

            $event_title = $res['title'];
            $name = ($res['first_name']) . " " . ($res['last_name']);
            $email = $res['email'];
            $description = $res['description'];
            $startdate = date("m/d/Y", strtotime($res['start_date']));
            $starttime = date("H:i a", strtotime($res['start_time']));

            $subject2 = translate('appointment_booking_reminder');
            $define_param2['to_name'] = $name;
            $define_param2['to_email'] = $email;

            $parameterv['name'] = $name;
            $parameterv['appointment_date'] = get_formated_date($startdate . " " . $starttime);
            $parameterv['service_data'] = $service_data;

            if ($staff_id > 0):
                $parameterv['staff_data'] = get_staff_row_by_id($staff_id);
            endif;
            
            $parameterv['price'] = translate('free');
            $html2 = $this->load->view("email_template/service_reminder", $parameterv, true);

            $send = $this->sendmail->send($define_param2, $subject2, $html2);
            if ($send) {
                $this->session->set_flashdata('msg', translate('remainder_mail_success'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $this->session->set_flashdata('msg', translate('remainder_mail_failure'));
                $this->session->set_flashdata('msg_class', 'failure');
            }
        }
    }

    public function change_appointment($id, $status) {
        if ((int) $id > 0) {
            $this->model_dashboard->update('app_event_book', array('status' => strtoupper($status)), "id='$id'");
            $this->session->set_flashdata('msg', str_replace('{status}', print_appointment_status(strtoupper($status)), translate('appointment_status')));
            $this->session->set_flashdata('msg_class', 'success');
        }
        echo 'true';
        exit;
    }

    function view_booking_details($id) {
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
        $event_data = $this->model_dashboard->getData("app_event_book", "app_event_book.* ,app_event_book.price as final_price,app_event.title as Event_title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,CONCAT(app_customer.first_name,' ',app_customer.last_name) as Customer_name,app_customer.phone as Customer_phone,app_customer.email as Customer_email,app_event_book.addons_id,app_event.price,app_users.company_name,app_event.description as Event_description, app_event.payment_type", $e_condition, $join);
        
        $data['event_data'] = $event_data;
        $this->load->view('staff/view_booking_details', $data);
    }

}

?>