<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Timezone {

    function __construct() {
        $CI = & get_instance();
        date_default_timezone_set(get_time_zone());
    }

}
