<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Service extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_event');
        set_time_zone();

        if (get_site_setting('enable_service') == 'N'):
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/dashboard', 'redirect');
        endif;
    }

    //show service page
    public function index() {
        $join = array(
            array(
                'table' => 'app_event_category',
                'condition' => 'app_event_category.id=app_event.category_id',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_users',
                'condition' => 'app_users.id=app_event.created_by',
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
        );


        $vendor = (int) $this->input->get('vendor');

        if ($this->login_type == 'A') {
            if (isset($vendor) && $vendor != "" && $vendor > 0) {
                $service_condition = "app_event.type ='S' AND app_event.created_by=" . $vendor;
            } else {
                $service_condition = "app_event.type ='S'=" . $this->login_id;
            }
        } else {
            $service_condition = "app_event.type ='S' AND app_event.created_by=" . $this->login_id;
        }


        $event = $this->model_event->getData('', 'app_event.*,app_event_category.title as category_title,app_users.company_name, app_users.first_name, app_users.last_name,app_city.city_title,app_location.loc_title', $service_condition, $join);

        $vendor_list = $this->model_event->getData('app_users', 'id,first_name,last_name,company_name', "status='A' AND type='V' AND profile_status='V'", "", "company_name ASC");
        $data['event_data'] = $event;
        $data['vendor_list'] = $vendor_list;
        $data['title'] = translate('manage') . " " . translate('service');
        $this->load->view('admin/service/index', $data);
    }

    //show add service form
    public function add_service() {
        if (isset($this->login_type) && $this->login_type == 'A') {
            check_mandatory();
        }
        $staff_data = get_staff_by_vendor_id($this->login_id);
        $data['staff_data'] = $staff_data;
        $where = "status='A' AND type ='S'";
        $data['category_data'] = $this->model_event->getData('app_event_category', '*', $where);
        $data['city_data'] = $this->model_event->getData('app_city', '*', "city_status='A'");
        $data['title'] = translate('add') . " " . translate('service');
        $this->load->view('admin/service/add_update', $data);
    }

    //show edit service form
    public function update_service($id) {

        $service = $this->model_event->getData("app_event", "*", "id='$id'");
        if (isset($service[0]) && !empty($service[0])) {
            $data['event_data'] = $service[0];
            $where = " status='A' AND type ='S'";
            $data['category_data'] = $this->model_event->getData('app_event_category', '*', $where);
            $data['city_data'] = $this->model_event->getData('app_city', '*', "city_status='A'");
            $loc_city_id = $service[0]['city'];
            $data['location_data'] = $this->model_event->getData('app_location', '*', "loc_city_id='$loc_city_id' AND loc_status='A'");
            $data['app_service_addons'] = $this->model_event->getData("app_service_addons", "*", 'event_id=' . $id);

            if ($this->login_type == 'A' && $service[0]['created_by'] != $this->login_id) {
                $staff_data = get_staff_by_vendor_id($service[0]['created_by']);
            } else {
                $staff_data = get_staff_by_vendor_id($this->login_id);
            }
            $data['staff_data'] = $staff_data;

            $data['title'] = translate('update') . " " . translate('service');
            $this->load->view('admin/service/add_update', $data);
        } else {
            show_404();
        }
    }

    //update service booking 
    public function service_booking_update() {
        $book_id = (int) $this->input->post('book_id', true);
        $customer_id = (int) $this->input->post('customer_id', true);
        $event_id = (int) $this->input->post('event_id', true);
        $staff_member_id = (int) $this->input->post('staff_member_id', true);
        $user_datetime = $this->input->post('user_datetime', true);

        $user_datetime = explode(" ", $user_datetime);
        $data['start_date'] = $user_datetime[0];
        $data['start_time'] = $user_datetime[1];
        $data['staff_id'] = $staff_member_id;

        //check booking
        $app_event_book = $this->model_event->getData("app_event_book", '*', "event_id=" . $event_id . " AND customer_id=" . $customer_id . " AND id=" . $book_id);
        if (count($app_event_book) == 0) {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            if (isset($this->login_type) && $this->login_type == 'V') {
                redirect(base_url('vendor/manage-appointment'));
            } else {
                redirect(base_url('admin/manage-appointment'));
            }
        }
        $id = $this->model_event->update('app_event_book', $data, "event_id=" . $event_id . " AND customer_id=" . $customer_id . " AND id=" . $book_id);
        $get_service_event_by_id = get_service_event_by_id($event_id);
        if ($id) {
            $this->session->set_flashdata('msg_class', 'success');
            $this->session->set_flashdata('msg', translate('update_booking_time'));

            //send success email to user
            $customer_data = $this->db->query("SELECT * FROM app_customer WHERE id=" . $customer_id)->row_array();

            if (isset($customer_data['email']) && $customer_data['email'] != ""):
                //set email template data
                $parameter['service_data'] = $get_service_event_by_id;
                $parameter['SERVICE_TIME'] = get_formated_date($user_datetime[0] . " " . $user_datetime[1]);
                $parameter['name'] = $customer_data['first_name'] . " " . $customer_data['last_name'];

                if ($staff_member_id > 0):
                    $parameter['staff_data'] = get_staff_row_by_id($staff_member_id);
                endif;

                $html = $this->load->view('email_template/booking_time_change', $parameter, true);

                $subject = get_CompanyName() . " | " . translate('appointment') . " " . translate('notification');
                $define_param['to_name'] = $customer_data['first_name'] . " " . $customer_data['last_name'];
                $define_param['to_email'] = $customer_data['email'];
                $send = $this->sendmail->send($define_param, $subject, $html);
            endif;

            $this->session->set_flashdata('msg', translate('update_booking_time'));
            $this->session->set_flashdata('msg_class', 'success');

            if (isset($this->login_type) && $this->login_type == 'V') {
                redirect(base_url('vendor/manage-appointment'));
            } else {
                redirect(base_url('admin/manage-appointment'));
            }
        } else {
            if (isset($this->login_type) && $this->login_type == 'V') {
                redirect(base_url('vendor/manage-appointment'));
            } else {
                redirect(base_url('admin/manage-appointment'));
            }
        }
    }

    //add/edit an service
    public function save_service() {
        $service_id = (int) $this->input->post('id', true);
        $hidden_image = $this->input->post('hidden_image', true);
        $this->form_validation->set_rules('name', '', 'trim|required');
        $this->form_validation->set_rules('description', '', 'trim|required');
        $this->form_validation->set_rules('days[]', '', 'required');
        $this->form_validation->set_rules('start_time', '', 'required');
        $this->form_validation->set_rules('end_time', '', 'required');
        $this->form_validation->set_rules('city', '', 'required');
        $this->form_validation->set_rules('location', '', 'required');
        $this->form_validation->set_rules('status', '', 'required');
        $this->form_validation->set_rules('payment_type', '', 'required');
        $this->form_validation->set_rules('category_id', '', 'required');
        $this->form_validation->set_rules('padding_time', 'Padding time', 'integer');
        $this->form_validation->set_rules('multiple_slotbooking_limit', 'Multiple slot booking limit', 'integer');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == false) {
            if ($service_id > 0) {
                $this->update_service($service_id);
            } else {
                $this->add_service();
            }
        } else {

            $staff_value = $this->input->post('staff[]', true);

            if (isset($staff_value) && count($staff_value) > 0):
                $staff_data = implode(',', $staff_value);
            else:
                $staff_data = '';
            endif;

            $data['title'] = trim($this->input->post('name', true));
            $data['event_slug'] = convert_lang_string(trim($this->input->post('name', true)));
            $data['description'] = trim($this->input->post('description', true));
            $data['days'] = implode(",", $this->input->post('days[]', true));
            $data['start_time'] = $this->input->post('start_time', true);
            $data['end_time'] = $this->input->post('end_time', true);
            $data['slot_time'] = $this->input->post('slot_time', true);

            $data['city'] = $this->input->post('city', true);
            $data['staff'] = $staff_data;
            $data['location'] = $this->input->post('location', true);
            $data['payment_type'] = $this->input->post('payment_type', true);
            $data['price'] = $this->input->post('price', true);
            $data['category_id'] = $this->input->post('category_id', true);
            $data['status'] = $this->input->post('status', true);
            $data['discount'] = $this->input->post('discount', true);
            $data['from_date'] = $this->input->post('from_date', true) != '' ? date("Y-m-d", strtotime($this->input->post('from_date', true))) : '';
            $data['to_date'] = $this->input->post('to_date', true) != '' ? date("Y-m-d", strtotime($this->input->post('to_date', true))) : '';
            $data['discounted_price'] = $this->input->post('discounted_price', true);
            $data['seo_description'] = $this->input->post('seo_description', true);
            $data['seo_keyword'] = $this->input->post('seo_keyword', true);
            $data['address'] = $this->input->post('address', true);
            $data['address_map_link'] = $this->input->post('address_map_link', true);
            $data['latitude'] = $this->input->post('event_latitude', true);
            $data['longitude'] = $this->input->post('event_longitude', true);
            $data['padding_time'] = $this->input->post('padding_time', true);
            $data['multiple_slotbooking_allow'] = $this->input->post('multiple_slotbooking_allow', true);
            $data['multiple_slotbooking_limit'] = $this->input->post('multiple_slotbooking_limit', true);
            $data['type'] = "S";

            $faq_title = $this->input->post('faq_title', true);
            $faq_description = $this->input->post('faq_description', true);

            $faq_data_array = array();
            for ($i = 0; $i < count($faq_title); $i++) {
                if ($faq_title[$i] != '' || $faq_description[$i] != '') {
                    $array_val['faq_title'] = $faq_title[$i];
                    $array_val['faq_description'] = $faq_description[$i];
                    array_push($faq_data_array, $array_val);
                }
            }

            $data['faq'] = json_encode($faq_data_array);

            $is_Files = $this->check_upload_images($_FILES['image']['name']);

            if (isset($_FILES['image']) && $is_Files) {
                $filesCount = count($_FILES['image']['name']);

                $thumb_config['image_library'] = 'gd2';
                $thumb_config['maintain_ratio'] = TRUE;
                $thumb_config['width'] = 350;
                $thumb_config['height'] = 230;
                $thumb_config['create_thumb'] = TRUE;
                $thumb_config['thumb_marker'] = '_thumb';

                for ($i = 0; $i < $filesCount; $i++) {

                    $_FILES['userFile']['name'] = $fname = $_FILES['image']['name'][$i];
                    if ($fname != '') {
                        $_FILES['userFile']['type'] = $_FILES['image']['type'][$i];
                        $_FILES['userFile']['tmp_name'] = $_FILES['image']['tmp_name'][$i];
                        $_FILES['userFile']['error'] = $_FILES['image']['error'][$i];
                        $_FILES['userFile']['size'] = $_FILES['image']['size'][$i];

                        $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/event';
                        $config['upload_path'] = $uploadPath;
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $temp = explode(".", $_FILES["userFile"]["name"]);
                        $name = uniqid();
                        $new_name = $name . '.' . end($temp);

                        $config['file_name'] = $new_name;

                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('userFile')) {
                            $fileData = $this->upload->data();
                            $image[] = $fileData['file_name'];

                            $source_path = $uploadPath . '/' . $new_name;
                            $dest = $uploadPath . '/' . $new_name;

                            $thumb_config['source_image'] = $source_path;
                            $thumb_config['new_image'] = $dest;
                            $this->load->library('image_lib');
                            $this->image_lib->initialize($thumb_config);
                            if (!$this->image_lib->resize()) {
                                echo $this->image_lib->display_errors();
                            }
                            // clear //
                            $this->image_lib->clear();
                        }
                    }
                }
                if ($hidden_image != '' && $hidden_image != 'null') {
                    $data['image'] = json_encode(array_filter(array_merge(json_decode($hidden_image), $image)));
                } else {
                    $data['image'] = json_encode($image);
                }
            } else {
                if ($hidden_image != '' && $hidden_image != 'null') {
                    $data['image'] = ($hidden_image);
                }
            }
            if (isset($_FILES['seo_og_image']) && $_FILES['seo_og_image']['name'] != '') {
                $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/event';
                $banner_tmp_name = $_FILES["seo_og_image"]["tmp_name"];
                $banner_temp = explode(".", $_FILES["seo_og_image"]["name"]);
                $nanner_name = uniqid();
                $new_banner_name = $nanner_name . '.' . end($banner_temp);
                $data['seo_og_image'] = $new_banner_name;
                move_uploaded_file($banner_tmp_name, "$uploadPath/$new_banner_name");
            }
            if ($service_id > 0) {
                $id = $this->model_event->update('app_event', $data, "id=$service_id");

                $this->session->set_flashdata('msg', translate('service_update'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $data['created_by'] = $this->login_id;
                $data['created_on'] = date('Y-m-d H:i:s');
                $id = $this->model_event->insert('app_event', $data);

                $this->session->set_flashdata('msg', translate('service_insert'));
                $this->session->set_flashdata('msg_class', 'success');
            }
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/service', 'redirect');
        }
    }

    //delete an service
    public function delete_service($id) {
        $appointment = $this->model_event->getData('app_event_book', 'id', "event_id='$id'");
        if (isset($appointment) && count($appointment) > 0) {
            $this->session->set_flashdata('msg', translate('event_book_Appointment'));
            $this->session->set_flashdata('msg_class', 'failure');
            echo 'false';
            exit;
        }
        $this->model_event->delete('app_event', 'id=' . $id);
        $this->session->set_flashdata('msg', translate('service_delete'));
        $this->session->set_flashdata('msg_class', 'success');
        echo 'true';
        exit;
    }

    //get location
    public function get_location($city_id) {
        $location_data = $this->model_event->getData('app_location', '*', "loc_city_id='$city_id' AND loc_status='A'");
        $html = '<option value="">' . translate('select_location') . '</option>';
        if (isset($location_data) && count($location_data) > 0) {
            foreach ($location_data as $value) {
                $html .= '<option value="' . $value['loc_id'] . '">' . $value['loc_title'] . '</option>';
            }
        }
        echo $html;
    }

    //delete event image
    public function delete_event_image() {
        $image = $this->input->post('i', TRUE);
        $event_id = $this->input->post('id', TRUE);
        $hidden_image = json_decode($this->input->post('h', TRUE));

        if (file_exists(dirname(FCPATH) . "/" . $image)) {
            if (unlink(dirname(FCPATH) . "/" . $image)) {
                $key = array_search(basename($image), $hidden_image);
                unset($hidden_image[$key]);
                $new_array = array_values($hidden_image);
                $data['image'] = json_encode($new_array);
                $id = $this->model_event->update('app_event', $data, "id=$event_id");
                if ($id) {
                    echo json_encode($new_array);
                } else {
                    echo 'false';
                }
            } else {
                echo 'false';
            }
        } else {
            $key = array_search(basename($image), $hidden_image);
            unset($hidden_image[$key]);
            $new_array = array_values($hidden_image);
            $data['image'] = json_encode($new_array);
            $id = $this->model_event->update('app_event', $data, "id=$event_id");
            if ($id) {
                echo json_encode($new_array);
            } else {
                echo 'false';
            }
        }
        exit;
    }

    //show service category page
    public function service_category() {
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
            redirect($folder_url . '/service-category', 'redirect');
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
                redirect($folder_url . '/service/addons/' . $service_id, 'redirect');
            }
        } else {
            if ($this->login_type == 'V') {
                redirect('vendor/service');
            } else {
                redirect('admin/service');
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
                'table' => 'app_users',
                'condition' => 'app_users.id=app_event.created_by',
                'jointype' => 'left'
            )
        );

        $e_condition = "app_event_book.id=" . $id;
        $event_data = $this->model_event->getData("app_event_book", "app_event_book.* ,app_event_book.price as final_price,app_event.title as Event_title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,CONCAT(app_customer.first_name,' ',app_customer.last_name) as Customer_name,app_customer.phone as Customer_phone,app_customer.email as Customer_email,app_event_book.addons_id,app_event.price,app_users.company_name,app_event.description as Event_description, app_event.payment_type", $e_condition, $join);

        $data['event_data'] = $event_data;
        $this->load->view('admin/service/view_booking_details', $data);
    }

    public function appointment_payment() {

        $Vendor_ID = $this->session->userdata('Vendor_ID');
        $fields = "";
        $fields .= "app_appointment_payment.*,CONCAT(app_users.first_name,' ',app_users.last_name) as vendor_name,app_event.title as event_name,CONCAT(app_customer.first_name,' ',app_customer.last_name) as customer_name";
        $join = array(
            array(
                "table" => "app_users",
                "condition" => "app_users.id=app_appointment_payment.vendor_id",
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
            $data['status'] = $this->input->post('status');
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