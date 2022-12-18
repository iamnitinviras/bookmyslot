<?php

include APPPATH . 'third_party/autoload.php';

use Ausi\SlugGenerator\SlugGenerator;

function convert_lang_string($str) {
    $generator = new SlugGenerator;
    return $generator->generate($str);
}

function get_day_of_week($val) {
    $val = trim(strtolower($val));

    if ($val == "sun") {
        return translate('sunday');
    } else if ($val == "mon") {
        return translate('monday');
    } else if ($val == "tue") {
        return translate('tuesday');
    } else if ($val == "wed") {
        return translate('wednesday');
    } else if ($val == "thu") {
        return translate('thursday');
    } else if ($val == "fri") {
        return translate('friday');
    } else if ($val == "sat") {
        return translate('saturday');
    } else {
        return translate('sunday');
    }
}

function run_default_query() {
    $CI = & get_instance();
    $CI->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
}

function expire_holiday_date() {
    $CI = & get_instance();
    $CI->db->query("UPDATE `app_holidays` SET status='I' WHERE holiday_date<'" . date('Y-m-d') . "';");
}

function get_holiday_list_by_vendor($id) {
    $CI = & get_instance();
    $app_holidays = $CI->db->query("SELECT holiday_date FROM app_holidays WHERE created_by=" . $id . " AND status='A'")->result_array();

    $app_holidays_list = array();
    foreach ($app_holidays as $val):
        array_push($app_holidays_list, date("d-m-Y", strtotime($val['holiday_date'])));
    endforeach;

    return $app_holidays_list;
}

function get_cash_payment_vendor() {

    $CI = & get_instance();
    $vendor_id = $CI->session->userdata('Vendor_ID');

    $CI->db->select('cash_payment');
    $CI->db->from('app_admin');
    $CI->db->where('id', $vendor_id);
    $data = $CI->db->get()->row_array();
    return isset($data['cash_payment']) ? $data['cash_payment'] : 0;
}

function get_ticket_type_by_event($id) {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('app_event_ticket_type');
    $CI->db->where('event_id', $id);
    $CI->db->where('available_ticket>', 0);
    $data = $CI->db->get()->result_array();
    return isset($data) ? $data : array();
}

function price_format($val) {
    $currency_position = get_site_setting('currency_position');
    $get_current_currency = get_current_currency();

    if ($currency_position == 'R') {
        return number_format((float) $val, 2, '.', '') . "" . $get_current_currency['currency_code'];
    } else {
        return $get_current_currency['currency_code'] . "" . number_format((float) $val, 2, '.', '');
    }
}

function get_app_currency($id = NULL) {
    $CI = & get_instance();
    $CI->db->select('*');

    $CI->db->from('app_currency');
    if ($id == NULL) {
        $CI->db->where('status', 'A');
    } else {
        $CI->db->where('id', $id);
    }

    $data = $CI->db->get()->row_array();
    return isset($data) ? $data : array();
}

function get_current_currency() {
    $currency_id = get_site_setting('currency_id');
    $currency_id = (isset($currency_id) && $currency_id > 0) ? $currency_id : 25;
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('app_currency');
    $CI->db->where('id', $currency_id);
    $data = $CI->db->get()->row_array();
    if (isset($data['id']) && $data['id'] > 0) {
        return isset($data) ? $data : array();
    } else {
        return $CI->db->query("SELECT * FROM app_currency WHERE id=25")->row_array();
    }
}

function get_ticket_type($id) {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('app_event_ticket_type');
    $CI->db->where('ticket_type_id', $id);
    $data = $CI->db->get()->row_array();
    return isset($data) ? $data : array();
}

function get_app_testimonial() {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('app_testimonial');
    $CI->db->where('status', 'A');
    $data = $CI->db->get()->result_array();
    return isset($data) ? $data : array();
}

function get_ticket_details_by_booking_id($booking_id) {
    $CI = & get_instance();
    $qry = "SELECT app_event_ticket_type_booking.*,app_event_ticket_type.available_ticket,app_event_ticket_type.ticket_type_title FROM app_event_ticket_type_booking ";
    $qry .= " INNER JOIN app_event_ticket_type ON app_event_ticket_type.ticket_type_id=app_event_ticket_type_booking.ticket_type_id ";
    $qry .= " WHERE app_event_ticket_type_booking.booking_id=" . $booking_id;
    return $CI->db->query($qry)->result_array();
}

function get_event_price_by_id($event_id, $type = 'P') {
    $CI = & get_instance();
    $res = $CI->db->query("SELECT SUM(`ticket_type_price`) as price,MIN(ticket_type_price) as min_price,MAX(ticket_type_price) as max_price FROM `app_event_ticket_type` WHERE `event_id`=" . $event_id)->row_array();
    if ($res['price'] > 0) {
        if ($type == 'P') {
            return price_format($res['price']);
        } else {
            return price_format($res['min_price']) . "-" . price_format($res['max_price']);
        }
    } else {
        return translate('free');
    }
}

function update_event_status() {
    $CI = & get_instance();

    if (get_site_setting('enable_membership') == 'Y') {
        $membership_check = $CI->db->query('SELECT id FROM app_admin WHERE type="V" AND (package_id=0 OR membership_till IS NULL OR membership_till<"' . date("Y-m-d") . '")')->result_array();
        if (count($membership_check) > 0) {
            foreach ($membership_check as $val):
                $CI->db->query("UPDATE app_event SET status='SS' WHERE created_by=" . $val['id'] . " AND status='A'");
            endforeach;
        }
    }

    $CI->db->query("UPDATE app_event SET status='E' WHERE end_date<'" . date('Y-m-d H:i:s') . "' AND type='E'");
    $CI->db->query("DELETE FROM app_event_book WHERE created_on + INTERVAL 5 MINUTE<'" . date('Y-m-d H:i:s') . "' AND payment_status='IN'");
}

function get_service_event_by_id($id) {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('app_event');
    $where = "id=" . $id;
    $CI->db->where($where);
    $app_event = $CI->db->get()->row_array();
    return isset($app_event) ? $app_event : array();
}

function get_staff_by_vendor_id($id) {
    $CI = & get_instance();
    $CI->db->select('id,designation,profile_image,first_name,last_name,email,phone');
    $CI->db->from('app_admin');
    $where = "created_by=" . $id . " AND status='A' AND type='S'";
    $CI->db->where($where);
    $app_event = $CI->db->get()->result_array();
    return isset($app_event) ? $app_event : array();
}

function get_staff_by_id($id) {
    $CI = & get_instance();
    $res = $CI->db->query("SELECT id,designation,profile_image,first_name,last_name,email,phone FROM app_admin WHERE id IN(" . $id . ")")->result_array();
    return isset($res) ? $res : array();
}

function get_staff_row_by_id($id) {
    $CI = & get_instance();
    $res = $CI->db->query("SELECT id,designation,profile_image,first_name,last_name,email,phone FROM app_admin WHERE id IN(" . $id . ")")->row_array();
    return isset($res) ? $res : array();
}

function get_booking_details($id) {
    $CI = & get_instance();
    $res = $CI->db->query("SELECT * FROM app_event_book	 WHERE id=" . $id)->row_array();
    return isset($res) ? $res : array();
}

function get_service_addons_by_id($id) {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('app_service_addons	');
    $where = "add_on_id IN (" . $id . ")";
    $CI->db->where($where);
    $app_service_addons = $CI->db->get()->result_array();
    return isset($app_service_addons) ? $app_service_addons : array();
}

function get_formated_date($value, $his = "Y") {
    $CI = & get_instance();
    $CI->db->select('time_format');
    $CI->db->from('app_site_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $site_data = $CI->db->get()->row_array();
    if ($his == "Y") {
        return isset($site_data['time_format']) ? date($site_data['time_format'], strtotime($value)) : date('m-d-Y H:i', strtotime($value));
    } else {
        $explode = explode(" ", $site_data['time_format']);
        return isset($site_data['time_format']) ? date(isset($explode[0]) ? $explode[0] : "m-d-Y", strtotime($value)) : date('m-d-Y', strtotime($value));
    }
}

function is_maintenance_mode() {
    $CI = & get_instance();
    $CI->db->select('is_maintenance_mode');
    $CI->db->from('app_site_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $site_data = $CI->db->get()->row_array();
    return isset($site_data['is_maintenance_mode']) ? $site_data['is_maintenance_mode'] : "N";
}

function get_addons_price_by_id($id) {
    $CI = & get_instance();
    $CI->db->select('price');
    $CI->db->from('app_service_addons');
    $where = "add_on_id=" . $id;
    $CI->db->where($where);
    $site_data = $CI->db->get()->row_array();
    return isset($site_data['price']) ? $site_data['price'] : 0;
}

function get_formated_time($value) {
    $CI = & get_instance();
    $CI->db->select('time_format');
    $CI->db->from('app_site_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $site_data = $CI->db->get()->row_array();
    if (isset($site_data['time_format']) && $site_data['time_format'] != "") {
        $explode = explode(" ", $site_data['time_format']);
        return date($explode[1], $value);
    } else {
        return date('H:i', $value);
    }
}

function get_thumb_image($image) {
    $data_array = explode(".", $image);
    $image_name = $data_array[0];
    $image_ext = $data_array[1];
    $thumb_image = $image_name . "_thumb." . $image_ext;
    return $thumb_image;
}

function get_vendor_amount($amount, $vendor_id) {
    $CI = & get_instance();
    $commission_percentage = get_site_setting('commission_percentage');
    $vendor_amount = ($amount - ($amount * ($commission_percentage / 100)));
    return number_format((float) $vendor_amount, 2, '.', '');
}

function get_admin_amount($amount) {
    $CI = & get_instance();
    $commission_percentage = get_site_setting('commission_percentage');
    $admin_amount = (($amount * ($commission_percentage / 100)));
    return number_format((float) $admin_amount, 2, '.', '');
}

function get_discount_price_by_date($event_id, $booking_date) {

    $CI = & get_instance();
//get Event data
    $CI->db->select('*');
    $CI->db->from('app_event');
    $where = "id=" . $event_id . " AND status='A'";
    $CI->db->where($where);
    $app_event_data = $CI->db->get()->row_array();

    if (isset($app_event_data['id']) && $app_event_data['id'] > 0) {
//get event price details
        $event_price = 0;
        $discountDate = date('Y-m-d', strtotime($booking_date));
        if (isset($app_event_data['discount']) && $app_event_data['discount'] > 0 && isset($app_event_data['discounted_price']) && $app_event_data['discounted_price'] > 0 && ($discountDate >= $app_event_data['from_date']) && ($discountDate <= $app_event_data['to_date'])) {
            $event_price = $app_event_data['discounted_price'];
        } else {
            $event_price = $app_event_data['price'];
        }
        return $event_price;
    } else {
        return 0;
    }
}

function get_price($event_id, $discountDates) {
    $CI = & get_instance();
//get Event data
    $CI->db->select('*');
    $CI->db->from('app_event');
    $where = "id=" . $event_id . " AND status='A'";
    $CI->db->where($where);
    $app_event_data = $CI->db->get()->row_array();

    $event_price = 0;
    $discountDate = date('Y-m-d', strtotime($discountDates));
    if (isset($app_event_data['discounted_price']) && $app_event_data['discounted_price'] > 0 && ($discountDate >= $app_event_data['from_date']) && ($discountDate <= $app_event_data['to_date'])) {
        $event_price = $app_event_data['discounted_price'];
    } else {
        $event_price = $app_event_data['price'];
    }
    return $event_price;
}

function get_discount_price($event_id, $discount_coupon, $discount_coupon_id, $booking_date) {

    $CI = & get_instance();
//get Event data
    $CI->db->select('*');
    $CI->db->from('app_event');
    $where = "id=" . $event_id . " AND status='A'";
    $CI->db->where($where);
    $app_event_data = $CI->db->get()->row_array();

//get app_coupon data
    $CI->db->select('*');
    $CI->db->from('app_coupon');
    $wheres = "code='" . $discount_coupon . "' AND status='A'";
    $CI->db->where($wheres);
    $coupon_signle_data = $CI->db->get()->row_array();

    if (count($app_event_data) > 0) {
        if (count($coupon_signle_data) > 0) {

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

            $final_price = $event_price;
//Apply coupon disocunt on event price
            if ($discount_type == 'P') {
                $final_price = ($final_price - ($final_price * ($discount_value / 100)));
            } else {
                $final_price = $final_price - $discount_value;
            }

            $event_id_ary = json_decode($event_id_array);

            if ($valid_till >= date('Y-m-d')) {

                if (in_array($event_id, $event_id_ary)) {
                    return number_format((float) $final_price, 2, '.', '');
                } else {
                    $discountDate = date('Y-m-d');
                    $event_price = $app_event_data['price'];
                    if (isset($app_event_data['discounted_price']) && $app_event_data['discounted_price'] > 0 && ($discountDate >= $app_event_data['from_date']) && ($discountDate <= $app_event_data['to_date'])) {
                        $event_price = $app_event_data['discounted_price'];
                    }
                    return number_format((float) $event_price, 2, '.', '');
                }
            } else {
                $discountDate = date('Y-m-d');
                $event_price = $app_event_data['price'];
                if (isset($app_event_data['discounted_price']) && $app_event_data['discounted_price'] > 0 && ($discountDate >= $app_event_data['from_date']) && ($discountDate <= $app_event_data['to_date'])) {
                    $event_price = $app_event_data['discounted_price'];
                }
                return number_format((float) $event_price, 2, '.', '');
            }
        } else {
            $discountDate = date('Y-m-d');
            $event_price = $app_event_data['price'];
            if (isset($app_event_data['discounted_price']) && $app_event_data['discounted_price'] > 0 && ($discountDate >= $app_event_data['from_date']) && ($discountDate <= $app_event_data['to_date'])) {
                $event_price = $app_event_data['discounted_price'];
            }
            return number_format((float) $event_price, 2, '.', '');
        }
    } else {
        $event_price = isset($app_event_data['price']) ? $app_event_data['price'] : 0;
        return number_format((float) $event_price, 2, '.', '');
    }
}

function get_payment_setting($field) {
    $CI = & get_instance();
    $CI->db->select($field);
    $CI->db->from('app_payment_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $payment_data = $CI->db->get()->result_array();
    return isset($payment_data) && count($payment_data) > 0 ? $payment_data[0][$field] : '';
}

function app_vendor_setting() {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('app_vendor_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $app_vendor_setting_data = $CI->db->get()->row_array();
    return $app_vendor_setting_data;
}

function get_site_setting($field) {
    $CI = & get_instance();
    $CI->db->select($field);
    $CI->db->from('app_site_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $site_data = $CI->db->get()->result_array();
    return isset($site_data) && count($site_data) > 0 ? trim($site_data[0][$field]) : '';
}

function get_CompanyName() {
    $CI = & get_instance();
    $CI->db->select('company_name');
    $CI->db->from('app_site_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $user_data = $CI->db->get()->result_array();
    return isset($user_data) && count($user_data) > 0 ? $user_data[0]['company_name'] : '';
}

function get_slote_count($id) {
    $CI = & get_instance();
    $type = $CI->session->userdata('Type_' . ucfirst($CI->uri->segment(1)));
    $vendor_id = $CI->session->userdata('Vendor_ID');
    $CI->db->select('COUNT(app_event_book.slot_time) as slot_time');
    $CI->db->join('app_event', 'app_event.id=app_event_book.event_id', 'left');
    $CI->db->from('app_event_book');
    if ($type == 'V') {
        $where = "event_id='$id' AND app_event.created_by='$vendor_id'";
    } else {
        $where = "event_id=" . $id;
    }
    $CI->db->where($where);
    $user_data = $CI->db->get()->result_array();
    return isset($user_data) && count($user_data) > 0 ? $user_data[0]['slot_time'] : 0;
}

function get_total_booked_seat_count($id) {
    $CI = & get_instance();
    $type = $CI->session->userdata('Type_' . ucfirst($CI->uri->segment(1)));
    $vendor_id = $CI->session->userdata('Vendor_ID');
    $CI->db->select('SUM(app_event_book.event_booked_seat) as Total_booked');
    $CI->db->join('app_event', 'app_event.id=app_event_book.event_id', 'left');
    $CI->db->from('app_event_book');
    if ($type == 'V') {
        $where = "event_id='$id' AND app_event.created_by='$vendor_id'";
    } else {
        $where = "event_id=" . $id;
    }
    $CI->db->where($where);
    $user_data = $CI->db->get()->result_array();
    return isset($user_data) && count($user_data) > 0 && $user_data[0]['Total_booked'] != '' ? $user_data[0]['Total_booked'] : 0;
}

function get_CompanyLogo() {
    $CI = & get_instance();
    $CI->db->select('company_logo');
    $CI->db->from('app_site_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $user_data = $CI->db->get()->row_array();
    if (isset($user_data['company_logo']) && $user_data['company_logo'] != "") {
        $company_logo = $user_data['company_logo'];
        if (file_exists(FCPATH . UPLOAD_PATH . "sitesetting/" . $company_logo)) {
            return base_url(UPLOAD_PATH . "sitesetting/" . $company_logo);
        } else {
            return base_url('assets/images/logo.png');
        }
    } else {
        return base_url('assets/images/logo.png');
    }
}

function get_time_zone() {
    $CI = & get_instance();
    $CI->db->select('time_zone');
    $CI->db->from('app_site_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $user_data = $CI->db->get()->result_array();
    return isset($user_data) && count($user_data) > 0 && $user_data[0]['time_zone'] != '' ? $user_data[0]['time_zone'] : 'Asia/Kolkata';
}

function set_time_zone() {
    $CI = & get_instance();
    $CI->db->select('time_zone');
    $CI->db->from('app_site_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $user_data = $CI->db->get()->result_array();
    return isset($user_data) && count($user_data) > 0 && $user_data[0]['time_zone'] != '' ? date_default_timezone_set($user_data[0]['time_zone']) : date_default_timezone_set('Asia/Kolkata');
}

function tz_list() {
    $zones_array = array();
    $timestamp = time();
    foreach (timezone_identifiers_list() as $key => $zone) {
        date_default_timezone_set($zone);
        $zones_array[$key]['zone'] = $zone;
        $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
    }
    return $zones_array;
}

function get_CustomerDetails() {
    $CI = & get_instance();
    $id = $CI->session->userdata('CUST_ID');
    $CI->db->select('first_name, last_name, profile_image,email');
    $CI->db->from('app_customer');
    $where = "id='$id'";
    $CI->db->where($where);
    $user_data = $CI->db->get()->result_array();
    return isset($user_data) && count($user_data) > 0 ? $user_data[0] : '';
}

function get_customer_data($id) {
    $CI = & get_instance();
    $CI->db->select('first_name, last_name, profile_image,email');
    $CI->db->from('app_customer');
    $CI->db->where('id', $id);
    $user_data = $CI->db->get()->row_array();
    return $user_data;
}

function get_VendorDetails($id = NULL) {
    $CI = & get_instance();
    if (is_null($id)) {
        $id = $CI->session->userdata('Vendor_ID');
    }
    $CI->db->select('id as user_id,first_name, last_name,email, profile_image,company_name,package_id,membership_till');
    $CI->db->from('app_admin');
    $where = "id='$id'";
    $CI->db->where($where);
    $user_data = $CI->db->get()->result_array();
    return isset($user_data) && count($user_data) > 0 ? $user_data[0] : '';
}

function slugify($str, $options = array()) {
    // Make sure string is in UTF-8 and strip invalid UTF-8 characters
    $str = mb_convert_encoding((string) $str, 'UTF-8', mb_list_encodings());

    $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => false,
    );

    // Merge options
    $options = array_merge($defaults, $options);

    $char_map = array(
        // Latin
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
        'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
        'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
        'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
        'ÿ' => 'y',
        // Latin symbols
        '©' => '(c)',
        // Greek
        'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
        'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
        'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
        'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
        'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
        'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
        'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
        'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
        // Turkish
        'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
        'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
        // Russian
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
        'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
        'я' => 'ya',
        // Ukrainian
        'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
        'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
        // Czech
        'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
        'ž' => 'z',
        // Polish
        'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
        'ż' => 'z',
        // Latvian
        'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
        'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
        'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
        'š' => 's', 'ū' => 'u', 'ž' => 'z'
    );

    // Make custom replacements
    $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

    // Transliterate characters to ASCII
    if ($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }

    // Replace non-alphanumeric characters with our delimiter
    $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

    // Remove duplicate delimiters
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

    // Truncate slug to max. characters
    $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

    // Remove delimiter from ends
    $str = trim($str, $options['delimiter']);

    return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}

function get_Langauge() {
    $CI = & get_instance();
    $CI->db->select('language');
    $CI->db->from('app_site_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $user_data = $CI->db->get()->result_array();
    if (isset($user_data) && count($user_data) > 0) {
        $file = APPPATH . "/language/" . $user_data[0]['language'] . "/";
        if (is_dir($file)) {
            return strtolower($user_data[0]['language']);
        } else {
            return strtolower($CI->config->item('language'));
        }
    } else {
        return strtolower($CI->config->item('language'));
    }
}

function check_admin_image($image) {
    if (file_exists(dirname(BASEPATH) . "/" . $image) && pathinfo($image, PATHINFO_EXTENSION) != '') {
        return base_url() . $image;
    } else {
        return base_url() . "assets/images/no-image.png";
    }
}

function check_event_image($image) {
    if (file_exists(dirname(BASEPATH) . "/" . $image) && pathinfo($image, PATHINFO_EXTENSION) != '') {
        return base_url() . $image;
    } else {
        return $img_src = base_url() . UPLOAD_PATH . "event/events.png";
    }
}

function check_service_image($image) {
    if (file_exists(dirname(BASEPATH) . "/" . $image) && pathinfo($image, PATHINFO_EXTENSION) != '') {
        return base_url() . $image;
    } else {
        return base_url() . "assets/images/service.jpg";
    }
}

function check_profile_image($image) {
    if (file_exists(dirname(BASEPATH) . "/" . $image) && pathinfo($image, PATHINFO_EXTENSION) != '') {
        return base_url() . $image;
    } else {
        return base_url() . "assets/images/user.png";
    }
}

function check_appointment_status($val) {
    $CI = & get_instance();
    if ($val == 'C') {
        return '<span class="badge badge-success">' . translate('completed') . '</span>';
    } elseif ($val == 'A') {
        return '<span class="badge badge-primary">' . translate('approved') . '</span>';
    } elseif ($val == 'P') {
        return '<span class="badge badge-info">' . translate('pending') . '</span>';
    } elseif ($val == 'R') {
        return '<span class="badge badge-warning">' . translate('rejected') . '</span>';
    } else {
        return '<span class="badge badge-danger">' . translate('deleted') . '</span>';
    }
}

function check_appointment_pstatus($val) {
    $CI = & get_instance();
    if ($val == 'S') {
        return '<span class="badge badge-success">' . translate('paid') . '</span>';
    } elseif ($val == 'P') {
        return '<span class="badge badge-info">' . translate('pending') . '</span>';
    } else {
        return '<span class="badge badge-danger">' . translate('failed') . '</span>';
    }
}

function print_appointment_status($val) {
    $CI = & get_instance();
    if ($val == 'C') {
        return translate('completed');
    } elseif ($val == 'A') {
        return translate('approved');
    } elseif ($val == 'P') {
        return translate('pending');
    } elseif ($val == 'R') {
        return translate('rejected');
    } else {
        return translate('deleted');
    }
}

function validatecard($number) {
    global $type;
    $cardtype = array(
        "visa" => "/^4[0-9]{12}(?:[0-9]{3})?$/",
        "mastercard" => "/^5[1-5][0-9]{14}$/",
        "amex" => "/^3[47][0-9]{13}$/",
        "discover" => "/^6(?:011|5[0-9]{2})[0-9]{12}$/",
    );
    if (preg_match($cardtype['visa'], $number)) {
        $type = "visa";
        return 'visa';
    } else if (preg_match($cardtype['mastercard'], $number)) {
        $type = "mastercard";
        return 'mastercard';
    } else if (preg_match($cardtype['amex'], $number)) {
        $type = "amex";
        return 'amex';
    } else if (preg_match($cardtype['discover'], $number)) {
        $type = "discover";
        return 'discover';
    } else {
        return false;
    }
}

function check_vendor_profile($profile_status = NULL) {
    $CI = & get_instance();
    $vendor_id = $CI->session->userdata('Vendor_ID');
    $CI->db->select('*');
    $CI->db->where('id', $vendor_id);
    $vendor_result = $CI->db->get('app_admin')->result_array();

    if (isset($vendor_result) && count($vendor_result) > 0) {
        if (!is_null($profile_status)) {
            if (isset($vendor_result[0]['profile_status']) && $vendor_result[0]['profile_status'] != 'V') {
                $CI->session->set_flashdata('msg_class', 'failure');
                $CI->session->set_flashdata('msg', translate('profile_under_review'));
                redirect("vendor/dashboard");
            } else {
                if (get_site_setting('enable_membership') == 'Y') {
                    $package_id = $vendor_result[0]['package_id'];
                    $membership_till = $vendor_result[0]['membership_till'];

                    if (isset($package_id) && $package_id > 0 && isset($membership_till) && $membership_till != "null" && $membership_till != "") {
                        if ($membership_till < date('Y-m-d')) {
                            $CI->session->set_flashdata('msg_class', 'failure');
                            $CI->session->set_flashdata('msg', translate('membership_expired'));
                            redirect("vendor/membership-purchase");
                        }
                    } else {
                        $CI->session->set_flashdata('msg_class', 'failure');
                        $CI->session->set_flashdata('msg', translate('membership_not_select'));
                        redirect("vendor/membership-purchase");
                    }
                }
            }
        }
    } else {
        $CI->session->set_flashdata('msg_class', 'failure');
        $CI->session->set_flashdata('msg', translate('invalid_request'));
        redirect("vendor/login");
    }
}

function get_last_seen($id) {
    $CI = & get_instance();
    $CI->db->select('last_login');
    $CI->db->where("id", $id);
    $admin_data = $CI->db->get('app_admin')->row_array();

    if (isset($admin_data['last_login']) && $admin_data['last_login'] != "null" && $admin_data['last_login'] != "") {
        $datetime1 = new DateTime(date("Y-m-d H:i:s"));
        $datetime2 = new DateTime($admin_data['last_login']);
        $interval = $datetime1->diff($datetime2);

        $year = $interval->format('%y');
        $month = $interval->format('%m');
        $day = $interval->format('%d');
        $hour = $interval->format('%h');
        $minutes = ($interval->format('%i') != null) ? $interval->format('%i') : 0;

        if ($year != 0) {
            return $year . " year ago";
        } else if ($month != 0) {
            return $month . " " . translate('month') . " " . translate('ago');
        } else if ($day != 0) {
            return $day . " " . translate('days') . " " . translate('ago');
        } else if ($hour != 0) {
            return $hour . " " . translate('hours') . " " . translate('ago');
        } else if ($minutes != 0) {
            return $minutes . " " . translate('minute') . " " . translate('ago');
        } else {
            return $minutes . " " . translate('minute') . " " . translate('ago');
        }
    } else {
        return "Never";
    }
}

function print_vendor_status($status) {
    $CI = & get_instance();
    if ($status == 'A') {
        return '<span class="badge bg-success">' . translate('active') . '</span>';
    } elseif ($status == 'P') {
        return '<span class="badge bg-warning">' . translate('pending') . '</span>';
    } elseif ($status == 'I') {
        return '<span class="badge bg-info">' . translate('inactive') . '</span>';
    } elseif ($status == 'D') {
        return '<span class="badge bg-danger">' . translate('deleted') . '</span>';
    }
}

function get_profile_slider($created_by) {
    $CI = & get_instance();
    $CI->db->select('image');
    $CI->db->where("created_by = '$created_by' AND status='A'");
    $slider = $CI->db->get('app_slider')->result_array();
    return $slider;
}

function get_message($date, $chat_id) {
    $CI = & get_instance();
    $CI->db->select('app_chat.*,  DATE_FORMAT(app_chat.created_on, "%H:%i:%s") AS timestamp, u.profile_image,u.first_name,u.last_name,a.profile_image as aprofile_image,a.first_name as aname,a.last_name as alname');
    $CI->db->join("app_customer u", "u.id = app_chat.from_id", "LEFT");
    $CI->db->join("app_admin a", "a.id = app_chat.from_id", "LEFT");
    $CI->db->where("DATE(app_chat.created_on) = '$date' AND app_chat.chat_id='$chat_id'");
    $CI->db->group_by("app_chat.id");
    $message = $CI->db->get('app_chat')->result_array();
    return $message;
}

function get_full_event_service_data($id) {
    $CI = & get_instance();
    $CI->db->select('app_event.*,app_city.city_title,app_location.loc_title,app_event_category.title as category_title,app_admin.profile_image,app_admin.first_name,app_admin.last_name,app_admin.email,app_admin.phone,app_admin.address as vendor_address,app_admin.company_name,');
    $CI->db->join("app_admin", "app_event.created_by = app_admin.id", "INNER");
    $CI->db->join("app_city", "app_event.city = app_city.city_id", "INNER");
    $CI->db->join("app_location", "app_event.location = app_location.loc_id", "INNER");
    $CI->db->join("app_event_category", "app_event.category_id = app_event_category.id", "INNER");
    $CI->db->where("app_event.id", $id);
    $data_ary = $CI->db->get('app_event')->row_array();
    return $data_ary;
}

function get_full_event_service_data_by_booking_id($id) {
    $CI = & get_instance();

    $select_field = "app_customer.id as customer_id,app_customer.first_name as customer_first_name,app_customer.email as customer_email,app_customer.last_name as customer_last_name,";
    $select_field .= "app_event.id as event_id,app_event.start_date as event_start_date,app_event.end_date as event_end_date,app_event.type,app_event.address,app_event.title,app_event.type,app_event.payment_type,app_event.type,app_city.city_title,app_location.loc_title,";
    $select_field .= "app_event_category.title as category_title,";
    $select_field .= "app_admin.profile_image,app_admin.first_name,app_admin.address as vendor_address,app_admin.last_name,app_admin.email,app_admin.phone,app_admin.address as vendor_address,app_admin.company_name,";
    $select_field .= "app_event_book.id as booking_id,app_event_book.staff_id,app_event_book.addons_id,app_event_book.event_booked_seat,app_event_book.description,app_event_book.start_date,app_event_book.start_time,app_event_book.slot_time,app_event_book.price,app_event_book.status,app_event_book.payment_status,app_event_book.created_on";

    $CI->db->select($select_field);
    $CI->db->join("app_event", "app_event.id = app_event_book.event_id", "INNER");
    $CI->db->join("app_customer", "app_customer.id = app_event_book.customer_id", "INNER");
    $CI->db->join("app_admin", "app_event.created_by = app_admin.id", "INNER");
    $CI->db->join("app_city", "app_event.city = app_city.city_id", "INNER");
    $CI->db->join("app_location", "app_event.location = app_location.loc_id", "INNER");
    $CI->db->join("app_event_category", "app_event.category_id = app_event_category.id", "INNER");
    $CI->db->where("app_event_book.id", $id);
    $data_ary = $CI->db->get('app_event_book')->row_array();
    return $data_ary;
}

function check_unread_msg($chat_id, $vendor_id, $customer_id) {
    $CI = & get_instance();
    $CI->db->select('id');
    $CI->db->where("chat_id='$chat_id' AND to_id='$customer_id' AND from_id='$vendor_id' AND msg_read='N' AND chat_type='NC'");
    $message = $CI->db->get('app_chat')->result_array();
    return isset($message) && count($message) > 0 ? count($message) : 0;
}

function get_unread_msg($chat_id, $vendor_id, $customer_id) {
    $CI = & get_instance();
    $CI->db->select('id');
    $CI->db->where("chat_id='$chat_id' AND to_id='$vendor_id' AND from_id='$customer_id' AND msg_read='N' AND chat_type='C'");
    $message = $CI->db->get('app_chat')->result_array();
    return isset($message) && count($message) > 0 ? count($message) : 0;
}

function get_StripeSecret() {
    $CI = & get_instance();
    $CI->db->select('stripe_secret,stripe');
    $CI->db->from('app_payment_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $user_data = $CI->db->get()->result_array();
    return isset($user_data) && count($user_data) > 0 && $user_data[0]['stripe'] == 'Y' ? $user_data[0]['stripe_secret'] : '';
}

function get_Stripepublish() {
    $CI = & get_instance();
    $CI->db->select('stripe_publish,stripe');
    $CI->db->from('app_payment_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $user_data = $CI->db->get()->result_array();
    return isset($user_data) && count($user_data) > 0 && $user_data[0]['stripe'] == 'Y' ? $user_data[0]['stripe_publish'] : '';
}

function check_payment_method($val) {
    $CI = & get_instance();
    $CI->db->select($val);
    $CI->db->from('app_payment_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $user_data = $CI->db->get()->result_array();
    return isset($user_data) && count($user_data) > 0 && $user_data[0][$val] == 'Y' ? true : false;
}

function get_fevicon() {
    $CI = & get_instance();
    $CI->db->select('fevicon_icon');
    $CI->db->from('app_site_setting');
    $where = "id='1'";
    $CI->db->where($where);
    $user_data = $CI->db->get()->row_array();

    if (isset($user_data['fevicon_icon']) && $user_data['fevicon_icon'] != "") {
        if (file_exists(FCPATH . UPLOAD_PATH . "sitesetting/" . $user_data['fevicon_icon'])) {
            return base_url(UPLOAD_PATH . "sitesetting/" . $user_data['fevicon_icon']);
        } else {
            return base_url("assets/images/fevicon.png");
        }
    } else {
        return base_url("assets/images/fevicon.png");
    }
}

function get_single_row($table, $field, $where) {
    $CI = & get_instance();
    $CI->db->select($field);
    $CI->db->from($table);
    $CI->db->where($where);
    $user_data = $CI->db->get()->row_array();
    return $user_data;
}

function check_mandatory() {
    $CI = & get_instance();
    $categtory = $CI->db->select('id')->from('app_event_category')->get()->result_array();
    $city = $CI->db->select('city_id')->from('app_city')->get()->result_array();
    $location = $CI->db->select('loc_id')->from('app_location')->get()->result_array();
    $payment = $CI->db->select('id')->where("on_cash='Y' OR stripe='Y' OR paypal='Y' OR 2checkout='Y'")->get('app_payment_setting')->result_array();
    if (isset($categtory) && count($categtory) == 0 || isset($city) && count($city) == 0 || isset($location) && count($location) == 0 || isset($payment) && count($payment) == 0) {
        redirect("admin/mandatory-update");
    }
}

//return translation
if (!function_exists('translate_old_db_fetch')) {

    function translate_old_db_fetch($word) {

        $CI = & get_instance();
        $CI->load->database();

        $language_session = $CI->session->userdata('language');
        if (isset($language_session) && $language_session != "" && $language_session != NULL) {
            $language = isset($language_session) ? trim($language_session) : "english";
        } else {
            $language_data = $CI->db->select('language')->where("id=1")->get('app_site_setting')->row_array();
            $language = isset($language_data['language']) ? trim($language_data['language']) : "english";
        }
        $return = '';
        $result = $CI->db->select($language)->where("default_text='" . $word . "'")->get('app_language_data')->row_array();
        $english_result = $CI->db->select('english')->where("default_text='" . $word . "'")->get('app_language_data')->row_array();

        if (isset($result) && count($result) > 0) {
            if (isset($result[$language]) && $result[$language] != "" && $result[$language] != NULL) {
                $return = $result[$language];
            } else {
                $return = $english_result['english'];
            }
        } else {
            $return = $english_result['english'];
        }
        return $return;
    }

}

function translate($word) {
    $CI = & get_instance();
    $CI->load->database();
    $CI->load->helper('language');

    $language_session = $CI->session->userdata('language');
    $language = "english";
    if (isset($language_session) && $language_session != "" && $language_session != NULL) {
        $language = isset($language_session) ? trim($language_session) : "english";
    } else {
        $language_data = $CI->db->select('language')->where("id=1")->get('app_site_setting')->row_array();
        $language = isset($language_data['language']) ? trim($language_data['language']) : "english";
    }

    $CI->lang->load('basic', trim($language));
    $language_word = strtolower(trim($word));
    $translated_word = stripslashes($CI->lang->line($language_word));

    return $translated_word;
}

function get_languages() {
    $CI = & get_instance();
    return $languages = $CI->db->select('*')->where('status', 'A')->from('app_language')->get()->result_array();
}

function cal_avarage_rating($type = 'quality_rating', $vendor_id = '', $event_id = '') {
    $CI = & get_instance();
    $CI->db->select('FORMAT((5 * sum(' . $type . ') / (count(id) * 5)), 2) as average');
    if ($vendor_id != '') {
        $CI->db->where('vendor_id', $vendor_id);
    }
    if ($event_id != '') {
        $CI->db->where('event_id', $event_id);
    }
    $query = $CI->db->get('app_vendor_review');
    return $query->row()->average;
}

function get_latest_chat($cust_id) {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->where('from_id', $cust_id);
    $CI->db->order_by('created_on', 'DESC');
    $CI->db->limit(1);
    $query = $CI->db->get('app_chat');
    $res = $query->row();
    return isset($res->to_id) ? $res->to_id : 0;
}

function chek_rating($id, $appointment_id) {
    $CI = & get_instance();
    $customer_id = $CI->session->userdata('CUST_ID');
    $CI->db->select("id");
    $CI->db->from('app_vendor_review');
    $where = "event_id='$id' AND customer_id='$customer_id' AND appointment_id=" . $appointment_id;
    $CI->db->where($where);
    $avr_rating = $CI->db->get()->result_array();
    return isset($avr_rating) && count($avr_rating) > 0 ? 'true' : 'false';
}

function event_rating($appointment_id, $event_id) {
    if ($appointment_id) {
        $CI = & get_instance();
        $CI->db->select('*');
        $CI->db->where('appointment_id', $appointment_id);
        $CI->db->where('event_id', $event_id);
        $query = $CI->db->get('app_vendor_review');
        $res = $query->row_array();
        return isset($res) ? $res : array();
    } else {
        return array();
    }
}

function check_multiple_book_status($start_date, $start_time, $type, $event_id, $staff_member_id) {
//echo $start_date.", ".$start_time.", ".$type.", ".$event_id;exit;
    $CI = & get_instance();
    $CI->load->model('model_front');
//get event data
    $event = $CI->model_front->getData("app_event", "*", "id=" . $event_id);
    $min = $event[0]['slot_time'];
    $slot_time = $event[0]['slot_time'];
    $multiple_slotbooking_allow = $event[0]['multiple_slotbooking_allow'];
    $multiple_slotbooking_limit = $event[0]['multiple_slotbooking_limit'];
    $staff_member_id = (int) $staff_member_id;

    if ($staff_member_id > 0):
        $multiple_boook_result = $CI->model_front->getData("app_event_book", "start_time,slot_time", "start_time='" . $start_time . "' AND staff_id=" . $staff_member_id . " AND start_date = '" . $start_date . "' AND event_id=" . $event_id . " AND status IN ('A')");
    else:
        $multiple_boook_result = $CI->model_front->getData("app_event_book", "start_time,slot_time", "start_time='" . $start_time . "' AND start_date = '" . $start_date . "' AND event_id=" . $event_id . " AND status IN ('A')");
    endif;


    if (isset($multiple_slotbooking_allow) && $multiple_slotbooking_allow == 'Y') {
        if (count($multiple_boook_result) <= $multiple_slotbooking_limit) {
            return true;
        } else {

            if ($staff_member_id > 0):
                $check_booking_existing = $CI->model_front->getData("app_event_book", "id", "start_date='" . $start_date . "' AND staff_id=" . $staff_member_id . " AND start_time='" . $start_time . "' AND type='$type' AND event_id=" . $event_id . " AND status IN ('A')");
            else:
                $check_booking_existing = $CI->model_front->getData("app_event_book", "id", "start_date='" . $start_date . "' AND start_time='" . $start_time . "' AND type='$type' AND event_id=" . $event_id . " AND status IN ('A')");
            endif;

            if (count($check_booking_existing) > 0) {
                return FALSE;
            } else {
                return true;
            }
        }
    } else {
        if ($staff_member_id > 0):
            $check_booking_existing = $CI->model_front->getData("app_event_book", "id", "start_date='" . $start_date . "' AND start_time='" . $start_time . "' AND staff_id=" . $staff_member_id . " AND type='$type' AND event_id=" . $event_id . " AND status IN ('A')");
        else:
            $check_booking_existing = $CI->model_front->getData("app_event_book", "id", "start_date='" . $start_date . "' AND start_time='" . $start_time . "' AND type='$type' AND event_id=" . $event_id . " AND status IN ('A')");
        endif;

        if (count($check_booking_existing) > 0) {
            return FALSE;
        } else {
            return true;
        }
    }
}

function check_slot_available($eventid, $date, $staff_member_value) {
    $staff_member_value = (int) $staff_member_value;

    if ($date < date("Y-m-d")) {
        return "false";
    } else {
        $CI = & get_instance();
        $customer_id = (int) $CI->session->userdata('CUST_ID');
        $CI->load->model('model_front');
        //get event data
        $event = $CI->model_front->getData("app_event", "*", "id=" . $eventid);

        if (count($event) > 0) {
            $min = $event[0]['slot_time'];
            $slot_time = $event[0]['slot_time'];
            $multiple_slotbooking_allow = $event[0]['multiple_slotbooking_allow'];
            $multiple_slotbooking_limit = $event[0]['multiple_slotbooking_limit'];

            $j = date("h:i a", strtotime("-" . $slot_time . "minute", strtotime($event[0]['end_time'])));
            $datetime1 = new DateTime($event[0]['start_time']);
            $datetime2 = new DateTime($event[0]['end_time']);
            $interval = $datetime1->diff($datetime2);
            $extra_minute = $interval->format('%i');
            $minute = $interval->format('%h') * 60 + $extra_minute;
            $gap_time = ($event[0]['padding_time']);

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
            if ($staff_member_value > 0):
                $result = $CI->model_front->getData("app_event_book", "start_time,slot_time", "start_date = '" . $date . "' AND staff_id=" . $staff_member_value . " AND status IN ('A')");
            else:
                $result = $CI->model_front->getData("app_event_book", "start_time,slot_time", "start_date = '" . $date . "' AND event_id=" . $eventid . " AND status IN ('A')");
            endif;


            if (isset($result) && count($result) > 0) {
                foreach ($result as $key => $value) {
                    if ($min == $value['slot_time']) {

                        if ($staff_member_value > 0):
                            $multiple_boook_result = $CI->model_front->getData("app_event_book", "start_time,slot_time", "start_time='" . $value['start_time'] . "' AND staff_id=" . $staff_member_value . " AND start_date = '" . $date . "' AND event_id=" . $eventid . " AND status IN ('A')");
                        else:
                            $multiple_boook_result = $CI->model_front->getData("app_event_book", "start_time,slot_time", "start_time='" . $value['start_time'] . "' AND start_date = '" . $date . "' AND event_id=" . $eventid . " AND status IN ('A')");
                        endif;

                        if (isset($multiple_slotbooking_allow) && $multiple_slotbooking_allow == 'Y') {
                            if (count($multiple_boook_result) < $multiple_slotbooking_limit) {
                                $time_array = check_slot($time_array, $value['start_time'], $value['slot_time'], $min, $gap_time);
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
                        $time_array = check_slot($time_array, $value['start_time'], $value['slot_time'], $min, $gap_time);
                    }
                }
            }
            if (isset($time_array) && !empty($time_array)) {
                return "true";
            } else {
                return "false";
            }
        } else {
            return "false";
        }
    }
}

function check_slot($time_array, $start_time, $slot_time, $current_slot_time) {
    if ($slot_time > $current_slot_time) {
        $min_time = get_formated_time(strtotime($start_time));
        $max_time = get_formated_time(strtotime("+" . $slot_time . " minute", strtotime($start_time)));
        foreach ($time_array as $key => $value) {
            if ($min_time <= $value && $max_time > $value) {
                if (($key = array_search($value, $time_array)) !== false) {
                    unset($time_array[$key]);
                }
            }
        }
    } else if ($slot_time < $current_slot_time) {
        $min_time = get_formated_time(strtotime($start_time));
        $max_time = get_formated_time(strtotime("+" . $slot_time . " minute", strtotime($start_time)));
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

function convertToHoursMins($time, $format = '%02d Hours : %02d Minute') {
    if ($time < 1) {
        return;
    }
    if ($time < 60) {
        $format = '%02d ' . translate('minute');
        $minutes = ($time % 60);
        return sprintf($format, $minutes);
    }

    if ($time % 60 == 0) {
        $format = '%02d ' . translate('hours');
        $hours = ($time / 60);
        return sprintf($format, $hours);
    }

    if ($time > 60) {
        $format = '%02d ' . translate('hours') . ' : %02d ' . translate('minute');
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }
}

?>
