<?php

ini_set('max_execution_time', 300); //300 seconds 
if (isset($_POST)) {
    try {

        $host = $_POST["host"];
        $dbuser = $_POST["dbuser"];
        $dbpassword = $_POST["dbpassword"];
        $dbname = $_POST["dbname"];

        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $email = $_POST["email"];
        $login_password = $_POST["password"] ? $_POST["password"] : "";

        //site setting
        $sitename = $_POST["sitename"];
        $siteemail = $_POST["siteemail"];

        //email setting
        $mail_type = $_POST["mail_type"];
        if (isset($mail_type) && $mail_type == 'S') {
            $email_from = $_POST["smtp_username"];
        } else {
            $email_from = $_POST["email_from"];
        }
        $smtp_host = isset($_POST["smtp_host"]) ? $_POST["smtp_host"] : 0;
        $smtp_username = isset($_POST["smtp_username"]) ? $_POST["smtp_username"] : "";
        $smtp_password = isset($_POST["smtp_password"]) ? $_POST["smtp_password"] : "";
        $smtp_port = (isset($_POST["smtp_port"]) && $_POST["smtp_port"] != "") ? $_POST["smtp_port"] : 0;
        $smtp_secure = isset($_POST["smtp_secure"]) ? $_POST["smtp_secure"] : "";

        // Create connection
        $conn = new mysqli($host, $dbuser, $dbpassword);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Create database
        $sql = "CREATE DATABASE $dbname";
        $conn->query($sql);


        //check required fields
        if (!($host && $dbuser && $dbname && $first_name && $last_name && $email && $login_password )) {
            echo json_encode(array("success" => false, "message" => "Please input all fields.", 'id' => 'db_step'));
            exit();
        }

        //check for valid email
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            echo json_encode(array("success" => false, "message" => "Please input a valid email.", 'id' => 'db_step'));
            exit();
        }
        if (filter_var($siteemail, FILTER_VALIDATE_EMAIL) === false) {
            echo json_encode(array("success" => false, "message" => "Please input a valid email.", 'id' => 'site_step'));
            exit();
        }

        //check for valid database connection
        $mysqli = @new mysqli($host, $dbuser, $dbpassword, $dbname);

        if (mysqli_connect_errno()) {
            echo json_encode(array("success" => false, "message" => $mysqli->connect_error, 'id' => 'db_step'));
            exit();
        }

        //all input seems to be ok. check required fiels
        if (!is_file('database.sql')) {
            echo json_encode(array("success" => false, "message" => "The database.sql file could not found in install folder!", 'id' => 'db_step'));
            exit();
        }

        /*
         * check the db config file
         * if db already configured, we'll assume that the installation has completed
         */

        $conn->close();
        if (isset($_FILES['logo']) && $_FILES['logo']['name'] != '') {
            $uploadPath = '../assets/uploads/sitesetting';
            $tmp_name = $_FILES["logo"]["tmp_name"];
            $temp = explode(".", $_FILES["logo"]["name"]);
            $newfilename = (uniqid()) . '.' . end($temp);
            move_uploaded_file($tmp_name, "$uploadPath/$newfilename");
        }

        $environment_file_path = "../environment.php";
        $db_file = file_get_contents($environment_file_path);
        $is_installed = strpos($db_file, "enter_hostname");

        if (!$is_installed) {
            echo json_encode(array("success" => false, "message" => "Seems this app is already installed! You can't reinstall it again."));
            exit();
        }

        /*
         * check the constant config file
         * replace company email and admin email
         */
        $constant_file_path = "../app/config/constants.php";

        //start installation
        $sql = file_get_contents("database.sql");


        //set admin information to database
        $now = date("Y-m-d H:i:s");
        $sql = str_replace('admin_first_name', $first_name, $sql);
        $sql = str_replace('admin_last_name', $last_name, $sql);
        $sql = str_replace('admin_email', $email, $sql);
        $sql = str_replace('admin_password', md5($login_password), $sql);
        $sql = str_replace('admin_created_at', $now, $sql);
        $sql = str_replace('admin_company_name', $sitename, $sql);


        //set site setting information to database
        $sql = str_replace('site_setting_company_email', $siteemail, $sql);
        $sql = str_replace('site_setting_company_name', $sitename, $sql);
        $sql = str_replace('site_setting_company_logo', $newfilename, $sql);

        //set email setting information to database
        $sql = str_replace('email_smtp_host', $smtp_host, $sql);
        $sql = str_replace('email_smtp_username', $smtp_username, $sql);
        $sql = str_replace('email_smtp_password', $smtp_password, $sql);
        $sql = str_replace('email_smtp_port', $smtp_port, $sql);
        $sql = str_replace('email_smtp_secure', $smtp_secure, $sql);
        $sql = str_replace('email_mail_type', $mail_type, $sql);
        $sql = str_replace('email_email_from', $email_from, $sql);

        //create tables in datbase 

        $mysqli->multi_query($sql);
        do {
            
        } while (mysqli_more_results($mysqli) && mysqli_next_result($mysqli));
        $mysqli->close();

        // set the database config file
        $db_file = str_replace('enter_hostname', $host, $db_file);
        $db_file = str_replace('enter_db_username', $dbuser, $db_file);
        $db_file = str_replace('enter_db_password', $dbpassword, $db_file);
        $db_file = str_replace('enter_database_name', $dbname, $db_file);

        file_put_contents($environment_file_path, $db_file);

        // set the environment = production
        $index_file_path = "../environment.php";
        $index_file = file_get_contents($index_file_path);
        $index_file = preg_replace('/pre_installation/', 'production', $index_file, 1); //replace the first occurence of 'pre_installation'
        file_put_contents($index_file_path, $index_file);
        echo json_encode(array("success" => true, "message" => "Installation done."));
        exit(0);
    }
//catch exception
    catch (Exception $e) {
        echo json_encode(array("success" => FALSE, "message" => $e->getMessage()));
        exit(0);
    }
}


