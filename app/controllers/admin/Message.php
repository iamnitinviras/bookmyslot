<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Message extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('model_message');
        set_time_zone();
    }

    //show message page
    public function index($id = NULL) {
        $login_id = $this->login_id;
        $customer_list = $this->model_message->message_customer_list($this->login_id);
        $tempArr = array_unique(array_column($customer_list, 'chat_id'));
        $data['customer_list'] = (array_intersect_key($customer_list, $tempArr));
        if (($id)) {
            $check_chat = $this->model_message->getData('app_chat_master', 'id', "vendor_id='$this->login_id' AND customer_id='$id'");
            if (isset($check_chat) && !empty($check_chat)) {
                foreach ($check_chat as $row) {
                    $chat_id = $row['id'];
                }
            }
            $data['chat_id'] = isset($chat_id) ? $chat_id : 0;
            $this->model_message->update('app_chat', array('msg_read' => 'Y'), "to_id='$login_id' AND from_id='$id' AND chat_type='C'");
            $data['msg_customer_data'] = $this->model_message->msg_customer_data($id);
            $data['msg_group_list'] = $this->model_message->msg_group_list($this->login_id, $id);
        } else {
            $id = isset($data['customer_list'][0]) ? $data['customer_list'][0]['customer_id'] : 0;
        }
        $data['title'] = translate('manage') . " " . translate('event');
        $this->load->view('admin/message', $data);
    }

    public function message_action() {
        $from_id = (int) $this->login_id;
        $to_id = (int) $this->input->post('msg_to_id');
        $message = $this->input->post('message');
        $chat_id = (int) $this->model_message->get_chat_id($from_id, $to_id);
        if (isset($chat_id) && $chat_id > 0) {
            $inser_data = array(
                'chat_id' => $chat_id,
                'to_id' => $to_id,
                'from_id' => $from_id,
                'message' => $message,
                'chat_type' => 'NC',
                'created_on' => date('Y-m-d H:i:s')
            );
            $this->model_message->insert('app_chat', $inser_data);
        }
        $folder_url = isset($this->login_type) && $this->login_type == 'V' ? 'vendor' : 'admin';
        redirect($folder_url . '/message/' . $to_id);
    }

    public function send_message() {
        $from_id = (int) $this->login_id;
        $to_id = (int) $this->input->post('msg_to_id');
        $message = $this->input->post('message');
        $chat_id = (int) $this->model_message->get_chat_id($from_id, $to_id);
        if (isset($chat_id) && $chat_id > 0) {
            $ins_time = date('Y-m-d H:i:s');
            $inser_data = array(
                'chat_id' => $chat_id,
                'to_id' => $to_id,
                'from_id' => $from_id,
                'message' => $message,
                'chat_type' => 'NC',
                'created_on' => $ins_time
            );
            $ins_id = $this->model_message->insert('app_chat', $inser_data);
            echo $this->_get_chats_messages($chat_id);
        }
        exit;
    }

    public function ajax_get_chats_messages() {
        /* Posting */
        $chat_id = $this->input->post('chat_id');

        echo $this->_get_chats_messages($chat_id);
    }

    public function _get_chats_messages($chat_id) {
        $last_chat_message_id = (int) $this->session->userdata('last_chat_message_id_' . $chat_id);

//        echo $this->session->unset_userdata('last_chat_message_id_' . $chat_id);
//        exit;
        /* Executing the method on model */
        $chats_messages = $this->model_message->get_chats_messages($chat_id, $last_chat_message_id);

        if ($chats_messages->num_rows() > 0) {
            $base_url = base_url();

            /* Store the last chat message id */
            $last_chat_message_id = $chats_messages->row($chats_messages->num_rows() - 1)->id;

            $this->session->set_userdata('last_chat_message_id_' . $chat_id, $last_chat_message_id);

            // return the messages
            $chats_messages_html = "";
            $j = 0;
            foreach ($chats_messages->result() as $chats_messages) {
//                $record = $this->db->get_where('app_admin', ['id' => $chats_messages->user_id])->row_array();
//                $avatar = $record['avatar'];

                $li_class = ($this->login_id == $chats_messages->from_id) && $chats_messages->chat_type == 'NC' ?
                        'class="by_current_user text-right"' : 'class="get_current_user"';

                if ($chats_messages->chat_type == 'C') {
                    $sender_name = $chats_messages->first_name;
                    if ($chats_messages->profile_image != "" && file_exists(FCPATH . "assets/uploads/profiles/" . $chats_messages->profile_image)) {
                        $avatar_img = base_url() . "assets/uploads/profiles/" . $chats_messages->profile_image;
                    } else {
                        $avatar_img = base_url() . "assets/images/user.png";
                    }
                    $append_content = '<p class="message_content">';
                    $append_content .= '<span class="chat-message">' . $chats_messages->message . "</span>";
                    $append_content .= '<img class="rounded-circle" src="' . $avatar_img . '"/> ';
                    $append_content .= '</p>';
                } else {
                    $sender_name = $chats_messages->aname;
                    if ($chats_messages->aprofile_image != "" && file_exists(FCPATH . "assets/uploads/profiles/" . $chats_messages->aprofile_image)) {
                        $avatar_img = base_url() . "assets/uploads/profiles/" . $chats_messages->aprofile_image;
                    } else {
                        $avatar_img = base_url() . "assets/images/user.png";
                    }
                    $append_content = '<p class="message_content">';
                    $append_content .= '<img class="rounded-circle" src="' . $avatar_img . '"/>';
                    $append_content .= '<span class="chat-message">' . $chats_messages->message . "</span>";
                    $append_content .= '</p>';
                }
                $deliver_check_icon = ($chats_messages->msg_read == 'Y') ? "<i class='fa pl-10 fa-check-circle text-info'></i>" : "<i class='fa pl-10 fa-check'></i>";
                $deliver_check_class = ($this->login_id == $chats_messages->from_id) && $chats_messages->chat_type == 'NC' ? $deliver_check_icon : "";

                $chats_messages_html .= '
                        <li ' . $li_class . '>'
                        . $append_content .
                        '<span class="chat_message_header"><i>'
                        . $chats_messages->timestamp
                        . ' by '
                        . $sender_name
                        . '</i>' . $deliver_check_class . '</span></li>';
                if ($j == 0) {
                    $notification_msg = strlen($chats_messages->message) > 100 ? substr($chats_messages->message, 0, 100) . '...' : $chats_messages->message;
                }
                $j++;
            }



            $result = [
                'status' => 'ok',
                'content' => $chats_messages_html,
                'last_chat_message_id' => $last_chat_message_id,
                'notification_msg' => isset($notification_msg) ? $notification_msg : '',
                'total_messages' => $j,
            ];

            return json_encode($result);
            exit();
        } else {
            $result = [
                'status' => 'ok',
                'content' => '',
                'last_chat_message_id' => $last_chat_message_id
            ];

            return json_encode($result);
            exit();
        }
    }

}

?>