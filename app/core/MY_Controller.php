<?php

class MY_Controller extends CI_Controller {

    public $login_type;
    public $login_id;

    function __construct() {
        parent::__construct();
        $this->lang->load('basic', get_Langauge());
        run_default_query();
        expire_holiday_date();
        $this->login_type = $this->session->userdata('Type_' . ucfirst($this->uri->segment(1)));
        if ($this->login_type == 'V') {
            $this->login_id = $this->session->userdata('Vendor_ID');
            $this->authenticate->check_vendor();

            if ($this->router->fetch_class() != 'dashboard' && $this->router->fetch_class() != "membership") {
                check_vendor_profile('1');
            }
        } else {
            $this->login_id = $this->session->userdata('ADMIN_ID');
            $this->authenticate->check_admin();
        }
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
    }

}
