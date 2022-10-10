<?php

class Model_message extends CI_Model {

    private $primary_key;
    private $main_table;
    public $errorCode;
    public $errorMessage;

    public function __construct() {
        parent::__construct();
        $this->main_table = "app_chat_master";
        $this->primary_key = "id";
    }

    function msg_customer_data($customer_id) {
        $this->db->select('profile_image,first_name,last_name,id');
        $this->db->where('id', $customer_id);
        $res = $this->db->get('app_customer')->result_array();
        return $res;
    }

    function message_customer_list($login_id) {
        $this->db->select('DISTINCT(app_chat.chat_id),app_chat_master.*,app_chat.msg_read,app_chat.to_id,app_chat.chat_id,app_customer.profile_image,app_customer.first_name,app_customer.last_name,app_customer.id as vendor_id');
        $this->db->join('app_customer', 'app_customer.id=app_chat_master.customer_id');
        $this->db->join('app_chat', 'app_chat.chat_id=app_chat_master.id');
        $this->db->where("app_chat.to_id='$login_id' AND chat_type='C'");
        $this->db->order_by('app_chat.msg_read desc');
        //$this->db->group_by('app_chat.chat_id');
        $res = $this->db->get('app_chat_master')->result_array();
        return $res;
    }

    function msg_group_list($vendor_id, $customer_id) {
        $this->db->select('DATE(app_chat.created_on) as date,app_chat.chat_id');
        $this->db->join('app_chat_master', 'app_chat_master.id=app_chat.chat_id');
        $this->db->where("app_chat_master.vendor_id='$vendor_id' AND app_chat_master.customer_id='$customer_id'");
        $this->db->group_by("DATE(app_chat.created_on),app_chat.chat_id");
        $res = $this->db->get('app_chat')->result_array();
        return $res;
    }

    function insert($tbl = '', $data = array()) {
        if ($tbl == '') {
            $tbl = $this->main_table;
        }

        $this->db->insert($tbl, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update($tbl = '', $data = array(), $where = '') {
        if ($tbl == '') {
            $tbl = $this->main_table;
        }
        $this->db->where($where, false, false);
        $res = $this->db->update($tbl, $data);
        $rs = $this->db->affected_rows();
        return $rs;
    }

    function updateBatch($tbl = '', $data = array()) {
        if ($tbl == '') {
            $tbl = $this->main_table;
        }
        $this->db->where();
        $res = $this->db->update_batch($tbl, $data);
        $rs = $this->db->affected_rows();
        return $rs;
    }

    function getData($tbl = '', $fields, $condition = '', $join_ary = array(), $orderby = '', $groupby = '', $having = '', $climit = '', $paging_array = array(), $reply_msgs = '', $like = array()) {

        if ($fields == '') {
            $fields = "*";
        }

        $this->db->select($fields, false);

        if (is_array($join_ary) && count($join_ary) > 0) {
            foreach ($join_ary as $ky => $vl) {
                $this->db->join($vl['table'], $vl['condition'], $vl['jointype']);
            }
        }

        if (trim($condition) != '') {
            $this->db->where($condition, false, false);
        }
        if (trim($groupby) != '') {
            $this->db->group_by($groupby);
        }
        if (trim($having) != '') {
            $this->db->having($having, false);
        }
        if ($orderby != '' && is_array($paging_array) && count($paging_array) == "0") {
            $this->db->order_by($orderby, false);
        }
        if (trim($climit) != '') {
            $this->db->limit($climit);
        }
        if ($tbl != '') {
            $this->db->from($tbl);
        } else {
            $this->db->from($this->main_table);
        }
        $list_data = $this->db->get()->result_array();
        return $list_data;
    }

    function delete($tbl = '', $where = '') {
        if ($tbl == '') {
            $tbl = $this->main_table;
        }
        $this->db->where($where);
        $this->db->delete($tbl);
        return 'deleted';
    }

    function get_latest_messages($cond) {
        $this->db->select('*');
        $this->db->where($cond);
        $res = $this->db->get('app_chat')->result_array();
        return $res;
    }

    public function get_chats_messages($chat_id, $last_chat_message_id = 0) {
        $query = "SELECT
                    DISTINCT(cm.id),
                    cm.chat_type,
                    cm.from_id,
                    cm.message,
                      cm.msg_read,
                    DATE_FORMAT(cm.created_on, '%H:%i:%s') AS timestamp,
                    u.profile_image,
                    u.first_name,
                    u.last_name,
                    a.profile_image as aprofile_image,
                    a.first_name as aname,
                    a.last_name as alname
                FROM
                    app_chat AS cm
                LEFT JOIN
                    app_customer AS u
                ON
                    cm.from_id = u.id
                LEFT JOIN
                    app_admin AS a
                ON
                    cm.from_id = a.id
                    
                WHERE 
                    cm.chat_id = ?
                AND 
                    cm.id > ?
                ORDER BY
                    cm.id
                ASC";

        $result = $this->db->query($query, [$chat_id, $last_chat_message_id]);
//echo $this->db->last_query();
//exit;
        return $result;
    }

    function get_chat_id($vendor_id, $customer_id) {
        $this->db->select('id');
        $this->db->where("vendor_id='$vendor_id' AND customer_id='$customer_id'");
        $res = $this->db->get('app_chat_master')->result_array();
        return isset($res) && count($res) > 0 ? $res[0]['id'] : 0;
    }

}

?>