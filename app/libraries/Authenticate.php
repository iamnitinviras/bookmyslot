<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authenticate {

    public function check() {
        $CI = & get_instance();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: private, no-store, max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $session_val = (int) $CI->session->userdata('CUST_ID');
        if ($session_val == 0) {
            $CI->session->set_flashdata('msg_class', 'failure');
            $CI->session->set_flashdata('msg', translate('protected_message'));
            redirect("login");
        }
    }

    public function check_admin() {
        $CI = & get_instance();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: private, no-store, max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $session_val = (int) $CI->session->userdata('ADMIN_ID');
        if ($session_val == 0) {
            $folder_name = $CI->uri->segment(1);
            $CI->session->set_flashdata('msg_class', 'failure');
            $CI->session->set_flashdata('msg', translate('protected_message'));
            if ($folder_name == 'vendor') {
                redirect("vendor/login");
            } else {
                redirect("admin/login");
            }
        }
    }

    public function check_vendor() {
        $CI = & get_instance();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: private, no-store, max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $session_val = (int) $CI->session->userdata('Vendor_ID');
        if ($session_val == 0) {
            $CI->session->set_flashdata('msg_class', 'failure');
            $CI->session->set_flashdata('msg', translate('protected_message'));
            redirect("vendor/login");
        }
    }

    public function check_staff() {
        $CI = & get_instance();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: private, no-store, max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $session_val = (int) $CI->session->userdata('staff_id');
        if ($session_val == 0) {
            $CI->session->set_flashdata('msg_class', 'failure');
            $CI->session->set_flashdata('msg', translate('protected_message'));
            redirect("staff/login");
        }
    }

}
