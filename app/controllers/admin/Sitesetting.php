<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sitesetting extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_sitesetting');
        set_time_zone();
    }

    //show site setting form
    public function index() {
        $data['title'] = translate('manage') . " " . translate('site_setting');
        $company_data = $this->model_sitesetting->get();
        $language_data = $this->model_sitesetting->getData('app_language', '*', 'status ="A"');
        $data['company_data'] = $company_data[0];
        $data['language_data'] = $language_data;

        $this->load->view('admin/setting/site', $data);
    }

    //add/edit site setting
    public function save_sitesetting() {

        $id = $this->input->post('id', true);
        $this->form_validation->set_rules('company_logo', '', 'callback_check_logo_size');
        $this->form_validation->set_rules('banner_img', '', 'callback_check_banner_size');
        $this->form_validation->set_rules('community_banner', '', 'callback_check_banner_size');
        $this->form_validation->set_rules('company_name', '', 'required');
        $this->form_validation->set_rules('company_email1', '', 'required');
        $this->form_validation->set_rules('company_email1', '', 'required');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == false) {
            $this->index();
        } else {
            $data['company_name'] = $this->input->post('company_name', true);
            $data['company_address1'] = $this->input->post('company_address1', true);
            $data['company_address2'] = $this->input->post('company_address2', true);
            $data['company_phone1'] = $this->input->post('company_phone1', true);
            $data['company_phone2'] = $this->input->post('company_phone2', true);
            $data['company_email1'] = $this->input->post('company_email1', true);
            $data['language'] = $this->input->post('language', true);
            $data['home_page'] = $this->input->post('home_page', true);
            $data['time_zone'] = $this->input->post('time_zone', true);

            $data['fb_link'] = $this->input->post('fb_link', true);
            $data['google_link'] = $this->input->post('google_link', true);
            $data['twitter_link'] = $this->input->post('twitter_link', true);
            $data['insta_link'] = $this->input->post('insta_link', true);
            $data['linkdin_link'] = $this->input->post('linkdin_link', true);



            if (isset($_FILES['fevicon_icon']) && $_FILES['fevicon_icon']['name'] != '') {
                $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/sitesetting';
                $fevicon_tmp_name = $_FILES["fevicon_icon"]["tmp_name"];
                $fevicon_temp = explode(".", $_FILES["fevicon_icon"]["name"]);
                $fevicon_name = uniqid();
                $new_fevicon_name = $fevicon_name . '.' . end($fevicon_temp);
                move_uploaded_file($fevicon_tmp_name, "$uploadPath/$new_fevicon_name");
                $data['fevicon_icon'] = $new_fevicon_name;
            }
            if (isset($_FILES['company_logo']) && $_FILES['company_logo']['name'] != '') {
                $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/sitesetting';
                $logo_tmp_name = $_FILES["company_logo"]["tmp_name"];
                $logo_temp = explode(".", $_FILES["company_logo"]["name"]);
                $logo_name = uniqid();
                $new_logo_name = $logo_name . '.' . end($logo_temp);
                move_uploaded_file($logo_tmp_name, "$uploadPath/$new_logo_name");
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $uploadPath . '/' . $new_logo_name;
                $config['create_thumb'] = TRUE;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 241;
                $config['height'] = 61;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if ($this->image_lib->resize()) {
                    $data['company_logo'] = $logo_name . "_thumb." . end($logo_temp);
                }
            }
            if (isset($_FILES['banner_img']) && $_FILES['banner_img']['name'] != '') {
                $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/sitesetting';
                $banner_tmp_name = $_FILES["banner_img"]["tmp_name"];
                $banner_temp = explode(".", $_FILES["banner_img"]["name"]);
                $nanner_name = uniqid();
                $new_banner_name = $nanner_name . '.' . end($banner_temp);
                move_uploaded_file($banner_tmp_name, "$uploadPath/$new_banner_name");
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $uploadPath . '/' . $new_banner_name;
                $config['create_thumb'] = TRUE;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 1900;
                $config['height'] = 500;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if ($this->image_lib->resize()) {
                    $data['banner_image'] = $nanner_name . "_thumb." . end($banner_temp);
                }
            }
            if (isset($_FILES['community_banner']) && $_FILES['community_banner']['name'] != '') {
                $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/sitesetting';
                $banner_tmp_name = $_FILES["community_banner"]["tmp_name"];
                $banner_temp = explode(".", $_FILES["community_banner"]["name"]);
                $nanner_name = uniqid();
                $new_banner_name = $nanner_name . '.' . end($banner_temp);
                move_uploaded_file($banner_tmp_name, "$uploadPath/$new_banner_name");
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $uploadPath . '/' . $new_banner_name;
                $config['create_thumb'] = TRUE;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 1900;
                $config['height'] = 500;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if ($this->image_lib->resize()) {
                    $data['community_banner'] = $nanner_name . "_thumb." . end($banner_temp);
                }
            }
            $this->model_sitesetting->edit(1, $data);
            $this->session->set_flashdata('msg', translate('site_setting_update'));
            $this->session->set_flashdata('msg_class', "success");
            redirect('admin/sitesetting', 'redirect');
        }
    }

    //show email form
    public function email_setting() {
        $company_data = $this->model_sitesetting->get_email();
        $data['title'] = translate('email') . " " . translate('details');
        $data['email_data'] = $company_data;
        $this->load->view('admin/setting/email', $data);
    }

    //add/edit email data
    public function save_email_setting() {
        $mail_type = $this->input->post('mail_type');

        $this->form_validation->set_rules('smtp_host', '', 'trim|required');
        $this->form_validation->set_rules('smtp_username', '', 'trim|required');
        $this->form_validation->set_rules('smtp_password', '', 'trim|required');
        $this->form_validation->set_rules('smtp_port', '', 'trim|required');
        $this->form_validation->set_rules('smtp_secure', '', 'trim|required');
        $this->form_validation->set_rules('email_from_smtp', 'From email', 'trim|required|valid_email');

        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
            $this->email_setting();
        } else {

            $data['mail_type'] = $this->input->post('mail_type', true);
            $data['smtp_host'] = $this->input->post('smtp_host', true);
            $data['smtp_password'] = $this->input->post('smtp_password', true);
            $data['smtp_username'] = $this->input->post('smtp_username', true);
            $data['smtp_port'] = $this->input->post('smtp_port', true);
            $data['smtp_secure'] = $this->input->post('smtp_secure', true);
            $data['email_from'] = $this->input->post('email_from_smtp', true);

            $this->model_sitesetting->edit_email(1, $data);
            $this->session->set_flashdata('msg', translate('smtp_update'));
            $this->session->set_flashdata('msg_class', "success");
            redirect('admin/setting/email');
        }
    }

    //show payment setting form
    public function payment_setting() {
        $data['title'] = translate('payment_setting');
        $payment_data = $this->model_sitesetting->getData('app_payment_setting', '*');
        $data['payment_data'] = isset($payment_data[0]) ? $payment_data[0] : '';
        $this->load->view('admin/setting/payment', $data);
    }

    public function currency_setting() {
        $data['title'] = translate('currency') . " " . translate('setting');
        $c_data = $this->model_sitesetting->getData('app_site_setting', 'currency_id,currency_position')[0];
        $app_currency = $this->model_sitesetting->getData('app_currency', '*');

        $data['app_currency'] = isset($app_currency) ? $app_currency : array();
        $data['currency_data'] = $c_data;
        $this->load->view('admin/setting/currency', $data);
    }

    public function save_curenncy_setting() {
        $data['currency_id'] = $this->input->post('currency_id', true);
        $data['currency_position'] = $this->input->post('currency_position', true);
        $this->model_sitesetting->edit_data('app_site_setting', 1, $data);

        $this->db->query('UPDATE app_currency SET status="I" WHERE 1');
        $this->db->query('UPDATE app_currency SET status="A" WHERE id=' . $this->input->post('currency_id', true));

        $this->session->set_flashdata('msg', "Currency has been updated.");
        $this->session->set_flashdata('msg_class', "success");
        redirect('admin/setting/currency');
    }

    //save payment setting
    public function save_payment_setting() {
        $id = (int) $this->input->post('id');
        $this->form_validation->set_rules('stripe', '', 'required');
        $this->form_validation->set_rules('paypal', '', 'required');
        $this->form_validation->set_rules('on_cash', '', 'required');

        if ($this->input->post('stripe') == 'Y') {
            $this->form_validation->set_rules('stripe_secret', '', 'required');
            $this->form_validation->set_rules('stripe_publish', '', 'required');
        }
        if ($this->input->post('paypal') == 'Y') {
            $this->form_validation->set_rules('paypal_merchant_email', '', 'required');
        }
        if ($this->input->post('2checkout') == 'Y') {
            $this->form_validation->set_rules('2checkout_account_no', '', 'trim|required');
            $this->form_validation->set_rules('2checkout_publishable_key', '', 'trim|required');
            $this->form_validation->set_rules('2checkout_private_key', '', 'trim|required');
        }

        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == false) {
            $this->payment_setting();
        } else {
            $data['stripe'] = $this->input->post('stripe', true);
            $data['paypal'] = $this->input->post('paypal', true);
            $data['paypal_sendbox_live'] = $this->input->post('paypal_sendbox_live', true);
            $data['paypal_merchant_email'] = $this->input->post('paypal_merchant_email', true);
            $data['on_cash'] = $this->input->post('on_cash', true);

            $data['stripe_secret'] = $this->input->post('stripe_secret', true);
            $data['stripe_publish'] = $this->input->post('stripe_publish', true);

            $data['2checkout'] = $this->input->post('2checkout', true);
            $data['2checkout_account_no'] = $this->input->post('2checkout_account_no', true);
            $data['2checkout_live_sandbox'] = $this->input->post('2checkout_live_sandbox', true);
            $data['2checkout_publishable_key'] = $this->input->post('2checkout_publishable_key', true);
            $data['2checkout_private_key'] = $this->input->post('2checkout_private_key', true);


            if ($id > 0) {
                $this->model_sitesetting->edit_data('app_payment_setting', $id, $data);
            } else {
                $data['created_on'] = date('Y-m-d H:i:s');
                $this->model_sitesetting->insert_data('app_payment_setting', $data);
            }
            $this->session->set_flashdata('msg', translate('payment_setting_update'));
            $this->session->set_flashdata('msg_class', "success");
            redirect('admin/setting/payment');
        }
    }

    public function check_logo_size() {
        if (isset($_FILES['banner_img']['tmp_name']) && $_FILES['banner_img']['tmp_name'] != "") {
            $ext = pathinfo($_FILES['banner_img']['name'], PATHINFO_EXTENSION);
            $valid_extension_arr = array('jpg', 'png', 'jpeg', 'gif');
            if (!in_array(strtolower($ext), $valid_extension_arr)) {
                $this->form_validation->set_message('check_logo_size', translate('valid_image'));
                return FALSE;
            } else {
                $data = getimagesize($_FILES['banner_img']['tmp_name']);
                $width = isset($data[0]) ? (int) $data[0] : 0;
                $height = isset($data[1]) ? (int) $data[1] : 0;
                if ($width >= 241 && $height >= 61) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('check_logo_size', translate('valid_logo'));
                    return FALSE;
                }
            }
        }
    }

    public function check_banner_size() {
        if (isset($_FILES['banner_img']['tmp_name']) && $_FILES['banner_img']['tmp_name'] != "") {
            $ext = pathinfo($_FILES['banner_img']['name'], PATHINFO_EXTENSION);
            $valid_extension_arr = array('jpg', 'png', 'jpeg', 'gif');
            if (!in_array(strtolower($ext), $valid_extension_arr)) {
                $this->form_validation->set_message('check_banner_size', translate('valid_image'));
                return FALSE;
            } else {
                $data = getimagesize($_FILES['banner_img']['tmp_name']);
                $width = isset($data[0]) ? (int) $data[0] : 0;
                $height = isset($data[1]) ? (int) $data[1] : 0;
                if ($width >= 1900 && $height >= 500) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('check_banner_size', translate('valid_banner'));
                    return FALSE;
                }
            }
        }
    }

    //show email form
    public function display_setting() {
        $data['title'] = translate('Display') . " " . translate('site_setting');
        $company_data = $this->model_sitesetting->get();
        $data['company_data'] = $company_data[0];
        $this->load->view('admin/setting/display', $data);
    }

    //add/edit email data
    public function update_display_setting() {
        $action = $this->input->post('action');
        if ($action == "perpage_setting") {
            $up_data = $this->input->post('up_data');
            $name = $this->input->post('name');
            $data[$name] = $up_data;
            $res = $this->model_sitesetting->edit(1, $data);
        } else if ($action == "update_time") {
            $time_format = $this->input->post('time_format');
            $data['time_format'] = $time_format;
            $res = $this->model_sitesetting->edit(1, $data);
        } else {
            $name = $this->input->post('name');
            $is_display = $this->input->post('display');
            if ($name == 'is_display_location' && $is_display == 'N') {
                $this->session->unset_userdata('location');
            }
            $data[$name] = $is_display;
            $res = $this->model_sitesetting->edit(1, $data);
        }
        $this->session->set_flashdata('msg', translate('site_setting_update'));
        $this->session->set_flashdata('msg_class', "success");
        if ($res) {
            return TRUE;
        }
    }

    public function business_setting() {
        $company_data = $this->model_sitesetting->get();

        $vendor_data = $this->model_sitesetting->getData('app_vendor_setting', '*', 'id=1');

        $data['title'] = translate('manage') . " " . translate('business') . " " . translate('setting');
        $data['business_data'] = $company_data[0];
        $data['vendor_data'] = $vendor_data[0];
        $this->load->view('admin/setting/business', $data);
    }

    public function save_businesss_setting() {
        $membership = $this->input->post('membership', true);
        if (isset($membership) && $membership == "N") {
            $this->form_validation->set_rules('commission_percentage', '', 'required');
        }

        $allow_city_location = $this->input->post('allow_city_location', true);
        $allow_service_category = $this->input->post('allow_service_category', true);
        $allow_event_category = $this->input->post('allow_event_category', true);


        $this->form_validation->set_rules('minimum_vendor_payout', '', 'trim|required');
        $this->form_validation->set_rules('slot_display_days', '', 'trim|required|numeric');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == false) {
            $this->index();
        } else {
            $data = array();
            $data['minimum_vendor_payout'] = $this->input->post('minimum_vendor_payout', true);
            $data['enable_membership'] = "Y";
            $data['slot_display_days'] = $this->input->post('slot_display_days', true);

            $this->model_sitesetting->edit(1, $data);


            $vendor_data['allow_city_location'] = isset($allow_city_location) ? $allow_city_location : "N";
            $vendor_data['allow_service_category'] = isset($allow_service_category) ? $allow_service_category : "N";
            $vendor_data['allow_event_category'] = isset($allow_event_category) ? $allow_event_category : "N";

            $res = $this->model_sitesetting->edit_data('app_vendor_setting', 1, $vendor_data);


            $this->session->set_flashdata('msg', translate('business_setting_update'));
            $this->session->set_flashdata('msg_class', "success");
            redirect('admin/setting/business', 'redirect');
        }
    }

    public function integrateon_webpage() {
        $token = "";
        $logged_id = $this->login_type == 'V' ? $this->session->userdata('Vendor_ID') : $this->session->userdata('ADMIN_ID');
        $admin_data = $this->model_sitesetting->getData('app_users', '*', 'id =' . $logged_id);
        if (isset($admin_data) && !empty($admin_data)) {
            foreach ($admin_data as $aRow) {
                $token = $aRow['token'];
            }
        }
        if ($token == "") {
            $this->load->helper('string');
            $token = random_string('alnum', 16);
            $u_data['token'] = $token;
            $res = $this->model_sitesetting->edit_data('app_users', $logged_id, $u_data);
        }
        $data['token'] = $token;
        $data['title'] = translate('integrateon_webpage');
        $webpage = $this->input->post('webpage');
        $data['webpage'] = $webpage;
        $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
        if ($folder_url == 'admin') {
            $this->load->view($folder_url . '/setting/manage_integrateon_webpage', $data);
        } else {
            $this->load->view($folder_url . '/manage_integrateon_webpage', $data);
        }
    }

}

?>