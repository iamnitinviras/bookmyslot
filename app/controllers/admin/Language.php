<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Language extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_event');
        set_time_zone();
    }

    //show language page
    public function index() {

        $language_data = $this->model_event->getData('app_language', '*');
        $data['language_data'] = $language_data;
        $data['title'] = translate('manage') . " " . translate('language');
        $this->load->view('admin/language/language_list', $data);
    }

    //show add language form
    public function add_language() {

        if (is_writeable(FCPATH . "app/language")) {
            $data['title'] = translate('add') . " " . translate('language');
            $this->load->view('admin/language/add_update_language', $data);
        } else {
            $this->session->set_flashdata('msg', "You folder app/language is not writable. Please update permission to proceed further.");
            $this->session->set_flashdata('msg_class', 'failure');
            redirect('admin/manage-language');
        }
    }

    //show edit language form
    public function update_language($id) {
        if (is_writeable(FCPATH . "app/language")) {
            $language_data = $this->model_event->getData("app_language", "*", "id='$id'");
            if (count($language_data) > 0) {
                $data['language_data'] = $language_data[0];
                $data['title'] = translate('update') . " " . translate('language');
                $this->load->view('admin/language/add_update_language', $data);
            } else {
                redirect('admin/manage-language');
            }
        } else {
            $this->session->set_flashdata('msg', "You folder app/language is not writable. Please update permission to proceed further.");
            $this->session->set_flashdata('msg_class', 'failure');
            redirect('admin/manage-language');
        }
    }

    //Set Language Translation
    public function language_translate($id) {

        $act_language_data = $this->model_event->getData('app_language', '*', 'status="A"');

        if (is_writeable(FCPATH . "app/language")) {
            $language_data = $this->model_event->getData("app_language", "*", "id='$id'");
            if (count($language_data) > 0) {

                $this->db->order_by('id', 'asc');
                $data['words'] = $this->db->get('app_language_data')->result_array();
                $data['title'] = translate('translate') . " " . translate('words');
                $data['language_data'] = $language_data[0];
                $data['act_language_data'] = $act_language_data;
                $this->load->view('admin/language/language_translate', $data);
            } else {
                $this->session->set_flashdata('msg', translate('invalid_request'));
                $this->session->set_flashdata('msg_class', 'failure');
                redirect('admin/manage-language');
            }
        } else {
            $this->session->set_flashdata('msg', "You folder app/language is not writable. Please update permission to proceed further.");
            $this->session->set_flashdata('msg_class', 'failure');
            redirect('admin/manage-language');
        }
    }

    //add/edit an language
    public function save_language() {
        $id = (int) $this->input->post('id', true);
        if ($id != 1):
            $this->form_validation->set_rules('title', '', 'trim|alpha_numeric_spaces|required|is_unique[app_language.title.id.' . $id . ']');
            $this->form_validation->set_message('required', translate('required_message'));
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            if ($this->form_validation->run() == false) {
                if ($id > 0) {
                    $this->update_language($id);
                } else {
                    $this->add_language();
                }
            } else {
                $data['title'] = strtolower($this->input->post('title', true));
                $data['status'] = $this->input->post('status', true);
                if ($id > 0) {
                    if ($id == 1) {
                        $this->session->set_flashdata('msg', translate('language_used'));
                        $this->session->set_flashdata('msg_class', 'failure');
                        redirect('admin/language-list');
                    } else {
                        //get old language title
                        $old_language_data = $this->db->query("SELECT * FROM app_language WHERE id=" . $id)->row_array();

                        $lang_title_new = strtolower($this->input->post('title', true));
                        $new_db_field = $language = str_replace(' ', '_', trim($lang_title_new));

                        $old_db_field = trim($old_language_data['db_field']);
                        $old_title = trim($old_language_data['title']);
                        $data['db_field'] = strtolower(str_replace(' ', '_', trim($this->input->post('title', true))));
                        $this->db->update('app_language', $data, "id=" . $id);
                        if ($new_db_field != $old_db_field):
                            $this->update_language_field($old_db_field, $new_db_field);
                        endif;

                        $this->session->set_flashdata('msg', translate('language_update'));
                        $this->session->set_flashdata('msg_class', 'success');
                        redirect('admin/manage-language');
                    }
                } else {
                    $data['created_date'] = date('Y-m-d H:i:s');
                    $data['db_field'] = strtolower(str_replace(' ', '_', $this->input->post('title', true)));
                    $id = $this->model_event->insert('app_language', $data);

                    $this->add_language_field(strtolower($this->input->post('title', true)));
                    $this->session->set_flashdata('msg', translate('language_add'));
                    $this->session->set_flashdata('msg_class', 'success');
                }
                redirect('admin/manage-language');
            }
        else:
            $this->session->set_flashdata('msg', "You are not allowed to update english language.");
            $this->session->set_flashdata('msg_class', 'failure');
            redirect('admin/manage-language');
        endif;
    }

    //delete an language
    public function delete_language($id) {
        $id = (int) $id;
        $app_site_setting = $this->model_event->getData('app_site_setting', 'language', "id=1");
        $get_lang_data = $this->model_event->getData('app_language', 'title,db_field', "id=" . $id);
        if ($id > 1) {
            if (isset($get_lang_data[0]['title'])) {
                if ($app_site_setting[0]['language'] == $get_lang_data[0]['db_field']) {
                    $this->session->set_flashdata('msg', translate('language_used'));
                    $this->session->set_flashdata('msg_class', 'failure');
                    echo 'false';
                    exit;
                } else {
                    //get language data
                    //Delete Lanaguage Column
                    $delete_langu_column = str_replace(' ', '_', $get_lang_data[0]['title']);
                    $this->load->dbforge();
                    $this->dbforge->drop_column('app_language_data', $delete_langu_column);

                    $unlink_lang = trim($get_lang_data[0]['db_field']);
                    @unlink(FCPATH . "app/language/" . $unlink_lang . "/basic_lang.php");
                    @rmdir(FCPATH . "app/language/" . $unlink_lang);

                    $this->model_event->delete('app_language', 'id=' . $id);
                    $this->session->set_flashdata('msg', translate('language_delete'));
                    $this->session->set_flashdata('msg_class', 'success');
                    echo 'true';
                    exit;
                }
            } else {
                echo 'false';
                exit(0);
            }
        } else {
            $this->session->set_flashdata('msg', translate('language_used'));
            $this->session->set_flashdata('msg_class', 'failure');

            echo 'false';
            exit(0);
        }
    }

    public function save_translated_language() {
        $id = (int) $this->input->post('id', true);
        $field = trim($this->input->post('field', true));
        $text_value = $this->input->post('text_value', true);

        $data[$field] = ($text_value);
        $up_status = $this->model_event->update('app_language_data', $data, 'id=' . $id);

        //Update language file
        if ($up_status) {

            $file_loc = APPPATH . 'language/' . $field . '/basic_lang.php';
            $myfile = fopen($file_loc, "w");

            $app_language_data = $this->model_event->getData('app_language_data', '*');

            $string_data = "<?php ";
            foreach ($app_language_data as $val):
                $string_data .= nl2br('$lang["' . strtolower($val["default_text"]) . '"]="' . addslashes($val[$field]) . '";');
            endforeach;
            $string_data .= " ?>";

            fwrite($myfile, $string_data);
            fclose($myfile);
        }
        echo TRUE;
    }

    //language data table
    public function add_language_field($language) {
        $language = str_replace(' ', '_', trim($language));
        $this->db->query("ALTER TABLE `app_language_data` ADD " . $language . " TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL");
        $this->db->query("UPDATE `app_language_data` SET `" . $language . "`=`english`");

        //Create new folder for language
        try {
            $folderPath = APPPATH . 'language/' . $language;

            if (file_exists(FCPATH . 'app/language/' . $language)) {
                //Create new file in newly created file
                $myfile = fopen($folderPath . "/basic_lang.php", "w");
                $file_update_in_new_file = APPPATH . 'language/english/basic_lang.php';

                $new_file_content = file_get_contents($file_update_in_new_file);
                fwrite($myfile, $new_file_content);
                fclose($myfile);
            } else {
                if (!mkdir($folderPath, 0777, true)) {
                    
                }
                //CReate new file in newly created file
                $myfile = fopen($folderPath . "/basic_lang.php", "w");
                $file_update_in_new_file = APPPATH . 'language/english/basic_lang.php';

                $new_file_content = file_get_contents($file_update_in_new_file);
                fwrite($myfile, $new_file_content);
                fclose($myfile);
            }
        } catch (Exception $e) {
            
        }
    }

    public function update_language_field($old_language_title, $new_language_title) {
        $this->db->query("ALTER TABLE `app_language_data` CHANGE `" . $old_language_title . "` `" . $new_language_title . "` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
        //Rename folder in language folder
        rename(FCPATH . 'app/language/' . $old_language_title, FCPATH . 'app/language/' . $new_language_title);
    }

    public function add_new_word() {
        $this->form_validation->set_rules('default_text', 'Word', 'trim|required|alpha_numeric_spaces|is_unique[app_language_data.default_text]');
        if ($this->form_validation->run() == true) {

            $language_word = str_replace(' ', '_', trim($this->input->post('default_text')));
            $language_word = str_replace('-', '_', trim($language_word));

            $add_data['default_text'] = $language_word;

            $act_language_data = $this->model_event->getData('app_language', '*', 'status="A"');
            foreach ($act_language_data as $val):
                $add_data[$val['db_field']] = $this->input->post($val['db_field']);
            endforeach;

            $id = $this->model_event->insert('app_language_data', $add_data);
            if ($id) {

                foreach ($act_language_data as $vals):
                    if (isset($vals['db_field']) && $vals['db_field'] != "") {
                        $field = $vals['db_field'];

                        $file_loc = APPPATH . 'language/' . $field . '/basic_lang.php';
                        $myfile = fopen($file_loc, "w");

                        $app_language_data = $this->model_event->getData('app_language_data', '*');

                        $string_data = "<?php ";
                        foreach ($app_language_data as $val):
                            $string_data .= nl2br('$lang["' . strtolower($val["default_text"]) . '"]="' . addslashes($val[$field]) . '";');
                        endforeach;
                        $string_data .= " ?>";

                        fwrite($myfile, $string_data);
                        fclose($myfile);
                    }
                endforeach;

                $this->session->set_flashdata('msg', translate('language_add'));
                $this->session->set_flashdata('msg_class', 'success');
                echo true;
            } else {
                $this->session->set_flashdata('msg', translate('language_add'));
                $this->session->set_flashdata('msg_class', 'failure');
                echo true;
            }
        } else {
            echo validation_errors('<div class="alert alert-danger">', '</div>');
        }
    }

}

?>