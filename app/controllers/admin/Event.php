<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Event extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_event');
        set_time_zone();

        if (get_site_setting('enable_event') == 'N'):
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/dashboard', 'redirect');
        endif;
    }

    //show event page
    public function index() {
        $join = array(
            array(
                'table' => 'app_event_category',
                'condition' => 'app_event_category.id=app_event.category_id',
                'jointype' => 'left'
            ),
            array(
                'table' => 'app_admin',
                'condition' => 'app_admin.id=app_event.created_by',
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
                $service_condition = "app_event.type ='E' AND app_event.created_by=" . $vendor;
            } else {
                $service_condition = "app_event.type ='E'=" . $this->login_id;
            }
        } else {
            $service_condition = "app_event.type ='E' AND app_event.created_by=" . $this->login_id;
        }

        $event = $this->model_event->getData('', 'app_event.*,app_event_category.title as category_title,app_admin.company_name, app_admin.first_name, app_admin.last_name,app_city.city_title,app_location.loc_title', $service_condition, $join, 'app_event.id DESC');
        $vendor_list = $this->model_event->getData('app_admin', 'id,first_name,last_name,company_name', "status='A' AND type='V' AND profile_status='V'", "", "company_name ASC");

        $data['vendor_list'] = $vendor_list;
        $data['event_data'] = $event;
        $data['title'] = translate('manage') . " " . translate('event');
        $this->load->view('admin/event/event-list', $data);
    }

    //show add event form
    public function add_event() {
        if (isset($this->login_type) && $this->login_type == 'A') {
            check_mandatory();
        }
        $where = "status ='A' AND type ='E'";
        $data['category_data'] = $this->model_event->getData('app_event_category', '*', $where);
        $data['city_data'] = $this->model_event->getData('app_city', '*', "city_status='A'");
        $data['title'] = translate('add') . " " . translate('event');
        $this->load->view('admin/event/manage-event', $data);
    }

    //show edit event form
    public function update_event($id) {
        if (isset($this->login_type) && $this->login_type == 'V'):
            $folder_url = 'vendor';
        else:
            $folder_url = 'admin';
        endif;

        if (isset($id) && $id != '') {
            $join = array(
                array(
                    'table' => 'app_event_sponsor',
                    'condition' => 'app_event_sponsor.event_id=app_event.id',
                    'jointype' => 'left'
            ));
            $event = $this->model_event->getData("app_event", "app_event.*,app_event_sponsor.sponsor_name,app_event_sponsor.website_link, app_event_sponsor.sponsor_image, app_event_sponsor.id as sid", "app_event.id='$id'", $join);
            if (isset($event) && count($event) > 0 && $event[0]['status'] != 'E') {
                $data['event_data'] = $event[0];
                $where = "status ='A' AND type ='E'";
                $data['category_data'] = $this->model_event->getData('app_event_category', '*', $where);
                $data['city_data'] = $this->model_event->getData('app_city', '*', "city_status='A'");
                $data['app_event_ticket_type'] = $this->model_event->getData('app_event_ticket_type', '*', "event_id=" . $id);
                $loc_city_id = $event[0]['city'];
                $data['location_data'] = $this->model_event->getData('app_location', '*', "loc_city_id='$loc_city_id' AND loc_status='A'");

                $data['title'] = translate('update') . " " . translate('event');
                $this->load->view('admin/event/manage-event', $data);
            } else {
                $this->session->set_flashdata('msg_class', 'failure');
                $this->session->set_flashdata('msg', translate('invalid_request'));
                redirect($folder_url . '/manage-event', 'redirect');
            }
        } else {
            $this->session->set_flashdata('msg_class', 'failure');
            $this->session->set_flashdata('msg', translate('invalid_request'));
            redirect($folder_url . '/manage-event', 'redirect');
        }
    }

    //add/edit an event
    public function save_event() {
        $event_id = (int) $this->input->post('id', true);
        $hidden_image = $this->input->post('hidden_image', true);

        $ticket_type_title = $this->input->post('ticket_title[]', true);
        $ticket_type_amount = $this->input->post('ticket_amount[]', true);
        $ticket_type_price = $this->input->post('ticket_price[]', true);
        $ticket_type_id = $this->input->post('ticket_type_id[]', true);


        $this->form_validation->set_rules('name', '', 'trim|required');
        $this->form_validation->set_rules('description', '', 'trim|required');
        $this->form_validation->set_rules('event_start_date', '', 'trim|required');
        $this->form_validation->set_rules('event_end_date', '', 'trim|required');
        $this->form_validation->set_rules('city', '', 'required');
        $this->form_validation->set_rules('location', '', 'required');
        $this->form_validation->set_rules('status', '', 'required');
        $this->form_validation->set_rules('category_id', '', 'required');
        $this->form_validation->set_message('required', translate('required_message'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == false) {
            if ($event_id > 0) {
                $this->update_event($event_id);
            } else {
                $this->add_event();
            }
        } else {

            $data['title'] = trim($this->input->post('name', true));
            $data['event_slug'] = convert_lang_string(trim($this->input->post('name', true)));
            $data['description'] = trim($this->input->post('description', true));
            $data['start_date'] = $this->input->post('event_start_date', true) != '' ? date("Y-m-d H:i:s", strtotime($this->input->post('event_start_date', true))) : '';
            $data['end_date'] = $this->input->post('event_end_date', true) != '' ? date("Y-m-d H:i:s", strtotime($this->input->post('event_end_date', true))) : '';
            $data['city'] = $this->input->post('city', true);
            $data['location'] = $this->input->post('location', true);
            $data['price'] = 0;
            $data['category_id'] = $this->input->post('category_id', true);
            $data['status'] = $this->input->post('status', true);

            $data['seo_description'] = $this->input->post('seo_description', true);
            $data['seo_keyword'] = $this->input->post('seo_keyword', true);
            $data['address'] = $this->input->post('address', true);
            $data['address_map_link'] = $this->input->post('address_map_link', true);
            $data['total_seat'] = $this->input->post('total_seat', true);
            $data['event_limit_type'] = 0;
            $data['latitude'] = $this->input->post('event_latitude', true);
            $data['longitude'] = $this->input->post('event_longitude', true);
            $data['type'] = "E";
            $title = $this->input->post('title', TRUE);
            $description = $this->input->post('description', TRUE);


            $faq_title = $this->input->post('faq_title', true);
            $faq_description = $this->input->post('faq_description', true);

            $faq_data_array = array();
            for ($i = 0; $i < count($faq_title); $i++) {
                $array_val['faq_title'] = $faq_title[$i];
                $array_val['faq_description'] = $faq_description[$i];
                if ($faq_title[$i] != '' || $faq_description[$i] != '') {
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

            /* sponsor */
            $sponsor_image = trim($this->input->post('sponsor_old_image', true));
            if (isset($_FILES['sponsor_image']) && $_FILES['sponsor_image']['name'] != '') {
                if (isset($sponsor_image) && $sponsor_image != '') {
                    if (file_exists(FCPATH . uploads_path . '/event/' . $sponsor_image)) {
                        @unlink(FCPATH . uploads_path . '/event/' . $sponsor_image);
                    }
                }
                $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/event/';
                $banner_tmp_name = $_FILES["sponsor_image"]["tmp_name"];
                $banner_temp = explode(".", $_FILES["sponsor_image"]["name"]);
                $nanner_name = uniqid();
                $new_banner_name = $nanner_name . '.' . end($banner_temp);
                $sponsor_image = $new_banner_name;
                move_uploaded_file($banner_tmp_name, "$uploadPath/$new_banner_name");
            }
            if ($event_id > 0) {

                $id = $this->model_event->update('app_event', $data, "id=$event_id");
                for ($i = 0; $i < count($ticket_type_title); $i++):
                    //Check if ticket type is booked or not
                    if (isset($ticket_type_id[$i]) && $ticket_type_id[$i] != 0):
                        $app_event_ticket_type_booking = $this->model_event->getData("app_event_ticket_type_booking", "id", "ticket_type_id=" . $ticket_type_id[$i]);
                        if (isset($app_event_ticket_type_booking) && count($app_event_ticket_type_booking) == 0):
                            $app_event_ticket_type['event_id'] = $event_id;
                            $app_event_ticket_type['ticket_type_title'] = $ticket_type_title[$i];
                            $app_event_ticket_type['ticket_type_price'] = $ticket_type_price[$i];
                            $app_event_ticket_type['total_seat'] = $ticket_type_amount[$i];
                            $app_event_ticket_type['available_ticket'] = $ticket_type_amount[$i];
                            $app_event_ticket_type['created_by'] = $this->login_id;
                            $this->db->update('app_event_ticket_type', $app_event_ticket_type, "ticket_type_id=" . $ticket_type_id[$i]);
                        endif;
                    else:
                        $app_event_ticket_type['event_id'] = $event_id;
                        $app_event_ticket_type['ticket_type_title'] = $ticket_type_title[$i];
                        $app_event_ticket_type['ticket_type_price'] = $ticket_type_price[$i];
                        $app_event_ticket_type['total_seat'] = $ticket_type_amount[$i];
                        $app_event_ticket_type['available_ticket'] = $ticket_type_amount[$i];
                        $app_event_ticket_type['created_by'] = $this->login_id;
                        $app_event_ticket_type['created_on'] = date("Y-m-d H:i:s");
                        $this->db->insert('app_event_ticket_type', $app_event_ticket_type);
                    endif;
                endfor;

                /* sponsor_update */
                $sponser_id = $this->input->post('sid', true);
                $sponson_data = array(
                    'event_id' => $event_id,
                    'sponsor_name' => trim($this->input->post('sponsor_company', true)),
                    'website_link' => trim($this->input->post('sponsor_website', true)),
                    'sponsor_image' => isset($sponsor_image) ? $sponsor_image : ''
                );


                if ($sponser_id > 0) {
                    $sponson_data['updated_on'] = date('Y-m-d H:i:s');
                    $this->model_event->update('app_event_sponsor', $sponson_data, "event_id=$event_id");
                } else {
                    if ($sponsor_image != '') {
                        $sponson_data['created_on'] = date('Y-m-d H:i:s');
                        $this->model_event->insert('app_event_sponsor', $sponson_data);
                    }
                }

                $this->session->set_flashdata('msg', translate('event_update'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $data['created_by'] = $this->login_id;
                $data['created_on'] = date('Y-m-d H:i:s');
                $id = $this->model_event->insert('app_event', $data);

                for ($i = 0; $i < count($ticket_type_title); $i++):
                    $app_event_ticket_type['event_id'] = $id;
                    $app_event_ticket_type['ticket_type_title'] = $ticket_type_title[$i];
                    $app_event_ticket_type['ticket_type_price'] = $ticket_type_price[$i];
                    $app_event_ticket_type['total_seat'] = $ticket_type_amount[$i];
                    $app_event_ticket_type['available_ticket'] = $ticket_type_amount[$i];
                    $app_event_ticket_type['created_by'] = $this->login_id;
                    $app_event_ticket_type['created_on'] = date("Y-m-d H:i:s");
                    $this->db->insert('app_event_ticket_type', $app_event_ticket_type);
                endfor;

                if (isset($sponsor_image) && $sponsor_image != '') {
                    /* sponson */
                    $sponson_data = array(
                        'event_id' => $id,
                        'sponsor_name' => trim($this->input->post('sponsor_company', true)),
                        'website_link' => trim($this->input->post('sponsor_website', true)),
                        'sponsor_image' => $sponsor_image
                    );

                    $sponson_data['created_on'] = date('Y-m-d H:i:s');
                    $this->model_event->insert('app_event_sponsor', $sponson_data);
                }
                $this->session->set_flashdata('msg', translate('event_insert'));
                $this->session->set_flashdata('msg_class', 'success');
            }
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/manage-event', 'redirect');
        }
    }

    //delete an event
    public function delete_event($id) {
        $appointment = $this->model_event->getData('app_event_book', 'id', "event_id='$id'");
        if (isset($appointment) && count($appointment) > 0) {
            $this->session->set_flashdata('msg', translate('event_book_Appointment'));
            $this->session->set_flashdata('msg_class', 'failure');
            echo 'false';
            exit;
        }
        $this->model_event->delete('app_event', 'id=' . $id);
        $this->session->set_flashdata('msg', translate('event_delete'));
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
        exit;
    }

    //show event category page
    public function event_category() {
        if (isset($this->login_type) && $this->login_type == 'V') {
            $app_vendor_setting_data = app_vendor_setting();
            if (isset($app_vendor_setting_data['allow_event_category']) && $app_vendor_setting_data['allow_event_category'] == 'N'):
                redirect('vendor/dashboard');
            endif;
        }

        $where = "type = 'E'";
        $event = $this->model_event->getData('app_event_category', '*', $where);
        $data['category_data'] = $event;
        $data['title'] = translate('manage_event_category');
        $this->load->view('admin/event/event-category-list', $data);
    }

    //show add event category form
    public function add_category() {
        $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/category';
        if (is_writeable($uploadPath)) {
            if (isset($this->login_type) && $this->login_type == 'V') {
                $app_vendor_setting_data = app_vendor_setting();
                if (isset($app_vendor_setting_data['allow_event_category']) && $app_vendor_setting_data['allow_event_category'] == 'N'):
                    redirect('vendor/dashboard');
                endif;
            }
            $data['title'] = translate('add_event_category');
            $this->load->view('admin/event/manage-event-category', $data);
        }else {
            $this->session->set_flashdata('msg', "Please make sure you have set the writeable permission to 'assets/uploads/category'.");
            $this->session->set_flashdata('msg_class', 'failure');
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/event-category', 'redirect');
        }
    }

    //show edit event category form
    public function update_category($id) {

        $uploadPath = dirname(BASEPATH) . "/" . uploads_path . '/category';
        if (is_writeable($uploadPath)) {
            if (isset($this->login_type) && $this->login_type == 'V') {
                $app_vendor_setting_data = app_vendor_setting();
                if (isset($app_vendor_setting_data['allow_event_category']) && $app_vendor_setting_data['allow_event_category'] == 'N'):
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
                $data['title'] = translate('update') . " " . translate('category');

                $this->load->view('admin/event/manage-event-category', $data);
            } else {
                show_404();
            }
        } else {
            $this->session->set_flashdata('msg', "Please make sure you have set the writeable permission to 'assets/uploads/category'.");
            $this->session->set_flashdata('msg_class', 'failure');
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/event-category', 'redirect');
        }
    }

    public function validate_image() {
        $allowedExts = array("image/gif", "image/jpeg", "image/jpg", "image/png");
        if (!empty($_FILES["event_category_image"]["type"])) {
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

    //add/edit an event category
    public function save_category() {
        if (isset($this->login_type) && $this->login_type == 'V') {
            $app_vendor_setting_data = app_vendor_setting();
            if (isset($app_vendor_setting_data['allow_event_category']) && $app_vendor_setting_data['allow_event_category'] == 'N'):
                redirect('vendor/dashboard');
            endif;
        }
        $hidden_main_image = $this->input->post('hidden_category_image', true);
        $id = (int) $this->input->post('id', true);
        $this->form_validation->set_rules('title', 'title', 'trim|required|callback_check_event_category_title');
        $this->form_validation->set_rules('status', 'Status', 'required');
        if ($id > 0) {
            $this->form_validation->set_rules('event_category_image', translate('event_category_image'), 'trim|callback_validate_image_edit');
        } else {
            $this->form_validation->set_rules('event_category_image', translate('event_category_image'), 'trim|callback_validate_image');
        }

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
            $data['type'] = "E";

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
                $config['maintain_ratio'] = true;
                $config['width'] = 256;
                $config['height'] = 143;

                $this->load->library('image_lib', $config);

                $this->image_lib->resize();

                if (isset($hidden_main_image) && $hidden_main_image != "" && file_exists(FCPATH . uploads_path . '/category/' . $hidden_main_image)) {
                    @unlink($uploadPath . "/" . $hidden_main_image);
                }
            }

            if ($id > 0) {
                $data['updated_on'] = date("Y-m-d H:i:s");
                $this->model_event->update('app_event_category', $data, "id=$id");
                $this->session->set_flashdata('msg', translate('event_category_update'));
                $this->session->set_flashdata('msg_class', 'success');
            } else {
                $data['created_on'] = date("Y-m-d H:i:s");
                $id = $this->model_event->insert('app_event_category', $data);
                if ($id) {
                    $this->session->set_flashdata('msg', translate('event_category_insert'));
                    $this->session->set_flashdata('msg_class', 'success');
                } else {
                    $this->session->set_flashdata('msg', "Unable to create category.");
                    $this->session->set_flashdata('msg_class', 'failure');
                }
            }
            $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
            redirect($folder_url . '/event-category', 'redirect');
        }
    }

    //delete an event category
    public function delete_category($id) {
        $event_data = $this->model_event->getData('app_event', 'id', "category_id='$id'");
        if (isset($event_data) && count($event_data) > 0) {
            $this->session->set_flashdata('msg', translate('event_category_exist'));
            $this->session->set_flashdata('msg_class', 'failure');
            echo 'false';
            exit(0);
        } else {

            $app_event_category = $this->model_event->getData('app_event_category', '*', "id=" . $id);

            if ($this->login_id == 1) {
                $this->model_event->delete('app_event_category', "id=" . $id);
            } else {
                $this->model_event->delete('app_event_category', "id='$id' AND created_by='$this->login_id'");
            }

            $hidden_main_image = $app_event_category[0]['event_category_image'];
            if (isset($hidden_main_image) && $hidden_main_image != "" && file_exists(FCPATH . uploads_path . '/category/' . $hidden_main_image)) {
                @unlink(FCPATH . uploads_path . '/category/' . $hidden_main_image);
            }

            $this->session->set_flashdata('msg', translate('event_category_delete'));
            $this->session->set_flashdata('msg_class', 'success');
            echo 'true';
            exit;
        }
    }

    //     check event category title
    public function check_event_category_title() {
        $id = (int) $this->input->post('id', true);
        $title = trim($this->input->post('title', TRUE));

        if (isset($id) && $id > 0) {
            $this->db->where('id!=',$id);
        }

        $this->db->where('title', $title);
        $this->db->where('type', 'E');
        $this->db->from('app_event_category');
        $check_title=$this->db->count_all_results();

        if (isset($check_title) && ($check_title) > 0) {
            $this->form_validation->set_message('check_event_category_title', 'Title already exist.');
            return false;
        } else {
            return true;
        }
    }

    //delete event seo image
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

    /* Event Booking */

    public function event_booking() {

        $event = isset($_REQUEST['event']) ? $_REQUEST['event'] : "";
        $vendor = isset($_REQUEST['vendor']) ? $_REQUEST['vendor'] : "";
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : "";
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : "";
        $appointment_type = isset($_REQUEST['appointment_type']) ? $_REQUEST['appointment_type'] : "U";

        $cond = " app_event_book.event_id >0 AND app_event_book.type='E' ";

        $vendor_condition = "";
        if ($this->login_type == 'V') {
            $cond .= " AND app_event.created_by = $this->login_id";
            $vendor_condition .= "app_event.type='E' AND app_event.created_by=" . $this->login_id;
        } else {
            $cond .= '';
            $vendor_condition .= "app_event.type='E'";
        }

        if (isset($event) && $event > 0) {
            $cond .= " AND app_event_book.event_id=" . $event;
        }

        if (isset($vendor) && $vendor > 0) {
            $cond .= " AND app_event.created_by=" . $vendor;
        }

        if (isset($status) && $status != "") {
            $cond .= " AND app_event_book.status='" . $status . "'";
        }
        if (isset($type) && $type != "") {
            $cond .= " AND app_event.payment_type='" . $type . "'";
        }

        $cur_date = date("Y-m-d");

        if (isset($appointment_type) && $appointment_type == 'U') {
            $cond .= " AND DATE(app_event.end_date)>='" . $cur_date . "' ";
        } else {
            $cond .= " AND DATE(app_event.end_date)<'" . $cur_date . "' ";
        }

        $join = array(
            array(
                "table" => "app_customer",
                "condition" => "app_customer.id=app_event_book.customer_id",
                "jointype" => "LEFT"),
            array(
                "table" => "app_event",
                "condition" => "app_event.id=app_event_book.event_id",
                "jointype" => "LEFT"),
            array(
                "table" => "app_admin",
                "condition" => "app_event.created_by=app_admin.id",
                "jointype" => "INNER")
        );

        $appointment = $this->model_event->getData('app_event_book', 'app_event_book.*,app_admin.company_name,app_customer.first_name,app_customer.last_name,app_event.title,app_event.created_by,app_event.payment_type', $cond, $join, 'app_event_book.id DESC');
        $data['appointment_data'] = $appointment;

        $join_one = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_event.id=app_event_book.event_id',
                'jointype' => 'INNER'
            )
        );


        $join_two = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_event.id=app_event_book.event_id',
                'jointype' => 'inner'
            ), array(
                'table' => 'app_admin',
                'condition' => 'app_event.created_by=app_admin.id',
                'jointype' => 'inner'
            )
        );

        $appointment_event = $this->model_event->getData("app_event_book", "app_event_book.event_id,app_event.id as event_id,app_event.title as title", $vendor_condition, $join_one, "", "app_event.id");

        $appointment_vendor = $this->model_event->getData("app_event_book", "app_admin.company_name,app_admin.first_name,app_admin.last_name,app_admin.id", "", $join_two, "", "app_admin.id");

        $city_join = array(
            array(
                'table' => 'app_event',
                'condition' => 'app_city.city_id=app_event.city',
                'jointype' => 'inner'
            )
        );
        $top_cities = $this->model_event->getData('app_city', 'app_city.*', 'app_event.status="A"', $city_join, 'city_id', 'city_id', '', 12, array(), '', array(), 'DESC');

        $data['appointment_data'] = $appointment;
        $data['appointment_vendor'] = $appointment_vendor;
        $data['topCity_List'] = $top_cities;
        $data['appointment_event'] = $appointment_event;

        $data['title'] = translate('manage') . " " . translate('appointment');
        $this->load->view('admin/event/event-booking', $data);
    }

    /* Event Payment */

    public function event_payment() {
        $fields = "";
        $fields .= "app_appointment_payment.*,CONCAT(app_admin.first_name,' ',app_admin.last_name) as vendor_name,app_admin.company_name,app_event.title as event_name,CONCAT(app_customer.first_name,' ',app_customer.last_name) as customer_name";
        $join = array(
            array(
                "table" => "app_admin",
                "condition" => "app_admin.id=app_appointment_payment.vendor_id",
                "jointype" => "INNER"),
            array(
                "table" => "app_event",
                "condition" => "(app_event.id=app_appointment_payment.event_id AND app_event.type='E')",
                "jointype" => "INNER"),
            array(
                "table" => "app_customer",
                "condition" => "app_customer.id=app_appointment_payment.customer_id",
                "jointype" => "INNER")
        );

        $payment_data = $this->model_event->getData("app_appointment_payment", $fields, "", $join, "id DESC");

        $data['title'] = translate('payout_request');
        $data['payment_data'] = $payment_data;
        $this->load->view('admin/event/event-payment', $data);
    }

    /* Event Payment Details */

    public function event_payment_details($id = FALSE) {
        if ($id) {
            $fields = "";
            $fields .= "app_appointment_payment.*,CONCAT(app_admin.first_name,' ',app_admin.last_name) as vendor_name,app_admin.company_name,app_event.title as event_name,CONCAT(app_customer.first_name,' ',app_customer.last_name) as customer_name";
            $join = array(
                array(
                    "table" => "app_admin",
                    "condition" => "app_admin.id=app_appointment_payment.vendor_id",
                    "jointype" => "INNER"),
                array(
                    "table" => "app_event",
                    "condition" => "(app_event.id=app_appointment_payment.event_id AND app_event.type='E')",
                    "jointype" => "INNER"),
                array(
                    "table" => "app_customer",
                    "condition" => "app_customer.id=app_appointment_payment.customer_id",
                    "jointype" => "INNER")
            );

            $payment_data = $this->model_event->getData("app_appointment_payment", $fields, "app_appointment_payment.id=" . $id, $join, "id DESC");
            $html = "";
            $data['payment_data'] = $payment_data[0];
            $data['title'] = translate('event') . " " . translate('payment') . " " . translate("details");
            $this->load->view('front/profile/event-payment-details', $data);
        }
    }

    function view_event_booking_details($id) {
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
        $event_data = $this->model_event->getData("app_event_book", "app_event_book.* ,app_event_book.price as final_price,app_event.title as Event_title,app_location.loc_title,app_city.city_title,app_event_category.title as category_title,CONCAT(app_customer.first_name,' ',app_customer.last_name) as Customer_name,app_customer.phone as Customer_phone,app_customer.email as Customer_email,app_event.price,app_admin.company_name,app_event.description as Event_description, app_event.payment_type", $e_condition, $join);

        $data['event_data'] = $event_data;
        $this->load->view('admin/event/view_event_booking_details', $data);
    }

    public function delete_ticket_type() {
        $ticket_type_id = (int) $this->input->post('ticket_type_id');

        if (isset($ticket_type_id) && $ticket_type_id != 0):
            $app_event_ticket_type_booking = $this->model_event->getData("app_event_ticket_type_booking", "id", "ticket_type_id=" . $ticket_type_id);

            if (isset($app_event_ticket_type_booking) && count($app_event_ticket_type_booking) == 0):
                $this->db->query("DELETE FROM app_event_ticket_type WHERE ticket_type_id=" . $ticket_type_id);
                echo true;
            else:
                echo false;
            endif;
        else:
            echo false;
        endif;
    }

}

?>