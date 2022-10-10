<?php
include "../environment.php";

$get_loaded_extensions = get_loaded_extensions();
if (ENVIRONMENT === "pre_installation") {
    $php_version_success = false;
    $mysql_success = false;
    $curl_success = false;
    $gd_success = false;
    $timezone_success = false;
    $allow_url_fopen = false;
    $php_intl = false;

    $isEnabled = true;

    $php_version_required = "7.1.0";
    $current_php_version = PHP_VERSION;

//check required php version
    if (version_compare($current_php_version, $php_version_required) >= 0) {
        $php_version_success = true;
    }

//check mySql 
    if (function_exists("mysqli_connect")) {
        $mysql_success = true;
    }

//check curl 
    if (function_exists("curl_version")) {
        $curl_success = true;
    }

    if (in_array('intl', $get_loaded_extensions)) {
        $php_intl = true;
    }

    //check allow url fopen
    if (ini_get('allow_url_fopen')) {
        $allow_url_fopen = TRUE;
    } else {
        $allow_url_fopen = FALSE;
    }

    //check if all requirement is success
    if ($php_version_success && $mysql_success && $curl_success && $isEnabled && $php_intl) {
        $all_requirement_success = true;
    } else {
        $all_requirement_success = false;
    }

    $writeable_directories = array(
        'routes' => '/index.php',
        'env' => '/environment.php',
        'cache' => '/app/cache',
        'upload' => '/assets/uploads/sitesetting',
        'category' => '/assets/uploads/category',
        'event' => '/assets/uploads/event',
        'profiles' => '/assets/uploads/profiles',
        'slider' => '/assets/uploads/slider',
    );

    foreach ($writeable_directories as $value) {
        if (!is_writeable(".." . $value)) {
            $all_requirement_success = false;
        }
    }

    $dashboard_url = $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
    $dashboard_url = preg_replace('/install.*/', '', $dashboard_url); //remove everything after index.php
    if (!empty($_SERVER['HTTPS'])) {
        $dashboard_url = 'https://' . $dashboard_url;
    } else {
        $dashboard_url = 'http://' . $dashboard_url;
    }

    include "view/index.php";
} else {
    $new_domain = str_replace('install/', '', $domain);
    header("Location: $new_domain");
    exit(0);
}
?>