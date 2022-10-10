<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Appointment extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_appointment');
        set_time_zone();
    }

    //show appointment page
    public function index($id = '0') {

        $event = isset($_REQUEST['event']) ? $_REQUEST['event'] : "";
        $vendor = isset($_REQUEST['vendor']) ? $_REQUEST['vendor'] : "";
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : "";
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : "";
        $appointment_type = isset($_REQUEST['appointment_type']) ? $_REQUEST['appointment_type'] : "U";

        $cond = " app_event_book.event_id >0 AND app_event_book.type='S' AND app_event_book.payment_status!='IN'";

        $vendor_condition = " ";

        if ($this->login_type == 'V') {
            $cond .= " AND app_event.created_by = $this->login_id";
            $vendor_condition .= "app_event.type='S' AND app_event.created_by=" . $this->login_id;
        } else {
            $vendor_condition .= "app_event.type='S'";
            $cond .= '';
        }
        if ((int) $id > 0) {
            $cond .= " AND app_event_book.event_id = '$id'";
        }

        if (isset($event) && $event > 0) {
            $cond .= " AND app_event_book.event_id=" . $event;
        }

        if (isset($vendor) && $vendor > 0) {
            $cond .= " AND app_event.created_by=" . $vendor;
        }

        if (isset($status) && $status != "") {
            $cond .= " AND app_event_book.status='" . $status . "'";
        }
        if (isset($type) && $type != "") {
            $cond .= " AND app_event.payment_type='" . $type . "'";
        }
        $cur_date = date("Y-m-d");

        if (isset($appointment_type) && $appointment_type == 'U') {
            $cond .= " AND app_event_book.start_date>='" . $cur_date . "' ";
        } else {
            $cond .= " AND app_event_book.start_date<'" . $cur_date . "'  ";
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
                "table" => "app_admin",
                "condition" => "app_event.created_by=app_admin.id",
                "jointype" => "INNER")
        );

        $appointment = $this->model_appointment->getData('app_event_book', 'app_event_book.*,app_admin.company_name,app_customer.first_name,app_customer.last_name,app_event.title,app_event.created_by,app_event.payment_type', $cond, $join, "app_event_book.start_date ASC,app_event_book.start_time ASC");
        $data['appointment_data'] = $appointment;

        $join_one = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_event.id=app_event_book.event_id',
                'jointype' => 'INNER'
            )
        );


        $join_two = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_event.id=app_event_book.event_id',
                'jointype' => 'inner'
            ), array(
                'table' => 'app_admin',
                'condition' => 'app_event.created_by=app_admin.id',
                'jointype' => 'inner'
            )
        );

        $appointment_event = $this->model_appointment->getData("app_event_book", "app_event_book.event_id,app_event.id as event_id,app_event.title as title", $vendor_condition, $join_one, "", "app_event.id");
        $appointment_vendor = $this->model_appointment->getData("app_event_book", "app_admin.company_name,app_admin.first_name,app_admin.last_name,app_admin.id", "", $join_two, "", "app_admin.id");

        $city_join = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'inner'
            )
        );
        $top_cities = $this->model_appointment->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');

        $data['appointment_data'] = $appointment;
        $data['appointment_vendor'] = $appointment_vendor;
        $data['topCity_List'] = $top_cities;
        $data['appointment_event'] = $appointment_event;

        $data['title'] = translate('manage') . " " . translate('appointment');
        $this->load->view('admin/appointment/appointment-list', $data);
    }

    public function change_appointment_status($id, $status) {
        if ((int) $id > 0) {
            //Get Booking Details
            $result_data = get_full_event_service_data_by_booking_id($id);

            $start_date = $result_data['start_date'];
            $start_time = $result_data['start_time'];
            $event_id = $result_data['event_id'];
            $customer_id = $result_data['customer_id'];
            $staff_id = (int) $result_data['staff_id'];
            $customer_data = get_customer_data($customer_id);
            $service_data = get_full_event_service_data($event_id);

            $multiple_slotbooking_allow = $service_data['multiple_slotbooking_allow'];
            $multiple_slotbooking_limit = $service_data['multiple_slotbooking_limit'];

            if ($status == 'A'):

                if ($staff_id > 0):
                    $get_data = $this->db->query("SELECT * FROM app_event_book WHERE id!=" . $id . " AND staff_id=" . $staff_id . " AND event_id=" . $event_id . " AND start_date='" . $start_date . "' AND start_time='" . $start_time . "' AND type='S' AND status IN ('A')")->result_array();
                else:
                    $get_data = $this->db->query("SELECT * FROM app_event_book WHERE id!=" . $id . " AND event_id=" . $event_id . " AND start_date='" . $start_date . "' AND start_time='" . $start_time . "' AND type='S' AND status IN ('A')")->result_array();
                endif;

                //Check if multiple booking allowed
                if ($multiple_slotbooking_allow == 'Y'):
                    if (count($get_data) <= $multiple_slotbooking_limit) {
                        //Approvr this
                        $this->model_appointment->update('', array('status' => strtoupper($status)), "id='$id'");

                        $name = ($customer_data['first_name']) . " " . ($customer_data['last_name']);
                        $subject = translate('appointment_booking') . " | " . translate('notification');
                        $define_param['to_name'] = $name;
                        $define_param['to_email'] = $customer_data['email'];

                        $parameter['name'] = $name;
                        $parameter['content'] = str_replace('{status}', print_appointment_status(strtoupper($status)), translate('booking_approve_reject'));
                        $parameter['appointment_date'] = get_formated_date(($start_date . " " . $start_time));
                        $parameter['service_data'] = $service_data;

                        if ($staff_id > 0):
                            $parameter['staff_data'] = get_staff_row_by_id($staff_id);
                        endif;

                        $html = $this->load->view("email_template/service_booking_approve", $parameter, true);

                        $this->session->set_flashdata('msg_class', 'success');
                        $this->session->set_flashdata('msg', str_replace('{status}', print_appointment_status(strtoupper($status)), translate('appointment_status')));

                        $this->sendmail->send($define_param, $subject, $html);
                    } else {

                        //Reject this booking as not available
                        $this->model_appointment->update('', array('status' => 'R'), "id='$id'");

                        $name = ($customer_data['first_name']) . " " . ($customer_data['last_name']);
                        $subject = translate('appointment_booking') . " | " . translate('notification');
                        $define_param['to_name'] = $name;
                        $define_param['to_email'] = $customer_data['email'];

                        $parameter['name'] = $name;
                        $parameter['content'] = str_replace('{status}', print_appointment_status(strtoupper('R')), translate('booking_approve_reject'));
                        $parameter['appointment_date'] = get_formated_date(($start_date . " " . $start_time));
                        $parameter['service_data'] = $service_data;

                        if ($staff_id > 0):
                            $parameter['staff_data'] = get_staff_row_by_id($staff_id);
                        endif;

                        $html = $this->load->view("email_template/service_booking_approve", $parameter, true);

                        $this->session->set_flashdata('msg_class', 'success');
                        $this->session->set_flashdata('msg', str_replace('{status}', print_appointment_status(strtoupper('R')), translate('appointment_status')));

                        $this->sendmail->send($define_param, $subject, $html);
                    }
                else:
                    if (count($get_data) > 0) {
                        //Reject this booking as not available
                        $this->model_appointment->update('', array('status' => 'R'), "id='$id'");

                        $name = ($customer_data['first_name']) . " " . ($customer_data['last_name']);
                        $subject = translate('appointment_booking') . " | " . translate('notification');
                        $define_param['to_name'] = $name;
                        $define_param['to_email'] = $customer_data['email'];

                        $parameter['name'] = $name;
                        $parameter['content'] = str_replace('{status}', print_appointment_status(strtoupper('R')), translate('booking_approve_reject'));
                        $parameter['appointment_date'] = get_formated_date(($start_date . " " . $start_time));
                        $parameter['service_data'] = $service_data;

                        if ($staff_id > 0):
                            $parameter['staff_data'] = get_staff_row_by_id($staff_id);
                        endif;

                        $html = $this->load->view("email_template/service_booking_approve", $parameter, true);

                        $this->session->set_flashdata('msg_class', 'success');
                        $this->session->set_flashdata('msg', str_replace('{status}', print_appointment_status(strtoupper('R')), translate('appointment_status')));

                        $this->sendmail->send($define_param, $subject, $html);
                    } else {
                        //Approvr this
                        $this->model_appointment->update('', array('status' => strtoupper($status)), "id='$id'");

                        $name = ($customer_data['first_name']) . " " . ($customer_data['last_name']);
                        $subject = translate('appointment_booking') . " | " . translate('notification');
                        $define_param['to_name'] = $name;
                        $define_param['to_email'] = $customer_data['email'];

                        $parameter['name'] = $name;
                        $parameter['content'] = str_replace('{status}', print_appointment_status(strtoupper($status)), translate('booking_approve_reject'));
                        $parameter['appointment_date'] = get_formated_date(($start_date . " " . $start_time));
                        $parameter['service_data'] = $service_data;

                        if ($staff_id > 0):
                            $parameter['staff_data'] = get_staff_row_by_id($staff_id);
                        endif;

                        $html = $this->load->view("email_template/service_booking_approve", $parameter, true);

                        $this->session->set_flashdata('msg_class', 'success');
                        $this->session->set_flashdata('msg', str_replace('{status}', print_appointment_status(strtoupper($status)), translate('appointment_status')));

                        $this->sendmail->send($define_param, $subject, $html);
                    }
                endif;
            else:
                $this->model_appointment->update('', array('status' => strtoupper($status)), "id='$id'");

                $name = ($customer_data['first_name']) . " " . ($customer_data['last_name']);
                $subject = translate('appointment_booking') . " | " . translate('notification');
                $define_param['to_name'] = $name;
                $define_param['to_email'] = $customer_data['email'];

                $parameter['name'] = $name;
                $parameter['content'] = str_replace('{status}', print_appointment_status(strtoupper($status)), translate('booking_approve_reject'));
                $parameter['appointment_date'] = get_formated_date(($start_date . " " . $start_time));
                $parameter['service_data'] = $service_data;

                if ($staff_id > 0):
                    $parameter['staff_data'] = get_staff_row_by_id($staff_id);
                endif;

                $html = $this->load->view("email_template/service_booking_approve", $parameter, true);
                $this->session->set_flashdata('msg_class', 'success');
                $this->session->set_flashdata('msg', str_replace('{status}', print_appointment_status(strtoupper($status)), translate('appointment_status')));
                $this->sendmail->send($define_param, $subject, $html);
            endif;
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
        }
    }

    public function change_event_booking_status($id, $status) {
        if ((int) $id > 0) {
            //Get Booking Details

            $result_data = get_full_event_service_data_by_booking_id($id);

            $start_date = $result_data['start_date'];
            $start_time = $result_data['start_time'];
            $event_id = $result_data['event_id'];
            $customer_id = $result_data['customer_id'];
            $staff_id = (int) $result_data['staff_id'];
            $customer_data = get_customer_data($customer_id);
            $service_data = get_full_event_service_data($event_id);
            $get_ticket_details_by_booking_id = get_ticket_details_by_booking_id($id);
            $update_status = $status;

            if ($status == 'A'):
                $booking_seat_error = FALSE;
                for ($i = 0; $i < count($get_ticket_details_by_booking_id); $i++):
                    if ((int) $get_ticket_details_by_booking_id[$i]['available_ticket'] < (int) $get_ticket_details_by_booking_id[$i]['total_ticket']):
                        $booking_seat_error = true;
                    endif;
                endfor;

                if ($booking_seat_error == true) {
                    //Reject this
                    $update_status = 'R';
                    $this->db->query("UPDATE app_event_ticket_type_booking SET status='R' WHERE booking_id=" . $id);
                } else {
                    $update_status = 'A';
                    for ($j = 0; $j < count($get_ticket_details_by_booking_id); $j++):
                        $this->db->query("UPDATE app_event_ticket_type SET available_ticket=available_ticket-" . $get_ticket_details_by_booking_id[$j]['total_ticket'] . " WHERE ticket_type_id=" . $get_ticket_details_by_booking_id[$j]['ticket_type_id']);
                    endfor;
                    $this->db->query("UPDATE app_event_ticket_type_booking SET status='A' WHERE booking_id=" . $id);
                }

            else:
                $update_status = 'R';
                $this->db->query("UPDATE app_event_ticket_type_booking SET status='R' WHERE booking_id=" . $id);
            endif;

            $this->model_appointment->update('', array('status' => strtoupper($update_status)), "id=" . $id);

            //Approve this
            $name = ($customer_data['first_name']) . " " . ($customer_data['last_name']);
            $subject = translate('appointment_booking') . " | " . translate('notification');
            $define_param['to_name'] = $name;
            $define_param['to_email'] = $customer_data['email'];

            $parameter['name'] = $name;
            $parameter['content'] = str_replace('{status}', print_appointment_status(strtoupper($update_status)), translate('booking_approve_reject'));
            $parameter['event_booking_seat'] = $result_data['event_booked_seat'];
            $parameter['event_data'] = $service_data;

            $html = $this->load->view("email_template/event_booking_approve", $parameter, true);
            $this->sendmail->send($define_param, $subject, $html);


            if ($status == 'R') {
                $this->session->set_flashdata('msg', str_replace('{status}', print_appointment_status(strtoupper($update_status)), translate('appointment_status')));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                if ($update_status == 'R') {
                    $this->session->set_flashdata('msg', translate('seat_not_available') . "<br/>" . str_replace('{status}', print_appointment_status(strtoupper($update_status)), translate('appointment_status')));
                    $this->session->set_flashdata('msg_class', 'failure');
                } else {
                    $this->session->set_flashdata('msg', str_replace('{status}', print_appointment_status(strtoupper($update_status)), translate('appointment_status')));
                    $this->session->set_flashdata('msg_class', 'success');
                }
            }
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
        }
    }

    public function send_remainder() {
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
        $id = $this->input->post('event_book_id', true);
        if ((int) $id > 0) {
            $cond = "app_event_book.id = '$id'";
            $res = $this->model_appointment->getData('', 'app_event_book.*,app_customer.first_name,app_customer.last_name,app_customer.email, app_event.title,app_event.description, app_event.created_by', $cond, $join)[0];

            $service_data = get_full_event_service_data($res['event_id']);

            $staff_id = $res['staff_id'];
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

    public function payment_history() {
        $fields = "";
        $fields .= "app_appointment_payment.*,CONCAT(app_admin.first_name,' ',app_admin.last_name) as vendor_name,app_admin.company_name,app_event.title as event_name,CONCAT(app_customer.first_name,' ',app_customer.last_name) as customer_name";
        $join = array(
            array(
                "table" => "app_admin",
                "condition" => "app_admin.id=app_appointment_payment.vendor_id",
                "jointype" => "INNER"),
            array(
                "table" => "app_event",
                "condition" => "(app_event.id=app_appointment_payment.event_id AND app_event.type='S')",
                "jointype" => "INNER"),
            array(
                "table" => "app_customer",
                "condition" => "app_customer.id=app_appointment_payment.customer_id",
                "jointype" => "INNER")
        );

        $payment_data = $this->model_appointment->getData("app_appointment_payment", $fields, "", $join, "id DESC");

        $data['title'] = translate('payout_request');
        $data['payment_data'] = $payment_data;
        $this->load->view('admin/appointment/payment_history', $data);
    }

    public function change_booking_slot($book_id, $event_id, $staff_id = null) {
        $folder_name = "admin";
        if ($this->login_type == 'V'):
            $folder_name = "vendor";
        endif;
        //show days page
        if ($book_id && $event_id) {
            $id = (int) $event_id;
            $join_data = array(
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
                    'condition' => 'app_admin.id=app_event.created_by',
                    'jointype' => 'left'
                ),
            );
            $select_value = "app_event.*,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,app_admin.company_name";
            $event = $this->model_appointment->getData("app_event", $select_value, "app_event.id=" . $id . " AND app_event.type='S'", $join_data);
            if (!isset($event) || isset($event) && count($event) == 0) {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url($folder_name . '/manage-appointment'));
            }

            //check booking
            $app_event_book = $this->model_appointment->getData("app_event_book", '*', "id=" . $book_id);
            if (count($app_event_book) == 0) {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url($folder_name . '/manage-appointment'));
            }

            $booking_staff_id = $app_event_book[0]['staff_id'];
            //Get Staff
            $staff_member_value = 0;

            if (isset($event[0]['staff']) && $event[0]['staff'] != ""):
                $staff = get_staff_by_id($event[0]['staff']);
                if ($staff_id > 0):
                    $staff_member_value = $staff_id;
                else:
                    if (isset($booking_staff_id) && $booking_staff_id > 0) {
                        $staff_member_value = $booking_staff_id;
                    } else {
                        $staff_member_value = $staff[0]['id'];
                    }
                endif;
                $data['staff_data'] = $staff;
            endif;


            if ($staff_member_value > 0) {
                $current_selected_staff = get_staff_by_id($id);
            } else {
                $current_selected_staff = get_VendorDetails($event[0]['created_by']);
            }

            $data['staff_member_value'] = $staff_member_value;
            $data['current_selected_staff'] = $current_selected_staff;

            $min = $event[0]['slot_time'];
            $allow_day = explode(",", $event[0]['days']);
            $date = date('d-m-Y');

            $month_ch = date("M", strtotime($date));
            $year = date("Y", strtotime($date));
            $day = date("d", strtotime($date));

            //Get Holiday List
            $get_holiday_list = get_holiday_list_by_vendor($event[0]['created_by']);

            //Display Current date data
            if (isset($date) && $date != "") {
                if (!in_array($date, $get_holiday_list)) {
                    $dayOfWeek = date('D', strtotime($date));
                    $todays_date = date('d', strtotime($date));

                    if (in_array($dayOfWeek, $allow_day)) {
                        $check = $this->_day_slots_check($todays_date . "-" . $month . "-" . $year, $min, $id, $staff_member_value);
                        $day_data[] = array(
                            "week" => get_day_of_week($dayOfWeek),
                            "month" => $month_ch,
                            "date" => $todays_date,
                            "check" => $check,
                            "full_date" => "$year-$month-$todays_date"
                        );
                    }
                }
            }


            // Calculate Next Days 
            $number = get_site_setting('slot_display_days');

            for ($k = 1; $k <= $number; $k++) {

                $datetime = new DateTime($date);
                $datetime->modify('+' . $k . ' day');
                $new_next_date = $datetime->format('d-m-Y');

                $dayOfWeeks = date('D', strtotime($new_next_date));
                $next_year = date('Y', strtotime($new_next_date));
                $next_month = date('m', strtotime($new_next_date));
                $updated_new_date = date('d', strtotime($new_next_date));

                if (!in_array($new_next_date, $get_holiday_list)) {
                    if (in_array($dayOfWeeks, $allow_day)) {
                        $checks = $this->_day_slots_check($updated_new_date . "-" . $next_month . "-" . $next_year, $min, $id, $staff_member_value);
                        $day_data[] = array(
                            "week" => get_day_of_week($dayOfWeeks),
                            "month" => date('M', strtotime($new_next_date)),
                            "date" => $updated_new_date,
                            "check" => $checks,
                            "full_date" => "$next_year-$next_month-$updated_new_date"
                        );
                    }
                }
            }


            $data['event_payment_price'] = number_format($event[0]['price'], 0);
            $data['event_payment_type'] = $event[0]['payment_type'];
            $data['booking_data'] = $app_event_book[0];
            $data['slot_time'] = $event[0]['slot_time'];
            $data['event_title'] = $event[0]['title'];
            $data['event_id'] = $event[0]['id'];
            $data['current_date'] = $date;
            $data['day_data'] = $day_data;
            $data['event_data'] = isset($event[0]) ? $event[0] : array();
            $data['book_id'] = $book_id;
            $data['title'] = translate('change') . " " . translate('booking') . " " . translate('time');
            $this->load->view('admin/service/days', $data);
        }
    }

    //check days available or not
    private function _day_slots_check($k, $min, $cur_event_id, $staff_member_id) {
        $event = $this->model_appointment->getData("app_event", "*", "status='A' AND slot_time='" . $min . "' AND id=" . $cur_event_id);

        $slot_time = $event[0]['slot_time'];
        $multiple_slotbooking_allow = $event[0]['multiple_slotbooking_allow'];
        $multiple_slotbooking_limit = $event[0]['multiple_slotbooking_limit'];

        $j = get_formated_time(strtotime("-" . $slot_time . "minute", strtotime($event[0]['end_time'])));
        $datetime1 = new DateTime($event[0]['start_time']);
        $datetime2 = new DateTime($event[0]['end_time']);
        $interval = $datetime1->diff($datetime2);
        $minute = $interval->format('%h') * 60;
        $time_array = array();
        for ($i = 1; $i <= $minute / $slot_time; $i++) {
            if ($i == 1) {
                $time_array[] = get_formated_time(strtotime($event[0]['start_time']));
            } else {
                $time_array[] = get_formated_time(strtotime("+" . $slot_time * ($i - 1) . " minute", strtotime($event[0]['start_time'])));
            }
        }
        if (($key = array_search(get_formated_time(strtotime($event[0]['end_time'])), $time_array)) !== false) {
            unset($time_array[$key]);
        }
        $start_date = date("Y-m-d", strtotime($k));
        if ($start_date == date("Y-m-d")) {
            foreach ($time_array as $key => $value) {
                if (get_formated_time(strtotime('H:i')) > get_formated_time(strtotime($value))) {
                    if (($key = array_search($value, $time_array)) !== false) {
                        unset($time_array[$key]);
                    }
                }
            }
        }
        $customer_id = (int) $this->session->userdata('CUST_ID');
        $book_month = date('m', strtotime($start_date));

        if ($staff_member_id > 0):
            $result = $this->model_appointment->getData("app_event_book", "start_time,slot_time", "start_date = '" . $start_date . "' AND staff_id=" . $staff_member_id);
        else:
            $result = $this->model_appointment->getData("app_event_book", "start_time,slot_time", "start_date = '" . $start_date . "' AND event_id=" . $cur_event_id);
        endif;


        if (isset($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                if ($min == $value['slot_time']) {

                    if ($staff_member_id > 0):
                        $multiple_boook_result = $this->model_appointment->getData("app_event_book", "start_time,slot_time", "start_time='" . $value['start_time'] . "' AND start_date = '" . $start_date . "' AND event_id=" . $cur_event_id . " AND staff_id=" . $staff_member_id . " AND status IN ('A')");
                    else:
                        $multiple_boook_result = $this->model_appointment->getData("app_event_book", "start_time,slot_time", "start_time='" . $value['start_time'] . "' AND start_date = '" . $start_date . "' AND event_id=" . $cur_event_id . " AND status IN ('A')");
                    endif;

                    if (isset($multiple_slotbooking_allow) && $multiple_slotbooking_allow == 'Y') {
                        if (count($multiple_boook_result) <= $multiple_slotbooking_limit) {
                            $time_array = $this->_check_slot($time_array, $value['start_time'], $value['slot_time'], $min);
                        } else {
                            $time_slot = date("H:i", strtotime($value['start_time']));
                            if (($key = array_search($time_slot, $time_array)) !== false) {
                                unset($time_array[$key]);
                            }
                        }
                    } else {
                        $time_slot = date("H:i", strtotime($value['start_time']));
                        if (($key = array_search($time_slot, $time_array)) !== false) {
                            unset($time_array[$key]);
                        }
                    }
                } else {
                    $time_array = $this->_check_slot($time_array, $value['start_time'], $value['slot_time'], $min);
                }
            }
            if (isset($time_array) && count($time_array) > 0) {
                return '1';
            }
            return '0';
        }
        return '1';
    }

    private function _check_slot($time_array, $start_time, $slot_time, $current_slot_time, $gap_time = 0) {
        if ($slot_time > $current_slot_time) {
            $min_time = get_formated_time(strtotime($start_time));

            $max_time = get_formated_time(strtotime("+" . $slot_time + $gap_time . " minute", strtotime($start_time)));
            foreach ($time_array as $key => $value) {
                if ($min_time <= $value && $max_time > $value) {
                    if (($key = array_search($value, $time_array)) !== false) {
                        unset($time_array[$key]);
                    }
                }
            }
        } else if ($slot_time < $current_slot_time) {
            $min_time = get_formated_time(strtotime($start_time));
            $max_time = get_formated_time(strtotime("+" . $slot_time + $gap_time . " minute", strtotime($start_time)));
            foreach ($time_array as $key => $value) {
                $current_end_time = get_formated_time(strtotime("+" . $current_slot_time . " minute", strtotime($value)));
                if ($value <= $min_time && $current_end_time > $min_time) {
                    if (($key = array_search($value, $time_array)) !== false) {
                        unset($time_array[$key]);
                    }
                }
            }
        }
        return $time_array;
    }

}

?>
