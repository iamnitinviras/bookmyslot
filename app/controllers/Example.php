<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Example extends REST_Controller {

    function __construct() {
        parent::__construct();
        echo "s";
    }

    public function login_get() {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        echo "s";
        exit;
        $this->response(['status' => true, 'message' => 'OTP verify successfully.'], REST_Controller::HTTP_OK);
    }

}
