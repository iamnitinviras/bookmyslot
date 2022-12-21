<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        /*
         * check vendor module enable by admin
         */
        if (get_site_setting('is_display_vendor') == "N") {
            redirect('front');
        }
        $this->load->model('model_dashboard');
        $this->load->model('model_event');
        set_time_zone();
    }

    //show vendor dashboard
    public function index() {

        $vendor_id = (int) $this->session->userdata('Vendor_ID');
        $commission_percentage = get_site_setting('commission_percentage');
        $package_id = $this->model_event->get_current_membership($vendor_id);
        $data['total_event'] = $this->model_dashboard->Totalcount('app_event', "created_by='$vendor_id' AND type='E'");
        $data['vendor_staff'] = $this->model_dashboard->Totalcount('app_users', "created_by='$vendor_id' AND type='S'");
        $data['total_service'] = $this->model_dashboard->Totalcount('app_event', "created_by='$vendor_id' AND type='S'");
        $data['total_appointment'] = $this->model_dashboard->vendor_total_appointment($vendor_id);

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
        $cond = " app_event.type='S' AND app_event_book.status='A' AND app_event_book.start_date >= '$current_date' AND app_event_book.start_date <= '$up_date' AND app_event.created_by='$this->login_id'";
        $appointment = $this->model_dashboard->getData('app_event_book', 'app_event_book.*,app_customer.first_name,app_customer.last_name,app_event.title,app_event.payment_type,app_event.created_by', $cond, $join);

        $data['appointment_data'] = $appointment;

        //get total amount
        $joins = array(
            array(
                "table" => "app_event",
                "condition" => "app_event.id=app_event_book.event_id",
                "jointype" => "INNER")
        );
        $vendor_appointment_amount = $this->model_dashboard->getData('app_event_book', 'SUM(app_event_book.vendor_price) as vendor_appointment_amount', "app_event.created_by=" . $vendor_id . " AND app_event_book.payment_status='S'", $joins);

        $cond_pending = "app_event_book.status='P' AND app_event.type='S' AND app_event_book.start_date >= '$current_date'  AND app_event.created_by=" . $this->login_id;
        $pending_appointment = $this->model_dashboard->getData('app_event_book', 'app_event_book.*,app_customer.first_name,app_customer.last_name,app_event.title,app_event.payment_type,app_event.created_by', $cond_pending, $join);
        $data['pending_appointment'] = $pending_appointment;

        //Get Pending Event
        $event_cond_pending = "app_event_book.status='P' AND app_event.type='E' AND app_event_book.start_date >= '$current_date' AND  app_event.created_by=" . $this->login_id;
        $pending_event = $this->model_dashboard->getData('app_event_book', 'app_event_book.*,app_customer.first_name,app_customer.last_name,app_event.title,app_event.payment_type,app_event.created_by', $event_cond_pending, $join);
        $data['pending_event'] = $pending_event;

        $data['commission_percentage'] = $commission_percentage;
        $data['vendor_appointment_amount'] = $vendor_appointment_amount;
        $data['title'] = translate('dashboard');
        $this->load->view('vendor/dashboard', $data);
    }

    public function wallet() {


        $data['title'] = translate('my_wallet_amount');
        $Vendor_ID = $this->session->userdata('Vendor_ID');
        if (isset($Vendor_ID) && $Vendor_ID > 0) {
            $minimum_vendor_payout = get_site_setting('minimum_vendor_payout');
            $total = $this->model_dashboard->getData('app_users', 'my_wallet,my_earning', "id = " . $Vendor_ID)[0];
            $data['payment_data'] = $this->model_dashboard->getData("app_payment_request", '*', "vendor_id=" . $Vendor_ID, '', "id desc");
            $data['minimum_vendor_payout'] = $minimum_vendor_payout;
            $data['total_earning'] = isset($total['my_wallet']) ? $total['my_wallet'] : "";
            $data['total_withdrawable'] = isset($total['my_earning']) ? $total['my_earning'] : "";
            $this->load->view('vendor/wallet', $data);
        } else {
            redirect('vendor/dashboard');
        }
    }

    public function payment_request_save() {
        $Vendor_ID = $this->session->userdata('Vendor_ID');
        $cash_payment_vendor = get_cash_payment_vendor();
        if ($Vendor_ID > 0) {
            $get_VendorDetails = get_VendorDetails($Vendor_ID);

            $minimum_vendor_payout = get_site_setting('minimum_vendor_payout');
            $vendor_wallet = $this->model_dashboard->getData("app_users", 'my_wallet,my_earning', "id=" . $Vendor_ID);
            if (isset($vendor_wallet[0]['my_earning']) && $vendor_wallet[0]['my_earning'] > 0) {
                $my_wallet = $this->input->post('payout_amount');

                if ($my_wallet > $vendor_wallet[0]['my_earning']) {

                    $this->session->set_flashdata('msg', translate('wallet_error'));
                    $this->session->set_flashdata('msg_class', 'failure');
                    redirect('vendor/wallet');
                } else {

                    if ($my_wallet >= $minimum_vendor_payout) {

                        $app_vendor_payment['vendor_id'] = $Vendor_ID;
                        $app_vendor_payment['amount'] = $my_wallet;
                        $app_vendor_payment['created_date'] = date('Y-m-d H:i:s');
                        $app_vendor_payment['status'] = 'P';
                        $app_vendor_payment['cash_payment'] = $cash_payment_vendor;
                        $app_vendor_payment['payment_gateway_ref'] = $this->input->post('payment_gateway_ref');
                        $app_vendor_payment['choose_payment_gateway'] = $this->input->post('payment_gateway');

                        $my_wallet = $my_wallet + $cash_payment_vendor;

                        $this->db->insert('app_payment_request', $app_vendor_payment);
                        $id = $this->db->insert_ID();
                        if ($id) {

                            $this->db->query("UPDATE app_users SET my_earning=my_earning-" . $my_wallet . ",cash_payment=0,my_wallet=my_wallet-" . $my_wallet . " WHERE id=" . $Vendor_ID);
                            $this->session->set_flashdata('msg', translate('payout_request_success'));
                            $this->session->set_flashdata('msg_class', 'success');

                            //Send email to admin
                            $admin_data = $this->model_dashboard->getData("app_users", '*', "type='A'");
                            $subject = get_CompanyName() . " | " . translate('payout_request');

                            foreach ($admin_data as $val):
                                $define_param2['to_name'] = $val['first_name'] . " " . $val['last_name'];
                                $define_param2['to_email'] = $val['email'];

                                $parameters['name'] = $val['first_name'] . " " . $val['last_name'];
                                $parameters['vendor_name'] = $get_VendorDetails['first_name'] . " " . $get_VendorDetails['last_name'];
                                $parameters['company_name'] = $get_VendorDetails['company_name'];
                                $parameters['price'] = price_format($my_wallet);
                                $html2 = $this->load->view("email_template/payout_request", $parameters, true);
                                $this->sendmail->send($define_param2, $subject, $html2);
                            endforeach;
                            redirect('vendor/wallet');
                        } else {
                            $this->session->set_flashdata('msg', translate('payout_request_error'));
                            $this->session->set_flashdata('msg_class', 'failure');
                            redirect('vendor/wallet');
                        }
                    } else {
                        $this->session->set_flashdata('msg', translate('payout_minimum_amount') . " " . price_format($minimum_vendor_payout));
                        $this->session->set_flashdata('msg_class', 'failure');
                        redirect('vendor/wallet');
                    }
                }
            } else {
                $this->session->set_flashdata('msg', translate('payout_minimum_amount') . " " . price_format($minimum_vendor_payout));
                $this->session->set_flashdata('msg_class', 'failure');
                redirect('vendor/wallet');
            }
        } else {
            redirect('vendor/dashboard');
        }
    }

    public function payment_status_update($id) {
        if ($id) {
            $payment_data = $this->model_dashboard->getData("app_appointment_payment", '*', "id=" . $id . " AND payment_status!='S'");

            if (count($payment_data) > 0) {
                $Vendor_ID = $this->session->userdata('Vendor_ID');
                $payment_method = $payment_data[0]['payment_method'];

                $data['payment_status'] = "S";
                $this->model_dashboard->update('app_appointment_payment', $data, "id=$id");

                //update event status
                $data_event['payment_status'] = "S";
                $this->model_dashboard->update('app_event_book', $data_event, "id=" . $payment_data[0]['booking_id']);


                if (get_site_setting('enable_membership') == 'Y') {
                    $vendor_amount = $payment_data[0]['vendor_price'] + $payment_data[0]['admin_price'];
                    $admin_amount = 0;
                } else {
                    $vendor_amount = $payment_data[0]['vendor_price'];
                    $admin_amount = $payment_data[0]['admin_price'];
                }


                if ($payment_method == "Cash") {
                    $up_qry_vendor1 = $this->db->query("UPDATE app_users SET cash_payment=cash_payment+" . $admin_amount . " WHERE id=" . $Vendor_ID);
                    $up_qry_vendor = $this->db->query("UPDATE app_users SET my_wallet=my_wallet+" . $vendor_amount . " WHERE id=" . $Vendor_ID);
                } else {
                    $up_qry_vendor = $this->db->query("UPDATE app_users SET my_earning=my_earning+" . $vendor_amount . ",my_wallet=my_wallet+" . $vendor_amount . " WHERE id=" . $Vendor_ID);
                }
                $up_qry_admin = $this->db->query("UPDATE app_users SET my_wallet=my_wallet+" . $admin_amount . " WHERE id=1");

                $this->session->set_flashdata('msg', translate('status_update'));
                $this->session->set_flashdata('msg_class', 'success');
                echo true;
                exit(0);
            } else {
                $this->session->set_flashdata('msg', translate('Invalid_request'));
                $this->session->set_flashdata('msg_class', 'failure');
                echo FALSE;
                exit(0);
            }
        } else {
            $this->session->set_flashdata('msg', translate('Invalid_request'));
            $this->session->set_flashdata('msg_class', 'failure');
            echo FALSE;
            exit(0);
        }
    }

    public function contact_us() {
        $Vendor_ID = $this->session->userdata('Vendor_ID');
        $fields = "app_contact_us.*,CONCAT(app_users.first_name,' ',app_users.last_name) as vendor_name,app_event.title as event_name";
        $join = array(
            array(
                "table" => "app_users",
                "condition" => "app_users.id=app_contact_us.admin_id",
                "jointype" => "INNER"),
            array(
                "table" => "app_event",
                "condition" => "app_event.id=app_contact_us.event_id",
                "jointype" => "INNER")
        );
        $app_contact_us = $this->model_dashboard->getData("app_contact_us", $fields, "app_contact_us.admin_id=" . $Vendor_ID, $join, "id DESC");
        $data['title'] = translate('contact-us-request');
        $data['app_contact_us'] = $app_contact_us;
        $this->load->view('vendor/contact-us', $data);
    }

    public function vendor_event_inquiry_reply() {

        $reply_name = isset($_POST['reply_name']) ? $_POST['reply_name'] : "";
        $record_id = isset($_POST['record_id']) ? $_POST['record_id'] : "";
        $reply_email = isset($_POST['reply_email']) ? $_POST['reply_email'] : "";
        $reply_subject = isset($_POST['reply_subject']) ? $_POST['reply_subject'] : "";
        $reply_text = isset($_POST['reply_text']) ? $_POST['reply_text'] : "";

        if (isset($reply_email) && $reply_email != "" && $reply_email != NULL) {
            $define_param2['to_name'] = $reply_name;
            $define_param2['to_email'] = $reply_email;

            $parameters['name'] = $reply_name;
            $parameters['content_data'] = $reply_email;
            $html2 = $this->load->view("email_template/comman", $parameters, true);
            $send = $this->sendmail->send($define_param2, $reply_subject, $html2);

            $this->session->set_flashdata('msg', translate('reply_send_success'));
            $this->session->set_flashdata('msg_class', 'success');
        }
        redirect(base_url('vendor/contact-us'));
    }

}

?>