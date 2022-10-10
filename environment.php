<?php

//set the environment to production after installation
if (!defined('ENVIRONMENT'))
    define('ENVIRONMENT', 'pre_installation');

$domain = $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
$domain = preg_replace('/index.php.*/', '', $domain); //remove everything after index.php
if (!empty($_SERVER['HTTPS'])) {
    $domain = 'https://' . $domain;
} else {
    $domain = 'http://' . $domain;
}

//database content
$hostname = "enter_hostname";
$username = "enter_db_username";
$password = "enter_db_password";
$database = "enter_database_name";