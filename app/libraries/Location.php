<?php

include APPPATH . 'third_party/class.ipdetails.php';

class Location {

    public function map() {
        $CI = & get_instance();
        if (preg_match("/^([d]{1,3}).([d]{1,3}).([d]{1,3}).([d]{1,3})$/", getenv('HTTP_X_FORWARDED_FOR'))) {
            return getenv('HTTP_X_FORWARDED_FOR');
        }
        
        $ip_add = (getenv('REMOTE_ADDR')) ?getenv('REMOTE_ADDR') : "";
        $myip = new vsipdetails($ip_add);
        $result = $myip->scan();
        return $result;
        $myip->close();
    }


}

?>
