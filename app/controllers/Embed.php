<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Embed extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('model_front');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    public function check_token($token) {
        $token_user_Res = $this->model_front->getData('app_admin', '*', 'token="' . $token . '"');
        if (isset($token_user_Res) && count($token_user_Res) > 0) {
            return $token_user_Res[0];
        } else {
            redirect('something-wrong');
        }
    }

    public function something_wrong() {
        $data['title'] = get_CompanyName();
        $this->load->view('embed/wrong', $data);
    }

    //get vendor services
    public function services($token) {
        $token_user_Res = $this->check_token($token);
        $user_id = $token_user_Res['id'];

        $join = array(
            array(
                'table' => 'app_event_category',
                'condition' => 'app_event_category.id=app_event.category_id',
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
                'table' => 'app_admin',
                'condition' => 'app_admin.id=app_event.created_by',
                'jointype' => 'left'
            ),
        );
        $cond = 'app_event.status="A" AND app_event.type="S" AND app_event.created_by = ' . $user_id;
        $select = 'app_event.*,';
        $select .= 'app_event.id as event_id,app_event_category.title as category_title,app_city.city_title, app_location.loc_title,app_admin.profile_image, app_admin.first_name,';
        $select .= 'app_admin.first_name,app_admin.last_name,app_admin.company_name,app_admin.email,app_admin.profile_image';
        $total_service = $this->model_front->getData("app_event", $select, $cond, $join, '', 'app_event.id');

        $data['total_service'] = $total_service;
        $data['title'] = translate('service');
        $this->load->view('embed/services', $data);
    }

    //service details
    public function services_details($slug, $token, $event_id) {

        $token_user_Res = $this->check_token($token);
        $user_id = $token_user_Res['id'];
        $event_id = (int) $event_id;

        if (isset($event_id) && $event_id > 0) {
            /* Get Event List */
            $user_id = $this->session->userdata('CUST_ID');
            $join = array(
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
                )
            );
            $event = $this->model_front->getData("app_event", "app_event.*,app_location.loc_title,app_city.city_title,app_event_category.title as category_title", "app_event.id=" . $event_id . " AND app_event.type='S'", $join);

            if (isset($event) && count($event) > 0) {

                if (isset($event[0]['created_by']) && $event[0]['created_by'] > 0) {
                    $event_book = $this->model_front->getData("app_event_book", "id", "event_id='$event_id'");
                    $admin_data = $this->model_front->getData("app_admin", "*", "id=" . $event[0]['created_by']);

                    //all rating data
                    $rjoin = array(array(
                            'table' => 'app_customer',
                            'condition' => 'app_customer.id=app_vendor_review.customer_id',
                            'jointype' => 'inner'
                        ),
                        array(
                            'table' => 'app_event',
                            'condition' => 'app_vendor_review.event_id=app_event.id',
                            'jointype' => 'INNER'
                    ));
                    $vendor_rating_data = $this->model_front->getData('app_vendor_review', 'app_event.title, app_customer.first_name, app_customer.last_name, app_customer.profile_image, app_vendor_review.*', 'app_vendor_review.event_id = ' . $event_id, $rjoin);

                    $data['event_data'] = $event[0];
                    $data['event_book'] = count($event_book);
                    $data['event_rating'] = $vendor_rating_data;
                    $data['admin_data'] = $admin_data[0];

                    $data['title'] = isset($event[0]['title']) ? ($event[0]['title']) : translate('event_details');
                    $data['meta_description'] = isset($event[0]['seo_description']) ? $event[0]['seo_description'] : '';
                    $data['meta_keyword'] = isset($event[0]['seo_keyword']) ? $event[0]['seo_keyword'] : '';
                    $data['meta_og_img'] = isset($event[0]['seo_og_image']) ? $event[0]['seo_og_image'] : '';

                    $this->load->view('embed/service-details', $data);
                } else {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('invalid_request'));
                    redirect(base_url('vendor-services/' . $token));
                }
            } else {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url('vendor-services/' . $token));
            }
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url('vendor-services/' . $token));
        }
    }

    //show   days page
    public function day_slots($token, $id, $staff_id = null) {
        $token_user_Res = $this->check_token($token);

        $staff_id = (int) $staff_id;
        $id = (int) $id;


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
        $event = $this->model_front->getData("app_event", $select_value, "app_event.id=" . $id . " AND app_event.type='S'", $join_data);
        if (!isset($event) || isset($event) && count($event) == 0) {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        }

        $min = $event[0]['slot_time'];
        $allow_day = explode(",", $event[0]['days']);
        $date = date('d-m-Y');
        $next_date = date('d-m-Y', strtotime(date('d-m-Y', strtotime("+30 days"))));
        $month = date("m", strtotime($date));

        //Get Staff
        $staff_member_value = 0;
        if (isset($event[0]['staff']) && $event[0]['staff'] != ""):
            $staff = get_staff_by_id($event[0]['staff']);

            if ($staff_id > 0):
                $staff_member_value = $staff_id;
            else:
                $staff_member_value = $staff[0]['id'];
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
                        "week" => $dayOfWeek,
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
                        "week" => $dayOfWeeks,
                        "month" => date('M', strtotime($new_next_date)),
                        "date" => $updated_new_date,
                        "check" => $checks,
                        "full_date" => "$next_year-$next_month-$updated_new_date"
                    );
                }
            }
        }

        //get user details
        $customer_id_sess = (int) $this->session->userdata('CUST_ID');
        $customer = $this->model_front->getData("app_customer", "id,first_name,last_name,email", "id=" . $customer_id_sess);
        $app_service_addons = $this->model_front->getData("app_service_addons", "*", "event_id=" . $id);

        $data['event_payment_price'] = number_format($event[0]['price'], 0);
        $data['event_payment_type'] = $event[0]['payment_type'];
        $data['slot_time'] = $event[0]['slot_time'];
        $data['event_title'] = $event[0]['title'];
        $data['event_id'] = $event[0]['id'];
        $data['current_date'] = $date;
        $data['day_data'] = $day_data;
        $data['type'] = 'S';
        $data['app_service_addons'] = $app_service_addons;
        $data['event_data'] = isset($event[0]) ? $event[0] : array();
        $data['customer_data'] = isset($customer[0]) ? $customer[0] : array();
        $data['title'] = translate('book_your_appointment');
        $this->load->view('embed/days', $data);
    }

    public function time_slots($min, $update = NULL) {

        $customer_id = (int) $this->session->userdata('CUST_ID');
        $eventid = $this->input->post('eventid');
        $staff_id = (int) $this->input->post('staff');
        $event = $this->model_front->getData("app_event", "*", "id = $eventid AND slot_time='$min'");
        $slot_time = $event[0]['slot_time'];
        $multiple_slotbooking_allow = $event[0]['multiple_slotbooking_allow'];
        $multiple_slotbooking_limit = $event[0]['multiple_slotbooking_limit'];

        $j = date("h:i a", strtotime("-" . $slot_time . "minute", strtotime($event[0]['end_time'])));
        $datetime1 = new DateTime($event[0]['start_time']);
        $datetime2 = new DateTime($event[0]['end_time']);
        $gap_time = ($event[0]['padding_time']);

        $interval = $datetime1->diff($datetime2);
        $extra_minute = $interval->format('%i');
        $minute = $interval->format('%h') * 60 + $extra_minute;

        $time_array = array();
        $var_gap_time = 1;
        for ($i = 1; $i <= $minute / ($slot_time + $gap_time); $i++) {
            if ($i == 1) {
                $time_array[] = date("H:i", strtotime($event[0]['start_time']));
            } else {
                $time_array[] = date("H:i", strtotime("+" . (($slot_time * ($i - 1)) + $gap_time * $var_gap_time) . " minute", strtotime($event[0]['start_time'])));
                $var_gap_time++;
            }
        }

        if (($key = array_search(date("H:i", strtotime($event[0]['end_time'])), $time_array)) !== false) {
            unset($time_array[$key]);
        }
        $date = $this->input->post('date');
        if (date('Y-m-d', strtotime($date)) == date("Y-m-d")) {
            $current_time = date("H:i");
            foreach ($time_array as $key => $value) {
                $time_slot = date("H:i", strtotime($value));
                if ($current_time > $value) {
                    if (($key = array_search($value, $time_array)) !== false) {
                        unset($time_array[$key]);
                    }
                }
            }
        }

        if ($staff_id > 0):
            $result = $this->model_front->getData("app_event_book", "start_time,slot_time", "start_date = '$date' AND staff_id=" . $staff_id . " AND status IN ('A')");
        else:
            $result = $this->model_front->getData("app_event_book", "start_time,slot_time", "start_date = '$date' AND event_id=" . $eventid . " AND status IN ('A')");
        endif;

        if (isset($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                if ($min == $value['slot_time']) {

                    if ($staff_id > 0):
                        $multiple_boook_result = $this->model_front->getData("app_event_book", "start_time,slot_time", "start_time='" . $value['start_time'] . "' AND start_date = '" . $date . "' AND staff_id=" . $staff_id . " AND event_id=" . $eventid . " AND status IN ('A')");
                    else:
                        $multiple_boook_result = $this->model_front->getData("app_event_book", "start_time,slot_time", "start_time='" . $value['start_time'] . "' AND start_date = '" . $date . "' AND event_id=" . $eventid . " AND status IN ('A')");
                    endif;


                    if (isset($multiple_slotbooking_allow) && $multiple_slotbooking_allow == 'Y') {
                        if (count($multiple_boook_result) < $multiple_slotbooking_limit) {
                            $time_array = $this->_check_slot($time_array, $value['start_time'], $value['slot_time'], $min, $gap_time);
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
                    $time_array = $this->_check_slot($time_array, $value['start_time'], $value['slot_time'], $min, $gap_time);
                }
            }
        }


        $html = '<div class="row">';
        $is_exist_morning = $is_exist_noon = 0;

        if (isset($time_array) && !empty($time_array)) {
            foreach ($time_array as $key => $value) {
                $end_time = strtotime($value . ' +' . $slot_time . '  minute');
                $end_time_format = get_formated_time($end_time);


                $date_check = date("H", strtotime($value));
                $html .= '<div class="col-md-12">';

                if ($is_exist_morning == 0 && $date_check < 12) {
                    $html .= '<div class="time-info"> <div>' . translate('morning') . '</div> </div>';
                    $is_exist_morning = 1;
                }
                if ($is_exist_noon == 0 && $date_check >= 12) {
                    $html .= '<div class="time-info"> <div>' . translate('noon') . '</div> </div>';
                    $is_exist_noon = 1;
                }


                $html .= '<div class="col-md-12 mt-2 text-center"><a class="time-button" onclick="confirm_time(this);"><span id="time-select">' . get_formated_time(strtotime($value)) . ' </span> - <span>' . $end_time_format . '</span></a>';
                $html .= '<a class="time-button w-49 time-respo ml-2 time-confirm hide-confirm" onclick="confirm_form(this);" data-price=' . get_price($eventid, $date) . ' data-ddate="' . get_formated_date($date, "N") . '" data-date="' . $date . '" data-dtime="' . get_formated_time(strtotime($value)) . '" data-time="' . date('H:i:s', strtotime($value)) . '"> ' . translate('confirm') . '</a>';


                $html .= '</div></div>';
            }
        } else {
            $html .= '<div class="col-md-12 text-center error">' . translate('booking_time_expired') . '</div>';
        }
        $html .= '</div>';
        echo $html;
        exit;
    }

    //check days available or not
    private function _day_slots_check($k, $min, $cur_event_id, $staff_member_id) {
        $event = $this->model_front->getData("app_event", "*", "status='A' AND slot_time='" . $min . "' AND id=" . $cur_event_id);

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
            $result = $this->model_front->getData("app_event_book", "start_time,slot_time", "start_date = '" . $start_date . "' AND staff_id=" . $staff_member_id);
        else:
            $result = $this->model_front->getData("app_event_book", "start_time,slot_time", "start_date = '" . $start_date . "' AND event_id=" . $cur_event_id);
        endif;


        if (isset($result) && count($result) > 0) {
            foreach ($result as $key => $value) {
                if ($min == $value['slot_time']) {

                    if ($staff_member_id > 0):
                        $multiple_boook_result = $this->model_front->getData("app_event_book", "start_time,slot_time", "start_time='" . $value['start_time'] . "' AND start_date = '" . $start_date . "' AND event_id=" . $cur_event_id . " AND staff_id=" . $staff_member_id . " AND status IN ('A')");
                    else:
                        $multiple_boook_result = $this->model_front->getData("app_event_book", "start_time,slot_time", "start_time='" . $value['start_time'] . "' AND start_date = '" . $start_date . "' AND event_id=" . $cur_event_id . " AND status IN ('A')");
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

    function generate_user($data) {
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $email = $data['email'];
        $phone = $data['phone'];

        $customer = $this->model_front->getData("app_customer", "id,first_name,last_name,email", "email='" . $email . "'");
        if (count($customer) > 0) {
            return $customer[0];
        } else {
            $this->load->helper('string');
            $password = random_string('alnum', 8);
            $data = array(
                'first_name' => trim($first_name),
                'last_name' => trim($last_name),
                'email' => trim($email),
                'password' => md5(trim($password)),
                'phone' => $phone,
                'status' => 'A',
                'created_on' => date("Y-m-d H:i:s")
            );

            $cust_id = $this->model_front->insert('app_customer', $data);
            if ($cust_id) {
                //Send email to customer
                $subject = translate('customer') . " | " . translate('account_registration');
                $define_param['to_name'] = $first_name . " " . $last_name;
                $define_param['to_email'] = $email;

                $parameter['NAME'] = $first_name . " " . $last_name;
                $parameter['LOGIN_URL'] = base_url('login');
                $parameter['EMAIL'] = $email;
                $parameter['PASSWORD'] = $password;

                $html = $this->load->view("email_template/registration_admin", $parameter, true);
                $send = $this->sendmail->send($define_param, $subject, $html);
            }

            $customer = $this->model_front->getData("app_customer", "id,first_name,last_name,email", "id=" . $cust_id)[0];
            return $customer;
        }
    }

    //add booking for free
    public function booking_free() {


        //Request post data
        $appointment_id = (int) $this->input->post('appointment_id');
        $description = $this->input->post('description', true);
        $slot_time = $this->input->post('user_slot_time');
        $event_id = (int) $this->input->post('event_id');
        $bookdate = $this->input->post('user_datetime');
        $event_payment_type = $this->input->post('event_payment_type');
        $event_booking_seat = $this->input->post('total_booked_seat');
        $vendor_token = $this->input->post('vendor_token');
        $staff_member_id = $this->input->post('staff_member_id');

        //Check valid event id
        if ($event_id > 0):
            $service_data = get_full_event_service_data($event_id);

            if (isset($service_data['id']) && $service_data['id'] > 0 && $service_data['type'] == 'S'):

                $event_title = isset($service_data['title']) ? ($service_data['title']) : '';
                $vendor_id = isset($service_data['created_by']) ? ($service_data['created_by']) : '';
                $type = $service_data['type'];
                $total_seat = $service_data['total_seat'];

                $check_multiple_book_status = check_multiple_book_status(date("Y-m-d", strtotime($bookdate)), date("H:i:s", strtotime($bookdate)), $type, $event_id, $staff_member_id);
                if ($check_multiple_book_status == FALSE) {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('not_allowed_booking'));
                    redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
                } else {

                    $customer_data = $this->generate_user($_POST);

                    $insert['customer_id'] = $customer_data['id'];
                    $insert['description'] = $description;
                    $insert['slot_time'] = $slot_time;
                    $insert['event_id'] = $event_id;
                    $insert['event_booked_seat'] = $event_booking_seat;
                    $insert['start_date'] = date("Y-m-d", strtotime($bookdate));
                    $insert['start_time'] = date("H:i:s", strtotime($bookdate));
                    $insert['payment_status'] = 'S';
                    $insert['status'] = 'P';
                    $insert['staff_id'] = $staff_member_id;
                    $insert['created_on'] = date("Y-m:d H:i:s");
                    $insert['type'] = $type;
                    $book = $this->model_front->insert("app_event_book", $insert);

                    //Send email to customer
                    $name = ($customer_data['first_name']) . " " . ($customer_data['last_name']);
                    $subject = translate('appointment_booking');
                    $define_param['to_name'] = ($customer_data['first_name']) . " " . ($customer_data['last_name']);
                    $define_param['to_email'] = $customer_data['email'];

                    $parameter['name'] = $name;
                    $parameter['appointment_date'] = get_formated_date(($bookdate));
                    $parameter['service_data'] = $service_data;
                    $parameter['price'] = translate('free');

                    if ($staff_member_id > 0):
                        $parameter['staff_data'] = get_staff_row_by_id($staff_member_id);
                    endif;

                    $html = $this->load->view("email_template/service_booking_customer", $parameter, true);
                    $this->sendmail->send($define_param, $subject, $html);


                    //Send email to vendor
                    $vendor_name = ($service_data['first_name']) . " " . ($service_data['last_name']);
                    $vendor_email = $service_data['email'];
                    $subject2 = $service_data['title'] . ' | ' . translate('appointment_booking');
                    $define_param2['to_name'] = $vendor_name;
                    $define_param2['to_email'] = $vendor_email;

                    $parameterv['name'] = $vendor_name;
                    $parameterv['appointment_date'] = get_formated_date(($bookdate));
                    $parameterv['service_data'] = $service_data;
                    if ($staff_member_id > 0):
                        $parameterv['staff_data'] = get_staff_row_by_id($staff_member_id);
                    endif;
                    $parameterv['customer_data'] = $customer_data;
                    $parameterv['price'] = translate('free');
                    $html2 = $this->load->view("email_template/service_booking_vendor", $parameterv, true);
                    $this->sendmail->send($define_param2, $subject2, $html2);

                    if ($staff_member_id > 0):
                        // Send email to staff if selected
                        $staff_e_data = get_staff_row_by_id($staff_member_id);
                        $staff_name = ($staff_e_data['first_name']) . " " . ($staff_e_data['last_name']);
                        $staff_email = $staff_e_data['email'];

                        $subject2 = $service_data['title'] . ' | ' . translate('appointment_booking');
                        $define_param2['to_name'] = $staff_name;
                        $define_param2['to_email'] = $staff_email;

                        $parameters['name'] = $staff_name;
                        $parameters['appointment_date'] = get_formated_date(($bookdate));
                        $parameters['service_data'] = $service_data;
                        $parameters['customer_data'] = $customer[0];
                        $parameters['price'] = translate('free');

                        $html2 = $this->load->view("email_template/service_booking_vendor", $parameters, true);
                        $this->sendmail->send($define_param2, $subject2, $html2);
                    endif;

                    $this->session->set_flashdata('msg', translate('booking_pending'));
                    $this->session->set_flashdata('msg_class', 'info');
                    redirect('eappointment-success/' . $book . '/' . $vendor_token);
                }
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url('eservices/' . $vendor_token));
            endif;
        else:
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url('eservices/' . $vendor_token));
        endif;
    }

    //add booking by cash method
    public function booking_oncash() {
        $appointment_id = (int) $this->input->post('appointment_id');
        $main_amount = $this->input->post('main_amount');
        $description = $this->input->post('description');
        $slot_time = $this->input->post('user_slot_time');
        $event_id = (int) $this->input->post('event_id');
        $bookdate = $this->input->post('user_datetime');
        $event_payment_type = $this->input->post('event_payment_type');
        $event_booking_seat = $this->input->post('total_booked_seat');
        $staff_member_id = $this->input->post('staff_member_id');
        $add_ons_hidden_id = $this->input->post('add_ons_id') ? $this->input->post('add_ons_id') : array();
        $vendor_token = $this->input->post('vendor_token');

        $addons_id = "";
        if (count($add_ons_hidden_id) > 0) {
            $addons_id = implode(',', $add_ons_hidden_id);
        }

        //Calculate addons price
        $add_ons_price = 0;
        foreach ($add_ons_hidden_id as $val):
            $add_ons_price = $add_ons_price + get_addons_price_by_id($val);
        endforeach;

        //Check valid event id
        if (isset($event_id) && $event_id > 0):
            $service_data = get_full_event_service_data($event_id);
            if (isset($service_data['id']) && $service_data['id'] > 0 && $service_data['type'] == 'S'):
                $event_title = isset($service_data['title']) ? ($service_data['title']) : '';
                $type = $service_data['type'];
                $total_seat = $service_data['total_seat'];

                $check_multiple_book_status = check_multiple_book_status(date("Y-m-d", strtotime($bookdate)), date("H:i:s", strtotime($bookdate)), $type, $event_id, $staff_member_id);
                if ($check_multiple_book_status == FALSE) {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('not_allowed_booking'));
                    redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
                } else {
                    //discount data
                    $discount_coupon = $this->input->post('discount_coupon');
                    $discount_coupon_id = base64_decode($this->input->post('discount_coupon_id'));
                    $final_price = isset($service_data['price']) ? $service_data['price'] : 0;

                    if (isset($discount_coupon_id) && $discount_coupon_id > 0 && isset($discount_coupon)) {
                        $final_price = get_discount_price($event_id, $discount_coupon, $discount_coupon_id, $bookdate);
                    } else {
                        $final_price = get_discount_price_by_date($event_id, $bookdate);
                    }
                    //add add_ons value in final amount 
                    $final_price = $final_price + $add_ons_price;

                    $vendor_amount = get_vendor_amount($final_price, $service_data['created_by']);
                    $admin_amount = get_admin_amount($final_price);

                    $customer_data = $this->generate_user($_POST);

                    $insert['customer_id'] = $customer_data['id'];
                    $insert['description'] = $description;
                    $insert['slot_time'] = $slot_time;
                    $insert['event_id'] = $event_id;
                    $insert['event_booked_seat'] = $event_booking_seat;
                    $insert['start_date'] = date("Y-m-d", strtotime($bookdate));
                    $insert['start_time'] = date("H:i:s", strtotime($bookdate));

                    $insert['payment_status'] = 'P';
                    $insert['price'] = $final_price;
                    $insert['vendor_price'] = $vendor_amount;
                    $insert['addons_id'] = $addons_id;
                    $insert['admin_price'] = $admin_amount;
                    $insert['status'] = 'P';
                    $insert['staff_id'] = $staff_member_id;
                    $insert['type'] = $type;
                    $insert['created_on'] = date("Y-m:d H:i:s");
                    $book = $this->model_front->insert("app_event_book", $insert);

                    $data['customer_id'] = $customer_data['id'];
                    $data['vendor_id'] = $service_data['created_by'];
                    $data['event_id'] = $event_id;
                    $data['booking_id'] = $book;
                    $data['payment_id'] = '';
                    $data['customer_payment_id'] = '';
                    $data['transaction_id'] = '';
                    $data['payment_price'] = $final_price;
                    $data['vendor_price'] = $vendor_amount;
                    $data['admin_price'] = $admin_amount;
                    $data['failure_code'] = '';
                    $data['failure_message'] = '';
                    $data['payment_method'] = translate('on_cash');
                    $data['payment_status'] = 'P';
                    $data['created_on'] = date('Y-m-d H:i:s');

                    $this->model_front->insert('app_appointment_payment', $data);

                    //Send email to customer
                    $name = ($customer_data['first_name']) . " " . ($customer_data['last_name']);
                    $subject = translate('appointment_booking');
                    $define_param['to_name'] = ($customer_data['first_name']) . " " . ($customer_data['last_name']);
                    $define_param['to_email'] = $customer_data['email'];

                    $parameter['name'] = $name;
                    if ($staff_member_id > 0):
                        $parameter['staff_data'] = get_staff_row_by_id($staff_member_id);
                    endif;
                    $parameter['appointment_date'] = get_formated_date(($bookdate));
                    $parameter['service_data'] = $service_data;
                    $parameter['price'] = price_format($final_price);
                    $html = $this->load->view("email_template/service_booking_customer", $parameter, true);
                    $this->sendmail->send($define_param, $subject, $html);
                    //Send email to vendor

                    $vendor_name = ($service_data['first_name']) . " " . ($service_data['last_name']);
                    $vendor_email = $service_data['email'];
                    $subject2 = $service_data['title'] . ' | ' . translate('appointment_booking');
                    $define_param2['to_name'] = $vendor_name;
                    $define_param2['to_email'] = $vendor_email;

                    $parameterv['name'] = $vendor_name;
                    if ($staff_member_id > 0):
                        $parameterv['staff_data'] = get_staff_row_by_id($staff_member_id);
                    endif;
                    $parameterv['appointment_date'] = get_formated_date(($bookdate));
                    $parameterv['service_data'] = $service_data;
                    $parameterv['customer_data'] = $customer_data;
                    $parameterv['price'] = price_format($final_price);
                    $html2 = $this->load->view("email_template/service_booking_vendor", $parameterv, true);
                    $this->sendmail->send($define_param2, $subject2, $html2);

                    if ($staff_member_id > 0):
                        // Send email to staff if selected
                        $staff_e_data = get_staff_row_by_id($staff_member_id);
                        $staff_name = ($staff_e_data['first_name']) . " " . ($staff_e_data['last_name']);
                        $staff_email = $staff_e_data['email'];

                        $subject2 = $service_data['title'] . ' | ' . translate('appointment_booking');
                        $define_param2['to_name'] = $staff_name;
                        $define_param2['to_email'] = $staff_email;

                        $parameters['name'] = $staff_name;
                        $parameters['appointment_date'] = get_formated_date(($bookdate));
                        $parameters['service_data'] = $service_data;
                        $parameters['customer_data'] = $customer_data;
                        $parameters['price'] = price_format($final_price);

                        $html2 = $this->load->view("email_template/service_booking_vendor", $parameters, true);
                        $this->sendmail->send($define_param2, $subject2, $html2);
                    endif;

                    $this->session->set_flashdata('msg', translate('booking_pending'));
                    $this->session->set_flashdata('msg_class', 'info');
                    redirect('eappointment-success/' . $book . '/' . $vendor_token);
                }
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url('eservices/' . $vendor_token));
            endif;
        else:
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url('eservices/' . $vendor_token));
        endif;
    }

    //add by stripe method
    public function booking_stripe() {

        //Request post data
        $appointment_id = (int) $this->input->post('appointment_id');
        $description = $this->input->post('description');
        $slot_time = $this->input->post('user_slot_time');
        $event_id = (int) $this->input->post('event_id');
        $bookdate = $this->input->post('user_datetime');
        $event_payment_type = $this->input->post('event_payment_type');
        $staff_member_id = $this->input->post('staff_member_id');
        $add_ons_hidden_id = $this->input->post('add_ons_id') ? $this->input->post('add_ons_id') : array();
        $vendor_token = $this->input->post('vendor_token');

        $addons_id = "";
        if (count($add_ons_hidden_id) > 0) {
            $addons_id = implode(',', $add_ons_hidden_id);
        }

        //Calculate addons price
        $add_ons_price = 0;
        foreach ($add_ons_hidden_id as $val):
            $add_ons_price = $add_ons_price + get_addons_price_by_id($val);
        endforeach;


        //Check valid event id
        if (isset($event_id) && $event_id > 0):
            $service_data = get_full_event_service_data($event_id);
            if (isset($service_data['id']) && $service_data['id'] > 0 && $service_data['type'] == 'S'):
                $event_title = isset($service_data['title']) ? ($service_data['title']) : '';
                $type = $service_data['type'];
                $check_multiple_book_status = check_multiple_book_status(date("Y-m-d", strtotime($bookdate)), date("H:i:s", strtotime($bookdate)), $type, $event_id, $staff_member_id);
                if ($check_multiple_book_status == FALSE) {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('not_allowed_booking'));
                    redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
                } else {
                    include APPPATH . 'third_party/init.php';

                    $customer_data = $this->generate_user($_POST);

                    $insert['customer_id'] = $customer_data['id'];
                    $insert['description'] = $description;
                    $insert['slot_time'] = $slot_time;
                    $insert['event_id'] = $event_id;
                    $insert['start_date'] = date("Y-m-d", strtotime($bookdate));
                    $insert['start_time'] = date("H:i:s", strtotime($bookdate));

                    //discount data
                    $discount_coupon = $this->input->post('discount_coupon');
                    $discount_coupon_id = base64_decode($this->input->post('discount_coupon_id'));
                    $final_price = isset($service_data['price']) ? $service_data['price'] : 0;

                    if (isset($discount_coupon_id) && $discount_coupon_id > 0 && isset($discount_coupon)) {
                        $final_price = get_discount_price($event_id, $discount_coupon, $discount_coupon_id, $bookdate);
                    } else {
                        $final_price = get_discount_price_by_date($event_id, $bookdate);
                    }

                    //add add_ons value in final amount 
                    $final_price = $final_price + $add_ons_price;


                    if ($this->input->post('stripeToken')) {
                        try {
                            $stripe_api_key = get_StripeSecret();
                            \Stripe\Stripe::setApiKey($stripe_api_key); //system payment settings
                            $customer_email = $this->db->get_where('app_customer', array('id' => $customer_id))->row()->email;

                            $charge = \Stripe\Charge::create(array(
                                        "amount" => ceil($final_price * 100),
                                        "currency" => "USD",
                                        "source" => $_POST['stripeToken'], // obtained with Stripe.js
                                        "description" => $this->input->post('purpose')
                            ));

                            $charge_response = $charge->jsonSerialize();

                            if ($charge_response['paid'] == true) {
                                $vendor_amount = get_vendor_amount($final_price, $service_data['created_by']);
                                $admin_amount = get_admin_amount($final_price);

                                $insert['payment_status'] = 'S';
                                $insert['status'] = 'A';
                                $insert['addons_id'] = $addons_id;
                                $insert['vendor_price'] = $vendor_amount;
                                $insert['admin_price'] = $admin_amount;
                                $insert['created_on'] = date("Y-m:d H:i:s");
                                $insert['price'] = $final_price;
                                $insert['staff_id'] = $staff_member_id;
                                $book = $this->model_front->insert("app_event_book", $insert);

                                $data['customer_id'] = $customer_data['id'];
                                $data['vendor_id'] = $service_data['created_by'];
                                $data['event_id'] = $event_id;
                                $data['booking_id'] = $book;
                                $data['payment_id'] = $charge_response['id'];
                                $data['response_details'] = json_encode($charge_response);
                                $data['customer_payment_id'] = $_POST['stripeToken'];
                                $data['transaction_id'] = $charge_response['balance_transaction'];
                                $data['payment_price'] = $final_price;
                                $data['vendor_price'] = $vendor_amount;
                                $data['admin_price'] = $admin_amount;
                                $data['failure_code'] = $charge_response['failure_code'];
                                $data['failure_message'] = $charge_response['failure_message'];
                                $data['payment_method'] = 'Stripe';
                                $data['payment_status'] = 'S';
                                $data['created_on'] = date('Y-m-d H:i:s');

                                $this->model_front->insert('app_appointment_payment', $data);

                                $up_qry_vendor = $this->db->query("UPDATE app_admin SET my_earning=my_earning+" . $vendor_amount . ",my_wallet=my_wallet+" . $vendor_amount . " WHERE id=" . $service_data['created_by']);
                                $up_qry_admin = $this->db->query("UPDATE app_admin SET my_wallet=my_wallet+" . $admin_amount . " WHERE id=1");

                                $transaction = true;

                                //Send email to customer
                                $name = ($customer_data['first_name']) . " " . ($customer[0]['last_name']);
                                $subject = translate('appointment_booking');
                                $define_param['to_name'] = ($customer_data['first_name']) . " " . ($customer_data['last_name']);
                                $define_param['to_email'] = $customer_data['email'];

                                $parameter['name'] = $name;
                                if ($staff_member_id > 0):
                                    $parameter['staff_data'] = get_staff_row_by_id($staff_member_id);
                                endif;
                                $parameter['appointment_date'] = get_formated_date(($bookdate));
                                $parameter['service_data'] = $service_data;
                                $parameter['price'] = price_format($final_price);
                                $html = $this->load->view("email_template/service_booking_customer", $parameter, true);
                                $this->sendmail->send($define_param, $subject, $html);
                                //Send email to vendor

                                $vendor_name = ($service_data['first_name']) . " " . ($service_data['last_name']);
                                $vendor_email = $service_data['email'];
                                $subject2 = $service_data['title'] . ' | ' . translate('appointment_booking');
                                $define_param2['to_name'] = $vendor_name;
                                $define_param2['to_email'] = $vendor_email;

                                $parameterv['name'] = $vendor_name;
                                if ($staff_member_id > 0):
                                    $parameterv['staff_data'] = get_staff_row_by_id($staff_member_id);
                                endif;
                                $parameterv['appointment_date'] = get_formated_date(($bookdate));
                                $parameterv['service_data'] = $service_data;
                                $parameterv['customer_data'] = $customer_data;
                                $parameterv['price'] = price_format($final_price);
                                $html2 = $this->load->view("email_template/service_booking_vendor", $parameterv, true);
                                $this->sendmail->send($define_param2, $subject2, $html2);

                                if ($staff_member_id > 0):
                                    // Send email to staff if selected
                                    $staff_e_data = get_staff_row_by_id($staff_member_id);
                                    $staff_name = ($staff_e_data['first_name']) . " " . ($staff_e_data['last_name']);
                                    $staff_email = $staff_e_data['email'];

                                    $subject2 = $service_data['title'] . ' | ' . translate('appointment_booking');
                                    $define_param2['to_name'] = $staff_name;
                                    $define_param2['to_email'] = $staff_email;

                                    $parameters['name'] = $staff_name;
                                    $parameters['appointment_date'] = get_formated_date(($bookdate));
                                    $parameters['service_data'] = $service_data;
                                    $parameters['customer_data'] = $customer_data;
                                    $parameters['price'] = price_format($final_price);

                                    $html2 = $this->load->view("email_template/service_booking_vendor", $parameters, true);
                                    $this->sendmail->send($define_param2, $subject2, $html2);
                                endif;

                                $this->session->set_flashdata('msg', translate('transaction_success_event') . "<br>" . translate('booking_insert'));
                                $this->session->set_flashdata('msg_class', 'success');
                                redirect('eappointment-success/' . $book . '/' . $vendor_token);
                            } else {
                                $this->session->set_flashdata('msg', translate('transaction_fail'));
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
                            }
                        } catch (\Stripe\Error\Card $e) {
                            $body = $e->getJsonBody();
                            $err = $body['error'];
                            $this->session->set_flashdata('msg', $err['message']);
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
                        } catch (\Stripe\Error\RateLimit $e) {
                            $this->session->set_flashdata('msg', "Too many requests made to the API too quickly");
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
                        } catch (\Stripe\Error\InvalidRequest $e) {
                            $this->session->set_flashdata('msg', "Invalid parameters were supplied to Stripe's API");
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
                        } catch (\Stripe\Error\Authentication $e) {
                            $this->session->set_flashdata('msg', "Authentication with Stripe's API failed");
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
                        } catch (\Stripe\Error\ApiConnection $e) {
                            $this->session->set_flashdata('msg', "Network communication with Stripe failed");
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
                        } catch (\Stripe\Error\Base $e) {
                            $this->session->set_flashdata('msg', "Something else happened, completely unrelated to Stripe");
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
                        } catch (Exception $e) {
                            $this->session->set_flashdata('msg', "Something else happened, completely unrelated to Stripe");
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
                        }
                    } else {
                        redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
                    }
                }
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url('eservices/' . $vendor_token));
            endif;
        else:
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url('eservices/' . $vendor_token));
        endif;
    }

    //add booking by paypal
    public function booking_paypal() {

        $this->load->library('paypal');

        $description = $this->input->post('description');
        $slot_time = $this->input->post('user_slot_time');
        $event_id = (int) $this->input->post('event_id');
        $bookdate = $this->input->post('user_datetime');
        $staff_member_id = $this->input->post('staff_member_id');
        $vendor_token = $this->input->post('vendor_token');
        $add_ons_hidden_id = $this->input->post('add_ons_id') ? $this->input->post('add_ons_id') : array();

        $addons_id = "";
        if (count($add_ons_hidden_id) > 0) {
            $addons_id = implode(',', $add_ons_hidden_id);
        }

        //Calculate addons price
        $add_ons_price = 0;
        foreach ($add_ons_hidden_id as $val):
            $add_ons_price = $add_ons_price + get_addons_price_by_id($val);
        endforeach;

        //Check valid event id
        if (isset($event_id) && $event_id > 0):
            $service_data = get_full_event_service_data($event_id);
            if (isset($service_data['id']) && $service_data['id'] > 0 && $service_data['type'] == 'S'):

                $type = $service_data['type'];
                $check_multiple_book_status = check_multiple_book_status(date("Y-m-d", strtotime($bookdate)), date("H:i:s", strtotime($bookdate)), $type, $event_id, $staff_member_id);
                if ($check_multiple_book_status == FALSE) {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('not_allowed_booking'));
                    redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
                } else {
                    //discount data
                    $discount_coupon = $this->input->post('discount_coupon');
                    $discount_coupon_id = base64_decode($this->input->post('discount_coupon_id'));
                    $final_price = isset($service_data['price']) ? $service_data['price'] : 0;

                    if (isset($discount_coupon_id) && $discount_coupon_id > 0 && isset($discount_coupon)) {
                        $final_price = get_discount_price($event_id, $discount_coupon, $discount_coupon_id, $bookdate);
                    } else {
                        $final_price = get_discount_price_by_date($event_id, $bookdate);
                    }

                    $customer_data = $this->generate_user($_POST);

                    //add add_ons value in final amount 
                    $final_price = $final_price + $add_ons_price;

                    $insert['customer_id'] = $customer_data['id'];
                    $insert['description'] = $description;
                    $insert['slot_time'] = $slot_time;
                    $insert['event_id'] = $event_id;
                    $insert['start_date'] = date("Y-m-d", strtotime($bookdate));
                    $insert['start_time'] = date("H:i:s", strtotime($bookdate));
                    $insert['payment_status'] = 'IN';
                    $insert['created_on'] = date("Y-m-d H:i:s");
                    $insert['status'] = 'IN';
                    $insert['staff_id'] = $staff_member_id;
                    $insert['addons_id'] = $addons_id;
                    $app_event_book = $this->model_front->insert("app_event_book", $insert);

                    $this->session->set_userdata('booking_id', $app_event_book);
                    $this->session->set_userdata('description', $description);
                    $this->session->set_userdata('bookdate', $bookdate);
                    $this->session->set_userdata('event_id', $event_id);
                    $this->session->set_userdata('event_price', $final_price);
                    $this->session->set_userdata('vendor_token', $vendor_token);

                    $this->paypal->add_field('rm', 2);
                    $this->paypal->add_field('cmd', '_xclick');
                    $this->paypal->add_field('amount', $final_price);
                    $this->paypal->add_field('item_name', "Service Booking Payment");
                    $this->paypal->add_field('currency_code', "USD");
                    $this->paypal->add_field('custom', $app_event_book);
                    $this->paypal->add_field('business', get_payment_setting('paypal_merchant_email'));
                    $this->paypal->add_field('cancel_return', base_url('epaypal_cancel'));
                    $this->paypal->add_field('return', base_url('epaypal_success'));
                    $this->paypal->submit_paypal_post();
                }
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url('eservices/' . $vendor_token));
            endif;
        else:
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url('eservices/' . $vendor_token));
        endif;
    }

    public function paypal_success() {

        if (isset($_REQUEST['st']) && $_REQUEST['st'] == "Completed") {

            $booking_id = $this->session->userdata('booking_id');

            $service_booking_data = get_booking_details($booking_id);
            $staff_id = isset($service_booking_data['staff_id']) ? $service_booking_data['staff_id'] : 0;
            $customer_id = isset($service_booking_data['customer_id']) ? $service_booking_data['customer_id'] : 0;

            $description = $this->session->userdata('description');
            $bookdate = $this->session->userdata('bookdate');
            $event_price = $this->session->userdata('event_price');
            $event_id = $this->session->userdata('event_id');
            $vendor_token = $this->session->userdata('vendor_token');

            $service_data = get_full_event_service_data($event_id);
            $event_title = isset($service_data['title']) ? ($service_data['title']) : '';

            $vendor_amount = get_vendor_amount($event_price, $service_data['created_by']);
            $admin_amount = get_admin_amount($event_price);

            $data['customer_id'] = $customer_id;
            $data['vendor_id'] = $service_data['created_by'];
            $data['event_id'] = $event_id;
            $data['booking_id'] = $booking_id;
            $data['vendor_price'] = $vendor_amount;
            $data['admin_price'] = $admin_amount;
            $data['payment_id'] = $_REQUEST['tx'];
            $data['customer_payment_id'] = $_REQUEST['tx'];
            $data['transaction_id'] = $_REQUEST['tx'];
            $data['payment_price'] = $event_price;
            $data['failure_code'] = '';
            $data['failure_message'] = '';
            $data['payment_method'] = 'PayPal';
            $data['payment_status'] = 'S';
            $data['created_on'] = date('Y-m-d H:i:s');

            $appointment_id = $this->model_front->insert('app_appointment_payment', $data);
            $customer = $this->model_front->getData("app_customer", "first_name,last_name,email", "id='$customer_id'");

            //update app_event_book
            $app_event_book['status'] = 'A';
            $app_event_book['vendor_price'] = $vendor_amount;
            $app_event_book['admin_price'] = $admin_amount;
            $app_event_book['price'] = $event_price;
            $app_event_book['payment_status'] = 'S';
            $this->model_front->update('app_event_book', $app_event_book, "id=" . $booking_id);

            $up_qry_vendor = $this->db->query("UPDATE app_admin SET my_earning=my_earning+" . $vendor_amount . ",my_wallet=my_wallet+" . $vendor_amount . " WHERE id=" . $service_data['created_by']);
            $up_qry_admin = $this->db->query("UPDATE app_admin SET my_wallet=my_wallet+" . $admin_amount . " WHERE id=1");

            //Send email to customer
            $name = ($customer[0]['first_name']) . " " . ($customer[0]['last_name']);
            $subject = translate('appointment_booking');
            $define_param['to_name'] = ($customer[0]['first_name']) . " " . ($customer[0]['last_name']);
            $define_param['to_email'] = $customer[0]['email'];

            $parameter['name'] = $name;
            if ($staff_id > 0):
                $parameter['staff_data'] = get_staff_row_by_id($staff_id);
            endif;
            $parameter['appointment_date'] = get_formated_date(($bookdate));
            $parameter['service_data'] = $service_data;
            $parameter['price'] = price_format($event_price);
            $html = $this->load->view("email_template/service_booking_customer", $parameter, true);
            $this->sendmail->send($define_param, $subject, $html);
            //Send email to vendor

            $vendor_name = ($service_data['first_name']) . " " . ($service_data['last_name']);
            $vendor_email = $service_data['email'];
            $subject2 = $service_data['title'] . ' | ' . translate('appointment_booking');
            $define_param2['to_name'] = $vendor_name;
            $define_param2['to_email'] = $vendor_email;

            $parameterv['name'] = $vendor_name;
            if ($staff_id > 0):
                $parameterv['staff_data'] = get_staff_row_by_id($staff_id);
            endif;
            $parameterv['appointment_date'] = get_formated_date(($bookdate));
            $parameterv['service_data'] = $service_data;
            $parameterv['customer_data'] = $customer[0];
            $parameterv['price'] = price_format($event_price);
            $html2 = $this->load->view("email_template/service_booking_vendor", $parameterv, true);
            $this->sendmail->send($define_param2, $subject2, $html2);

            if ($staff_id > 0):
                // Send email to staff if selected
                $staff_e_data = get_staff_row_by_id($staff_id);
                $staff_name = ($staff_e_data['first_name']) . " " . ($staff_e_data['last_name']);
                $staff_email = $staff_e_data['email'];

                $subject2 = $service_data['title'] . ' | ' . translate('appointment_booking');
                $define_param2['to_name'] = $staff_name;
                $define_param2['to_email'] = $staff_email;

                $parameters['name'] = $staff_name;
                $parameters['appointment_date'] = get_formated_date(($bookdate));
                $parameters['service_data'] = $service_data;
                $parameters['customer_data'] = $customer[0];
                $parameters['price'] = price_format($event_price);

                $html2 = $this->load->view("email_template/service_booking_vendor", $parameters, true);
                $this->sendmail->send($define_param2, $subject2, $html2);
            endif;

            //unset session
            $this->session->unset_userdata('booking_id');
            $this->session->unset_userdata('description');
            $this->session->unset_userdata('bookdate');
            $this->session->unset_userdata('event_price');
            $this->session->unset_userdata('event_id');
            $this->session->unset_userdata('vendor_token');


            $this->session->set_flashdata('msg', translate('transaction_success_event') . "<br>" . translate('booking_insert'));
            $this->session->set_flashdata('msg_class', 'success');
            redirect('eappointment-success/' . $booking_id . '/' . $vendor_token);
        } else {
            $booking_id = $this->session->userdata('booking_id');
            $vendor_token = $this->session->userdata('vendor_token');
            $event_id = $this->session->userdata('event_id');
            $this->db->where("id", $booking_id);
            $this->db->delete("app_event_book");

            $this->session->unset_userdata('booking_id');
            $this->session->unset_userdata('description');
            $this->session->unset_userdata('bookdate');
            $this->session->unset_userdata('event_price');
            $this->session->unset_userdata('event_id');
            $this->session->unset_userdata('vendor_token');

            $this->session->set_flashdata('msg', translate('transaction_fail'));
            $this->session->set_flashdata('msg_class', 'failure');
            redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
        }
    }

    public function paypal_cancel() {
        //remove booked event due to unseccesfull payment
        $booking_id = $this->session->userdata('booking_id');
        $this->db->where("id", $booking_id);
        $this->db->delete("app_event_book");

        $vendor_token = $this->session->userdata('vendor_token');
        $event_id = $this->session->userdata('event_id');

        //unset session value
        $this->session->unset_userdata('booking_id');
        $this->session->unset_userdata('description');
        $this->session->unset_userdata('bookdate');
        $this->session->unset_userdata('vendor_token');
        $this->session->unset_userdata('event_id');

        $this->session->set_flashdata('msg', translate('transaction_fail'));
        $this->session->set_flashdata('msg_class', 'failure');
        redirect(base_url('eslots/' . $vendor_token . '/' . $event_id));
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

    function appointment_success($id, $token) {
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
            )
        );
        $event = $this->model_front->getData("app_event_book", "app_event.image,app_event_book.*,app_event.title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title", "app_event_book.id=" . $id, $join);

        if (count($event) > 0) {
            $data['event_data'] = $event[0];
            $data['title'] = translate('appointment') . " " . translate('details');
            $this->load->view('embed/appointment-success', $data);
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url('eservices/' . $token));
        }
    }

    public function events($token = NULL) {

        if ($token == NULL) {
            $token = $this->uri->segment(2);
            if ($token == '') {
                redirect(base_url());
            }
        }
        //get vendor data
        $token_user_Res = $this->model_front->getData('app_admin', '*', 'token="' . $token . '"');
        if (isset($token_user_Res) && !empty($token_user_Res)) {
            foreach ($token_user_Res as $tRow) {
                $user_id = $tRow['id'];
            }
            $join = array(
                array(
                    'table' => 'app_event_category',
                    'condition' => 'app_event_category.id=app_event.category_id',
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
                    'table' => 'app_admin',
                    'condition' => 'app_admin.id=app_event.created_by',
                    'jointype' => 'left'
                ),
            );
            $cond = 'app_event.status="A" AND app_event.type="E" AND app_event.created_by = ' . $user_id;
            $select = 'app_event.*,';
            $select .= 'app_event.id as event_id,app_event_category.title as category_title,app_city.city_title, app_location.loc_title,app_admin.profile_image, app_admin.first_name,';
            $select .= 'app_admin.first_name,app_admin.last_name,app_admin.company_name,app_admin.email,app_admin.profile_image';
            $total_events = $this->model_front->getData("app_event", $select, $cond, $join, '', 'app_event.id');

            $data['total_events'] = $total_events;
            $data['title'] = translate('event');
            $this->load->view('embed/events', $data);
        } else {
            redirect(base_url());
        }
    }

    //event details
    public function event_details($slug, $token, $event_id) {
        $token_user_Res = $this->check_token($token);

        /*
         * list of top city
         */
        $city_join = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'inner'
            )
        );

        $event_id = (int) $event_id;
        if ($event_id > 0) {

            $join = array(
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
                array(
                    'table' => 'app_event_sponsor',
                    'condition' => 'app_event_sponsor.event_id=app_event.id',
                    'jointype' => 'left'
                )
            );

            $event = $this->model_front->getData("app_event", "app_event.*,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,app_admin.id as app_admin_id, app_admin.first_name, app_admin.last_name, app_admin.email,app_admin.phone, app_admin.profile_image,app_admin.google_link, app_admin.twitter_link, app_admin.fb_link, app_admin.instagram_link, app_admin.company_name, app_admin.website, app_event_sponsor.sponsor_name,app_event_sponsor.website_link, app_event_sponsor.sponsor_image, app_event_sponsor.id as sid", "app_event.id=" . $event_id . " AND app_event.type='E'", $join);

            if (isset($event) && count($event) > 0) {
                if (isset($event[0]['created_by']) && $event[0]['created_by'] > 0) {
                    $event_book = $this->model_front->getData("app_event_book", " sum(event_booked_seat) as booked_seat", "event_id='$event_id'");
                    $data['event_data'] = $event[0];
                    $data['event_book'] = isset($event_book[0]['booked_seat']) ? $event_book[0]['booked_seat'] : 0;

                    $data['title'] = isset($event[0]['title']) ? ($event[0]['title']) : translate('event_details');
                    $data['meta_description'] = isset($event[0]['seo_description']) ? $event[0]['seo_description'] : '';
                    $data['meta_keyword'] = isset($event[0]['seo_keyword']) ? $event[0]['seo_keyword'] : '';
                    $data['meta_og_img'] = isset($event[0]['seo_og_image']) ? $event[0]['seo_og_image'] : '';

                    $this->load->view('embed/event-details', $data);
                } else {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('invalid_request'));
                    redirect(base_url('eevents/' . $token));
                }
            } else {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url('eevents/' . $token));
            }
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url('eevents/' . $token));
        }
    }

    //add event booking for free
    public function event_booking_free() {

        //Request post data
        $description = $this->input->post('description');
        $event_id = (int) $this->input->post('event_id');
        $bookdate = $this->input->post('start_date');
        $event_payment_type = $this->input->post('event_payment_type');
        $event_booking_seat = $this->input->post('total_booked_seat');
        $event_category_id = $this->input->post('event_category_id');
        $vendor_token = $this->input->post('vendor_token');

        //Ticket type post data 
        $event_amount = $this->input->post('amount');
        $event_main_ticket = $this->input->post('main_ticket');
        $ticket_type_id = $this->input->post('ticket_type_id');
        $total_seat_book = $this->input->post('total_seat_book');
        $event_price = 0;
        $total_tickets = 0;

        //Check valid event id
        if ($event_id > 0):
            $event_data = get_full_event_service_data($event_id);
            if (isset($event_data['id']) && $event_data['id'] > 0 && $event_data['type'] == 'E'):

                $event_title = isset($event_data['title']) ? ($event_data['title']) : '';
                $event_price = isset($event_data['price']) ? ($event_data['price']) : 0;
                $event_slug = isset($event_data['event_slug']) ? ($event_data['event_slug']) : '';
                $event_start_date = isset($event_data['start_date']) ? get_formated_date($event_data['start_date'], 'Y') : '';
                $event_end_date = isset($event_data['end_date']) ? get_formated_date($event_data['end_date'], 'Y') : '';

                $type = $event_data['type'];


                //Check Even ticket
                for ($i = 0; $i < count($ticket_type_id); $i++):
                    $app_event_ticket_type = $this->db->query("SELECT * FROM app_event_ticket_type WHERE ticket_type_id=" . $ticket_type_id[$i])->row_array();

                    if (isset($app_event_ticket_type['available_ticket']) && $total_seat_book[$i] <= $app_event_ticket_type['available_ticket']) {
                        $event_price = $event_price + ($app_event_ticket_type['ticket_type_price'] * $total_seat_book[$i]);
                        $total_tickets = ((int) $total_tickets + (int) $total_seat_book[$i]);
                    } else {
                        $this->session->set_flashdata('msg_class', 'failure');
                        $this->session->set_flashdata('msg', translate('seat_not_available'));
                        redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                    }

                endfor;

                if (($total_tickets != $event_main_ticket) || ($event_amount != $event_price)) {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('something_wrong'));
                    redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                } else {
                    $customer_data = $this->generate_user($_POST);

                    $insert['customer_id'] = $customer_data['id'];
                    $insert['description'] = $description;
                    $insert['event_id'] = $event_id;
                    $insert['event_booked_seat'] = $total_tickets;
                    $insert['start_date'] = date("Y-m-d", strtotime($bookdate));
                    $insert['start_time'] = date("H:i:s", strtotime($bookdate));
                    $insert['payment_status'] = 'S';
                    $insert['status'] = 'P';
                    $insert['created_on'] = date('Y-m-d H:i:s');
                    $insert['type'] = 'E';
                    $booking_id = $this->model_front->insert("app_event_book", $insert);

                    //Insert Ticket type 
                    for ($i = 0; $i < count($ticket_type_id); $i++):
                        $app_event_ticket_type_booking['ticket_type_id'] = $ticket_type_id[$i];
                        $app_event_ticket_type_booking['event_id'] = $event_id;
                        $app_event_ticket_type_booking['booking_id'] = $booking_id;
                        $app_event_ticket_type_booking['total_ticket'] = $total_seat_book[$i];
                        $app_event_ticket_type_booking['status'] = 'P';
                        $app_event_ticket_type_booking['created_by'] = $customer_data['id'];
                        $app_event_ticket_type_booking['created_date'] = date('Y-m-d H:i:s');
                        $this->db->insert('app_event_ticket_type_booking', $app_event_ticket_type_booking);
                    endfor;

                    $cur_date = date("Y-m-d H:i:s");

                    //Send email to customer
                    $name = ($customer_data['first_name']) . " " . ($customer_data['last_name']);
                    $subject = $event_data['title'] . ' | ' . translate('appointment_booking');
                    $define_param['to_name'] = $name;
                    $define_param['to_email'] = $customer_data['email'];

                    $parameter['name'] = $name;
                    $parameter['event_booking_seat'] = $total_tickets;
                    $parameter['event_data'] = $event_data;
                    $parameter['price'] = translate('free');
                    $html = $this->load->view("email_template/event_booking_customer", $parameter, true);
                    $this->sendmail->send($define_param, $subject, $html);

                    //Send email to vendor
                    $vendor_name = ($event_data['first_name']) . " " . ($event_data['last_name']);
                    $vendor_email = $event_data['email'];
                    $subject2 = $event_data['title'] . ' | ' . translate('appointment_booking');
                    $define_param2['to_name'] = $vendor_name;
                    $define_param2['to_email'] = $vendor_email;

                    $parameterv['name'] = $vendor_name;
                    $parameterv['event_booking_seat'] = $total_tickets;
                    $parameterv['event_data'] = $event_data;
                    $parameterv['customer_data'] = $customer_data;
                    $parameterv['price'] = translate('free');
                    $html2 = $this->load->view("email_template/event_booking_vendor", $parameterv, true);
                    $this->sendmail->send($define_param2, $subject2, $html2);

                    $this->session->set_flashdata('msg', translate('booking_pending'));
                    $this->session->set_flashdata('msg_class', 'info');
                    redirect('eappointment-success/' . $booking_id . '/' . $vendor_token);
                }
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url('eevents/' . $vendor_token));
            endif;
        else:
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url('eevents/' . $vendor_token));
        endif;
    }

    //add event booking by cash method
    public function event_booking_oncash() {
        $appointment_id = (int) $this->input->post('appointment_id');

        $description = $this->input->post('description');
        $description = $this->input->post('description');
        $event_id = (int) $this->input->post('event_id');
        $bookdate = $this->input->post('start_date');
        $event_payment_type = $this->input->post('event_payment_type');
        $event_booking_seat = $this->input->post('total_booked_seat');
        $event_category_id = $this->input->post('event_category_id');
        $vendor_token = $this->input->post('vendor_token');

        //Ticket type post data 
        $event_amount = $this->input->post('amount');
        $event_main_ticket = $this->input->post('main_ticket');
        $ticket_type_id = $this->input->post('ticket_type_id');
        $total_seat_book = $this->input->post('total_seat_book');
        $event_price = 0;
        $total_tickets = 0;

        //Check valid event id
        if ($event_id > 0):
            $event_data = get_full_event_service_data($event_id);
            if (isset($event_data['id']) && $event_data['id'] > 0 && $event_data['type'] == 'E'):
                $event_title = isset($event_data['title']) ? ($event_data['title']) : '';
                $event_slug = isset($event_data['event_slug']) ? ($event_data['event_slug']) : '';
                $event_start_date = isset($event_data['start_date']) ? get_formated_date($event_data['start_date'], 'Y') : '';
                $event_end_date = isset($event_data['end_date']) ? get_formated_date($event_data['end_date'], 'Y') : '';

                //Check Even ticket
                for ($i = 0; $i < count($ticket_type_id); $i++):
                    $app_event_ticket_type = $this->db->query("SELECT * FROM app_event_ticket_type WHERE ticket_type_id=" . $ticket_type_id[$i])->row_array();

                    if (isset($app_event_ticket_type['available_ticket']) && $total_seat_book[$i] <= $app_event_ticket_type['available_ticket']) {
                        $event_price = $event_price + ($app_event_ticket_type['ticket_type_price'] * $total_seat_book[$i]);
                        $total_tickets = ((int) $total_tickets + (int) $total_seat_book[$i]);
                    } else {
                        $this->session->set_flashdata('msg_class', 'failure');
                        $this->session->set_flashdata('msg', translate('seat_not_available'));
                        redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                    }

                endfor;

                if (($total_tickets != $event_main_ticket) || ($event_amount != $event_price)) {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('something_wrong'));
                    redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                } else {
                    $customer_data = $this->generate_user($_POST);

                    $type = $event_data['type'];
                    $final_price = $event_price;
                    //discount data
                    $vendor_amount = get_vendor_amount($final_price, $event_data['created_by']);
                    $admin_amount = get_admin_amount($final_price);

                    $insert['customer_id'] = $customer_data['id'];
                    $insert['description'] = $description;
                    $insert['event_id'] = $event_id;
                    $insert['event_booked_seat'] = $total_tickets;
                    $insert['start_date'] = date("Y-m-d", strtotime($bookdate));
                    $insert['start_time'] = date("H:i:s", strtotime($bookdate));

                    $process_type = true;
                    $on_cash_booking = true;

                    $insert['payment_status'] = 'P';
                    $insert['price'] = $final_price;
                    $insert['vendor_price'] = $vendor_amount;
                    $insert['admin_price'] = $admin_amount;
                    $insert['status'] = 'P';
                    $insert['type'] = 'E';
                    $insert['created_on'] = date('Y-m-d H:i:s');
                    $booking_id = $this->model_front->insert("app_event_book", $insert);

                    //Insert Ticket type 
                    for ($i = 0; $i < count($ticket_type_id); $i++):
                        $app_event_ticket_type_booking['ticket_type_id'] = $ticket_type_id[$i];
                        $app_event_ticket_type_booking['event_id'] = $event_id;
                        $app_event_ticket_type_booking['booking_id'] = $booking_id;
                        $app_event_ticket_type_booking['total_ticket'] = $total_seat_book[$i];
                        $app_event_ticket_type_booking['status'] = 'P';
                        $app_event_ticket_type_booking['created_by'] = $customer_data['id'];
                        $app_event_ticket_type_booking['created_date'] = date('Y-m-d H:i:s');
                        $this->db->insert('app_event_ticket_type_booking', $app_event_ticket_type_booking);
                    endfor;

                    $cur_date = date("Y-m-d H:i:s");
                    $data['customer_id'] = $customer_data['id'];
                    $data['vendor_id'] = $event_data['created_by'];
                    $data['event_id'] = $event_id;
                    $data['booking_id'] = $booking_id;
                    $data['payment_id'] = '';
                    $data['customer_payment_id'] = '';
                    $data['transaction_id'] = '';
                    $data['payment_price'] = $final_price;
                    $data['vendor_price'] = $vendor_amount;
                    $data['admin_price'] = $admin_amount;
                    $data['failure_code'] = '';
                    $data['failure_message'] = '';
                    $data['payment_method'] = translate('on_cash');
                    $data['payment_status'] = 'P';
                    $data['created_on'] = date('Y-m-d H:i:s');
                    $this->model_front->insert('app_appointment_payment', $data);
                    $customer = $this->model_front->getData("app_customer", "first_name,last_name,email,phone", "id='$customer_id'");

                    $name = ($customer_data['first_name']) . " " . ($customer_data['last_name']);
                    $subject = $event_data['title'] . ' | ' . translate('appointment_booking');
                    $define_param['to_name'] = $name;
                    $define_param['to_email'] = $customer_data['email'];

                    $parameter['name'] = $name;
                    $parameter['event_booking_seat'] = $total_tickets;
                    $parameter['event_data'] = $event_data;
                    $parameter['price'] = price_format($final_price);
                    $html = $this->load->view("email_template/event_booking_customer", $parameter, true);
                    $this->sendmail->send($define_param, $subject, $html);

                    //Send email to vendor
                    $vendor_name = ($event_data['first_name']) . " " . ($event_data['last_name']);
                    $vendor_email = $event_data['email'];
                    $subject2 = $event_data['title'] . ' | ' . translate('appointment_booking');
                    $define_param2['to_name'] = $vendor_name;
                    $define_param2['to_email'] = $vendor_email;

                    $parameterv['name'] = $vendor_name;
                    $parameterv['event_booking_seat'] = $total_tickets;
                    $parameterv['event_data'] = $event_data;
                    $parameterv['customer_data'] = $customer_data;
                    $parameterv['price'] = price_format($final_price);
                    $html2 = $this->load->view("email_template/event_booking_vendor", $parameterv, true);
                    $this->sendmail->send($define_param2, $subject2, $html2);

                    $this->session->set_flashdata('msg', translate('booking_pending'));
                    $this->session->set_flashdata('msg_class', 'info');
                    redirect('eappointment-success/' . $booking_id . '/' . $vendor_token);
                }
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url('eevents/' . $vendor_token));
            endif;
        else:
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url('eevents/' . $vendor_token));
        endif;
    }

    //add event booking by stripe method
    public function event_booking_stripe() {
        //Request post data
        $appointment_id = (int) $this->input->post('appointment_id');
        $description = $this->input->post('description');
        $event_id = (int) $this->input->post('event_id');
        $bookdate = $this->input->post('start_date');
        $event_payment_type = $this->input->post('event_payment_type');
        $event_booking_seat = $this->input->post('total_booked_seat');
        $event_category_id = $this->input->post('event_category_id');
        $vendor_token = $this->input->post('vendor_token');

        //Ticket type post data 
        $event_amount = $this->input->post('amount');
        $event_main_ticket = $this->input->post('main_ticket');
        $ticket_type_id = $this->input->post('ticket_type_id');
        $total_seat_book = $this->input->post('total_seat_book');
        $event_price = 0;
        $total_tickets = 0;

        //Check valid event id
        if ($event_id > 0):
            $event_data = get_full_event_service_data($event_id);

            if (isset($event_data['id']) && $event_data['id'] > 0 && $event_data['type'] == 'E') {
                $event_title = isset($event_data['title']) ? ($event_data['title']) : '';
                $event_slug = isset($event_data['event_slug']) ? ($event_data['event_slug']) : '';
                $event_start_date = isset($event_data['start_date']) ? get_formated_date($event_data['start_date'], 'Y') : '';
                $event_end_date = isset($event_data['end_date']) ? get_formated_date($event_data['end_date'], 'Y') : '';
                $type = $event_data['type'];

                //Check Even ticket
                for ($i = 0; $i < count($ticket_type_id); $i++):
                    $app_event_ticket_type = $this->db->query("SELECT * FROM app_event_ticket_type WHERE ticket_type_id=" . $ticket_type_id[$i])->row_array();

                    if (isset($app_event_ticket_type['available_ticket']) && $total_seat_book[$i] <= $app_event_ticket_type['available_ticket']) {
                        $event_price = $event_price + ($app_event_ticket_type['ticket_type_price'] * $total_seat_book[$i]);
                        $total_tickets = ((int) $total_tickets + (int) $total_seat_book[$i]);
                    } else {
                        $this->session->set_flashdata('msg_class', 'failure');
                        $this->session->set_flashdata('msg', translate('seat_not_available'));
                        redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                    }
                endfor;

                if (($total_tickets != $event_main_ticket) || ($event_amount != $event_price)) {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('something_wrong'));
                    redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                } else {
                    include APPPATH . 'third_party/init.php';
                    $final_price = $event_price;

                    $stripeToken = $this->input->post('stripeToken');
                    if (isset($stripeToken) && $stripeToken != '') {
                        try {
                            $customer_data = $this->generate_user($_POST);

                            $stripe_api_key = get_StripeSecret();
                            \Stripe\Stripe::setApiKey($stripe_api_key); //system payment settings
                            $customer_email = $this->db->get_where('app_customer', array('id' => $customer_id))->row()->email;

                            $charge = \Stripe\Charge::create(array(
                                        "amount" => ceil($final_price * 100),
                                        "currency" => "USD",
                                        "source" => $_POST['stripeToken'], // obtained with Stripe.js
                                        "description" => $this->input->post('purpose')
                            ));
                            $charge_response = $charge->jsonSerialize();
                            if ($charge_response['paid'] == true) {
                                $vendor_amount = get_vendor_amount($final_price, $event_data['created_by']);
                                $admin_amount = get_admin_amount($final_price);

                                //Insert Booking details
                                $insert['customer_id'] = $customer_data['id'];
                                $insert['description'] = $description;
                                $insert['event_id'] = $event_id;
                                $insert['start_date'] = date("Y-m-d", strtotime($bookdate));
                                $insert['start_time'] = date("H:i:s", strtotime($bookdate));
                                $insert['payment_status'] = 'S';
                                $insert['status'] = 'A';
                                $insert['type'] = 'E';
                                $insert['vendor_price'] = $vendor_amount;
                                $insert['event_booked_seat'] = $total_tickets;
                                $insert['admin_price'] = $admin_amount;
                                $insert['price'] = $final_price;
                                $insert['created_on'] = date("Y-m:d H:i:s");
                                $book = $this->model_front->insert("app_event_book", $insert);

                                //Insert Ticket type 
                                for ($i = 0; $i < count($ticket_type_id); $i++):
                                    $app_event_ticket_type_booking['ticket_type_id'] = $ticket_type_id[$i];
                                    $app_event_ticket_type_booking['event_id'] = $event_id;
                                    $app_event_ticket_type_booking['booking_id'] = $book;
                                    $app_event_ticket_type_booking['status'] = 'A';
                                    $app_event_ticket_type_booking['total_ticket'] = $total_seat_book[$i];
                                    $app_event_ticket_type_booking['created_by'] = $customer_data['id'];
                                    $app_event_ticket_type_booking['created_date'] = date('Y-m-d H:i:s');
                                    $this->db->insert('app_event_ticket_type_booking', $app_event_ticket_type_booking);

                                    //Update Available ticket
                                    $this->db->query("UPDATE app_event_ticket_type SET available_ticket=available_ticket-" . $total_seat_book[$i] . " WHERE ticket_type_id=" . $ticket_type_id[$i]);
                                endfor;

                                //Insert Payment Details
                                $cur_date = date("Y-m-d H:i:s");
                                $data['customer_id'] = $customer_data['id'];
                                $data['vendor_id'] = $event_data['created_by'];
                                $data['event_id'] = $event_id;
                                $data['booking_id'] = $book;
                                $data['payment_id'] = $charge_response['id'];
                                $data['response_details'] = json_encode($charge_response);
                                $data['customer_payment_id'] = $_POST['stripeToken'];
                                $data['transaction_id'] = $charge_response['balance_transaction'];
                                $data['payment_price'] = $final_price;
                                $data['vendor_price'] = $vendor_amount;
                                $data['admin_price'] = $admin_amount;
                                $data['failure_code'] = $charge_response['failure_code'];
                                $data['failure_message'] = $charge_response['failure_message'];
                                $data['payment_method'] = 'Stripe';
                                $data['payment_status'] = 'S';
                                $data['created_on'] = date('Y-m-d H:i:s');

                                $this->model_front->insert('app_appointment_payment', $data);

                                $up_qry_vendor = $this->db->query("UPDATE app_admin SET my_earning=my_earning+" . $vendor_amount . ",my_wallet=my_wallet+" . $vendor_amount . " WHERE id=" . $event_data['created_by']);
                                $up_qry_admin = $this->db->query("UPDATE app_admin SET my_wallet=my_wallet+" . $admin_amount . " WHERE id=1");

                                $transaction = true;

                                $name = ($customer_data['first_name']) . " " . ($customer_data['last_name']);
                                $subject = $event_data['title'] . ' | ' . translate('appointment_booking');
                                $define_param['to_name'] = $name;
                                $define_param['to_email'] = $customer_data['email'];

                                $parameter['name'] = $name;
                                $parameter['event_booking_seat'] = $total_tickets;
                                $parameter['event_data'] = $event_data;
                                $parameter['price'] = price_format($final_price);
                                $html = $this->load->view("email_template/event_booking_customer", $parameter, true);
                                $this->sendmail->send($define_param, $subject, $html);

                                //Send email to vendor
                                $vendor_name = ($event_data['first_name']) . " " . ($event_data['last_name']);
                                $vendor_email = $event_data['email'];
                                $subject2 = $event_data['title'] . ' | ' . translate('appointment_booking');
                                $define_param2['to_name'] = $vendor_name;
                                $define_param2['to_email'] = $vendor_email;

                                $parameterv['name'] = $vendor_name;
                                $parameterv['event_booking_seat'] = $total_tickets;
                                $parameterv['event_data'] = $event_data;
                                $parameterv['customer_data'] = $customer_data;
                                $parameterv['price'] = price_format($final_price);
                                $html2 = $this->load->view("email_template/event_booking_vendor", $parameterv, true);
                                $this->sendmail->send($define_param2, $subject2, $html2);

                                $this->session->set_flashdata('msg', translate('transaction_success_event') . "<br>" . translate('booking_insert'));
                                $this->session->set_flashdata('msg_class', 'success');
                                redirect('eappointment-success/' . $book . '/' . $vendor_token);
                            } else {
                                $this->session->set_flashdata('msg', translate('transaction_fail'));
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('eevents/' . $vendor_token));
                            }
                        } catch (\Stripe\Error\Card $e) {
                            $body = $e->getJsonBody();
                            $err = $body['error'];
                            $this->session->set_flashdata('msg', $err['message']);
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                        } catch (\Stripe\Error\RateLimit $e) {
                            $this->session->set_flashdata('msg', "Too many requests made to the API too quickly");
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                        } catch (\Stripe\Error\InvalidRequest $e) {
                            $this->session->set_flashdata('msg', "Invalid parameters were supplied to Stripe's API");
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                        } catch (\Stripe\Error\Authentication $e) {
                            $this->session->set_flashdata('msg', "Authentication with Stripe's API failed");
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                        } catch (\Stripe\Error\ApiConnection $e) {
                            $this->session->set_flashdata('msg', "Network communication with Stripe failed");
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                        } catch (\Stripe\Error\Base $e) {
                            $this->session->set_flashdata('msg', "Something else happened, completely unrelated to Stripe");
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                        } catch (Exception $e) {
                            $this->session->set_flashdata('msg', "Something else happened, completely unrelated to Stripe");
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                        }
                    } else {
                        $this->session->set_flashdata('msg_class', 'failure');
                        $this->session->set_flashdata('msg', translate('invalid_request'));
                        redirect(base_url('eevents/' . $vendor_token));
                    }
                }
            } else {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url('eevents/' . $vendor_token));
            }
        else:
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url('eevents/' . $vendor_token));
        endif;
    }

    //add event booking by paypal
    public function event_booking_paypal() {
        $this->load->library('paypal');

        $description = $this->input->post('description');
        $event_id = (int) $this->input->post('event_id');
        $bookdate = $this->input->post('start_date');

        $event_payment_type = $this->input->post('event_payment_type');
        $event_booking_seat = $this->input->post('total_booked_seat');
        $event_category_id = $this->input->post('event_category_id');
        $vendor_token = $this->input->post('vendor_token');

        //Ticket type post data 
        $event_amount = $this->input->post('amount');
        $event_main_ticket = $this->input->post('main_ticket');
        $ticket_type_id = $this->input->post('ticket_type_id');
        $total_seat_book = $this->input->post('total_seat_book');
        $event_price = 0;
        $total_tickets = 0;

        //Check valid event id
        if ($event_id > 0):
            $event_data = get_full_event_service_data($event_id);
            if (isset($event_data['id']) && $event_data['id'] > 0 && $event_data['type'] == 'E'):
                $event_slug = isset($event_data['event_slug']) ? ($event_data['event_slug']) : '';
                $event_title = isset($event_data['title']) ? ($event_data['title']) : '';
                $event_start_date = isset($event_data['start_date']) ? get_formated_date($event_data['start_date'], 'Y') : '';
                $event_end_date = isset($event_data['end_date']) ? get_formated_date($event_data['end_date'], 'Y') : '';
                $type = $event_data['type'];

                //Check Even ticket
                for ($i = 0; $i < count($ticket_type_id); $i++):
                    $app_event_ticket_type = $this->db->query("SELECT * FROM app_event_ticket_type WHERE ticket_type_id=" . $ticket_type_id[$i])->row_array();

                    if (isset($app_event_ticket_type['available_ticket']) && $total_seat_book[$i] <= $app_event_ticket_type['available_ticket']) {
                        $event_price = $event_price + ($app_event_ticket_type['ticket_type_price'] * $total_seat_book[$i]);
                        $total_tickets = ((int) $total_tickets + (int) $total_seat_book[$i]);
                    } else {
                        $this->session->set_flashdata('msg_class', 'failure');
                        $this->session->set_flashdata('msg', translate('seat_not_available'));
                        redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                    }
                endfor;

                if (($total_tickets != $event_main_ticket) || ($event_amount != $event_price)) {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('something_wrong'));
                    redirect(base_url('eevent-details/' . ($event_slug) . '/' . $vendor_token . '/' . $event_id));
                } else {
                    $customer_data = $this->generate_user($_POST);


                    $final_price = $event_price;
                    $insert['customer_id'] = $customer_data['id'];
                    $insert['description'] = $description;
                    $insert['event_id'] = $event_id;
                    $insert['event_booked_seat'] = $total_tickets;
                    $insert['start_date'] = date("Y-m-d", strtotime($bookdate));
                    $insert['start_time'] = date("H:i:s", strtotime($bookdate));
                    $insert['payment_status'] = 'IN';
                    $insert['created_on'] = date("Y-m-d H:i:s");
                    $insert['status'] = 'P';
                    $insert['type'] = 'E';
                    $app_event_book = $this->model_front->insert("app_event_book", $insert);

                    //Insert Ticket type 
                    for ($i = 0; $i < count($ticket_type_id); $i++):
                        $app_event_ticket_type_booking['ticket_type_id'] = $ticket_type_id[$i];
                        $app_event_ticket_type_booking['event_id'] = $event_id;
                        $app_event_ticket_type_booking['booking_id'] = $app_event_book;
                        $app_event_ticket_type_booking['status'] = 'IN';
                        $app_event_ticket_type_booking['total_ticket'] = $total_seat_book[$i];
                        $app_event_ticket_type_booking['created_by'] = $customer_data['id'];
                        $app_event_ticket_type_booking['created_date'] = date('Y-m-d H:i:s');
                        $this->db->insert('app_event_ticket_type_booking', $app_event_ticket_type_booking);
                    endfor;

                    $cur_date = date("Y-m-d H:i:s");
                    $this->session->set_userdata('booking_id', $app_event_book);
                    $this->session->set_userdata('description', $description);
                    $this->session->set_userdata('bookdate', $bookdate);
                    $this->session->set_userdata('event_id', $event_id);
                    $this->session->set_userdata('event_price', $final_price);
                    $this->session->set_userdata('vendor_token', $vendor_token);

                    $this->paypal->add_field('rm', 2);
                    $this->paypal->add_field('cmd', '_xclick');
                    $this->paypal->add_field('amount', $final_price);
                    $this->paypal->add_field('item_name', "Event Booking Payment");
                    $this->paypal->add_field('currency_code', "USD");
                    $this->paypal->add_field('custom', $app_event_book);
                    $this->paypal->add_field('business', get_payment_setting('paypal_merchant_email'));
                    $this->paypal->add_field('cancel_return', base_url('eevent_paypal_cancel'));
                    $this->paypal->add_field('return', base_url('eevent_paypal_success'));
                    $this->paypal->submit_paypal_post();
                }
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url('eevents/' . $vendor_token));
            endif;
        else:
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url('eevents/' . $vendor_token));
        endif;
    }

    public function event_paypal_success() {

        if (isset($_REQUEST['st']) && $_REQUEST['st'] == "Completed") {

            $booking_id = $this->session->userdata('booking_id');
            $description = $this->session->userdata('description');
            $bookdate = $this->session->userdata('bookdate');
            $event_price = $this->session->userdata('event_price');
            $event_id = $this->session->userdata('event_id');
            $vendor_token = $this->session->userdata('vendor_token');

            $event_data = get_full_event_service_data($event_id);
            $service_booking_data = get_booking_details($booking_id);

            $event_title = isset($event_data['title']) ? ($event_data['title']) : '';
            $event_booking_seat = isset($event_data['event_booked_seat']) ? ($event_data['event_booked_seat']) : '';
            $customer_id = isset($service_booking_data['customer_id']) ? ($service_booking_data['customer_id']) : '';

            $vendor_amount = get_vendor_amount($event_price, $event_data['created_by']);
            $admin_amount = get_admin_amount($event_price);

            $data['customer_id'] = $customer_id;
            $data['vendor_id'] = $event_data['created_by'];
            $data['event_id'] = $event_id;
            $data['booking_id'] = $booking_id;
            $data['vendor_price'] = $vendor_amount;
            $data['admin_price'] = $admin_amount;
            $data['payment_id'] = $_REQUEST['tx'];
            $data['customer_payment_id'] = $_REQUEST['tx'];
            $data['transaction_id'] = $_REQUEST['tx'];
            $data['payment_price'] = $event_price;
            $data['failure_code'] = '';
            $data['failure_message'] = '';
            $data['payment_method'] = 'PayPal';
            $data['payment_status'] = 'S';
            $data['created_on'] = date('Y-m-d H:i:s');
            $appointment_id = $this->model_front->insert('app_appointment_payment', $data);

            $customer = $this->model_front->getData("app_customer", "first_name,last_name,email", "id='$customer_id'");

            //Update ticket type
            $app_event_ticket_type_booking = $this->model_front->getData("app_event_ticket_type_booking", "*", "booking_id=" . $booking_id);
            foreach ($app_event_ticket_type_booking as $val):
                $this->db->query("UPDATE app_event_ticket_type_booking SET status='A' WHERE id=" . $val['id']);
                $this->db->query("UPDATE app_event_ticket_type SET available_ticket=available_ticket-" . $val['total_ticket'] . " WHERE ticket_type_id=" . $val['ticket_type_id']);
            endforeach;

            //update app_event_book
            $app_event_book['status'] = 'A';
            $app_event_book['vendor_price'] = $vendor_amount;
            $app_event_book['admin_price'] = $admin_amount;
            $app_event_book['price'] = $event_price;
            $app_event_book['type'] = 'E';
            $app_event_book['payment_status'] = 'S';
            $this->model_front->update('app_event_book', $app_event_book, "id=" . $booking_id);

            $up_qry_vendor = $this->db->query("UPDATE app_admin SET my_earning=my_earning+" . $vendor_amount . ",my_wallet=my_wallet+" . $vendor_amount . " WHERE id=" . $event_data['created_by']);
            $up_qry_admin = $this->db->query("UPDATE app_admin SET my_wallet=my_wallet+" . $admin_amount . " WHERE id=1");


            $name = ($customer[0]['first_name']) . " " . ($customer[0]['last_name']);
            $subject = $event_data['title'] . ' | ' . translate('appointment_booking');
            $define_param['to_name'] = $name;
            $define_param['to_email'] = $customer[0]['email'];

            $parameter['name'] = $name;
            $parameter['event_booking_seat'] = $event_booking_seat;
            $parameter['event_data'] = $event_data;
            $parameter['price'] = price_format($event_price);
            $html = $this->load->view("email_template/event_booking_customer", $parameter, true);
            $this->sendmail->send($define_param, $subject, $html);

            //Send email to vendor
            $vendor_name = ($event_data['first_name']) . " " . ($event_data['last_name']);
            $vendor_email = $event_data['email'];
            $subject2 = $event_data['title'] . ' | ' . translate('appointment_booking');
            $define_param2['to_name'] = $vendor_name;
            $define_param2['to_email'] = $vendor_email;

            $parameterv['name'] = $vendor_name;
            $parameterv['event_booking_seat'] = $event_booking_seat;
            $parameterv['event_data'] = $event_data;
            $parameterv['customer_data'] = $customer[0];
            $parameterv['price'] = price_format($event_price);
            $html2 = $this->load->view("email_template/event_booking_vendor", $parameterv, true);
            $this->sendmail->send($define_param2, $subject2, $html2);

            //unset session
            $this->session->unset_userdata('booking_id');
            $this->session->unset_userdata('description');
            $this->session->unset_userdata('bookdate');
            $this->session->unset_userdata('event_price');
            $this->session->unset_userdata('event_id');

            $this->session->set_flashdata('msg', translate('transaction_success_event') . "<br>" . translate('booking_insert'));
            $this->session->set_flashdata('msg_class', 'success');
            redirect('eappointment-success/' . $booking_id . '/' . $vendor_token);
        } else {
            $booking_id = $this->session->userdata('booking_id');
            $vendor_token = $this->session->userdata('vendor_token');
            $this->db->where("id", $booking_id);
            $this->db->delete("app_event_book");

            $this->db->query("DELETE FROM app_event_ticket_type_booking WHERE booking_id=" . $booking_id);

            $this->session->unset_userdata('booking_id');
            $this->session->unset_userdata('description');
            $this->session->unset_userdata('bookdate');
            $this->session->unset_userdata('event_price');
            $this->session->unset_userdata('event_id');
            $this->session->unset_userdata('vendor_token');

            $this->session->set_flashdata('msg', translate('transaction_fail'));
            $this->session->set_flashdata('msg_class', 'failure');
            redirect(base_url('eevents/' . $vendor_token));
        }
    }

    public function event_paypal_cancel() {
        //remove booked event due to unseccesfull payment
        $booking_id = $this->session->userdata('booking_id');
        $vendor_token = $this->session->userdata('vendor_token');
        $this->db->where("id", $booking_id);
        $this->db->delete("app_event_book");

        $this->db->query("DELETE FROM app_event_ticket_type_booking WHERE booking_id=" . $booking_id);

        //unset session value
        $this->session->unset_userdata('vendor_token');
        $this->session->unset_userdata('booking_id');
        $this->session->unset_userdata('description');
        $this->session->unset_userdata('bookdate');
        $this->session->unset_userdata('event_id');

        $this->session->set_flashdata('msg', translate('transaction_fail'));
        $this->session->set_flashdata('msg_class', 'failure');
        redirect(base_url('eevents/' . $vendor_token));
    }

    public function contact_action() {

        $this->form_validation->set_rules('fullname', '', 'required');
        $this->form_validation->set_rules('emailid', '', 'required|valid_email');
        $this->form_validation->set_rules('phoneno', '', 'required');
        $this->form_validation->set_rules('message', '', 'required');
        $event_id = (int) $this->input->post('event_id', TRUE);
        $event_cat_id = $this->input->post('event_category_id', TRUE);
        $admin_id = $this->input->post('admin_id', TRUE);
        $v_token = $this->input->post('v_token', TRUE);
        $event_title_hd = $this->input->post('event_title_hd', TRUE);

        $event_Res = $this->model_front->getData("app_event", "*", "id='$event_id'");

        $type = isset($event_Res[0]['type']) ? $event_Res[0]['type'] : "";
        $event_title = isset($event_Res[0]['title']) ? $event_Res[0]['title'] : "";

        $created_by = isset($event_Res[0]['created_by']) ? $event_Res[0]['created_by'] : "";
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('msg', validation_errors());
            $this->session->set_flashdata('msg_class', 'failure');
            redirect('eevent-details/' . ($event_Res[0]['event_slug']) . '/' . $v_token . '/' . $event_id);
        } else {
            $fullname = $this->input->post('fullname', true);
            $emailid = $this->input->post('emailid', true);
            $phoneno = $this->input->post('phoneno', true);
            $message = $this->input->post('message', true);

            $ins_data = array();
            $ins_data['event_id'] = $event_id;
            $ins_data['admin_id'] = $admin_id;
            $ins_data['name'] = $fullname;
            $ins_data['email'] = $emailid;
            $ins_data['phone'] = $phoneno;
            $ins_data['message'] = $message;
            $ins_data['created_on'] = date("Y-m-d H:i:s");
            $ins_id = $this->model_front->insert('app_contact_us', $ins_data);
            if ($ins_id) {
                $admin = $this->model_front->getData("app_admin", "first_name,last_name,email", "id=" . $created_by);
                $subject = translate('contact-us') . " | " . $event_title;

                $admin_name = ($admin[0]['first_name']) . " " . ($admin[0]['last_name']);
                $define_param['to_name'] = $admin_name;
                $define_param['to_email'] = $admin[0]['email'];

                $parameter['user'] = $admin_name;
                $parameter['name'] = $fullname;
                $parameter['email'] = $emailid;
                $parameter['phone'] = $phoneno;
                if ($type == 'S'):
                    $parameter['service'] = $event_title;
                else:
                    $parameter['event'] = $event_title;
                endif;

                $parameter['message'] = $message;
                $html = $this->load->view("email_template/contact_us", $parameter, true);
                $send = $this->sendmail->send($define_param, $subject, $html);

                $this->session->set_flashdata('msg', translate('contact_detail_send'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $this->session->set_flashdata('msg', translate('something_wrong'));
                $this->session->set_flashdata('msg_class', 'failure');
            }
            if ($type == 'E') {
                redirect('eevent-details/' . ($event_Res[0]['event_slug']) . '/' . $v_token . '/' . $event_id);
            } else {
                redirect('eservices-details/' . ($event_Res[0]['event_slug']) . '/' . $v_token . '/' . $event_id);
            }
        }
    }

}
