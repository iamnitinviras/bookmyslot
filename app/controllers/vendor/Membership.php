<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Membership extends MY_Controller {

    public function __construct() {
        parent::__construct();
        /*
         * check vendor module enable by admin
         */
        if (get_site_setting('is_display_vendor') == "N") {
            redirect('front');
        }
        $this->load->model('model_membership');
        set_time_zone();
    }

    //show vendor package history
    public function index() {
        $data['membership_history'] = $this->model_membership->get_package_history($this->login_id);
        $data['title'] = translate('membership');
        $this->load->view('vendor/membership-history', $data);
    }

    public function membership_purchase() {
        $data['package_data'] = $this->model_membership->get_package();
        $data['title'] = translate('membership');
        $this->load->view('vendor/membership-purchase', $data);
    }

    public function purchase_details($id) {
        $id = (int) $id;
        $package_data = $this->model_membership->get_package($id);
        if (isset($package_data) && count($package_data) > 0) {
            $data['package_data'] = $package_data[0];

            $data['vendor_data'] = $this->db->get_where('app_users', array('id' => $this->login_id))->row_array();
            $data['title'] = translate('membership');
            $this->load->view('vendor/membership-details', $data);
        } else {
            redirect("vendor/membership-purchase");
        }
    }

    public function check_package_price($id) {
        $amount = $this->model_membership->get_package_price($id);
        echo $amount;
        exit;
    }

    public function package_purchase() {

        $vendor_data = $this->db->get_where('app_users', array('id' => $this->login_id))->row_array();


        $package_id = $this->input->post('package_id');
        $package_data = $this->model_membership->get_package($package_id);
        $payment_method = $this->input->post('payment_method');


        $vendor_membership_date = $vendor_data['membership_till'];
        $vendor_update_data['package_id'] = $package_id;

        if (isset($vendor_membership_date) && $vendor_membership_date != "null" && $vendor_membership_date != "") {

            if ($vendor_membership_date > date("Y-m-d")) {
                $date_update = date('Y-m-d', strtotime("+" . $package_data[0]['package_month'] . " month", strtotime($vendor_membership_date)));
                $vendor_update_data['membership_till'] = $date_update;
            } else {
                $date_update = date('Y-m-d', strtotime("+" . $package_data[0]['package_month'] . " month"));
                $vendor_update_data['membership_till'] = $date_update;
            }
        } else {
            $date_update = date('Y-m-d', strtotime("+" . $package_data[0]['package_month'] . " month"));
            $vendor_update_data['membership_till'] = $date_update;
        }



        if ($payment_method == 'on_cash') {
            $data['customer_id'] = $this->login_id;
            $data['package_id'] = $package_id;
            $data['remaining_event'] = 0;
            $data['payment_method'] = "Cash";
            $data['payment_status'] = 'paid';
            $data['membership_till'] = $vendor_update_data['membership_till'];

            $data['price'] = $package_data[0]['price'];
            $data['status'] = 'A';
            $data['created_on'] = date('Y-m-d H:i:s');
            $this->db->query("UPDATE app_event SET status='A' WHERE created_by=" . $this->login_id . " AND status='SS'");

            $this->model_membership->insert('app_membership_history', $data);
            $this->model_membership->update('app_users', $vendor_update_data, "id='$this->login_id'");
            $this->session->set_flashdata('msg', translate('transaction_success'));
            $this->session->set_flashdata('msg_class', 'success');
            redirect('vendor/membership');
        } else if ($payment_method == 'stripe') {
            include APPPATH . 'third_party/init.php';

            if ($this->input->post('stripeToken')) {
                $stripe_api_key = get_StripeSecret();
                \Stripe\Stripe::setApiKey($stripe_api_key); //system payment settings

                $charge = \Stripe\Charge::create(array(
                            "amount" => ceil($package_data[0]['price'] * 100),
                            "currency" => "USD",
                            "source" => $_POST['stripeToken'], // obtained with Stripe.js
                            "description" => ''
                ));

                $get_payment_details = $charge->jsonSerialize();


                if ($get_payment_details['paid'] == true) {
                    $data['customer_id'] = $this->login_id;
                    $data['package_id'] = $package_id;
                    $data['remaining_event'] = $package_data[0]['max_event'];
                    $data['payment_method'] = $payment_method;
                    $data['transaction_id'] = $get_payment_details['balance_transaction'];
                    $data['customer_payment_id'] = $_POST['stripeToken'];
                    $data['payment_id'] = $get_payment_details['id'];
                    $data['payment_status'] = 'paid';
                    $data['membership_till'] = $vendor_update_data['membership_till'];
                    $data['failure_code'] = $get_payment_details['failure_code'];
                    $data['failure_message'] = $get_payment_details['failure_message'];
                    $data['price'] = $package_data[0]['price'];
                    $data['status'] = 'A';
                    $data['created_on'] = date('Y-m-d H:i:s');

                    $this->db->query("UPDATE app_event SET status='A' WHERE created_by=" . $this->login_id . " AND status='SS'");

                    $this->model_membership->insert('app_membership_history', $data);
                    $this->model_membership->update('app_users', $vendor_update_data, "id='$this->login_id'");

                    $this->session->set_flashdata('msg', translate('transaction_success'));
                    $this->session->set_flashdata('msg_class', 'success');
                    redirect('vendor/membership');
                } else {
                    $this->session->set_flashdata('msg', translate('transaction_fail'));
                    $this->session->set_flashdata('msg_class', 'failure');
                    redirect('vendor/purchase-details/' . $package_id);
                }
            } else {
                $this->session->set_flashdata('msg', translate('invalid_card'));
                $this->session->set_flashdata('msg_class', 'failure');
                redirect('vendor/purchase-details/' . $package_id);
            }
        } else if ($payment_method == 'paypal') {
            $this->load->library('paypal');
            $get_current_currency = get_current_currency();
            if ($get_current_currency['paypal_supported'] == 'Y') {
                $this->session->set_userdata('package', $package_id);
                $this->session->set_userdata('membership_till', $vendor_update_data['membership_till']);

                $this->paypal->add_field('rm', 2);
                $this->paypal->add_field('cmd', '_xclick');
                $this->paypal->add_field('amount', $package_data[0]['price']);
                $this->paypal->add_field('item_name', "Membership Purchase");
                $this->paypal->add_field('currency_code', trim($get_current_currency['code']));
                $this->paypal->add_field('custom', $package_id);
                $this->paypal->add_field('business', get_payment_setting('paypal_merchant_email'));
                $this->paypal->add_field('cancel_return', base_url('vendor/membership_paypal_cancel'));
                $this->paypal->add_field('return', base_url('vendor/membership_paypal_success'));
                $this->paypal->submit_paypal_post();
            } else {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect('vendor/purchase-details/' . $package_id);
            }
        } else if ($payment_method == '2checkout') {

            $get_current_currency = get_current_currency();
            if ($get_current_currency['2checkout_supported'] == 'Y') {
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
                    'currency_code' => trim($get_current_currency['code']),
                    'li_0_name' => 'Membership Purchase',
                    'li_0_price' => $package_data[0]['price'],
                    'card_holder_name' => $this->input->post('first_name') . " " . $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'booking_type' => "VP",
                    'package' => $package_id,
                    'vendor_id' => $this->login_id,
                    'membership_till' => $vendor_update_data['membership_till'],
                    'x_receipt_link_url' => base_url('2checkout-success')
                );
                Twocheckout_Charge::form($params, 'auto');
            } else {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect('vendor/purchase-details/' . $package_id);
            }
        } else {
            $this->session->set_flashdata('msg', translate('select_payment'));
            $this->session->set_flashdata('msg_class', 'failure');
            redirect('vendor/purchase-details/' . $package_id);
        }
    }

    public function membership_paypal_success() {
        if (isset($_REQUEST['st']) && $_REQUEST['st'] == "Completed") {

            $package_id = $this->session->userdata('package');
            $membership_till = $this->session->userdata('membership_till');

            $data['customer_id'] = $this->login_id;
            $data['package_id'] = $package_id;
            $data['remaining_event'] = 0;
            $data['payment_method'] = 'PayPal';
            $data['transaction_id'] = $_REQUEST['tx'];
            $data['customer_payment_id'] = $_REQUEST['tx'];
            $data['payment_id'] = $_REQUEST['tx'];
            $data['payment_status'] = 'paid';
            $data['membership_till'] = $membership_till;
            $data['failure_code'] = "";
            $data['failure_message'] = "";
            $data['price'] = $_REQUEST['amt'];
            $data['status'] = 'A';
            $data['created_on'] = date('Y-m-d H:i:s');

            $vendor_update_data['package_id'] = $package_id;
            $vendor_update_data['membership_till'] = $membership_till;

            $this->db->query("UPDATE app_event SET status='A' WHERE created_by=" . $this->login_id . " AND status='SS'");

            $this->model_membership->insert('app_membership_history', $data);
            $this->model_membership->update('app_users', $vendor_update_data, "id='$this->login_id'");

            $this->session->unset_userdata('package');
            $this->session->unset_userdata('membership_till');

            $this->session->set_flashdata('msg', translate('transaction_success'));
            $this->session->set_flashdata('msg_class', 'success');
            redirect('vendor/membership');
        } else {
            $this->session->unset_userdata('package');
            $this->session->unset_userdata('membership_till');

            $this->session->set_flashdata('msg', translate('transaction_fail'));
            $this->session->set_flashdata('msg_class', 'failure');
            redirect('vendor/membership');
        }
    }

    public function membership_paypal_cancel() {
        //unset session value
        $this->session->unset_userdata('package');
        $this->session->unset_userdata('membership_till');

        $this->session->set_flashdata('msg', translate('transaction_fail'));
        $this->session->set_flashdata('msg_class', 'failure');
        redirect('vendor/membership');
    }

    public function membership_purchase_details($id) {
        $result_data = $this->model_membership->getData('app_membership_history', '*', "id=" . $id);
        $data['result_data'] = $result_data[0];
        $this->load->view('vendor/get_membership_details', $data);
    }

}

?>