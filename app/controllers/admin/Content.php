<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Content extends CI_Controller {

    public function __construct() {
        parent::__construct();
        run_default_query();
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $this->load->model('model_admin');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        set_time_zone();
    }

    //show dashboard
    public function index() {
        $this->authenticate->check_admin();
        redirect('admin/dashboard');
    }

    //logout
    public function logout() {
        $this->session->unset_userdata('ADMIN_ID');
        $this->session->unset_userdata('DefaultPassword');
        $this->session->set_flashdata('msg', translate('logout_success'));
        $this->session->set_flashdata('msg_class ', 'success');
        redirect('admin/login');
    }

    //show login form
    public function login() {
        if (!$this->session->userdata('ADMIN_ID')) {
            $data['title'] = translate('login');
            $company_data = $this->model_admin->getData("app_site_setting", "*");
            $data['company_data'] = $company_data[0];
            $this->load->view('admin/login', $data);
        } else {
            redirect('admin/dashboard');
        }
    }

    //check login email or password
    public function login_action() {
        $username = $this->db->escape($this->input->post("username", true));
        $password = $this->input->post("password", true);
        $this->form_validation->set_rules('username', '', 'trim|required');
        $this->form_validation->set_rules('password', '', 'trim|required');
        $this->form_validation->set_message('required', translate('required_message'));
        if ($this->form_validation->run() == false) {
            $this->login();
        } else {
            $users = $this->model_admin->authenticate($username, $password);
            //Check for login
            if ($users['errorCode'] == 0) {
                $this->session->set_flashdata('msg', $users['errorMessage']);
                $this->session->set_flashdata('msg_class', 'failure');
                redirect("admin/login");
            } else {
                 //last_login Update
                 $ADMIN_ID=$this->session->userdata('ADMIN_ID');
                 $up_data['last_login']=date('Y-m-d H:i:s');
                 $this->model_admin->update("app_admin",$up_data, "id=".$ADMIN_ID);

                $this->session->set_flashdata('msg', translate('login_success'));
                $this->session->set_flashdata('msg_class', 'success');
                redirect("admin/dashboard");
            }
        }
    }

    //show forgot password form
    public function forgot_password() {
        if (!$this->session->userdata('ADMIN_ID')) {
            $company_data = $this->model_admin->getData("app_site_setting", "*");
            $data['title'] = translate('forgot_password');
            $data['company_data'] = $company_data[0];
            $this->load->view('admin/forgot_password', $data);
        } else {
            redirect(base_url("admin/dashboard"));
        }
    }

    //check email and forgot password mail send
    public function forgot_password_action() {
        $postvar = $this->input->post("email", true);
        $admin_data = $this->model_admin->check_username($postvar);
        $this->load->helper('string');
        $html = '';
        if ($admin_data['errorCode'] == 1) {
            $code = random_string('numeric', 6);
            $updata = array(
                'reset_password_check' => $code,
                'reset_password_requested_on' => date("Y-m-d H:i:s")
            );
            $define_param['to_name'] = ($admin_data['first_name']) . " " . ($admin_data['last_name']);
            $define_param['to_email'] = $admin_data['email'];
            $userid = $admin_data['id'];
            $hidenuseremail = $admin_data['email'];
            $hidenusername = ($admin_data['first_name']) . " " . ($admin_data['last_name']);
            //Encryprt data
            $encid = $this->general->encryptData($userid);
            $encemail = $this->general->encryptData($hidenuseremail);
            $url = base_url("admin/reset-password/" . $encid . "/" . $encemail);
            $update['reset_password_check'] = 0;
            $update['reset_password_requested_on'] = date("Y-m-d H:i:S");
            $this->model_admin->update("app_admin", $update, "id='" . $userid . "'");

            //Send email
            $subject = translate('reset_password');
            $define_param['to_name'] = $hidenusername;
            $define_param['to_email'] = $hidenuseremail;

            $parameter['URL'] = $url;
            $html = $this->load->view("email_template/forgot_password", $parameter, true);
            $this->sendmail->send($define_param, $subject, $html);

            $this->session->set_flashdata('msg', $admin_data['errorMessage']);
            $this->session->set_flashdata('msg_class', 'success');
            redirect('admin/login');
        } else {
            $this->session->set_flashdata('msg', $admin_data['errorMessage']);
            $this->session->set_flashdata('msg_class', 'failure');
            $this->forgot_password();
        }
    }

    //show reset password 
    public function reset_password_admin($id_ency = '', $email_ency = '') {

        $id_ency = $this->uri->segment(3);
        $email_ency = $this->uri->segment(4);

        $id = (int) $this->general->decryptData($id_ency);
        $email = $this->general->decryptData($email_ency);
        $admin_data = $this->model_admin->getData("app_admin", "*", "id='" . $id . "' AND email='" . $email . "'");

        if (isset($admin_data) && count($admin_data) > 0 && !empty($admin_data)) {

            $h_id = $admin_data[0]['id'];
            $add_min = date("Y-m-d H:i:s", strtotime($admin_data[0]['reset_password_requested_on'] . "+1 hour"));
            if ($add_min > date("Y-m-d H:i:s")) {

                if ($admin_data[0]['reset_password_check'] != 1) {
                    $data['title'] = translate('reset_password');
                    $data['id'] = $id;
                    $this->load->view('admin/profile/reset_password', $data);
                } else {
                    $this->session->set_flashdata('failure', translate('reset_failure'));
                    redirect('admin/forgot_password');
                }
            } else {
                $this->session->set_flashdata('failure', translate('reset_failure'));
                redirect('admin/forgot-password');
            }
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            show_404();
        }
    }

    //edit password
    public function reset_password_admin_action() {
        $password = $this->input->post('password');
        $id = $this->input->post('id');

        $this->form_validation->set_rules('password', '', 'trim|required');
        $this->form_validation->set_rules('cpassword', '', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->reset_password_admin();
        } else {
            $update['reset_password_check'] = 1;
            $update['reset_password_requested_on'] = "0000-00-00 00:00:00";
            $update['password'] = md5($password);
            $this->model_admin->update("app_admin", $update, "id='" . $id . "'");
            $this->session->set_flashdata('msg', translate('reset_success'));
            $this->session->set_flashdata('msg_class', 'success');
            redirect('admin/login');
        }
    }

    //show update password form
    public function update_password() {
        $this->authenticate->check_admin();
        $data['title'] = translate('update_password');
        $this->load->view('admin/profile/update_password', $data);
    }

    //edit password
    public function update_password_action() {
        $admin_id = (int) $this->session->userdata('ADMIN_ID');
        $this->authenticate->check_admin();
        $this->form_validation->set_rules('old_password', '', 'trim|required');
        $this->form_validation->set_rules('password', '', 'trim|required');
        $this->form_validation->set_rules('confirm_password', '', 'trim|required');
        $this->form_validation->set_message('required', translate('required_message'));
        if ($this->form_validation->run() == false) {
            $this->update_password();
        } else {
            $old_password = $this->input->post('old_password', true);
            $new_password = $this->input->post('password', true);
            $admin_data = $this->model_admin->getData("app_admin", "*", "id='" . $admin_id . "'");
            if (isset($admin_data) && count($admin_data) > 0 && !empty($admin_data)) {
                $admin_password = $admin_data[0]['password'];
                if (isset($old_password) && $admin_password == md5($old_password)) {
                    $update['default_password_changed'] = 1;
                    $update['password'] = md5($new_password);
                    $result = $this->model_admin->update("app_admin", $update, "id='" . $admin_id . "'");
                    $this->session->set_userdata("DefaultPassword", 1);
                    $this->session->set_flashdata('msg', translate('reset_success'));
                    $this->session->set_flashdata('msg_class', 'success');
                    redirect('admin/update-password');
                } else {
                    $this->session->set_flashdata('msg', translate('current_password_failure'));
                    $this->session->set_flashdata('msg_class', 'failure');
                    redirect('admin/update-password');
                }
            } else {
                $this->session->set_flashdata('msg', translate('invalid_request'));
                $this->session->set_flashdata('msg_class', 'failure');
                show_404();
            }
        }
    }

    //show edit profile form
    public function profile() {
        $this->authenticate->check_admin();
        $admin_id = (int) $this->session->userdata('ADMIN_ID');
        if (isset($admin_id) && $admin_id > 0) {
            $admin_data = $this->model_admin->getData("app_admin", "*", "id=" . $admin_id);
            if (isset($admin_data) && count($admin_data) > 0 && !empty($admin_data)) {
                $data['title'] = translate('profile');
                $data['admin_data'] = $admin_data[0];
                $this->load->view('admin/profile/profile', $data);
            } else {
                $this->session->set_flashdata('msg', translate('invalid_request'));
                $this->session->set_flashdata('msg_class', 'failure');
                show_404();
            }
        } else {
            $this->session->set_flashdata('msg', translate('invalid_request'));
            $this->session->set_flashdata('msg_class', 'failure');
            show_404();
        }
    }

    //edit profile
    public function profile_save() {
        $this->authenticate->check_admin();
        $admin_id = (int) $this->session->userdata('ADMIN_ID');
        $this->form_validation->set_rules('first_name', '', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('last_name', '', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('email', '', 'trim|required|is_unique[app_admin.email.id.' . $admin_id . ']');
        $this->form_validation->set_rules('address', '', 'trim|required');
        $this->form_validation->set_rules('company_name', '', 'trim|required');
        $this->form_validation->set_rules('phone', '', 'required|min_length[10]|is_unique[app_admin.phone.id.' . $admin_id . ']');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class = "error"> ', '</div>');
        if ($this->form_validation->run() == false) {
            $this->profile();
        } else {
            $update = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company_name' => $this->input->post('company_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'fb_link' => $this->input->post('fb_link'),
                'twitter_link' => $this->input->post('twitter_link'),
                'google_link' => $this->input->post('google_link'),
                'instagram_link' => $this->input->post('instagram_link'),
                'profile_text' => $this->input->post('profile_text'),
                'updated_on' => date("Y-m-d H:i:s")
            );
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['name'] != '') {

                $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/profiles';
                $tmp_name = $_FILES["profile_image"]["tmp_name"];
                $temp = explode(".", $_FILES["profile_image"]["name"]);
                $newfilename = (uniqid()) . '.' . end($temp);
                move_uploaded_file($tmp_name, "$uploadPath/$newfilename");
                $update['profile_image'] = $newfilename;
            }
            if (isset($_FILES['profile_cover_image']) && $_FILES['profile_cover_image']['name'] != '') {
                $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/profiles';
                $tmp_name = $_FILES["profile_cover_image"]["tmp_name"];
                $temp = explode(".", $_FILES["profile_cover_image"]["name"]);
                $newfilename = (uniqid()) . '.' . end($temp);
                move_uploaded_file($tmp_name, "$uploadPath/$newfilename");
                $update['profile_cover_image'] = $newfilename;
            }
            $this->model_admin->update("app_admin", $update, "id='" . $admin_id . "'");
            $this->session->set_flashdata('msg', translate('profile_success'));
            $this->session->set_flashdata('msg_class', "success");
            redirect('admin/profile');
        }
    }

}
