<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include APPPATH . 'third_party/mail/class.phpmailer.php';
include APPPATH . "third_party/mail/class.smtp.php";

class Sendmail {

    public function send($toparam = array(), $subject, $html_body, $attachment = NULL) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $CI = & get_instance();
        $CI->db->select('*', FALSE);
        $CI->db->from('app_email_setting');
        $email_datat = $CI->db->get()->row_array();

        $smtp_host = isset($email_datat['smtp_host']) ? $email_datat['smtp_host'] : "smtp.gmail.com";
        $smtp_username = isset($email_datat['smtp_username']) ? $email_datat['smtp_username'] : "test@xyz.com";
        $smtp_password = isset($email_datat['smtp_password']) ? $email_datat['smtp_password'] : "password";
        $smtp_port = isset($email_datat['smtp_port']) ? $email_datat['smtp_port'] : 587;
        $smtp_secure = isset($email_datat['smtp_secure']) ? $email_datat['smtp_secure'] : "tls";
        $email_from = isset($email_datat['email_from']) ? $email_datat['email_from'] : '';

        $CI->load->library('email');

        if (isset($email_datat['mail_type']) && $email_datat['mail_type'] == 'S'):
            $config['protocol'] = "smtp";
            $config['smtp_host'] = $smtp_host;
            $config['smtp_port'] = $smtp_port;
            $config['smtp_crypto'] = $smtp_secure;
            $config['smtp_user'] = $smtp_username;
            $config['smtp_pass'] = $smtp_password;
        else:
            $config['mailpath'] = '/usr/sbin/sendmail';
        endif;

        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;
        $config['validate'] = FALSE;
        $config['priority'] = 3;
        $config['crlf'] = "\r\n";

        if (isset($toparam) && count($toparam) > 0) {
            $to_email = $toparam['to_email'];
            $to_name = $toparam['to_name'];
        }

        $CI->email->initialize($config);
        $CI->email->from($email_from, get_CompanyName());
        $CI->email->to($to_email);
        $CI->email->subject($subject);
        $CI->email->message($html_body);


        $app_email_log['email'] = $to_email;
        $app_email_log['name'] = $to_name;
        $app_email_log['subject'] = $subject;
        $app_email_log['content'] = $html_body;
        $app_email_log['created_date'] = date("Y-m-d H:i:s");

        if ($CI->email->send()) {
            $app_email_log['details'] = 'Sent';
            $app_email_log['status'] = 'Sent';
            $CI->db->insert('app_email_log', $app_email_log);
            return true;
        } else {
            $app_email_log['details'] = json_encode($CI->email->print_debugger());
            $app_email_log['status'] = 'Not Sent';
            $CI->db->insert('app_email_log', $app_email_log);
            return false;
        }
    }

    public function send_php_mailer_lib($toparam = array(), $subject, $html_body, $attachment = NULL) {

        $CI = & get_instance();
        $CI->db->select('*', FALSE);
        $CI->db->from('app_email_setting');
        $email_datat = $CI->db->get()->row_array();

        $CI->db->select('*', FALSE);
        $CI->db->from('app_site_setting');
        $sitesetting_datat = $CI->db->get()->row_array();

        $smtp_host = isset($email_datat['smtp_host']) ? $email_datat['smtp_host'] : "smtp.gmail.com";
        $smtp_username = isset($email_datat['smtp_username']) ? $email_datat['smtp_username'] : "test@xyz.com";
        $smtp_password = isset($email_datat['smtp_password']) ? $email_datat['smtp_password'] : "password";
        $smtp_port = isset($email_datat['smtp_port']) ? $email_datat['smtp_port'] : 587;
        $smtp_secure = isset($email_datat['smtp_secure']) ? $email_datat['smtp_secure'] : "tsl";

        $CI = & get_instance();
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_username;
        $mail->Password = $smtp_password;
        $mail->SMTPSecure = $smtp_secure;
        $mail->Port = $smtp_port;
        $mail->From = $smtp_username;
        $mail->FromName = get_CompanyName();

        if (isset($toparam) && count($toparam) > 0) {
            $to_email = $toparam['to_email'];
            $to_name = $toparam['to_name'];
        }

        if (file_exists(dirname(BASEPATH) . "/" . uploads_path . '/sitesetting/' . $sitesetting_datat['company_logo']) && $sitesetting_datat['company_logo'] != '') {
            $logo_image = base_url() . uploads_path . '/sitesetting/' . $sitesetting_datat['company_logo'];
        } else {
            $logo_image = base_url() . img_path . "/default_logo.png";

            $mail->addAddress($to_email, $to_name);
            if ($attachment != NULL) {
                $mail->addAttachment($attachment);
            }
            $mail->WordWrap = 50;
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $html;

            if (!$mail->send()) {
                return false;
            } else {
                return true;
            }
        }
    }

}
