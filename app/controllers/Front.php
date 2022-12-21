<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Front extends CI_Controller {

    function __construct() {
        parent::__construct();
        run_default_query();
        if (is_maintenance_mode() == 'Y') {
            redirect('maintenance');
            exit(0);
        }

        $this->load->model('model_front');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $this->Per_Page = get_site_setting('display_record_per_page');
        set_time_zone();
        update_event_status();
        //$this->output->enable_profiler(TRUE);
    }

    public function change_city() {
        $city_name = $this->input->post('city_name');
        $city_data_res = $this->model_front->getData('app_city', 'city_id', 'city_title="' . $city_name . '"');
        if (count($city_data_res) > 0) {
            $this->session->unset_userdata('location');
            $this->session->unset_userdata('location_id');

            $this->session->set_userdata('location', $city_name);
            $this->session->set_userdata('location_id', $city_data_res[0]['city_id']);
            $this->session->set_userdata('is_from_city', 1);
        }
        echo true;
    }

    //show home page
    public function index() {
        $location_event = $this->uri->segment(1);
        $location_segment = urldecode($this->uri->segment(2));
        if (isset($location_event) && $location_event == "city") {
            $city_data_res = $this->model_front->getData('app_city', 'city_id', 'city_title="' . $location_segment . '"');
            if (count($city_data_res) > 0) {
                $this->session->unset_userdata('location');
                $this->session->unset_userdata('location_id');

                $this->session->set_userdata('location', $location_segment);
                $this->session->set_userdata('location_id', $city_data_res[0]['city_id']);
                $this->session->set_userdata('is_from_city', 1);
                redirect(base_url());
            } else {
                redirect(base_url());
            }
        } else {
            $is_from_city = $this->session->userdata('is_from_city');
            if ($is_from_city == 0) {
                $city_data_res = $this->model_front->getData('app_city', 'city_title,city_id', 'is_default=1');
                if (count($city_data_res) > 0) {
                    $location_segment = $city_data_res[0]['city_title'];
                    $this->session->unset_userdata('location');
                    $this->session->unset_userdata('location_id');

                    $this->session->set_userdata('location_id', $city_data_res[0]['city_id']);
                    $this->session->set_userdata('location', $location_segment);
                } else {
                    $default_city_find_qry = $this->db->query("SELECT city_title,city_id FROM app_city WHERE 1 LIMIT 1");
                    $default_city_find = $default_city_find_qry->row_array();
                    if (isset($default_city_find['city_title']) && $default_city_find['city_title'] != "") {
                        $location_segment = $default_city_find['city_title'];
                        $this->session->unset_userdata('location');
                        $this->session->unset_userdata('location_id');

                        $this->session->set_userdata('location_id', $default_city_find['city_id']);
                        $this->session->set_userdata('location', $location_segment);
                    }
                }
            }
        }
        $is_search = $this->session->userdata('location');
        if ($is_search != '') {
            $city_Res = $this->model_front->get_row_result(array("city_title" => $is_search), 'app_city');
        }
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
        $top_cities = $this->model_front->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');
        /*
         * list of event category
         */
        $service_category = $this->model_front->getData('app_event_category', '*', 'status="A" AND type="S"');
        /*
         * recent list of booked events
         */
        $join = array(
            array(
                'table' => 'app_event_book',
                'condition' => 'app_event_book.event_id=app_event.id',
                'jointype' => 'inner'
            )
        );
        $book_cond = 'app_event.status="A" AND app_event.type="E"';

        $recent_events = $this->model_front->getData("app_event", 'app_event.*', $book_cond, $join, '', 'RAND()', '', 10);


        $event_join = array(
            array(
                'table' => 'app_event_category',
                'condition' => '(app_event_category.id=app_event.category_id AND app_event_category.type="E")',
                'jointype' => 'INNER'
            ),
            array(
                'table' => 'app_city',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'INNER'
            ),
            array(
                'table' => 'app_location',
                'condition' => 'app_location.loc_id=app_event.location',
                'jointype' => 'INNER'
            ), array(
                'table' => 'app_users',
                'condition' => 'app_users.id=app_event.created_by',
                'jointype' => 'INNER'
            )
        );

        $service_join = array(
            array(
                'table' => 'app_event_category',
                'condition' => '(app_event_category.id=app_event.category_id AND app_event_category.type="S")',
                'jointype' => 'INNER'
            ),
            array(
                'table' => 'app_city',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'INNER'
            ),
            array(
                'table' => 'app_location',
                'condition' => 'app_location.loc_id=app_event.location',
                'jointype' => 'INNER'
            ), array(
                'table' => 'app_users',
                'condition' => 'app_users.id=app_event.created_by',
                'jointype' => 'INNER'
            )
        );

        $service_cond = 'app_event.status="A" AND app_event.type="S"';
        if (isset($city_Res) && !empty($city_Res)) {
            $service_cond .= ' AND city = ' . $city_Res['city_id'];
        }
        $cond = 'app_event.status="A" AND app_event.type="E"';
        if (isset($city_Res) && !empty($city_Res)) {
            $cond .= ' AND city = ' . $city_Res['city_id'];
        }

        $display_record_per_page = get_site_setting('display_record_per_page');

        if (get_site_setting('enable_event') == 'Y'):
            $total_event = $this->model_front->getData("app_event", 'app_users.company_name,app_users.profile_image,app_event.*,app_event.id as event_id,app_event_category.title as category_title,app_city.city_title, app_location.loc_title', $cond, $event_join, 'RAND()', 'app_event.id', '', $display_record_per_page);
            $data['total_Event'] = $total_event;
        endif;

        $total_service = $this->model_front->getData("app_event", 'app_users.company_name,app_users.profile_image,app_event.*,app_event.id as event_id,app_event_category.title as category_title,app_city.city_title, app_location.loc_title', $service_cond, $service_join, 'app_event.id desc', 'app_event.id', '', $display_record_per_page);


        $event_cat_join = array(
            array(
                'table' => 'app_event',
                'condition' => '(app_event_category.id=app_event.category_id AND app_event_category.type="E")',
                'jointype' => 'INNER'
            )
        );
        $events_category = $this->model_front->getData("app_event_category", 'app_event_category.title,app_event_category.id,app_event_category.event_category_image', 'app_event_category.type="E" AND app_event.status="A"', $event_cat_join, '', 'app_event_category.title', '', 8);


        $data['total_service'] = $total_service;
        $data['title'] = translate('home');
        $data['topCity_List'] = $top_cities;
        $data['Service_Category'] = $service_category;
        $data['events_category'] = $events_category;
        $data['Recent_events'] = $recent_events;
        $this->load->view('front/home', $data);
    }

    public function set_language($lang) {
        $app_language = $this->model_front->getData('app_language', 'id', 'db_field="' . $lang . '"');
        if (count($app_language) > 0) {
            $this->session->set_userdata('language', $lang);
            redirect(base_url());
        } else {
            redirect(base_url());
        }
    }

    //show category page
    public function event_category($title, $category_id) {
        $title = trim($title);
        $category_id = (int) $category_id;
        if ($category_id) {
            $events_category = $this->model_front->getData("app_event_category", 'title,id', 'type="E"', '', '', 'title');
            $category_verify = $this->model_front->getData('app_event_category', 'title,id', 'status="A" AND id=' . $category_id);
            if (count($category_verify) > 0) {
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
                $top_cities = $this->model_front->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');

                $join = array(
                    array(
                        'table' => 'app_event_category',
                        'condition' => 'app_event_category.id=app_event.category_id',
                        'jointype' => 'INNER'
                    ),
                    array(
                        'table' => 'app_city',
                        'condition' => 'app_city.city_id=app_event.city',
                        'jointype' => 'INNER'
                    )
                );
                $event = $this->model_front->getData("app_event", 'app_event.*,app_event_category.title as category_title,app_city.city_title', " app_event.type='E' AND app_event.status='A' AND category_id=" . $category_id, $join);
                $data['event_data'] = $event;
                $data['events_category'] = $events_category;
                $data['category_data'] = $category_verify[0];
                $data['title'] = isset($category_verify[0]) && count($category_verify[0]) > 0 ? $category_verify[0]['title'] : translate('event_category');
                $data['topCity_List'] = $top_cities;
                $this->load->view('front/event/event_category', $data);
            } else {
                redirect(base_url());
            }
        } else {
            redirect(base_url());
        }
    }

    //event details
    public function event_details() {
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
        $top_cities = $this->model_front->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');
        $event_id = (int) $this->uri->segment(3);
        if ($event_id > 0) {
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
                ),
                array(
                    'table' => 'app_users',
                    'condition' => 'app_users.id=app_event.created_by',
                    'jointype' => 'left'
                ),
                array(
                    'table' => 'app_event_sponsor',
                    'condition' => 'app_event_sponsor.event_id=app_event.id',
                    'jointype' => 'left'
                )
            );

            $event = $this->model_front->getData("app_event", "app_event.*,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,app_users.id as app_users_id, app_users.first_name, app_users.last_name, app_users.email,app_users.phone, app_users.profile_image,app_users.google_link, app_users.twitter_link, app_users.fb_link, app_users.instagram_link, app_users.company_name, app_users.website, app_event_sponsor.sponsor_name,app_event_sponsor.website_link, app_event_sponsor.sponsor_image, app_event_sponsor.id as sid", "app_event.id=" . $event_id . " AND app_event.type='E'", $join);

            if (isset($event) && count($event) > 0) {
                if (isset($event[0]['created_by']) && $event[0]['created_by'] > 0) {
                    $event_book = $this->model_front->getData("app_event_book", " sum(event_booked_seat) as booked_seat", "event_id='$event_id'");
                    $customer_id_sess = (int) $this->session->userdata('CUST_ID');
                    $customer = $this->model_front->getData("app_customer", "id,first_name,last_name,email", "id=" . $customer_id_sess);
                    $data['customer_data'] = isset($customer[0]) ? $customer[0] : array();
                    $data['event_data'] = $event[0];
                    $data['event_book'] = isset($event_book[0]['booked_seat']) ? $event_book[0]['booked_seat'] : 0;

                    $data['title'] = isset($event[0]['title']) ? ($event[0]['title']) : translate('event_details');
                    $data['meta_description'] = isset($event[0]['seo_description']) ? $event[0]['seo_description'] : '';
                    $data['meta_keyword'] = isset($event[0]['seo_keyword']) ? $event[0]['seo_keyword'] : '';
                    $data['meta_og_img'] = isset($event[0]['seo_og_image']) ? $event[0]['seo_og_image'] : '';
                    $data['topCity_List'] = $top_cities;

                    $this->load->view('front/event/event-details', $data);
                } else {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('invalid_request'));
                    redirect(base_url());
                }
            } else {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url());
            }
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        }
    }

    //service details
    public function service_details() {
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
        $top_cities = $this->model_front->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');
        $event_id = (int) $this->uri->segment(3);
        if ($event_id > 0) {
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
                    $admin_data = $this->model_front->getData("app_users", "*", "id=" . $event[0]['created_by']);
                    $user_rating = $this->model_front->getData("app_rating", "*", "user_id='$user_id' AND event_id='$event_id'");
                    $user_rating = $this->model_front->getData("app_rating", "*", "user_id='$user_id' AND event_id='$event_id'");

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
                    $data['user_rating'] = isset($user_rating) && count($user_rating) > 0 ? $user_rating[0] : '';
                    $data['event_rating'] = $vendor_rating_data;
                    $data['admin_data'] = $admin_data[0];

                    $data['title'] = isset($event[0]['title']) ? ($event[0]['title']) : translate('event_details');
                    $data['meta_description'] = isset($event[0]['seo_description']) ? $event[0]['seo_description'] : '';
                    $data['meta_keyword'] = isset($event[0]['seo_keyword']) ? $event[0]['seo_keyword'] : '';
                    $data['meta_og_img'] = isset($event[0]['seo_og_image']) ? $event[0]['seo_og_image'] : '';
                    $data['topCity_List'] = $top_cities;

                    $this->load->view('front/service/service-details', $data);
                } else {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('invalid_request'));
                    redirect(base_url());
                }
            } else {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url());
            }
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        }
    }

    //show days page
    public function day_slots($id, $staff_id = null) {

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
                'table' => 'app_users',
                'condition' => 'app_users.id=app_event.created_by',
                'jointype' => 'left'
            ),
        );
        $select_value = "app_event.*,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,app_users.company_name";
        $event = $this->model_front->getData("app_event", $select_value, "app_event.id=" . $id . " AND app_event.type='S'", $join_data);
        if (!isset($event) || isset($event) && count($event) == 0) {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        }

        $min = $event[0]['slot_time'];
        $allow_day = explode(",", $event[0]['days']);
        $date = date('d-m-Y');
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
        $data['app_service_addons'] = $app_service_addons;
        $data['event_data'] = isset($event[0]) ? $event[0] : array();
        $data['customer_data'] = isset($customer[0]) ? $customer[0] : array();
        $data['title'] = translate('book_your_appointment');
        $this->load->view('front/service/days', $data);
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
                if (isset($customer_id) && $customer_id > 0) {
                    $html .= '<div class="col-md-12 mt-2 text-center"><a class="time-button" onclick="confirm_time(this);"><span id="time-select">' . get_formated_time(strtotime($value)) . ' </span> - <span>' . $end_time_format . '</span></a>';
                } else {
                    $html .= '<div class="col-md-12 mt-2 text-center"><a class="time-button" onclick="login_protected_modal(this)" data-message="' . translate("login_required_for_book") . '"><span id="time-select">' . get_formated_time(strtotime($value)) . ' </span> - <span>' . $end_time_format . '</span></a>';
                }

                if (isset($customer_id) && $customer_id > 0) {
                    $html .= '<a class="time-button w-49 time-respo ml-2 time-confirm hide-confirm" onclick="confirm_form(this);" data-price=' . get_price($eventid, $date) . ' data-ddate="' . get_formated_date($date, "N") . '" data-date="' . $date . '" data-dtime="' . get_formated_time(strtotime($value)) . '" data-time="' . date('H:i:s', strtotime($value)) . '"> ' . translate('confirm') . '</a>';
                } else {
                    $html .= '<a class="time-button w-49 time-respo ml-2 time-confirm hide-confirm" onclick="login_protected_modal(this)" data-message="' . translate("login_required_for_book") . '"> ' . translate('confirm') . '</a>';
                }
                $html .= '</div> 
                      </div>';
            }
        } else {
            $html .= '<div class="col-md-12 text-center error">' . translate('booking_time_expired') . '</div>';
        }
        $html .= '</div>';
        echo $html;
        exit;
    }

    public function time_slots_admin($min, $update = NULL) {
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
        $staff_member_id = $this->input->post('staff_member_id');

        //Check valid event id
        if ($event_id > 0):
            $service_data = get_full_event_service_data($event_id);

            if (isset($service_data['id']) && $service_data['id'] > 0 && $service_data['type'] == 'S'):

                $event_title = isset($service_data['title']) ? ($service_data['title']) : '';
                $vendor_id = isset($service_data['created_by']) ? ($service_data['created_by']) : '';
                $type = $service_data['type'];
                $total_seat = $service_data['total_seat'];

                $customer_id = (int) $this->session->userdata('CUST_ID');
                if ($customer_id == 0) {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('protected_message'));
                    redirect('login');
                }

                $check_multiple_book_status = check_multiple_book_status(date("Y-m-d", strtotime($bookdate)), date("H:i:s", strtotime($bookdate)), $type, $event_id, $staff_member_id);
                if ($check_multiple_book_status == FALSE) {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('not_allowed_booking'));
                    redirect(base_url('day-slots/' . $event_id));
                } else {
                    $insert['customer_id'] = $customer_id;
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
                    $customer = $this->model_front->getData("app_customer", "first_name,last_name,email", "id='$customer_id'");
                    $name = ($customer[0]['first_name']) . " " . ($customer[0]['last_name']);
                    $subject = translate('appointment_booking');
                    $define_param['to_name'] = ($customer[0]['first_name']) . " " . ($customer[0]['last_name']);
                    $define_param['to_email'] = $customer[0]['email'];

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
                    $parameterv['customer_data'] = $customer[0];
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
                    redirect('appointment-success/' . $book);
                }
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url());
            endif;
        else:
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        endif;
    }

    //add booking by cash method
    public function booking_oncash() {
        $customer_id = (int) $this->session->userdata('CUST_ID');
        if ($customer_id == 0) {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('protected_message'));
            redirect('login');
        }

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
                    redirect(base_url('day-slots/' . $event_id));
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

                    $insert['customer_id'] = $customer_id;
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

                    $data['customer_id'] = $customer_id;
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
                    $customer = $this->model_front->getData("app_customer", "first_name,last_name,email", "id='$customer_id'");
                    $name = ($customer[0]['first_name']) . " " . ($customer[0]['last_name']);
                    $subject = translate('appointment_booking');
                    $define_param['to_name'] = ($customer[0]['first_name']) . " " . ($customer[0]['last_name']);
                    $define_param['to_email'] = $customer[0]['email'];

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
                    $parameterv['customer_data'] = $customer[0];
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
                        $parameters['customer_data'] = $customer[0];
                        $parameters['price'] = price_format($final_price);

                        $html2 = $this->load->view("email_template/service_booking_vendor", $parameters, true);
                        $this->sendmail->send($define_param2, $subject2, $html2);
                    endif;

                    $this->session->set_flashdata('msg', translate('booking_pending'));
                    $this->session->set_flashdata('msg_class', 'info');
                    redirect('appointment-success/' . $book);
                }
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url());
            endif;
        else:
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        endif;
    }

    //add by stripe method
    public function booking_stripe() {

        //Get current set category
        $get_current_currency = get_current_currency();
        if ($get_current_currency['stripe_supported'] == 'Y') {
            //Request post data
            $appointment_id = (int) $this->input->post('appointment_id');
            $description = $this->input->post('description');
            $slot_time = $this->input->post('user_slot_time');
            $event_id = (int) $this->input->post('event_id');
            $bookdate = $this->input->post('user_datetime');
            $event_payment_type = $this->input->post('event_payment_type');
            $staff_member_id = $this->input->post('staff_member_id');
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
                    $event_title = isset($service_data['title']) ? ($service_data['title']) : '';
                    $type = $service_data['type'];

                    $check_multiple_book_status = check_multiple_book_status(date("Y-m-d", strtotime($bookdate)), date("H:i:s", strtotime($bookdate)), $type, $event_id, $staff_member_id);
                    if ($check_multiple_book_status == FALSE) {
                        $this->session->set_flashdata('msg_class', 'failure');
                        $this->session->set_flashdata('msg', translate('not_allowed_booking'));
                        redirect(base_url('day-slots/' . $event_id));
                    } else {
                        include APPPATH . 'third_party/init.php';
                        $customer_id = (int) $this->session->userdata('CUST_ID');
                        if ($customer_id == 0) {
                            $this->session->set_flashdata('msg_class', 'failure');
                            $this->session->set_flashdata('msg', translate('protected_message'));
                            redirect('login');
                        }
                        $insert['customer_id'] = $customer_id;
                        $insert['description'] = $description;
                        $insert['slot_time'] = $slot_time;
                        $insert['event_id'] = $event_id;
                        $insert['type'] = 'S';
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
                                            "currency" => trim($get_current_currency['code']),
                                            "source" => $_POST['stripeToken'], // obtained with Stripe.js
                                            "description" => $this->input->post('purpose')
                                ));

                                $charge_response = $charge->jsonSerialize();

                                if ($charge_response['paid'] == true) {

                                    // Price update for Vendor and Admin 
                                    if (get_site_setting('enable_membership') == 'Y') {
                                        $vendor_amount = $final_price;
                                        $admin_amount = 0;
                                    } else {
                                        $vendor_amount = get_vendor_amount($final_price, $service_data['created_by']);
                                        $admin_amount = get_admin_amount($final_price);
                                    }


                                    $insert['payment_status'] = 'S';
                                    $insert['status'] = 'A';
                                    $insert['addons_id'] = $addons_id;
                                    $insert['vendor_price'] = $vendor_amount;
                                    $insert['admin_price'] = $admin_amount;
                                    $insert['created_on'] = date("Y-m:d H:i:s");
                                    $insert['price'] = $final_price;
                                    $insert['staff_id'] = $staff_member_id;
                                    $book = $this->model_front->insert("app_event_book", $insert);

                                    $data['customer_id'] = $customer_id;
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

                                    $up_qry_vendor = $this->db->query("UPDATE app_users SET my_earning=my_earning+" . $vendor_amount . ",my_wallet=my_wallet+" . $vendor_amount . " WHERE id=" . $service_data['created_by']);
                                    $up_qry_admin = $this->db->query("UPDATE app_users SET my_wallet=my_wallet+" . $admin_amount . " WHERE id=1");

                                    $transaction = true;

                                    //Send email to customer
                                    $customer = $this->model_front->getData("app_customer", "first_name,last_name,email", "id='$customer_id'");
                                    $name = ($customer[0]['first_name']) . " " . ($customer[0]['last_name']);
                                    $subject = translate('appointment_booking');
                                    $define_param['to_name'] = ($customer[0]['first_name']) . " " . ($customer[0]['last_name']);
                                    $define_param['to_email'] = $customer[0]['email'];

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
                                    $parameterv['customer_data'] = $customer[0];
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
                                        $parameters['customer_data'] = $customer[0];
                                        $parameters['price'] = price_format($final_price);

                                        $html2 = $this->load->view("email_template/service_booking_vendor", $parameters, true);
                                        $this->sendmail->send($define_param2, $subject2, $html2);
                                    endif;

                                    $this->session->set_flashdata('msg', translate('transaction_success_event') . "<br>" . translate('booking_insert'));
                                    $this->session->set_flashdata('msg_class', 'success');
                                    redirect('appointment-success/' . $book);
                                } else {
                                    $this->session->set_flashdata('msg', translate('transaction_fail'));
                                    $this->session->set_flashdata('msg_class', 'failure');
                                    redirect(base_url());
                                }
                            } catch (\Stripe\Error\Card $e) {
                                $body = $e->getJsonBody();
                                $err = $body['error'];
                                $this->session->set_flashdata('msg', $err['message']);
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('day-slots/' . $event_id));
                            } catch (\Stripe\Error\RateLimit $e) {
                                $this->session->set_flashdata('msg', "Too many requests made to the API too quickly");
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('day-slots/' . $event_id));
                            } catch (\Stripe\Error\InvalidRequest $e) {
                                $this->session->set_flashdata('msg', "Invalid parameters were supplied to Stripe's API");
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('day-slots/' . $event_id));
                            } catch (\Stripe\Error\Authentication $e) {
                                $this->session->set_flashdata('msg', "Authentication with Stripe's API failed");
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('day-slots/' . $event_id));
                            } catch (\Stripe\Error\ApiConnection $e) {
                                $this->session->set_flashdata('msg', "Network communication with Stripe failed");
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('day-slots/' . $event_id));
                            } catch (\Stripe\Error\Base $e) {
                                $this->session->set_flashdata('msg', "Something else happened, completely unrelated to Stripe");
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('day-slots/' . $event_id));
                            } catch (Exception $e) {
                                $this->session->set_flashdata('msg', "Something else happened, completely unrelated to Stripe");
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('day-slots/' . $event_id));
                            }
                        } else {
                            redirect(base_url('day-slots/' . $event_id));
                        }
                    }
                else:
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('invalid_request'));
                    redirect(base_url());
                endif;
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url());
            endif;
        }else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        }
    }

    //add booking by paypal
    public function booking_paypal() {
        //Get current set category
        $get_current_currency = get_current_currency();
        if ($get_current_currency['paypal_supported'] == 'Y') {
            $this->load->library('paypal');

            $customer_id = (int) $this->session->userdata('CUST_ID');
            if ($customer_id == 0) {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('protected_message'));
                redirect('login');
            }

            $description = $this->input->post('description');
            $slot_time = $this->input->post('user_slot_time');
            $event_id = (int) $this->input->post('event_id');
            $bookdate = $this->input->post('user_datetime');
            $staff_member_id = $this->input->post('staff_member_id');
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
                        redirect(base_url('day-slots/' . $event_id));
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

                        $insert['customer_id'] = $customer_id;
                        $insert['description'] = $description;
                        $insert['slot_time'] = $slot_time;
                        $insert['event_id'] = $event_id;
                        $insert['start_date'] = date("Y-m-d", strtotime($bookdate));
                        $insert['start_time'] = date("H:i:s", strtotime($bookdate));
                        $insert['payment_status'] = 'IN';
                        $insert['created_on'] = date("Y-m-d H:i:s");
                        $insert['status'] = 'IN';
                        $insert['type'] = 'S';
                        $insert['staff_id'] = $staff_member_id;
                        $insert['addons_id'] = $addons_id;
                        $app_event_book = $this->model_front->insert("app_event_book", $insert);

                        $this->session->set_userdata('booking_id', $app_event_book);
                        $this->session->set_userdata('description', $description);
                        $this->session->set_userdata('bookdate', $bookdate);
                        $this->session->set_userdata('event_id', $event_id);
                        $this->session->set_userdata('event_price', $final_price);

                        $this->paypal->add_field('rm', 2);
                        $this->paypal->add_field('cmd', '_xclick');
                        $this->paypal->add_field('amount', $final_price);
                        $this->paypal->add_field('item_name', "Event Booking Payment");
                        $this->paypal->add_field('currency_code', trim($get_current_currency['code']));
                        $this->paypal->add_field('custom', $app_event_book);
                        $this->paypal->add_field('business', get_payment_setting('paypal_merchant_email'));
                        $this->paypal->add_field('cancel_return', base_url('paypal_cancel'));
                        $this->paypal->add_field('return', base_url('paypal_success'));
                        $this->paypal->submit_paypal_post();
                    }
                else:
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('invalid_request'));
                    redirect(base_url());
                endif;
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url());
            endif;
        }else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        }
    }

    public function booking_two_checkout() {
        $get_current_currency = get_current_currency();

        if (check_payment_method('2checkout') && $get_current_currency['2checkout_supported'] == 'Y'):


            $customer_id = (int) $this->session->userdata('CUST_ID');
            if ($customer_id == 0) {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('protected_message'));
                redirect('login');
            }

            $description = $this->input->post('description');
            $slot_time = $this->input->post('user_slot_time');
            $event_id = (int) $this->input->post('event_id');
            $bookdate = $this->input->post('user_datetime');
            $staff_member_id = $this->input->post('staff_member_id');
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
                        redirect(base_url('day-slots/' . $event_id));
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

                        $insert['customer_id'] = $customer_id;
                        $insert['description'] = $description;
                        $insert['slot_time'] = $slot_time;
                        $insert['event_id'] = $event_id;
                        $insert['start_date'] = date("Y-m-d", strtotime($bookdate));
                        $insert['start_time'] = date("H:i:s", strtotime($bookdate));
                        $insert['payment_status'] = 'IN';
                        $insert['created_on'] = date("Y-m-d H:i:s");
                        $insert['status'] = 'IN';
                        $insert['type'] = 'S';
                        $insert['staff_id'] = $staff_member_id;
                        $insert['addons_id'] = $addons_id;
                        $app_event_book = $this->model_front->insert("app_event_book", $insert);

                        $this->session->set_userdata('booking_id', $app_event_book);
                        $this->session->set_userdata('description', $description);
                        $this->session->set_userdata('bookdate', $bookdate);
                        $this->session->set_userdata('event_id', $event_id);
                        $this->session->set_userdata('event_price', $final_price);

                        include APPPATH . 'third_party/2checkout/Twocheckout.php';

                        // Your sellerId(account number) and privateKey are required to make the Payment API Authorization call.
                        Twocheckout::privateKey(get_payment_setting('2checkout_private_key'));
                        Twocheckout::sellerId(get_payment_setting('2checkout_account_no'));

                        // If you want to turn off SSL verification (Please don't do this in your production environment)
                        Twocheckout::verifySSL(false);  // this is set to true by default
                        // To use your sandbox account set sandbox to true

                        if (get_payment_setting('2checkout_live_sandbox') == 'S'):
                            Twocheckout::sandbox(true);
                        else:
                            Twocheckout::sandbox(FALSE);
                        endif;


                        // All methods return an Array by default or you can set the format to 'json' to get a JSON response.
                        Twocheckout::format('json');

                        $params = array(
                            'sid' => get_payment_setting('2checkout_account_no'),
                            'mode' => '2CO',
                            'currency_code' => $get_current_currency['code'],
                            'li_0_name' => 'Booking Payment',
                            'li_0_price' => $final_price,
                            'card_holder_name' => $this->input->post('first_name') . " " . $this->input->post('last_name'),
                            'email' => $this->input->post('email'),
                            'booking_type' => "S",
                            'x_receipt_link_url' => base_url('2checkout-success')
                        );
                        Twocheckout_Charge::form($params, 'auto');
                    }
                else:
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('invalid_request'));
                    redirect(base_url());
                endif;
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url());
            endif;
        else:
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        endif;
    }

    public function two_checkout_success() {

        include APPPATH . 'third_party/2checkout/Twocheckout.php';
        $params = array();
        foreach ($_REQUEST as $k => $v) {
            $params[$k] = $v;
        }
        $passback = Twocheckout_Return::check($params, "booking");
        if ($passback['response_code'] == "Success") {
            if (isset($_REQUEST['booking_type']) && $_REQUEST['booking_type'] == "S") {
                $booking_id = $this->session->userdata('booking_id');

                $service_booking_data = get_booking_details($booking_id);
                $staff_id = isset($service_booking_data['staff_id']) ? $service_booking_data['staff_id'] : 0;

                $description = $this->session->userdata('description');
                $bookdate = $this->session->userdata('bookdate');
                $event_price = $this->session->userdata('event_price');
                $event_id = $this->session->userdata('event_id');

                $customer_id = (int) $this->session->userdata('CUST_ID');
                $service_data = get_full_event_service_data($event_id);
                $event_title = isset($service_data['title']) ? ($service_data['title']) : '';

                if (get_site_setting('enable_membership') == 'Y') {
                    $vendor_amount = $event_price;
                    $admin_amount = 0;
                } else {
                    $vendor_amount = get_vendor_amount($event_price, $service_data['created_by']);
                    $admin_amount = get_admin_amount($event_price);
                }


                $data['customer_id'] = $customer_id;
                $data['vendor_id'] = $service_data['created_by'];
                $data['event_id'] = $event_id;
                $data['booking_id'] = $booking_id;
                $data['vendor_price'] = $vendor_amount;
                $data['admin_price'] = $admin_amount;
                $data['payment_id'] = $_REQUEST['invoice_id'];
                $data['customer_payment_id'] = $_REQUEST['order_number'];
                $data['transaction_id'] = $_REQUEST['order_number'];
                $data['payment_price'] = $event_price;
                $data['failure_code'] = '';
                $data['failure_message'] = '';
                $data['payment_method'] = '2Checkout';
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

                $up_qry_vendor = $this->db->query("UPDATE app_users SET my_earning=my_earning+" . $vendor_amount . ",my_wallet=my_wallet+" . $vendor_amount . " WHERE id=" . $service_data['created_by']);
                $up_qry_admin = $this->db->query("UPDATE app_users SET my_wallet=my_wallet+" . $admin_amount . " WHERE id=1");

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

                $this->session->set_flashdata('msg', translate('transaction_success_event') . "<br>" . translate('booking_insert'));
                $this->session->set_flashdata('msg_class', 'success');
                redirect('appointment-success/' . $booking_id);
            } else if (isset($_REQUEST['booking_type']) && $_REQUEST['booking_type'] == "E") {
                $booking_id = $this->session->userdata('booking_id');

                $description = $this->session->userdata('description');
                $bookdate = $this->session->userdata('bookdate');
                $event_price = $this->session->userdata('event_price');
                $event_id = $this->session->userdata('event_id');

                $customer_id = (int) $this->session->userdata('CUST_ID');
                $event_data = get_full_event_service_data($event_id);
                $event_title = isset($event_data['title']) ? ($event_data['title']) : '';
                $event_booking_seat = isset($event_data['event_booked_seat']) ? ($event_data['event_booked_seat']) : '';

                if (get_site_setting('enable_membership') == 'Y') {
                    $vendor_amount = $event_price;
                    $admin_amount = 0;
                } else {
                    $vendor_amount = get_vendor_amount($event_price, $event_data['created_by']);
                    $admin_amount = get_admin_amount($event_price);
                }


                $data['customer_id'] = $customer_id;
                $data['vendor_id'] = $event_data['created_by'];
                $data['event_id'] = $event_id;
                $data['booking_id'] = $booking_id;
                $data['vendor_price'] = $vendor_amount;
                $data['admin_price'] = $admin_amount;

                $data['payment_id'] = $_REQUEST['invoice_id'];
                $data['customer_payment_id'] = $_REQUEST['order_number'];
                $data['transaction_id'] = $_REQUEST['order_number'];


                $data['payment_price'] = $event_price;
                $data['failure_code'] = '';
                $data['failure_message'] = '';
                $data['payment_method'] = '2Checkout';
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

                $up_qry_vendor = $this->db->query("UPDATE app_users SET my_earning=my_earning+" . $vendor_amount . ",my_wallet=my_wallet+" . $vendor_amount . " WHERE id=" . $event_data['created_by']);
                $up_qry_admin = $this->db->query("UPDATE app_users SET my_wallet=my_wallet+" . $admin_amount . " WHERE id=1");


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
                redirect('appointment-success/' . $booking_id);
            } else if (isset($_REQUEST['booking_type']) && $_REQUEST['booking_type'] == "VP") {
                $this->load->model('model_membership');

                $vendor_id = $_REQUEST['vendor_id'];
                $package = $_REQUEST['package'];
                $membership_till = $_REQUEST['membership_till'];

                $data['customer_id'] = $vendor_id;
                $data['package_id'] = $package;
                $data['remaining_event'] = 0;
                $data['payment_method'] = '2Checkout';
                $data['transaction_id'] = $_REQUEST['order_number'];
                $data['customer_payment_id'] = $_REQUEST['invoice_id'];
                $data['payment_id'] = $_REQUEST['order_number'];
                $data['payment_status'] = 'paid';
                $data['membership_till'] = $membership_till;
                $data['failure_code'] = "";
                $data['failure_message'] = "";
                $data['price'] = $_REQUEST['total'];
                $data['status'] = 'A';
                $data['created_on'] = date('Y-m-d H:i:s');

                $vendor_update_data['package_id'] = $package;
                $vendor_update_data['membership_till'] = $membership_till;

                $this->db->query("UPDATE app_event SET status='A' WHERE created_by=" . $vendor_id . " AND status='SS'");

                $this->model_membership->insert('app_membership_history', $data);
                $this->model_membership->update('app_users', $vendor_update_data, "id=" . $vendor_id);


                $this->session->set_flashdata('msg', translate('transaction_success'));
                $this->session->set_flashdata('msg_class', 'success');
                redirect('vendor/membership');
            } else {
                
            }
        } else {
            $booking_id = (int) $this->session->userdata('booking_id');
            $this->db->where("id", $booking_id);
            $this->db->delete("app_event_book");

            $this->session->unset_userdata('booking_id');
            $this->session->unset_userdata('description');
            $this->session->unset_userdata('bookdate');
            $this->session->unset_userdata('event_price');
            $this->session->unset_userdata('event_id');

            $this->session->set_flashdata('msg', translate('transaction_fail'));
            $this->session->set_flashdata('msg_class', 'failure');
            redirect(base_url());
        }
    }

    public function paypal_success() {

        if (isset($_REQUEST['st']) && $_REQUEST['st'] == "Completed") {

            $booking_id = $this->session->userdata('booking_id');

            $service_booking_data = get_booking_details($booking_id);
            $staff_id = isset($service_booking_data['staff_id']) ? $service_booking_data['staff_id'] : 0;

            $description = $this->session->userdata('description');
            $bookdate = $this->session->userdata('bookdate');
            $event_price = $this->session->userdata('event_price');
            $event_id = $this->session->userdata('event_id');

            $customer_id = (int) $this->session->userdata('CUST_ID');
            $service_data = get_full_event_service_data($event_id);
            $event_title = isset($service_data['title']) ? ($service_data['title']) : '';

            if (get_site_setting('enable_membership') == 'Y') {
                $vendor_amount = $event_price;
                $admin_amount = 0;
            } else {
                $vendor_amount = get_vendor_amount($event_price, $service_data['created_by']);
                $admin_amount = get_admin_amount($event_price);
            }


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

            $up_qry_vendor = $this->db->query("UPDATE app_users SET my_earning=my_earning+" . $vendor_amount . ",my_wallet=my_wallet+" . $vendor_amount . " WHERE id=" . $service_data['created_by']);
            $up_qry_admin = $this->db->query("UPDATE app_users SET my_wallet=my_wallet+" . $admin_amount . " WHERE id=1");

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


            $this->session->set_flashdata('msg', translate('transaction_success_event') . "<br>" . translate('booking_insert'));
            $this->session->set_flashdata('msg_class', 'success');
            redirect('appointment-success/' . $booking_id);
        } else {
            $booking_id = $this->session->userdata('booking_id');
            $this->db->where("id", $booking_id);
            $this->db->delete("app_event_book");

            $this->session->unset_userdata('booking_id');
            $this->session->unset_userdata('description');
            $this->session->unset_userdata('bookdate');
            $this->session->unset_userdata('event_price');
            $this->session->unset_userdata('event_id');

            $this->session->set_flashdata('msg', translate('transaction_fail'));
            $this->session->set_flashdata('msg_class', 'failure');
            redirect(base_url());
        }
    }

    public function paypal_cancel() {
        //remove booked event due to unseccesfull payment
        $booking_id = $this->session->userdata('booking_id');
        $this->db->where("id", $booking_id);
        $this->db->delete("app_event_book");

        //unset session value
        $this->session->unset_userdata('booking_id');
        $this->session->unset_userdata('description');
        $this->session->unset_userdata('bookdate');
        $this->session->unset_userdata('event_id');

        $this->session->set_flashdata('msg', translate('transaction_fail'));
        $this->session->set_flashdata('msg_class', 'failure');
        redirect(base_url());
    }

    //appointment list
    public function appointment() {
        $event = isset($_REQUEST['event']) ? $_REQUEST['event'] : "";
        $vendor = isset($_REQUEST['vendor']) ? $_REQUEST['vendor'] : "";
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : "";
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : "";

        $this->authenticate->check();
        $customer_id = (int) $this->session->userdata('CUST_ID');
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
        $cur_date = date("Y-m-d");
        $condition = "app_event_book.customer_id=" . $customer_id . " AND app_event_book.type='S' AND app_event_book.start_date>='" . $cur_date . "' ";
        $condition_past = "app_event_book.customer_id=" . $customer_id . " AND app_event_book.type='S' AND app_event_book.start_date<'" . $cur_date . "' ";

        if (isset($event) && $event > 0) {
            $condition .= " AND app_event_book.event_id=" . $event;
        }

        if (isset($vendor) && $vendor > 0) {
            $condition .= " AND app_event.created_by=" . $vendor;
        }

        if (isset($status) && $status != "") {
            $condition .= " AND app_event_book.status='" . $status . "'";
        }
        if (isset($type) && $type != "") {
            $condition .= " AND app_event.payment_type='" . $type . "'";
        }

        $appointment = $this->model_front->getData("app_event_book", "app_event_book.*,app_users.id as aid ,app_event_book.price as final_price,app_users.company_name,app_event.title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,app_customer.first_name,app_customer.last_name,app_customer.phone,app_event.price,app_users.first_name,app_users.last_name,app_users.company_name,app_event.image,app_event.description as event_description, app_event.payment_type", $condition, $join, "app_event_book.start_date ASC,app_event_book.start_time ASC");
        $past_appointment = $this->model_front->getData("app_event_book", "app_event_book.*,app_users.id as aid ,app_event_book.price as final_price,app_users.company_name,app_event.title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,app_customer.first_name,app_customer.last_name,app_customer.phone,app_event.price,app_users.first_name,app_users.last_name,app_users.company_name,app_event.image,app_event.description as event_description, app_event.payment_type", $condition_past, $join, "app_event_book.start_date DESC,app_event_book.start_time DESC");

        $city_join = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'inner'
            )
        );
        $top_cities = $this->model_front->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');

        $data['past_appointment'] = $past_appointment;
        $data['appointment_data'] = $appointment;

        $data['topCity_List'] = $top_cities;
        $data['title'] = translate('my_appointment');
        $this->load->view('front/profile/appointment', $data);
    }

    public function my_event_booking() {
        $this->authenticate->check();
        $customer_id = (int) $this->session->userdata('CUST_ID');
        $join = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_event.id=app_event_book.event_id',
                'jointype' => 'INNER'
            ),
            array(
                'table' => 'app_city',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'INNER'
            ),
            array(
                'table' => 'app_location',
                'condition' => 'app_location.loc_id=app_event.location',
                'jointype' => 'INNER'
            ),
            array(
                'table' => 'app_event_category',
                'condition' => 'app_event_category.id=app_event.category_id',
                'jointype' => 'INNER'
            ),
            array(
                'table' => 'app_customer',
                'condition' => 'app_customer.id=app_event_book.customer_id',
                'jointype' => 'INNER'
            ),
            array(
                'table' => 'app_users',
                'condition' => 'app_users.id=app_event.created_by',
                'jointype' => 'INNER'
            )
        );

        $cur_date = date("Y-m-d");
        $condition = "app_event_book.customer_id=" . $customer_id . " AND app_event_book.type='E' AND DATE(app_event.end_date)>='" . $cur_date . "' ";
        $condition_past = "app_event_book.customer_id=" . $customer_id . " AND app_event_book.type='E' AND DATE(app_event.end_date)<'" . $cur_date . "' ";

        $appointment = $this->model_front->getData("app_event_book", "app_event_book.*,app_users.id as aid ,app_event_book.price as final_price,app_users.company_name,app_event.title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,app_customer.first_name,app_customer.last_name,app_customer.phone,app_event.price,app_users.first_name,app_users.last_name,app_users.company_name,app_event.image,app_event.description as event_description, app_event.payment_type", $condition, $join, "app_event_book.start_date ASC,app_event_book.start_time ASC");
        $past_appointment = $this->model_front->getData("app_event_book", "app_event_book.*,app_users.id as aid ,app_event_book.price as final_price,app_users.company_name,app_event.title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,app_customer.first_name,app_customer.last_name,app_customer.phone,app_event.price,app_users.first_name,app_users.last_name,app_users.company_name,app_event.image,app_event.description as event_description, app_event.payment_type", $condition_past, $join, "app_event_book.start_date DESC,app_event_book.start_time DESC");

        $data['appointment_data'] = $appointment;
        $data['past_appointment'] = $past_appointment;

        $data['title'] = translate('event') . " " . translate('booking');
        $this->load->view('front/profile/event-booking', $data);
    }

    //delete appointment
    public function delete_appointment() {
        $id = (int) $this->uri->segment(2);
        $services = $this->model_front->getData("app_event_book", "*", "id='$id' AND status IN('P','R')");
        if (count($services) > 0) {
            $event_id = (int) $services[0]['event_id'];
            $customer_id = (int) $services[0]['customer_id'];
            $app_customer = $this->model_front->getData("app_customer", "*", "id=" . $customer_id)[0];

            $bookdate = date("d-m-Y", strtotime($services[0]['start_date'])) . " " . date("h:i a", strtotime($services[0]['start_time']));
            $this->model_front->delete("app_event_book", "id=" . $id);
            $this->model_front->delete("app_appointment_payment", "booking_id=" . $id);

            if ($services[0]['type'] == 'E'):
                $this->model_front->delete("app_event_ticket_type_booking", "booking_id=" . $id);
            endif;

            if ($event_id > 0) {
                $event_data = get_full_event_service_data($event_id);
                //Send email to vendor
                $vendor_name = ($event_data['first_name']) . " " . ($event_data['last_name']);
                $vendor_email = $event_data['email'];
                $subject2 = translate('notification') . ' | ' . $event_data['title'];
                $define_param2['to_name'] = $vendor_name;
                $define_param2['to_email'] = $vendor_email;

                $parameterv['name'] = $vendor_name;
                $parameterv['event_data'] = $event_data;
                $parameterv['booking_date'] = get_formated_date($bookdate);
                $parameterv['customer_data'] = $app_customer;

                $html2 = $this->load->view("email_template/notifications", $parameterv, true);
                $this->sendmail->send($define_param2, $subject2, $html2);
            }
            $this->session->set_flashdata('msg', translate('appointment_delete'));
            $this->session->set_flashdata('msg_class', 'success');
            echo 'true';
            exit;
        } else {
            $this->session->set_flashdata('msg', translate('invalid_request'));
            $this->session->set_flashdata('msg_class', 'failure');
            echo 'false';
            exit;
        }
    }

    //update appointment
    public function update_appointment($id, $date = NULL) {
        $event = $this->model_front->getData("app_event_book", "*", "id='$id'");
        if (isset($event) && count($event) > 0) {
            if (!is_null($date)) {
                $data['update_date'] = $this->general->decrypt($date);
            }
            $data['appointment_data'] = $event[0];
            $data['title'] = translate('manage') . " " . translate('appointment');
            $this->load->view('front/profile/manage-appointment', $data);
        } else {
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        }
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
            $result = $this->model_front->getData("app_event_book", "start_time,slot_time", "start_date = '" . $start_date . "' AND staff_id=" . $staff_member_id . " AND status IN ('A')");
        else:
            $result = $this->model_front->getData("app_event_book", "start_time,slot_time", "start_date = '" . $start_date . "' AND event_id=" . $cur_event_id . " AND status IN ('A')");
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

    public function submit_rating($event_id) {
        $user_id = $this->session->userdata('CUST_ID');
        $rating = $this->input->post('rating');
        $review = $this->input->post('review');
        $appointment_id = $this->input->post('appointment_id');
        $check_rating = $this->model_front->getData('app_rating', 'id', "user_id='$user_id' AND event_id='$event_id'");
        if (isset($check_rating) && count($check_rating) == 0) {
            $data = array(
                'user_id' => $user_id,
                'event_id' => $event_id,
                'rating' => $rating,
                'appointment_id' => $appointment_id,
                'review' => $review
            );
            $id = $this->model_front->insert('app_rating', $data);
        }
        echo 'true';
        exit;
    }

    public function profile_details() {
        $company_name = trim($this->uri->segment(2));
        $admin_id = (int) trim($this->uri->segment(3));
        if ($admin_id > 0) {
            $user_id = $this->session->userdata('CUST_ID');


            $admin_data = $this->model_front->getData("app_users", "*", "id=" . $admin_id);
            if (count($admin_data) > 0) {
                $admin_id = $admin_data[0]['id'];
                $data['admin_data'] = $admin_data[0];
                $data['title'] = $admin_data[0]['company_name'];
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
                        'table' => 'app_users',
                        'condition' => 'app_users.id=app_event.created_by',
                        'jointype' => 'left'
                    )
                );
                $cjoin = array(array(
                        'table' => 'app_event',
                        'condition' => 'app_event_category.id=app_event.category_id',
                        'jointype' => 'INNER'
                ));
                $event_data = $this->model_front->getData("app_event", "app_users.company_name,app_users.profile_image,app_event.*,app_event_category.title as category_title,app_event_category.event_category_image, app_city.city_title,app_location.loc_title, app_users.profile_image, app_users.company_name", "app_event.status='A'AND app_event.type='E' AND app_event.created_by='$admin_id'", $join);
                $service_data = $this->model_front->getData("app_event", "app_users.company_name,app_users.profile_image,app_event.*,app_event_category.title as category_title,app_event_category.event_category_image, app_city.city_title,app_location.loc_title, app_users.profile_image, app_users.company_name", "app_event.status='A'AND app_event.type='S' AND app_event.created_by='$admin_id'", $join);
                $category_data = $this->model_front->getData("app_event_category", "app_event_category.*", "app_event.status='A' AND app_event.created_by='$admin_id'", $cjoin, 'title', 'app_event_category.id');
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
                $top_cities = $this->model_front->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');

                /*
                 * recent list of booked events
                 */
                $join = array(
                    array(
                        'table' => 'app_event_book',
                        'condition' => 'app_event_book.event_id=app_event.id',
                        'jointype' => 'inner'
                    )
                );
                $book_cond = 'app_event.status="A"';

                $recent_events = $this->model_front->getData("app_event", 'app_event.*', $book_cond, $join, '', 'app_event.id', '', 10);

                $city_join = array(
                    array(
                        'table' => 'app_event',
                        'condition' => 'app_city.city_id=app_event.city',
                        'jointype' => 'inner'
                    )
                );
                $top_cities = $this->model_front->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');

                //customer rating data
                if ($user_id) {
                    $rating_data = $this->model_front->getData('app_vendor_review', 'app_vendor_review.*', 'app_vendor_review.customer_id = ' . $user_id . ' AND app_vendor_review.vendor_id = ' . $admin_id);
                }

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
                $vendor_rating_data = $this->model_front->getData('app_vendor_review', 'app_event.title, app_event.category_id, app_customer.first_name, app_customer.last_name, app_customer.profile_image, app_vendor_review.*', 'app_vendor_review.vendor_id = ' . $admin_id, $rjoin);

                // gallery data
                $vendor_gallery_data = $this->model_front->getData('app_slider', 'app_slider.*', 'app_slider.created_by = ' . $admin_id);

                $data['topCity_List'] = $top_cities;
                $data['rating_data'] = isset($rating_data) ? $rating_data : array();
                $data['vendor_rating_data'] = $vendor_rating_data;
                $data['vendor_gallery_data'] = $vendor_gallery_data;
                $data['Recent_events'] = $recent_events;
                $data['topCity_List'] = $top_cities;
                $data['event_data'] = $event_data;
                $data['service_data'] = $service_data;
                $data['category_data'] = $category_data;
                $this->load->view('front/profile/profile-details', $data);
            } else {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url());
            }
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        }
    }

    public function message($id = NULL) {

        $customer_id = (int) $this->session->userdata('CUST_ID');
        $data['vendor_list'] = $this->model_front->message_vendor_list($customer_id);
        if (is_null($id)) {
            $id = isset($data['vendor_list'][0]['id']) ? $data['vendor_list'][0]['id'] : 0;
        }
        $check_chat = $this->model_front->getData('app_chat_master', 'id', "vendor_id='$id' AND customer_id='$customer_id'");
        if (isset($check_chat) && count($check_chat) == 0) {
            $insert_master = array(
                'customer_id' => $customer_id,
                'vendor_id' => $id,
                'created_on' => date('Y-m-d H:i:s')
            );
            $chat_id = $this->model_front->insert('app_chat_master', $insert_master);
        } else {
            if (isset($check_chat) && !empty($check_chat)) {
                foreach ($check_chat as $row) {
                    $chat_id = $row['id'];
                }
            }
        }

        $city_join = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'inner'
            )
        );
        $top_cities = $this->model_front->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');

        $data['topCity_List'] = $top_cities;
        $data['vendor_list'] = $this->model_front->message_vendor_list($customer_id);
        $this->model_front->update('app_chat', array('msg_read' => 'Y'), "to_id='$customer_id' AND from_id='$id' AND chat_type='NC'");

        if ($id > 0) {
            $data['msg_vendor_data'] = $this->model_front->msg_vendor_data($id);
            $data['msg_group_list'] = $this->model_front->msg_group_list($id, $customer_id);
            $data['admin_details'] = $this->model_front->getData('app_users', 'app_users.*', 'id=' . $id);
        }

        $data['title'] = translate('message');
        $data['chat_id'] = isset($chat_id) ? $chat_id : 0;
        $this->load->view('front/profile/message', $data);
    }

    public function message_action() {
        $from_id = (int) $this->session->userdata('CUST_ID');
        $to_id = (int) $this->input->post('msg_to_id');
        $message = $this->input->post('message');
        $chat_id = (int) $this->model_front->get_chat_id($to_id, $from_id);
        if (isset($chat_id) && $chat_id > 0) {
            $inser_data = array(
                'chat_id' => $chat_id,
                'to_id' => $to_id,
                'from_id' => $from_id,
                'message' => $message,
                'chat_type' => 'C',
                'created_on' => date('Y-m-d H:i:s')
            );
            $this->model_front->insert('app_chat', $inser_data);
        }
        redirect('message/' . $to_id);
    }

    //show home page
    public function payment_history() {
        $join = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_event.id=app_event_book.event_id',
                'jointype' => 'inner'
            ),
            array(
                'table' => 'app_appointment_payment',
                'condition' => 'app_appointment_payment.event_id=app_event_book.event_id',
                'jointype' => 'inner'
            )
        );
        $CUST_ID = (int) $this->session->userdata('CUST_ID');
        $payment_history = $this->model_front->getData("app_event_book", 'app_event.*,app_appointment_payment.payment_method as Payment_method, app_appointment_payment.payment_status as Payment_status,app_appointment_payment.created_on as payment_date,app_appointment_payment.payment_price', "app_event.status='A' AND app_appointment_payment.customer_id=" . $CUST_ID, $join, "", "app_appointment_payment.id");
        $data['payment_history'] = $payment_history;
        $data['title'] = translate('manage') . " " . translate('payment_history');

        $city_join = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'inner'
            )
        );
        $top_cities = $this->model_front->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');
        $data['topCity_List'] = $top_cities;

        $this->load->view('front/profile/payment_history', $data);
    }

    public function update_booking() {
        $appointment_id = (int) $this->input->post('appointment_id');
        $description = $this->input->post('description');
        $bookdate = $this->input->post('start_date');
        $insert['description'] = $description;
        $insert['start_date'] = date("Y-m-d", strtotime($bookdate));
        $insert['start_time'] = date("H:i:s", strtotime($bookdate));
        if ($appointment_id > 0) {
            $book = $this->model_front->update("app_event_book", $insert, "id='$appointment_id'");
        }
        $this->session->set_flashdata('msg', translate('booking_update'));
        $this->session->set_flashdata('msg_class', 'success');
        redirect('appointment');
    }

    function appointment_success($id) {
        $from_id = (int) $this->session->userdata('CUST_ID');
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
        $event = $this->model_front->getData("app_event_book", "app_event.image,app_event_book.*,app_event.title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title", "app_event_book.id='$id' AND app_event_book.customer_id=" . $from_id, $join);

        if (count($event) > 0) {
            //$data['invoice_path'] = $this->GeneratePDF_HTML("E", $id);
            //$this->model_front->update('app_event_book', array('invoice_file' => $data['invoice_path']), "id='$id'");
            $data['event_data'] = $event[0];
            $data['title'] = translate('appointment') . " " . translate('details');
            $this->load->view('front/profile/appointment-success', $data);
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        }
    }

    public function GeneratePDF_HTML($flag, $id) {
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
        $event = $this->model_front->getData("app_event_book", "app_users.company_name, app_users.first_name as afname, app_users.last_name as alname, app_users.email as aemail, app_users.phone as aphone, app_event_book.*,app_event.created_by,app_event.title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,app_customer.first_name,app_customer.last_name,app_customer.phone,app_event.price, app_customer.email", "app_event_book.id='$id'", $join);
        $img_path = get_CompanyLogo();

        if (isset($event) && !empty($event)) {
            foreach ($event as $eRow) {
                $title = $eRow['title'];
                $loc_title = $eRow['loc_title'];
                $city_title = $eRow['city_title'];
                $afname = $eRow['afname'];
                $alname = $eRow['alname'];
                $aemail = $eRow['aemail'];
                $aphone = $eRow['aphone'];
                $first_name = $eRow['first_name'];
                $last_name = $eRow['last_name'];
                $phone = $eRow['phone'];
                $company_name = $eRow['company_name'];
                $price = $eRow['price'];
                $email = $eRow['email'];
                $start_date = $eRow['start_date'];
                $start_time = $eRow['start_time'];
                $category_title = $eRow['category_title'];
                $slot_time = $eRow['slot_time'];
                $appointment_date = get_formated_date($start_date, "");
                $appointment_time = $appointment_date . " | " . $start_time;
            }

            $html = '
<html>
<head>
<style>
    td, th{
        font-size : 12px !important;
        
        margin : 0 !important;
    }
</style>
</head>
<body>
    <div style="margin:10px auto;text-align: center; border-bottom: 1px solid #000000; font-weight: bold; font-size: 10pt;">
        <img style="margin:10px auto;" src="' . $img_path . '"/>
    </div>
    <div class="vendor_detail" style="width:45%;float:left;">
    <p style="margin:5px 0px; font-size:14px;">' . translate('vendor') . " " . translate('details') . '</p>
    
    <p style="margin:0; font-size:12px;"><span width="25%"><strong>' . translate('company') . '</strong><span> : <span width="75%">' . $company_name . '</span>
    <p style="margin:0;font-size:12px;"><span width="25%"><strong>' . translate('email') . '</strong><span> : <span width="75%">' . $aemail . '</span>
    <p style="margin:0;font-size:12px;"><span width="25%"><strong>' . translate('phone') . '</strong><span> : <span width="75%">' . $aphone . '</span>
    
    </div>
     <div class="vendor_detail" style="margin-left:10%;width:45%;float:right;text-align:right">
    <p style="margin:5px 0px; font-size:14px;float:right;">' . translate('customer') . " " . translate('details') . '</p>
    
    <p style="margin:0; font-size:12px;"><span width="25%"><strong>' . translate('name') . '</strong><span> : <span width="75%">' . ($first_name) . " " . $last_name . '</span>
    <p style="margin:0;font-size:12px;"><span width="25%"><strong>' . translate('email') . '</strong><span> : <span width="75%">' . $email . '</span>
    <p style="margin:0;font-size:12px;"><span width="25%"><strong>' . translate('phone') . '</strong><span> : <span width="75%">' . $phone . '</span>
    
    </div>
    <div style="border-bottom:1px solid #c2c2c2;width:100%;margin:10px 0px;"></div>
    <h3>' . translate('appointment_details') . '</h3>
        <table style="width:100%" border=1>
            <tbody>
                <tr>
                    <th width="25%;">' . translate('title') . '</th>
                    <td width="75%;">' . $title . '</td>
                </tr>
                <tr>
                    <th width="25%;">' . translate('category') . '</th>
                    <td width="75%;">' . $category_title . '</td>
                </tr>
                <tr>
                    <th width="25%;">' . translate('slot_time') . '</th>
                    <td width="75%;">' . $slot_time . " " . translate("minute") . '</td>
                </tr>
                <tr>
                    <th width="25%;">' . translate('city') . '</th>
                    <td width="75%;">' . $city_title . '</td>
                </tr>
                <tr>
                    <th width="25%;">' . translate('location') . '</th>
                    <td width="75%;">' . $loc_title . '</td>
                </tr>
                  <tr>
                    <th width="25%;"> ' . translate('price') . '</th>
                    <td width="75%;">' . price_format($price) . '</td>
                </tr>
                 <tr>
                    <th width="25%;">' . translate('appointment_time') . '</th>
                    <td width="75%;">' . $appointment_time . '</td>
                </tr>
                </tbody>
        </table>
</body>
</html>';
            $this->load->library('mpdf');
            $filename = 'invoice_' . $id . '.pdf';
            $this->mpdf->GeneratePDF($html, $filename);
            return $filename;
        }
    }

    public function locations() {

        $search_txt = $this->input->post('search_txt');
        $city_Res = $this->model_front->getData("app_city", 'app_city.*', "city_status='A' AND city_title LIKE '" . $search_txt . "%'", array());
        if (isset($city_Res) && !empty($city_Res)) {
            echo json_encode(array("status" => "success", "data" => $city_Res));
            exit(0);
        } else {
            echo json_encode(array("status" => "failure"));
            exit(0);
        }
    }

    public function search_events() {

        $search_txt = $this->input->post('search_txt');
        $events = $this->model_front->getData("app_event", 'app_event.*', "app_event.status='A' AND title LIKE '" . $search_txt . "%'", array());
        if (isset($events) && !empty($events)) {
            echo json_encode(array("status" => "success", "data" => $events));
            exit(0);
        } else {
            echo json_encode(array("status" => "failure"));
            exit(0);
        }
    }

    public function locations_events() {
        $locations = $this->input->post('locations');
        $is_search = $this->session->userdata('location');
        if ($locations != '') {
            $locations = implode(",", $this->input->post('locations'));
        }
        $category_id = $this->input->post('category_id');
        $search_txt = $this->input->post('search_txt');
        $row = $this->input->post('row');
        $sort_by = $this->input->post('sort_by');

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
                'table' => 'app_users',
                'condition' => 'app_users.id=app_event.created_by',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_event_book',
                'condition' => 'app_event_book.event_id=app_event.id',
                'jointype' => 'left'
            ),
        );

        $cond = 'app_event.status="A" AND app_event.type="S"';
        if (get_site_setting('is_display_location') == 'Y') {
            $cond .= ' AND app_city.city_title="' . $is_search . '"';
        }

        if (isset($category_id) && $category_id != NULL && $category_id > 0) {
            $cond .= ' AND category_id=' . $category_id;
        }
        if ($search_txt != '') {
            $cond .= ' AND (app_event.title LIKE "' . $search_txt . '%" OR app_event_category.title LIKE "' . $search_txt . '%" OR app_city.city_title LIKE "' . $search_txt . '%" OR app_location.loc_title LIKE "' . $search_txt . '%" OR app_users.company_name LIKE "' . $search_txt . '%")';
        }
        if (isset($locations) && !empty($locations)) {
            $cond .= ' AND location  IN (' . $locations . ')';
        }
        $events = $this->model_front->getData("app_event", '(SELECT COUNT(id) FROM app_event_book WHERE event_id = app_event.id) as totalBook, app_event.*,app_event_category.title as category_title,app_city.city_title, app_location.loc_title, app_users.profile_image, app_users.company_name', $cond, $join, '', 'app_event.id', '', '', array(), '', array(), '', $row, $sort_by);

        $total_Event = $this->model_front->getData("app_event", 'app_event.*,app_event_category.title as category_title,app_city.city_title, app_location.loc_title, app_users.profile_image, app_users.company_name', $cond, $join, '', 'app_event.id', '', '', array(), '', array(), '');
        if (isset($events) && !empty($events)) {
            echo json_encode(array("status" => "success", "data" => $events, "total_Event" => count($total_Event)));
            exit(0);
        } else {
            echo json_encode(array("status" => "failure"));
            exit(0);
        }
    }

    public function discount_coupon() {
        $event_id = $this->input->post('event_id');
        $add_ons_amount = $this->input->post('add_ons_amount');
        $booking_date = $this->input->post('booking_date');
        $discount_coupon = $this->input->post('discount_coupon');
        $coupon = $this->model_front->getData("app_coupon", "*", "code='$discount_coupon' AND status='A'");
        $app_event = $this->model_front->getData("app_event", "*", "id=" . $event_id . " AND status='A'");

        if (count($app_event) > 0) {
            $app_event_data = $app_event[0];

            if (count($coupon) > 0) {
                $coupon_signle_data = $coupon[0];
                $valid_till = $coupon_signle_data['valid_till'];
                $event_id_array = $coupon_signle_data['event_id'];
                $discount_type = $coupon_signle_data['discount_type'];
                $discount_value = $coupon_signle_data['discount_value'];

                //get event price details
                $event_price = 0;
                $discountDate = date('Y-m-d', strtotime($booking_date));
                if (isset($app_event_data['discounted_price']) && $app_event_data['discounted_price'] > 0 && ($discountDate >= $app_event_data['from_date']) && ($discountDate <= $app_event_data['to_date'])) {
                    $event_price = $app_event_data['discounted_price'];
                } else {
                    $event_price = $app_event_data['price'];
                }

                $final_price = ($event_price + $add_ons_amount);
                //Apply coupon disocunt on event price
                if ($discount_type == 'P') {
                    $final_price = ($final_price - ($final_price * ($discount_value / 100)));
                } else {
                    $final_price = $final_price - $discount_value;
                }

                $event_id_ary = json_decode($event_id_array);

                if ($valid_till >= $discountDate) {

                    if (in_array($event_id, $event_id_ary)) {
                        $html = "";
                        echo json_encode(array("status" => true, 'id' => base64_encode($coupon_signle_data['id']), 'price' => ($final_price), "message" => translate('coupon_code_apply')));
                        exit(0);
                    } else {
                        echo json_encode(array("status" => false, "message" => translate('coupon_code_not_associated_event')));
                        exit(0);
                    }
                } else {
                    echo json_encode(array("status" => false, "message" => translate('coupon_code_expired')));
                    exit(0);
                }
            } else {
                echo json_encode(array("status" => false, "message" => translate('invalid_coupon_code')));
                exit(0);
            }
        } else {
            echo json_encode(array("status" => false, "message" => translate('invalid_request')));
            exit(0);
        }
    }

    public function send_message() {
        $from_id = (int) $this->session->userdata('CUST_ID');
        $to_id = (int) $this->input->post('msg_to_id');
        $message = $this->input->post('message');
        $chat_id = (int) $this->model_front->get_chat_id($to_id, $from_id);
        if (isset($chat_id) && $chat_id > 0) {
            $ins_time = date('Y-m-d H:i:s');
            $inser_data = array(
                'chat_id' => $chat_id,
                'to_id' => $to_id,
                'from_id' => $from_id,
                'message' => $message,
                'chat_type' => 'C',
                'created_on' => $ins_time
            );
            $ins_id = $this->model_front->insert('app_chat', $inser_data);
            echo $this->_get_chats_messages($chat_id);
        }

        exit;
    }

    public function ajax_get_chats_messages() {
        /* Posting */
        $chat_id = $this->input->post('chat_id');

        echo $this->_get_chats_messages($chat_id);
    }

    public function _get_chats_messages($chat_id) {
        $last_chat_message_id = (int) $this->session->userdata('last_chat_message_id_' . $chat_id);

        /* Executing the method on model */
        $chats_messages = $this->model_front->get_chats_messages($chat_id, $last_chat_message_id);

        if ($chats_messages->num_rows() > 0) {
            $base_url = base_url();

            /* Store the last chat message id */
            $last_chat_message_id = $chats_messages->row($chats_messages->num_rows() - 1)->id;

            $this->session->set_userdata('last_chat_message_id_' . $chat_id, $last_chat_message_id);

            // return the messages
            $chats_messages_html = "";
            $j = 0;
            foreach ($chats_messages->result() as $chats_messages) {


                $li_class = ($this->session->userdata('CUST_ID') == $chats_messages->from_id) && $chats_messages->chat_type == 'C' ?
                        'class="by_current_user text-right"' : 'class="get_current_user"';

                if ($chats_messages->chat_type == 'C') {
                    $sender_name = $chats_messages->first_name;
                    if ($chats_messages->profile_image != "" && file_exists(FCPATH . "assets/uploads/profiles/" . $chats_messages->profile_image)) {
                        $avatar_img = base_url() . "assets/uploads/profiles/" . $chats_messages->profile_image;
                    } else {
                        $avatar_img = base_url() . "assets/images/user.png";
                    }
                    $append_content = '<p class="message_content">';
                    $append_content .= '<span class="chat-message">' . $chats_messages->message . "</span>";
                    $append_content .= '<img class="rounded-circle" src="' . $avatar_img . '"/> ';
                    $append_content .= '</p>';
                } else {
                    $sender_name = $chats_messages->aname;
                    if ($chats_messages->aprofile_image != "" && file_exists(FCPATH . "assets/uploads/profiles/" . $chats_messages->aprofile_image)) {
                        $avatar_img = base_url() . "assets/uploads/profiles/" . $chats_messages->aprofile_image;
                    } else {
                        $avatar_img = base_url() . "assets/images/user.png";
                    }
                    $append_content = '<p class="message_content">';
                    $append_content .= '<img class="rounded-circle" src="' . $avatar_img . '"/>';
                    $append_content .= '<span class="chat-message">' . $chats_messages->message . "</span>";
                    $append_content .= '</p>';
                }

                $deliver_check_icon = ($chats_messages->msg_read == 'Y') ? "<i class='fa pl-10 fa-check-circle text-info'></i>" : "<i class='fa pl-10 fa-check'></i>";
                $deliver_check_class = ($this->session->userdata('CUST_ID') == $chats_messages->from_id) && $chats_messages->chat_type == 'C' ? $deliver_check_icon : "";

                $chats_messages_html .= '
                        <li ' . $li_class . '>'
                        . $append_content .
                        '<span class="chat_message_header"><i>'
                        . $chats_messages->timestamp
                        . ' by '
                        . $sender_name
                        . '</i>' . $deliver_check_class . '</span></li>';
                if ($j == 0) {
                    $notification_msg = strlen($chats_messages->message) > 100 ? substr($chats_messages->message, 0, 100) . '...' : $chats_messages->message;
                }
                $j++;
            }
            $result = [
                'status' => 'ok',
                'content' => $chats_messages_html,
                'last_chat_message_id' => $last_chat_message_id,
                'notification_msg' => isset($notification_msg) ? $notification_msg : '',
                'total_messages' => $j,
            ];

            return json_encode($result);
            exit();
        } else {
            $result = [
                'status' => 'ok',
                'content' => '',
                'last_chat_message_id' => $last_chat_message_id
            ];

            return json_encode($result);
            exit();
        }
    }

    public function save_vendorreview() {
        $user_id = $this->session->userdata('CUST_ID');
        $vendor_id = $this->input->post('vendor_id', true);
        $review_id = $this->input->post('review_id', true);

        $insert = array();
        $insert['customer_id'] = $user_id;
        $insert['vendor_id'] = $this->input->post('vendor_id', true);
        $insert['appointment_id'] = $this->input->post('appointment_id', true);
        $insert['event_id'] = $this->input->post('event_id', true);
        $insert['quality_rating'] = $this->input->post('quality_rating', true);
        $insert['location_rating'] = $this->input->post('location_rating', true);
        $insert['space_rating'] = $this->input->post('space_rating', true);
        $insert['service_rating'] = $this->input->post('service_rating', true);
        $insert['price_rating'] = $this->input->post('price_rating', true);
        $insert['review_comment'] = trim($this->input->post('review_comment', true));
        $insert['created_on'] = date("Y-m-d H:i:s");
        if ($review_id > 0) {
            $ins_id = $this->model_front->update('app_vendor_review', $insert, 'app_vendor_review.id=' . $review_id);
        } else {
            $ins_id = $this->model_front->insert('app_vendor_review', $insert);
        }

        if ($ins_id) {
            $this->session->set_flashdata('msg', translate('vendor_review_save'));
            $this->session->set_flashdata('msg_class', 'success');
        } else {
            $this->session->set_flashdata('msg', translate('something_wrong'));
            $this->session->set_flashdata('msg_class', 'failure');
        }
        redirect('appointment');
    }

    public function contact_action() {

        $this->form_validation->set_rules('fullname', '', 'required');
        $this->form_validation->set_rules('emailid', '', 'required|valid_email');
        $this->form_validation->set_rules('phoneno', '', 'required');
        $this->form_validation->set_rules('message', '', 'required');
        $event_id = (int) $this->input->post('event_id', TRUE);
        $event_cat_id = $this->input->post('event_category_id', TRUE);
        $admin_id = $this->input->post('admin_id', TRUE);

        $event_Res = $this->model_front->getData("app_event", "*", "id='$event_id'");

        $type = isset($event_Res[0]['type']) ? $event_Res[0]['type'] : "";
        $event_title = isset($event_Res[0]['title']) ? $event_Res[0]['title'] : "";

        $created_by = isset($event_Res[0]['created_by']) ? $event_Res[0]['created_by'] : "";
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('msg', validation_errors());
            $this->session->set_flashdata('msg_class', 'failure');
            redirect('event-details/' . slugify($event_Res[0]['title']) . '/' . $event_id);
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
                $admin = $this->model_front->getData("app_users", "first_name,last_name,email", "id=" . $created_by);
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
                redirect('event-details/' . slugify($event_Res[0]['title']) . '/' . $event_id);
            } else {
                redirect('service-details/' . slugify($event_Res[0]['title']) . '/' . $event_id);
            }
        }
    }

    public function terms_condition() {
        $data['title'] = translate('terms_condition');
        $this->load->view('front/terms-condition', $data);
    }

    public function privacy_policy() {
        $data['title'] = translate('privacy_policy');
        $this->load->view('front/privacy-policy', $data);
    }

    public function faqs() {
        $app_faq = $this->model_front->getData('app_faq', '*', "status='A'");
        $data['title'] = translate('faqs');
        $data['app_faq'] = $app_faq;
        $this->load->view('front/faqs', $data);
    }

    public function contact_us() {
        $data['title'] = translate('contact-us');
        $this->load->view('front/contact-us', $data);
    }

    public function contact_us_front() {

        $this->form_validation->set_rules('name', '', 'required');
        $this->form_validation->set_rules('email', '', 'required|valid_email');
        $this->form_validation->set_rules('subject', '', 'required');
        $this->form_validation->set_rules('message', '', 'required');
        if ($this->form_validation->run() == false) {
            $this->contact_us();
        } else {
            $fullname = $this->input->post('name', true);
            $emailid = $this->input->post('email', true);
            $subject = $this->input->post('subject', true);
            $phone = $this->input->post('phone', true);
            $message = $this->input->post('message', true);

            $ins_data = array();
            $ins_data['event_id'] = 0;
            $ins_data['admin_id'] = 0;
            $ins_data['name'] = $fullname;
            $ins_data['email'] = $emailid;
            $ins_data['phone'] = $phone;
            $ins_data['message'] = $message;
            $ins_data['subject'] = $subject;
            $ins_data['created_on'] = date("Y-m-d H:i:s");
            $ins_id = $this->model_front->insert('app_contact_us', $ins_data);

            if ($ins_id) {
                $admin = $this->model_front->getData("app_users", "first_name,last_name,email", "type='A'");
                foreach ($admin as $val):

                    $admin_name = ($val['first_name']) . " " . ($val['last_name']);
                    $define_param['to_name'] = $admin_name;
                    $define_param['to_email'] = $val['email'];

                    $parameter['user'] = $admin_name;
                    $parameter['name'] = $fullname;
                    $parameter['email'] = $emailid;
                    $parameter['subject'] = $subject;
                    $parameter['phone'] = $phone;
                    $parameter['message'] = $message;
                    $html = $this->load->view("email_template/contact_us", $parameter, true);

                    $this->sendmail->send($define_param, $subject, $html);
                endforeach;

                $this->session->set_flashdata('msg', translate('contact_detail_send'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $this->session->set_flashdata('msg', translate('something_wrong'));
                $this->session->set_flashdata('msg_class', 'failure');
            }
            redirect('contact-us');
        }
    }

    public function event_listing() {
        /* Pagination Data Start */
        $session_location = $this->session->userdata('location');
        $location_id = $this->session->userdata('location_id');

        $category = (int) $this->input->get('category', true);
        $vendor = (int) $this->input->get('vendor', true);
        $location = (int) $this->input->get('location', true);
        $duration = $this->input->get('duration', true);

        $join = array(
            array(
                'table' => 'app_event_category',
                'condition' => '(app_event_category.id=app_event.category_id AND app_event_category.type="E")',
                'jointype' => 'INNER'
            ),
            array(
                'table' => 'app_city',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'INNER'
            ),
            array(
                'table' => 'app_location',
                'condition' => 'app_location.loc_id=app_event.location',
                'jointype' => 'INNER'
            ), array(
                'table' => 'app_users',
                'condition' => 'app_users.id=app_event.created_by',
                'jointype' => 'INNER'
            ),
        );

        $cond = 'app_event.status="A" AND app_event.type="E" AND app_city.city_id=' . $location_id;
        if (isset($category) && $category > 0) {
            $cond .= " AND app_event_category.id=" . $category;
        }
        if (isset($vendor) && $vendor > 0) {
            $cond .= " AND app_users.id=" . $vendor;
        }
        if (isset($location) && $location > 0) {
            $cond .= " AND app_location.loc_id=" . $location;
        }


        if (isset($duration) && in_array($duration, array("W", "M"))) {


            if ($duration == 'W') {
                $start = (date('D') != 'Mon') ? date('Y-m-d', strtotime('last Monday')) : date('Y-m-d');
                $finish = (date('D') != 'Sun') ? date('Y-m-d', strtotime('next Sunday')) : date('Y-m-d');
                $cond .= " AND DATE(app_event.start_date)>='" . $start . "' AND DATE(app_event.start_date)<='" . $finish . "'";
            }

            if ($duration == 'M') {
                $cond .= " AND MONTH(app_event.start_date)=" . date('m');
            }
        }

        $cond1 = 'app_event.status="A" AND app_event.type="E"';
        $total_events1 = $this->model_front->getData("app_event", 'app_event.id', $cond, $join);

        $rec_count = count($total_events1);

        $rec_limit = get_site_setting('display_record_per_page');
        $total_pages = ceil($rec_count / $rec_limit);

        $get_page = $this->input->get('page', true);
        if (isset($get_page)) {
            $get_page = (int) $get_page;
            if ($get_page > $total_pages) {
                redirect(base_url('services?page=0'));
            } else {
                $page = (int) ($get_page + 1);
            }
        } else {
            $page = 1;
        }
        $start_from = ($page - 1) * $rec_limit;
        $left_rec = $rec_count - ($page * $rec_limit);
        /* Pagination Data End */

        $app_users = $this->model_front->getData("app_users", 'company_name,id', 'status="A" AND type!="S"', '', '', 'company_name');
        $event_category = $this->model_front->getData("app_event_category", 'title,id', 'type="E"', '', '', 'title');
        $app_location = $this->model_front->getData("app_location", 'loc_id,loc_title', 'loc_city_id=' . $location_id, '', '', 'loc_title');

        $data['title'] = translate('events');
        $total_event = $this->model_front->getData("app_event", 'app_users.profile_image,app_users.company_name,app_event.*,app_event.id as event_id,app_event_category.title as category_title,app_city.city_title, app_location.loc_title', $cond, $join, '', 'app_event.id', '', '', '', '', '', '', $start_from);

        $data['total_Event'] = $total_event;
        $data['left_rec'] = $left_rec;
        $data['rec_limit'] = $rec_limit;
        $data['page'] = $page;
        $data['total_pages'] = $total_pages;
        $data['vendor_data'] = $app_users;
        $data['app_location'] = $app_location;
        $data['event_category'] = $event_category;

        /* Get Top City */
        $city_join = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'inner'
            )
        );
        $top_cities = $this->model_front->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');
        $data['topCity_List'] = $top_cities;

        $this->load->view('front/event/event-listing', $data);
    }

    public function service_listing() {
        $data['title'] = translate('service');

        $session_location = $this->session->userdata('location');
        $location_id = (int) $this->session->userdata('location_id');

        $category = (int) $this->input->get('category', true);
        $vendor = (int) $this->input->get('vendor', true);
        $location = (int) $this->input->get('location', true);


        /* Pagination Data Start */
        $join = array(
            array(
                'table' => 'app_event_category',
                'condition' => '(app_event_category.id=app_event.category_id AND app_event_category.type="S")',
                'jointype' => 'INNER'
            ),
            array(
                'table' => 'app_city',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'INNER'
            ),
            array(
                'table' => 'app_location',
                'condition' => 'app_location.loc_id=app_event.location',
                'jointype' => 'INNER'
            ), array(
                'table' => 'app_users',
                'condition' => 'app_users.id=app_event.created_by',
                'jointype' => 'INNER'
            ),
        );

        $cond = 'app_event.status="A" AND app_event.type="S" AND app_city.city_id=' . $location_id;
        if (isset($category) && $category > 0) {
            $cond .= " AND app_event_category.id=" . $category;
        }
        if (isset($vendor) && $vendor > 0) {
            $cond .= " AND app_users.id=" . $vendor;
        }
        if (isset($location) && $location > 0) {
            $cond .= " AND app_location.loc_id=" . $location;
        }
        $total_service1 = $this->model_front->getData("app_event", 'app_event.id', $cond, $join);

        $rec_count = count($total_service1);
        $rec_limit = get_site_setting('display_record_per_page');
        $total_pages = ceil($rec_count / $rec_limit);

        $get_page = $this->input->get('page', true);
        if (isset($get_page)) {
            $get_page = (int) $get_page;
            if ($get_page > $total_pages) {
                redirect(base_url('services?page=0'));
            } else {
                $page = (int) ($get_page + 1);
            }
        } else {
            $page = 1;
        }
        $start_from = ($page - 1) * $rec_limit;
        $left_rec = $rec_count - ($page * $rec_limit);
        /* Pagination Data End */

        $app_users = $this->model_front->getData("app_users", 'company_name,id', 'status="A" AND type!="S"', '', '', 'company_name');
        $service_category = $this->model_front->getData("app_event_category", 'title,id', 'type="S"', '', '', 'title');
        $app_location = $this->model_front->getData("app_location", 'loc_id,loc_title', 'loc_city_id=' . $location_id, '', '', 'loc_title');

        $total_service = $this->model_front->getData("app_event", 'app_users.profile_image,app_users.company_name,app_event.*,app_event.id as event_id,app_event_category.title as category_title,app_city.city_title, app_location.loc_title', $cond, $join, '', 'app_event.id', '', '', '', '', '', '', $start_from);
        $data['total_service'] = $total_service;
        $data['left_rec'] = $left_rec;
        $data['rec_limit'] = $rec_limit;
        $data['page'] = $page;
        $data['total_pages'] = $total_pages;
        $data['vendor_data'] = $app_users;
        $data['app_location'] = $app_location;
        $data['service_category'] = $service_category;
        /* Get Top City */
        $city_join = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'inner'
            )
        );
        $top_cities = $this->model_front->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');
        $data['topCity_List'] = $top_cities;

        $this->load->view('front/service/service-listing', $data);
    }

    public function vendor_service() {
        $data['title'] = translate('vendor-service');
        $this->load->view('front/vendor-service', $data);
    }

    public function event_book($event_id) {

        $data['title'] = translate('book_your_event');
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
                'table' => 'app_users',
                'condition' => 'app_users.id=app_event.created_by',
                'jointype' => 'inner'
            )
        );

        $cond = "app_event.status='A' AND app_event.type='E' AND app_event.id= '$event_id'";
        $field = 'app_event.*,app_event.id as event_id,app_event_category.title as category_title,app_city.city_title, app_location.loc_title,CONCAT(app_users.first_name," " ,app_users.last_name) as Creater_name';
        $event = $this->model_front->getData("app_event", $field, $cond, $join, '', 'app_event.id', '', $this->Per_Page, array(), '', array(), '', '', $sort_by = 'N');


        //get user details
        $customer_id_sess = (int) $this->session->userdata('CUST_ID');
        $customer = $this->model_front->getData("app_customer", "id,first_name,last_name,email", "id=" . $customer_id_sess);
        $data['event_payment_type'] = $event[0]['payment_type'];
        $data['event_data'] = $event[0];
        $date = date('d-m-Y');
        $data['current_date'] = $date;
        $data['customer_data'] = isset($customer[0]) ? $customer[0] : array();
        $this->load->view('front/event/event_book', $data);
    }

    //add event booking for free
    public function event_booking_free() {

        $customer_id = (int) $this->session->userdata('CUST_ID');
        if ($customer_id == 0) {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('protected_message'));
            redirect('login');
        }

        //Request post data
        $description = $this->input->post('description');
        $event_id = (int) $this->input->post('event_id');
        $bookdate = $this->input->post('start_date');
        $event_payment_type = $this->input->post('event_payment_type');
        $event_booking_seat = $this->input->post('total_booked_seat');
        $event_category_id = $this->input->post('event_category_id');

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
                        redirect(base_url('event-details/' . slugify($event_title) . '/' . $event_id));
                    }

                endfor;

                if (($total_tickets != $event_main_ticket) || ($event_amount != $event_price)) {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('something_wrong'));
                    redirect(base_url('event-details/' . slugify($event_title) . '/' . $event_id));
                } else {
                    $insert['customer_id'] = $customer_id;
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
                        $app_event_ticket_type_booking['created_by'] = $customer_id;
                        $app_event_ticket_type_booking['created_date'] = date('Y-m-d H:i:s');
                        $this->db->insert('app_event_ticket_type_booking', $app_event_ticket_type_booking);
                    endfor;

                    $cur_date = date("Y-m-d H:i:s");

                    //Send email to customer
                    $customer = $this->model_front->getData("app_customer", "first_name,last_name,email,phone", "id='$customer_id'");

                    $name = ($customer[0]['first_name']) . " " . ($customer[0]['last_name']);
                    $subject = $event_data['title'] . ' | ' . translate('appointment_booking');
                    $define_param['to_name'] = $name;
                    $define_param['to_email'] = $customer[0]['email'];

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
                    $parameterv['customer_data'] = $customer[0];
                    $parameterv['price'] = translate('free');
                    $html2 = $this->load->view("email_template/event_booking_vendor", $parameterv, true);
                    $this->sendmail->send($define_param2, $subject2, $html2);

                    $this->session->set_flashdata('msg', translate('booking_pending'));
                    $this->session->set_flashdata('msg_class', 'info');
                    redirect('appointment-success/' . $booking_id);
                }
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url());
            endif;
        else:
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        endif;
    }

    //add event booking by cash method
    public function event_booking_oncash() {
        $customer_id = (int) $this->session->userdata('CUST_ID');
        if ($customer_id == 0) {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('protected_message'));
            redirect('login');
        }

        $appointment_id = (int) $this->input->post('appointment_id');

        $description = $this->input->post('description');
        $description = $this->input->post('description');
        $event_id = (int) $this->input->post('event_id');
        $bookdate = $this->input->post('start_date');
        $event_payment_type = $this->input->post('event_payment_type');
        $event_booking_seat = $this->input->post('total_booked_seat');
        $event_category_id = $this->input->post('event_category_id');

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
                        redirect(base_url('event-details/' . slugify($event_title) . '/' . $event_id));
                    }

                endfor;

                if (($total_tickets != $event_main_ticket) || ($event_amount != $event_price)) {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('something_wrong'));
                    redirect(base_url('event-details/' . slugify($event_title) . '/' . $event_id));
                } else {
                    $type = $event_data['type'];
                    $final_price = $event_price;
                    //discount data

                    if (get_site_setting('enable_membership') == 'Y') {
                        $vendor_amount = $final_price;
                        $admin_amount = 0;
                    } else {
                        $vendor_amount = get_vendor_amount($final_price, $event_data['created_by']);
                        $admin_amount = get_admin_amount($final_price);
                    }


                    $insert['customer_id'] = $customer_id;
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
                        $app_event_ticket_type_booking['created_by'] = $customer_id;
                        $app_event_ticket_type_booking['created_date'] = date('Y-m-d H:i:s');
                        $this->db->insert('app_event_ticket_type_booking', $app_event_ticket_type_booking);
                    endfor;

                    $cur_date = date("Y-m-d H:i:s");
                    $data['customer_id'] = $customer_id;
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

                    $name = ($customer[0]['first_name']) . " " . ($customer[0]['last_name']);
                    $subject = $event_data['title'] . ' | ' . translate('appointment_booking');
                    $define_param['to_name'] = $name;
                    $define_param['to_email'] = $customer[0]['email'];

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
                    $parameterv['customer_data'] = $customer[0];
                    $parameterv['price'] = price_format($final_price);
                    $html2 = $this->load->view("email_template/event_booking_vendor", $parameterv, true);
                    $this->sendmail->send($define_param2, $subject2, $html2);

                    $this->session->set_flashdata('msg', translate('booking_pending'));
                    $this->session->set_flashdata('msg_class', 'info');
                    redirect('appointment-success/' . $booking_id);
                }
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url());
            endif;
        else:
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        endif;
    }

    //add event booking by stripe method
    public function event_booking_stripe() {

        //Get current set category
        $get_current_currency = get_current_currency();
        if ($get_current_currency['stripe_supported'] == 'Y') {
            $customer_id = (int) $this->session->userdata('CUST_ID');
            if ($customer_id == 0) {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('protected_message'));
                redirect('login');
            }

            //Request post data
            $appointment_id = (int) $this->input->post('appointment_id');
            $description = $this->input->post('description');
            $event_id = (int) $this->input->post('event_id');
            $bookdate = $this->input->post('start_date');
            $event_payment_type = $this->input->post('event_payment_type');
            $event_booking_seat = $this->input->post('total_booked_seat');
            $event_category_id = $this->input->post('event_category_id');

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
                            redirect(base_url('event-details/' . slugify($event_title) . '/' . $event_id));
                        }

                    endfor;

                    if (($total_tickets != $event_main_ticket) || ($event_amount != $event_price)) {
                        $this->session->set_flashdata('msg_class', 'failure');
                        $this->session->set_flashdata('msg', translate('something_wrong'));
                        redirect(base_url('event-details/' . slugify($event_title) . '/' . $event_id));
                    } else {
                        include APPPATH . 'third_party/init.php';
                        $final_price = $event_price;

                        $stripeToken = $this->input->post('stripeToken');
                        if (isset($stripeToken) && $stripeToken != '') {
                            try {
                                $stripe_api_key = get_StripeSecret();
                                \Stripe\Stripe::setApiKey($stripe_api_key); //system payment settings
                                $customer_email = $this->db->get_where('app_customer', array('id' => $customer_id))->row()->email;

                                $charge = \Stripe\Charge::create(array(
                                            "amount" => ceil($final_price * 100),
                                            "currency" => trim($get_current_currency['code']),
                                            "source" => $_POST['stripeToken'], // obtained with Stripe.js
                                            "description" => $this->input->post('purpose')
                                ));
                                $charge_response = $charge->jsonSerialize();
                                if ($charge_response['paid'] == true) {

                                    if (get_site_setting('enable_membership') == 'Y') {
                                        $vendor_amount = $final_price;
                                        $admin_amount = 0;
                                    } else {
                                        $vendor_amount = get_vendor_amount($final_price, $event_data['created_by']);
                                        $admin_amount = get_admin_amount($final_price);
                                    }


                                    //Insert Booking details
                                    $insert['customer_id'] = $customer_id;
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
                                        $app_event_ticket_type_booking['created_by'] = $customer_id;
                                        $app_event_ticket_type_booking['created_date'] = date('Y-m-d H:i:s');
                                        $this->db->insert('app_event_ticket_type_booking', $app_event_ticket_type_booking);

                                        //Update Available ticket
                                        $this->db->query("UPDATE app_event_ticket_type SET available_ticket=available_ticket-" . $total_seat_book[$i] . " WHERE ticket_type_id=" . $ticket_type_id[$i]);
                                    endfor;

                                    //Insert Payment Details
                                    $cur_date = date("Y-m-d H:i:s");
                                    $data['customer_id'] = $customer_id;
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

                                    $up_qry_vendor = $this->db->query("UPDATE app_users SET my_earning=my_earning+" . $vendor_amount . ",my_wallet=my_wallet+" . $vendor_amount . " WHERE id=" . $event_data['created_by']);
                                    $up_qry_admin = $this->db->query("UPDATE app_users SET my_wallet=my_wallet+" . $admin_amount . " WHERE id=1");

                                    $transaction = true;

                                    $customer = $this->model_front->getData("app_customer", "first_name,last_name,email,phone", "id='$customer_id'");

                                    $name = ($customer[0]['first_name']) . " " . ($customer[0]['last_name']);
                                    $subject = $event_data['title'] . ' | ' . translate('appointment_booking');
                                    $define_param['to_name'] = $name;
                                    $define_param['to_email'] = $customer[0]['email'];

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
                                    $parameterv['customer_data'] = $customer[0];
                                    $parameterv['price'] = price_format($final_price);
                                    $html2 = $this->load->view("email_template/event_booking_vendor", $parameterv, true);
                                    $this->sendmail->send($define_param2, $subject2, $html2);

                                    $this->session->set_flashdata('msg', translate('transaction_success_event') . "<br>" . translate('booking_insert'));
                                    $this->session->set_flashdata('msg_class', 'success');
                                    redirect('appointment-success/' . $book);
                                } else {
                                    $this->session->set_flashdata('msg', translate('transaction_fail'));
                                    $this->session->set_flashdata('msg_class', 'failure');
                                    redirect(base_url());
                                }
                            } catch (\Stripe\Error\Card $e) {
                                $body = $e->getJsonBody();
                                $err = $body['error'];
                                $this->session->set_flashdata('msg', $err['message']);
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('event-details/' . slugify($event_data['title']) . '/' . $event_id));
                            } catch (\Stripe\Error\RateLimit $e) {
                                $this->session->set_flashdata('msg', "Too many requests made to the API too quickly");
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('event-details/' . slugify($event_data['title']) . '/' . $event_id));
                            } catch (\Stripe\Error\InvalidRequest $e) {
                                $this->session->set_flashdata('msg', "Invalid parameters were supplied to Stripe's API");
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('event-details/' . slugify($event_data['title']) . '/' . $event_id));
                            } catch (\Stripe\Error\Authentication $e) {
                                $this->session->set_flashdata('msg', "Authentication with Stripe's API failed");
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('event-details/' . slugify($event_data['title']) . '/' . $event_id));
                            } catch (\Stripe\Error\ApiConnection $e) {
                                $this->session->set_flashdata('msg', "Network communication with Stripe failed");
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('event-details/' . slugify($event_data['title']) . '/' . $event_id));
                            } catch (\Stripe\Error\Base $e) {
                                $this->session->set_flashdata('msg', "Something else happened, completely unrelated to Stripe");
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('event-details/' . slugify($event_data['title']) . '/' . $event_id));
                            } catch (Exception $e) {
                                $this->session->set_flashdata('msg', "Something else happened, completely unrelated to Stripe");
                                $this->session->set_flashdata('msg_class', 'failure');
                                redirect(base_url('event-details/' . slugify($event_data['title']) . '/' . $event_id));
                            }
                        } else {
                            $this->session->set_flashdata('msg_class', 'failure');
                            $this->session->set_flashdata('msg', translate('invalid_request'));
                            redirect(base_url());
                        }
                    }
                } else {
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('invalid_request'));
                    redirect(base_url());
                }
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url());
            endif;
        }else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        }
    }

    //add event booking by paypal
    public function event_booking_paypal() {
        //Get current set category
        $get_current_currency = get_current_currency();
        if ($get_current_currency['paypal_supported'] == 'Y') {
            $this->load->library('paypal');
            $customer_id = (int) $this->session->userdata('CUST_ID');
            if ($customer_id == 0) {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('protected_message'));
                redirect('login');
            }

            $description = $this->input->post('description');
            $event_id = (int) $this->input->post('event_id');
            $bookdate = $this->input->post('start_date');

            $event_payment_type = $this->input->post('event_payment_type');
            $event_booking_seat = $this->input->post('total_booked_seat');
            $event_category_id = $this->input->post('event_category_id');

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
                            redirect(base_url('event-details/' . slugify($event_title) . '/' . $event_id));
                        }
                    endfor;

                    if (($total_tickets != $event_main_ticket) || ($event_amount != $event_price)) {
                        $this->session->set_flashdata('msg_class', 'failure');
                        $this->session->set_flashdata('msg', translate('something_wrong'));
                        redirect(base_url('event-details/' . slugify($event_title) . '/' . $event_id));
                    } else {

                        $final_price = $event_price;
                        $insert['customer_id'] = $customer_id;
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
                            $app_event_ticket_type_booking['created_by'] = $customer_id;
                            $app_event_ticket_type_booking['created_date'] = date('Y-m-d H:i:s');
                            $this->db->insert('app_event_ticket_type_booking', $app_event_ticket_type_booking);
                        endfor;

                        $cur_date = date("Y-m-d H:i:s");
                        $this->session->set_userdata('booking_id', $app_event_book);
                        $this->session->set_userdata('description', $description);
                        $this->session->set_userdata('bookdate', $bookdate);
                        $this->session->set_userdata('event_id', $event_id);
                        $this->session->set_userdata('event_price', $final_price);

                        $this->paypal->add_field('rm', 2);
                        $this->paypal->add_field('cmd', '_xclick');
                        $this->paypal->add_field('amount', $final_price);
                        $this->paypal->add_field('item_name', "Event Ticket Booking Payment");
                        $this->paypal->add_field('currency_code', trim($get_current_currency['code']));
                        $this->paypal->add_field('custom', $app_event_book);
                        $this->paypal->add_field('business', get_payment_setting('paypal_merchant_email'));
                        $this->paypal->add_field('cancel_return', base_url('event_paypal_cancel'));
                        $this->paypal->add_field('return', base_url('event_paypal_success'));
                        $this->paypal->submit_paypal_post();
                    }
                else:
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('invalid_request'));
                    redirect(base_url());
                endif;
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url());
            endif;
        }else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        }
    }

    public function event_paypal_success() {

        if (isset($_REQUEST['st']) && $_REQUEST['st'] == "Completed") {

            $booking_id = $this->session->userdata('booking_id');
            $description = $this->session->userdata('description');
            $bookdate = $this->session->userdata('bookdate');
            $event_price = $this->session->userdata('event_price');
            $event_id = $this->session->userdata('event_id');

            $customer_id = (int) $this->session->userdata('CUST_ID');
            $event_data = get_full_event_service_data($event_id);
            $event_title = isset($event_data['title']) ? ($event_data['title']) : '';
            $event_booking_seat = isset($event_data['event_booked_seat']) ? ($event_data['event_booked_seat']) : '';

            if (get_site_setting('enable_membership') == 'Y') {
                $vendor_amount = $event_price;
                $admin_amount = 0;
            } else {
                $vendor_amount = get_vendor_amount($event_price, $event_data['created_by']);
                $admin_amount = get_admin_amount($event_price);
            }


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

            $up_qry_vendor = $this->db->query("UPDATE app_users SET my_earning=my_earning+" . $vendor_amount . ",my_wallet=my_wallet+" . $vendor_amount . " WHERE id=" . $event_data['created_by']);
            $up_qry_admin = $this->db->query("UPDATE app_users SET my_wallet=my_wallet+" . $admin_amount . " WHERE id=1");


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
            redirect('appointment-success/' . $booking_id);
        } else {
            $booking_id = $this->session->userdata('booking_id');
            $this->db->where("id", $booking_id);
            $this->db->delete("app_event_book");

            $this->db->query("DELETE FROM app_event_ticket_type_booking WHERE booking_id=" . $booking_id);

            $this->session->unset_userdata('booking_id');
            $this->session->unset_userdata('description');
            $this->session->unset_userdata('bookdate');
            $this->session->unset_userdata('event_price');
            $this->session->unset_userdata('event_id');

            $this->session->set_flashdata('msg', translate('transaction_fail'));
            $this->session->set_flashdata('msg_class', 'failure');
            redirect(base_url());
        }
    }

    public function event_paypal_cancel() {
        //remove booked event due to unseccesfull payment
        $booking_id = $this->session->userdata('booking_id');
        $this->db->where("id", $booking_id);
        $this->db->delete("app_event_book");

        $this->db->query("DELETE FROM app_event_ticket_type_booking WHERE booking_id=" . $booking_id);

        //unset session value
        $this->session->unset_userdata('booking_id');
        $this->session->unset_userdata('description');
        $this->session->unset_userdata('bookdate');
        $this->session->unset_userdata('event_id');

        $this->session->set_flashdata('msg', translate('transaction_fail'));
        $this->session->set_flashdata('msg_class', 'failure');
        redirect(base_url());
    }

    public function search() {

        $city = $this->session->userdata('location');
        $city_data = $this->model_front->getData("app_city", "*", "city_title='$city'");
        $city_id = isset($city_data[0]['city_id']) ? $city_data[0]['city_id'] : 0;
        $search_string = trim($this->input->get('q'));
        if (isset($search_string) && $search_string != "") {
            $data['title'] = translate('search-result');
            $data['search_string'] = $search_string;
            $like = array();

            /* Event Join Data */
            $event_join = array(
                array(
                    'table' => 'app_event_category',
                    'condition' => '(app_event_category.id=app_event.category_id AND app_event_category.type="E")',
                    'jointype' => 'INNER'
                ),
                array(
                    'table' => 'app_city',
                    'condition' => 'app_city.city_id=app_event.city',
                    'jointype' => 'INNER'
                ),
                array(
                    'table' => 'app_location',
                    'condition' => 'app_location.loc_id=app_event.location',
                    'jointype' => 'INNER'
                ),
                array(
                    'table' => 'app_users',
                    'condition' => 'app_users.id=app_event.created_by',
                    'jointype' => 'INNER'
                ),
            );

            /* Service Join  Data */
            $service_join = array(
                array(
                    'table' => 'app_event_category',
                    'condition' => '(app_event_category.id=app_event.category_id AND app_event_category.type="S")',
                    'jointype' => 'INNER'
                ),
                array(
                    'table' => 'app_city',
                    'condition' => 'app_city.city_id=app_event.city',
                    'jointype' => 'INNER'
                ),
                array(
                    'table' => 'app_location',
                    'condition' => 'app_location.loc_id=app_event.location',
                    'jointype' => 'INNER'
                ),
                array(
                    'table' => 'app_users',
                    'condition' => 'app_users.id=app_event.created_by',
                    'jointype' => 'INNER'
                ),
            );

            if (!empty($search_string)) {
                $like = array(
                    array(
                        "column" => "app_event.title",
                        "pattern" => $search_string,
                    ),
                    array(
                        "column" => "app_event.description",
                        "pattern" => $search_string,
                    ),
                    array(
                        "column" => "app_city.city_title",
                        "pattern" => $search_string,
                    ),
                    array(
                        "column" => "app_location.loc_title",
                        "pattern" => $search_string,
                    ),
                    array(
                        "column" => "app_users.first_name",
                        "pattern" => $search_string,
                    ),
                    array(
                        "column" => "app_users.last_name",
                        "pattern" => $search_string,
                    ),
                    array(
                        "column" => "app_users.company_name",
                        "pattern" => $search_string,
                    ),
                    array(
                        "column" => "app_event_category.title",
                        "pattern" => $search_string,
                    )
                );
            }

            $field = "app_event.*,app_location.loc_title,app_city.city_title,app_users.phone,app_users.company_name,app_event_category.title as category_title";
            $data['event'] = $this->model_front->getData("app_event", $field, "app_event.city='$city_id' AND app_event.type='E' AND app_event.status='A'", $event_join, "", "", "", "", array(), "", $like);
            $data['service'] = $this->model_front->getData("app_event", $field, "app_event.city='$city_id' AND app_event.type='S' AND app_event.status='A'", $service_join, "", "", "", "", array(), "", $like);
            /* orgnizer */
            if (!empty($search_string)) {
                $like_orgnizer = array(
                    array(
                        "column" => "company_name",
                        "pattern" => $search_string,
                    )
                );
            }
            $data['orgnizer'] = $this->model_front->getData("app_users", '*', "", array(), "", "", "", "", array(), "", $like_orgnizer);
            if (isset($data['orgnizer']) && count($data['orgnizer']) > 0) {
                $vendor_id = $data['orgnizer'][0]['id'];
                $data['total_event'] = $this->model_front->Totalcount("app_event", "created_by='$vendor_id'");
            }
            /* Get Top City */
            $city_join = array(
                array(
                    'table' => 'app_event',
                    'condition' => 'app_city.city_id=app_event.city',
                    'jointype' => 'inner'
                )
            );
            $top_cities = $this->model_front->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');
            $data['topCity_List'] = $top_cities;
            $this->load->view('front/search-result', $data);
        } else {
            redirect(base_url());
        }
    }

    public function register_success() {
        if (($this->session->flashdata('msg')) && $this->session->flashdata('msg') != NULL):
            $data['title'] = translate('register_success');
            $this->load->view('front/register-success', $data);
        else:
            redirect(base_url());
        endif;
    }

    public function no_script() {
        $data['title'] = translate('home');
        $this->load->view('front/no_script', $data);
    }

    public function dashboard() {
        $data['title'] = translate('home');
        $this->load->view('front/profile/dashboard', $data);
    }

    public function page($param) {
        if (isset($param) && $param != "") {
            $slug = $this->db->escape($param);
            $page_content = $this->model_front->getData('app_content', '*', 'status="A" AND slug=' . $slug);
            if (isset($page_content) && count($page_content) > 0) {
                $data['title'] = isset($page_content[0]['title']) ? $page_content[0]['title'] : "";
                $data['description'] = isset($page_content[0]['description']) ? $page_content[0]['description'] : "";
                $this->load->view('front/page', $data);
            } else {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url());
            }
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        }
    }

    public function invoice($id) {
        $data['title'] = translate('invoice');
        $this->load->view('front/profile/invoice', $data);
    }

    public function event_booking_two_checkout() {
        $get_current_currency = get_current_currency();

        if (check_payment_method('2checkout') && $get_current_currency['2checkout_supported'] == 'Y') {
            $customer_id = (int) $this->session->userdata('CUST_ID');
            if ($customer_id == 0) {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('protected_message'));
                redirect('login');
            }

            $description = $this->input->post('description');
            $event_id = (int) $this->input->post('event_id');
            $bookdate = $this->input->post('start_date');

            $event_payment_type = $this->input->post('event_payment_type');
            $event_booking_seat = $this->input->post('total_booked_seat');
            $event_category_id = $this->input->post('event_category_id');

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
                            redirect(base_url('event-details/' . slugify($event_title) . '/' . $event_id));
                        }
                    endfor;

                    if (($total_tickets != $event_main_ticket) || ($event_amount != $event_price)) {
                        $this->session->set_flashdata('msg_class', 'failure');
                        $this->session->set_flashdata('msg', translate('something_wrong'));
                        redirect(base_url('event-details/' . slugify($event_title) . '/' . $event_id));
                    } else {

                        $final_price = $event_price;
                        $insert['customer_id'] = $customer_id;
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
                            $app_event_ticket_type_booking['created_by'] = $customer_id;
                            $app_event_ticket_type_booking['created_date'] = date('Y-m-d H:i:s');
                            $this->db->insert('app_event_ticket_type_booking', $app_event_ticket_type_booking);
                        endfor;

                        $this->session->set_userdata('booking_id', $app_event_book);
                        $this->session->set_userdata('description', $description);
                        $this->session->set_userdata('bookdate', $bookdate);
                        $this->session->set_userdata('event_id', $event_id);
                        $this->session->set_userdata('event_price', $final_price);

                        include APPPATH . 'third_party/2checkout/Twocheckout.php';

                        // Your sellerId(account number) and privateKey are required to make the Payment API Authorization call.
                        Twocheckout::privateKey(get_payment_setting('2checkout_private_key'));
                        Twocheckout::sellerId(get_payment_setting('2checkout_account_no'));

                        // If you want to turn off SSL verification (Please don't do this in your production environment)
                        Twocheckout::verifySSL(false);  // this is set to true by default
                        // To use your sandbox account set sandbox to true

                        if (get_payment_setting('2checkout_live_sandbox') == 'S'):
                            Twocheckout::sandbox(true);
                        else:
                            Twocheckout::sandbox(FALSE);
                        endif;


                        // All methods return an Array by default or you can set the format to 'json' to get a JSON response.
                        Twocheckout::format('json');

                        $params = array(
                            'sid' => get_payment_setting('2checkout_account_no'),
                            'mode' => '2CO',
                            'currency_code' => $get_current_currency['code'],
                            'li_0_name' => 'Event Ticket Booking Payment',
                            'li_0_price' => $final_price,
                            'card_holder_name' => $this->input->post('first_name') . " " . $this->input->post('last_name'),
                            'email' => $this->input->post('email'),
                            'booking_type' => "E",
                            'x_receipt_link_url' => base_url('2checkout-success')
                        );
                        Twocheckout_Charge::form($params, 'auto');
                    }
                else:
                    $this->session->set_flashdata('msg_class', 'failure');
                    $this->session->set_flashdata('msg', translate('invalid_request'));
                    redirect(base_url());
                endif;
            else:
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect(base_url());
            endif;
        }else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect(base_url());
        }
    }

}

?>