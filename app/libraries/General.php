<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

Class General {

    protected $CI;
    public $orderBook;

    function __construct() {
        $this->CI = & get_instance();
        $this->orderBook = array();
    }

    public static function encrypt_decrypt($action, $string) {
        $output = false;

        $key = '@2g';

        // initialization vector
        $iv = md5(md5($key));

        if ($action == 'encrypt') {
            $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
            $output = str_replace('\0', '', addslashes(rtrim($output, "")));
        }

        return trim($output, "=");
    }

    function encrypt($sData, $sKey = '@2g') {
        $sResult = '';
        for ($i = 0; $i < strlen($sData); $i++) {
            $sChar = substr($sData, $i, 1);
            $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
            $sChar = chr(ord($sChar) + ord($sKeyChar));
            $sResult .= $sChar;
        }
        return $this->encode_base64($sResult);
    }

    function decrypt($sData, $sKey = '@2g') {
        $sResult = '';
        $sData = $this->decode_base64($sData);
        for ($i = 0; $i < strlen($sData); $i++) {
            $sChar = substr($sData, $i, 1);
            $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
            $sChar = chr(ord($sChar) - ord($sKeyChar));
            $sResult .= $sChar;
        }
        return $sResult;
    }

    function encode_base64($sData) {
        $sBase64 = trim(base64_encode($sData), '=');
        return strtr($sBase64, '+/', '-_');
    }

    function decode_base64($sData) {
        $sBase64 = strtr($sData, '-_', '+/');
        return base64_decode($sBase64);
    }

    function sendNotification($id, $msg) {

        $this->CI->db->select('vDeviceToken');

        $this->CI->db->from('user_master');
        $this->CI->db->where('iUserId', $id);

        $user_data = $this->CI->db->get()->result_array();
        // pr($user_data);exit;    
        $device_id = $user_data[0]['vDeviceToken'];
        $notification_array['aps']['alert'] = $msg;
        $this->pushNotification($device_id, $notification_array);
    }

    function pushNotification($device_id, $notification_array = array()) {
        if (!empty($device_id)) {
            if (strlen($device_id) > 70) {
                $messageText = $notification_array['aps']['alert'];
                $res = $this->android_notification($device_id, $messageText, $notification_array);
            } else {
                $this->iosnotification($device_id, $notification_array);
            }
        }
    }

    function iosnotification($device_id, $notification_array = array()) {
        $site_path = $this->CI->config->item('site_path');

        if (!empty($device_id)) {

            // push notification start .....

            $deviceToken = $device_id;
            //$message = "Push Notification Done";
            $badge = 0;
            $sound = 'received5.caf';
            $body = array();
            //$body['aps'] = array('alert' => str_replace("\n" , " " , strip_tags($message)));                    
            $body = array_merge($body, $notification_array);

            if ($badge)
                $body['aps']['badge'] = $badge;
            if ($sound)
                $body['aps']['sound'] = $sound;

//                    $body['aps']['alert'] = $body['aps']['alert_subject'];
            //$body['aps']['otherparam'] = "review~480";

            $ctx = stream_context_create();
            #echo $site_path.'apns-dev.pem';exit;
            stream_context_set_option($ctx, 'ssl', 'local_cert', $site_path . 'apns-dev.pem');

            $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
            if ($fp) {
                $payload = json_encode($body);
                $msg = chr(0) . pack("n", 32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n", strlen($payload)) . $payload;

                #print $deviceToken." sending message :" . $payload . "\n";exit;
                fwrite($fp, $msg);
                fclose($fp);

                $f = fopen($site_path . 'public/upload/notification.html', 'a+');
                fwrite($f, '<br/>' . date('Y-m-d H:i:s') . '<br/>');
                fwrite($f, print_r($device_id, true) . '<br/>');
                fwrite($f, print_r($body, true) . '<br/>');
                fwrite($f, print_r($fp, true));
                fwrite($f, '<br/>');
                fclose($f);
            }

            //echo 'Response:-';print_r($fp); //exit;
            // push notification end .....
        }
    }

    function android_notification($device_id = '', $message = 'hi', $extra = array()) {
        $result = '';
        if ($device_id != '') {
            $apiKey = $this->CI->config->item('ANDROID_NOTIFICATION_KEY');

            // Replace with real client registration IDs
            $registrationIDs = array($device_id);

            // Set POST variables
            $url = 'https://android.googleapis.com/gcm/send';

            $data1 = array("message" => $message);
            $data = array_merge($data1, $extra);

            $fields = array(
                'registration_ids' => $registrationIDs,
                'data' => $data,
            );

            $headers = array(
                'Authorization: key=' . $apiKey,
                'Content-Type: application/json'
            );

            // Open connection
            $ch = curl_init();

            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            //     curl_setopt($ch, CURLOPT_POST, true);
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            // Execute post
            $result = curl_exec($ch);

            //pr($result);exit;
            $f = fopen($this->CI->config->item('site_path') . 'public/upload/android.html', 'a+');
            fwrite($f, '<br/>' . date('Y-m-d H:i:s') . '<br/>');
            fwrite($f, print_r($registrationIDs, true) . '<br/>');
            fwrite($f, print_r($data, true));
            fwrite($f, print_r($result, true));
            fwrite($f, '<br/>');
            fclose($f);

            // Close connection
            curl_close($ch);
        }

        return $result;
    }

    function createfolder($path) {
        $site_path = $this->CI->config->item('upload_path');
        $res = '';
        $pathfolder = @explode("/", str_replace($site_path, "", $path));
        $realpath = "";
        for ($p = 0; $p < count($pathfolder); $p++) {
            if ($pathfolder[$p] != '') {
                $realpath = $realpath . $pathfolder[$p] . "/";
                $makefolder = $site_path . "/" . $realpath;
                if (!is_dir($makefolder)) {
//                    $makefolder = @mkdir($makefolder, 0777);
//                    @chmod($makefolder, 0777);

                    $oldUmask = umask(0);
                    $res = @mkdir($makefolder, 0777);
                    @chmod($makefolder, 0777);
                    umask($oldUmask);
                }
            }
        }

        return $res;
    }

    function encryptData($input) {
        $output = trim(base64_encode(base64_encode($input)), '==');
        $output = $this->encrypt($input);
        //$output = $this->encrypt_decrypt('encrypt', $input);
        return $output;
    }

    function decryptData($input) {
        $output = base64_decode(base64_decode($input));
        $output = $this->decrypt($input);
        //$output = $this->encrypt_decrypt('decrypt', $input);
        return $output;
    }

    function checkSession($d = '') {
        if (!$this->CI->session->userdata('expire') || $this->CI->session->userdata('expire') > time()) {
            $this->CI->session->set_userdata('start', time());
            $expire = $this->CI->session->userdata('start') + $this->CI->config->item('SESSION_TIMEOUT') * 60;
            $this->CI->session->set_userdata('expire', $expire);
            if ($d != '') {
                return 'true';
            }
        } else if ($this->CI->session->userdata('expire') > 0 && $this->CI->session->userdata('expire') < time()) {
            $this->CI->session->unset_userdata('expire', '');
            $this->CI->session->set_flashdata('failure', 'Session time out..');
            if ($d != '') {
                if ($this->CI->config->item('is_admin') == 1) {
                    return 'admin_timeout';
                } else {
                    return 'timeout';
                }
            } else {
                if ($this->CI->config->item('is_admin') == 1) {
                    redirect('signout');
                } else {
                    redirect('logout');
                }
            }
        }
    }

    function checkSessionfront() {
        if (!$this->CI->session->userdata('expire') || $this->CI->session->userdata('expire') > time()) {
            $this->CI->session->set_userdata('start', time());
            $expire = $this->CI->session->userdata('start') + $this->CI->config->item('SESSION_TIMEOUT') * 60;
            $this->CI->session->set_userdata('expire', $expire);
        } else if ($this->CI->session->userdata('expire') < time()) {
            $this->CI->session->unset_userdata('expire', '');
            $this->CI->session->set_flashdata('failure', 'Session time out..');
            redirect('signout');
        }
    }

    function checkVirusInFile($file) {
        $return = array('success' => 'false', 'err' => 'We doubt your file is affected by Virus or do not proper extension');
        $allowed_types = array(
            /* images extensions */
            'jpeg', 'bmp', 'png', 'gif', 'tiff', 'jpg',
            /* audio extensions */
            'mp3', 'wav', 'midi', 'aac', 'ogg', 'wma', 'm4a', 'mid', 'orb', 'aif',
            /* movie extensions */
            'mov', 'flv', 'mpeg', 'mpg', 'mp4', 'avi', 'wmv', 'qt',
            /* document extensions */
            'txt', 'pdf', 'ppt', 'pps', 'xls', 'doc', 'xlsx', 'pptx', 'ppsx', 'docx', 'csv'
        );
        $mime_type_black_list = array(
            # HTML may contain cookie-stealing JavaScript and web bugs
            'text/html', 'text/javascript', 'text/x-javascript', 'application/x-shellscript',
            # PHP scripts may execute arbitrary code on the server
            'application/x-php', 'text/x-php', 'text/x-php',
            # Other types that may be interpreted by some servers
            'text/x-python', 'text/x-perl', 'text/x-bash', 'text/x-sh', 'text/x-csh',
            'text/x-c++', 'text/x-c',
                # Windows metafile, client-side vulnerability on some systems
                # 'application/x-msmetafile',
                # A ZIP file may be a valid Java archive containing an applet which exploits the
                # same-origin policy to steal cookies      
                # 'application/zip',
        );
        $file_name = $file['name'];
        pathinfo($file_name, PATHINFO_EXTENSION);
        $tmp_file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!strlen($tmp_file_extension) || (!$allow_all_types &&
                !in_array($tmp_file_extension, $allowed_types))) {
            return $return;
        }
        $finfo = new finfo(FILEINFO_MIME, MIME_MAGIC_PATH);

        if ($finfo) {
            $mime = $finfo->file($file_name_tmp);
        } else {
            $mime = $file_type;
        }

        $mime = explode(" ", $mime);
        $mime = $mime[0];

        if (substr($mime, -1, 1) == ";") {
            $mime = trim(substr($mime, 0, -1));
        }
        $rs = in_array($mime, $mime_type_black_list) == false;
        if ($rs == 1) {
            $return['success'] = 'true';
            $return['err'] = '';
        }
        //echo "come<pre>"; print_r($return);die;
        //return (in_array($mime, $mime_type_black_list) == false);
        return $return;
    }

    function checkVirus($ImageFile, $redircturl = '', $ajax = '') {
        if ($redircturl == '') {
            $redircturl = basename($_SERVER['REQUEST_URI']);
        }
        if ($ImageFile != '') {
            $vrsRes = $this->checkVirusInFile($ImageFile);
            if ($vrsRes['success'] == 'true') {
                return true;
            } else {
                $alert = array('Failure' => array('message' => $vrsRes['err'], 'class' => 'alert-danger'));
                $this->CI->session->set_userdata("errorsocialAlert", $alert);
                echo $this->CI->config->item('site_url') . $redircturl;
                exit;
            }
        } else {
            return true;
        }
    }

    function getEnumValues($table, $field) {
        $type = $this->CI->db->query("SHOW COLUMNS FROM {$table} WHERE Field = '{$field
                        }'")->row(0)->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }

    public function add3dots($string, $repl, $limit) {
        if (strlen($string) > $limit) {
            return substr($string, 0, $limit) . $repl;
        } else {
            return $string;
        }
    }

}
