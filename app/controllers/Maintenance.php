<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Maintenance extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (is_maintenance_mode() == 'N') {
            redirect(base_url());
            exit(0);
        }
    }

    public function index() {
        $data['title'] = "Maintenance";
        $this->load->view('front/maintenance', $data);
    }

}
