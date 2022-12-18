<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ServiceCategory extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_event');
        set_time_zone();

        if (get_site_setting('enable_service') == 'N'):
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/dashboard', 'redirect');
        endif;
    }

    //show service category page
    public function index() {
        if (isset($this->login_type) && $this->login_type == 'V') {
            $app_vendor_setting_data = app_vendor_setting();
            if (isset($app_vendor_setting_data['allow_service_category']) && $app_vendor_setting_data['allow_service_category'] == 'N'):
                redirect('vendor/dashboard');
            endif;
        }
        $where = "type ='S'";
        $event = $this->model_event->getData('app_event_category', '*', $where);
        $data['category_data'] = $event;
        $data['title'] = translate('manage_service_category');
        $this->load->view('admin/service/service_category/index', $data);
    }

    //show add service category form
    public function add_category() {
        if (isset($this->login_type) && $this->login_type == 'V') {
            $app_vendor_setting_data = app_vendor_setting();
            if (isset($app_vendor_setting_data['allow_service_category']) && $app_vendor_setting_data['allow_service_category'] == 'N'):
                redirect('vendor/dashboard');
            endif;
        }
        $data['title'] = translate('add_service_category');
        $this->load->view('admin/service/service_category/add_update', $data);
    }

    //show edit service category form
    public function update_category($id) {
        if (isset($this->login_type) && $this->login_type == 'V') {
            $app_vendor_setting_data = app_vendor_setting();
            if (isset($app_vendor_setting_data['allow_service_category']) && $app_vendor_setting_data['allow_service_category'] == 'N'):
                redirect('vendor/dashboard');
            endif;
        }
        $cond = 'id=' . $id;
        if ($this->session->userdata('Type_Admin') != "A") {
            $cond .= ' AND created_by=' . $this->login_id;
        }
        $category = $this->model_event->getData("app_event_category", "*", $cond);
        if (isset($category) && count($category) > 0) {
            $data['category_data'] = $category[0];
            $data['title'] = translate('update') . " " . translate('service');
            $this->load->view('admin/service/service_category/add_update', $data);
        } else {
            show_404();
        }
    }

    public function validate_image() {
        $allowedExts = array("image/gif", "image/jpeg", "image/jpg", "image/png");
        if (isset($_FILES["event_category_image"]["type"]) && $_FILES["event_category_image"]["type"] != "") {
            if (in_array($_FILES["event_category_image"]["type"], $allowedExts)) {
                return true;
            } else {
                $this->form_validation->set_message('validate_image', 'Please select valid image.');
                return false;
            }
        } else {
            $this->form_validation->set_message('validate_image', 'The Category Image field is required.');
            return false;
        }
    }

    public function validate_image_edit() {
        $allowedExts = array("image/gif", "image/jpeg", "image/jpg", "image/png");
        if (empty($_FILES["event_category_image"]["type"])) {
            return true;
        } else {
            if (in_array($_FILES["event_category_image"]["type"], $allowedExts)) {
                return true;
            } else {
                $this->form_validation->set_message('validate_image_edit', 'Please select valid image.');
                return false;
            }
        }
    }

    //add/edit an service category
    public function save_category() {
        if (isset($this->login_type) && $this->login_type == 'V') {
            $app_vendor_setting_data = app_vendor_setting();
            if (isset($app_vendor_setting_data['allow_service_category']) && $app_vendor_setting_data['allow_service_category'] == 'N'):
                redirect('vendor/dashboard');
            endif;
        }
        $hidden_main_image = $this->input->post('hidden_category_image', true);
        $id = (int) $this->input->post('id', true);
        $this->form_validation->set_rules('title', 'title', 'required|callback_check_service_category_title');
        if ($id > 0) {
            $this->form_validation->set_rules('event_category_image', translate('event_category_image'), 'trim|callback_validate_image_edit');
        } else {
            $this->form_validation->set_rules('event_category_image', translate('event_category_image'), 'trim|callback_validate_image');
        }
        $this->form_validation->set_rules('status', 'Status', 'required');


        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == false) {
            if ($id > 0) {
                $this->update_category($id);
            } else {
                $this->add_category();
            }
        } else {
            $data['title'] = $this->input->post('title', true);
            $data['category_slug'] = convert_lang_string(trim($this->input->post('title', true)));
            $data['status'] = $this->input->post('status', true);
            $data['type'] = "S";
            $data['created_by'] = $this->login_id;

            $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/category';

            if (isset($_FILES['event_category_image']["name"]) && $_FILES['event_category_image']["name"] != "") {
                $tmp_name = $_FILES["event_category_image"]["tmp_name"];
                $temp = explode(".", $_FILES["event_category_image"]["name"]);
                $newfilename = (uniqid()) . '.' . end($temp);
                move_uploaded_file($tmp_name, "$uploadPath/$newfilename");
                $data['event_category_image'] = $newfilename;

                $config['image_library'] = 'gd2';
                $config['source_image'] = $uploadPath . "/" . $newfilename;
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = FALSE;
                $config['width'] = 40;
                $config['height'] = 40;

                $this->load->library('image_lib', $config);

                $this->image_lib->resize();

                if (isset($hidden_main_image) && $hidden_main_image != "" && file_exists(FCPATH . uploads_path . '/category/' . $hidden_main_image)) {
                    @unlink($uploadPath . "/" . $hidden_main_image);
                }
            }

            if ($id > 0) {
                $data['updated_on'] = date("Y-m-d H:i:s");
                $this->model_event->update('app_event_category', $data, "id=$id");
                $this->session->set_flashdata('msg', translate('service_category_update'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $data['created_on'] = date("Y-m-d H:i:s");
                $id = $this->model_event->insert('app_event_category', $data);
                if ($id) {
                    $this->session->set_flashdata('msg', translate('service_category_insert'));
                    $this->session->set_flashdata('msg_class', 'success');
                } else {
                    $this->session->set_flashdata('msg', "Unable to create category.");
                    $this->session->set_flashdata('msg_class', 'failure');
                }
            }
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/service/category', 'redirect');
        }
    }

    //delete an service category
    public function delete_service_category($id) {
        $event_data = $this->model_event->getData('app_event', 'id', "category_id='$id'");
        if (isset($event_data) && count($event_data) > 0) {
            $this->session->set_flashdata('msg', translate('event_category_exist'));
            $this->session->set_flashdata('msg_class', 'failure');
            echo 'false';
            exit(0);
        } else {
            $this->model_event->delete('app_event_category', "id='$id' AND created_by='$this->login_id'");
            $this->session->set_flashdata('msg', translate('service_category_delete'));
            $this->session->set_flashdata('msg_class', 'success');
            echo 'true';
            exit;
        }
    }

//     check service category title
    public function check_service_category_title() {
        $id = (int) $this->input->post('id', true);
        $title = trim($this->input->post('title', TRUE));

        if (isset($id) && $id > 0) {
            $this->db->where('id!=',$id);
        }

        $this->db->where('title', $title);
        $this->db->where('type', 'S');
        $this->db->from('app_event_category');
        $check_title=$this->db->count_all_results();

        if (isset($check_title) && ($check_title) > 0) {
            $this->form_validation->set_message('check_service_category_title', 'Title already exist.');
            return false;
        } else {
            return true;
        }
    }

    //delete service seo image
    public function delete_event_seo_image() {
        $image = $this->input->post('i', TRUE);
        $event_id = $this->input->post('id', TRUE);
        if (file_exists((FCPATH) . "/" . $image)) {
            if (unlink((FCPATH) . "/" . $image)) {
                $data['seo_og_image'] = "";
                $id = $this->model_event->update('app_event', $data, "id=$event_id");
                echo 'success';
            } else {
                echo 'false';
            }
        }
        exit;
    }

    function check_upload_images($files_arr) {
        if (isset($files_arr) && !empty($files_arr)) {
            $total_files = count($files_arr);
            $empty_files_cnt = 0;
            foreach ($files_arr as $key => $val):
                if ($val == "") {
                    $empty_files_cnt++;
                }
            endforeach;
            if ($empty_files_cnt == $total_files) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /* Service Addons */

    public function addons($service_id) {
        $service_id = (int) $service_id;
        if ($service_id > 0) {
            if ($this->login_type == 'V') {
                $service_data = $this->model_event->getData('app_event', '*', "id=" . $service_id . " AND payment_type='P' AND created_by=" . $this->login_id);
            } else {
                $service_data = $this->model_event->getData('app_event', '*', "id=" . $service_id . " AND payment_type='P'");
            }
            if (count($service_data) > 0) {

                if ($this->login_type == 'V') {
                    $app_service_addons = $this->model_event->getData('app_service_addons', '*', "user_id=" . $this->login_id . " AND event_id=" . $service_id);
                } else {
                    $app_service_addons = $this->model_event->getData('app_service_addons', '*', "event_id=" . $service_id);
                }

                $data['app_service_addons'] = $app_service_addons;
                $data['service_id'] = $service_id;
                $data['service_data'] = $service_data[0];
                $data['title'] = translate('manage') . " " . translate('service') . " " . translate('add_ons');
                $this->load->view('admin/service/addons/index', $data);
            } else {
                if ($this->login_type == 'V') {
                    redirect('vendor/service');
                } else {
                    redirect('admin/service');
                }
            }
        } else {
            if ($this->login_type == 'V') {
                redirect('vendor/service');
            } else {
                redirect('admin/service');
            }
        }
    }

    public function add_service_addons($service_id) {
        if ($this->login_type == 'V'):
            $service = $this->model_event->getData("app_event", "*", "id=" . $service_id . " AND payment_type='P'  AND created_by=" . $this->login_id);
        else:
            $service = $this->model_event->getData("app_event", "*", "id=" . $service_id . " AND payment_type='P' ");
        endif;

        if (count($service) > 0) {
            $data['title'] = translate('add') . " " . translate('service') . " " . translate('add_ons');
            $data['service_id'] = $service_id;
            $this->load->view('admin/service/addons/add_update', $data);
        } else {
            if ($this->login_type == 'V') {
                redirect('vendor/service');
            } else {
                redirect('admin/service');
            }
        }
    }

    public function update_addons_service($service_id, $add_on_id) {

        if ($this->login_type == 'V'):
            $service = $this->model_event->getData("app_event", "*", "id=" . $service_id . " AND payment_type='P' AND created_by=" . $this->login_id);
        else:
            $service = $this->model_event->getData("app_event", "*", "id=" . $service_id . " AND payment_type='P' ");
        endif;

        if (count($service) > 0) {
            $app_service_addons = $this->model_event->getData("app_service_addons", "*", "add_on_id='$add_on_id'");
            if (count($app_service_addons) > 0) {
                $data['app_service_addons'] = $app_service_addons[0];
                $data['service_id'] = $service_id;
                $data['title'] = translate('update') . " " . translate('service') . " " . translate('add_ons');
                $this->load->view('admin/service/addons/add_update', $data);
            } else {
                if ($this->login_type == 'V') {
                    redirect('vendor/manage-service');
                } else {
                    redirect('admin/manage-service');
                }
            }
        } else {
            if ($this->login_type == 'V') {
                redirect('vendor/manage-service');
            } else {
                redirect('admin/manage-service');
            }
        }
    }

    public function save_service_addons() {

        $hidden_main_image = $this->input->post('hidden_add_on_image', true);
        $service_id = $this->input->post('service_id', true);
        if ($this->login_type == 'V'):
            $service = $this->model_event->getData("app_event", "*", "id=" . $service_id . " AND payment_type='P' AND created_by=" . $this->login_id);
        else:
            $service = $this->model_event->getData("app_event", "*", "id=" . $service_id . " AND payment_type='P' ");
        endif;

        if (count($service) > 0) {

            $id = (int) $this->input->post('id', true);
            $this->form_validation->set_rules('title', 'title', 'required|trim');
            $this->form_validation->set_rules('details', 'details', 'required|trim');

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            if ($this->form_validation->run() == false) {
                if ($id > 0) {
                    $this->update_addons_service($id);
                } else {
                    $this->add_service_addons();
                }
            } else {
                $data['title'] = $this->input->post('title', true);
                $data['price'] = $this->input->post('price', true);
                $data['details'] = $this->input->post('details', true);
                $data['event_id'] = $this->input->post('service_id', true);
                $data['user_id'] = $this->login_id;

                $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/event';

                if (isset($_FILES['event_add_on_image']["name"]) && $_FILES['event_add_on_image']["name"] != "") {
                    $tmp_name = $_FILES["event_add_on_image"]["tmp_name"];
                    $temp = explode(".", $_FILES["event_add_on_image"]["name"]);
                    $newfilename = (uniqid()) . '.' . end($temp);
                    move_uploaded_file($tmp_name, "$uploadPath/$newfilename");
                    $data['image'] = $newfilename;

                    if ($hidden_main_image != "" && $hidden_main_image != NULL) {
                        unlink($uploadPath . "/" . $hidden_main_image);
                    }
                }

                if ($id > 0) {
                    $data['updated_date'] = date("Y-m-d H:i:s");
                    $this->model_event->update('app_service_addons', $data, "add_on_id=$id");
                    $this->session->set_flashdata('msg', translate('service_add_ons_update'));
                    $this->session->set_flashdata('msg_class', 'success');
                } else {
                    $data['created_date'] = date("Y-m-d H:i:s");
                    $id = $this->model_event->insert('app_service_addons', $data);
                    $this->session->set_flashdata('msg', translate('service_add_ons_insert'));
                    $this->session->set_flashdata('msg_class', 'success');
                }
                $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
                redirect($folder_url . '/manage-service-addons/' . $service_id, 'redirect');
            }
        } else {
            if ($this->login_type == 'V') {
                redirect('vendor/manage-service');
            } else {
                redirect('admin/manage-service');
            }
        }
    }

    public function delete_service_addons($id) {
        $app_service_addons = $this->model_event->getData('app_service_addons', '*', "add_on_id=" . $id);
        if (count($app_service_addons) > 0) {
            $this->model_event->delete('app_service_addons', "add_on_id='$id' AND user_id='$this->login_id'");

            $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/event';

            if (isset($app_service_addons[0]['image']) && $app_service_addons[0]['image'] != "") {
                unlink($uploadPath . "/" . $app_service_addons[0]['image']);
            }

            $this->session->set_flashdata('msg', translate('service_add_ons_delete'));
            $this->session->set_flashdata('msg_class', 'success');
            echo 'true';
            exit;
        } else {
            $this->model_event->delete('app_event_category', "id='$id' AND user_id='$this->login_id'");
            $this->session->set_flashdata('msg', translate('service_add_ons_delete'));
            $this->session->set_flashdata('msg_class', 'success');
            echo 'true';
            exit;
        }
    }

    function view_booking_details($id) {
        $data['title'] = translate('view') . " " . ('booking');
        $join = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_event.id=app_event_book.event_id',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_city',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_location',
                'condition' => 'app_location.loc_id=app_event.location',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_event_category',
                'condition' => 'app_event_category.id=app_event.category_id',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_customer',
                'condition' => 'app_customer.id=app_event_book.customer_id',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_admin',
                'condition' => 'app_admin.id=app_event.created_by',
                'jointype' => 'left'
            )
        );

        $e_condition = "app_event_book.id=" . $id;
        $event_data = $this->model_event->getData("app_event_book", "app_event_book.* ,app_event_book.price as final_price,app_event.title as Event_title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,CONCAT(app_customer.first_name,' ',app_customer.last_name) as Customer_name,app_customer.phone as Customer_phone,app_customer.email as Customer_email,app_event_book.addons_id,app_event.price,app_admin.company_name,app_event.description as Event_description, app_event.payment_type", $e_condition, $join);

        $data['event_data'] = $event_data;
        $this->load->view('admin/service/view_booking_details', $data);
    }

    public function appointment_payment() {

        $Vendor_ID = $this->session->userdata('Vendor_ID');
        $fields = "";
        $fields .= "app_appointment_payment.*,CONCAT(app_admin.first_name,' ',app_admin.last_name) as vendor_name,app_event.title as event_name,CONCAT(app_customer.first_name,' ',app_customer.last_name) as customer_name";
        $join = array(
            array(
                "table" => "app_admin",
                "condition" => "app_admin.id=app_appointment_payment.vendor_id",
                "jointype" => "INNER"),
            array(
                "table" => "app_event",
                "condition" => "(app_event.id=app_appointment_payment.event_id  AND app_event.type='S')",
                "jointype" => "INNER"),
            array(
                "table" => "app_customer",
                "condition" => "app_customer.id=app_appointment_payment.customer_id",
                "jointype" => "INNER")
        );

        $payment_data = $this->model_event->getData("app_appointment_payment", $fields, "app_appointment_payment.vendor_id=" . $Vendor_ID, $join, "id DESC");

        $data['title'] = translate('payment_history');
        $data['payment_data'] = $payment_data;
        $this->load->view('admin/service/vendor_appointment_payment', $data);
    }

    /* Holiday Module 07-11-2019 */

    public function holiday() {
        $holiday = $this->model_event->getData('app_holidays', '*', "created_by=" . $this->login_id);
        $data['title'] = translate('manage') . " " . translate('holiday');
        $data['holiday'] = $holiday;
        $this->load->view('admin/service/holiday/index', $data);
    }

    public function add_holiday() {

        $data['title'] = translate('add') . " " . translate('holiday');
        $data['holiday'] = array();
        $this->load->view('admin/service/holiday/add_update', $data);
    }

    public function update_holiday($id) {
        $id = (int) $id;

        if ($id > 0) {

            $app_holidays = $this->model_event->getData("app_holidays", "*", "id='$id'");
            if (isset($app_holidays[0]) && !empty($app_holidays[0])) {
                $data['title'] = translate('update') . " " . translate('holiday');
                $data['holiday'] = $app_holidays[0];
                $this->load->view('admin/service/holiday/add_update', $data);
            } else {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));

                $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
                redirect($folder_url . '/holiday', 'redirect');
            }
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/holiday', 'redirect');
        }
    }

    public function save_holiday() {
        $id = (int) $this->input->post('id', true);
        $this->form_validation->set_rules('title', translate('title'), 'required|trim');
        $this->form_validation->set_rules('holiday_date', translate('date'), 'required|trim|callback_holiday_check');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == false) {
            if ($id > 0) {
                $this->update_holiday($id);
            } else {
                $this->add_holiday();
            }
        } else {

            $data['title'] = $this->input->post('title', true);
            $data['holiday_date'] = date("Y-m-d", strtotime($this->input->post('holiday_date', true)));
            $data['status'] = $this->input->post('status', true);
            $data['created_by'] = $this->login_id;

            if ($id > 0) {
                $this->model_event->update('app_holidays', $data, "id=" . $id);
                $this->session->set_flashdata('msg', translate('record_update'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $data['created_date'] = date("Y-m-d H:i:s");
                $id = $this->model_event->insert('app_holidays', $data);
                $this->session->set_flashdata('msg', translate('record_insert'));
                $this->session->set_flashdata('msg_class', 'success');
            }
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/holiday/', 'redirect');
        }
    }

    public function delete_holiday($id) {
        $this->model_event->delete('app_holidays', 'id=' . $id . " AND created_by=" . $this->login_id);
        $this->session->set_flashdata('msg', translate('record_delete'));
        $this->session->set_flashdata('msg_class', 'success');
        echo 'true';
        exit;
    }

    public function holiday_check($str) {

        $holiday_date = date("Y-m-d", strtotime($this->input->post('holiday_date', true)));
        $id = $this->input->post('id');
        $created_by = $this->login_id;

        if ($id > 0) {
            $app_holidays = $this->model_event->getData("app_holidays", "*", "created_by=" . $created_by . " AND holiday_date='" . $holiday_date . "' AND id!=" . $id);
        } else {
            $app_holidays = $this->model_event->getData("app_holidays", "*", "created_by=" . $created_by . " AND holiday_date='" . $holiday_date . "'");
        }

        if (count($app_holidays) > 0) {
            $this->form_validation->set_message('holiday_check', 'You have already entered holiday for this day.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}

?>