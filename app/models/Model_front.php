<?php

class Model_front extends CI_Model {

    private $primary_key;
    private $main_table;
    public $errorCode;
    public $errorMessage;

    public function __construct() {
        parent::__construct();
        $this->main_table = "app_admin";
        $this->primary_key = "id";
        $this->Per_Page = get_site_setting('display_record_per_page');
    }

    function msg_vendor_data($vendor_id) {
        $this->db->select('profile_image,first_name,last_name,id');
        $this->db->where('id', $vendor_id);
        $res = $this->db->get('app_admin')->result_array();
        return $res;
    }

    function getSingleRow($tbl = '', $fields, $condition = '') {
        if ($fields == '') {
            $fields = "*";
        }
        $this->db->select($fields, FALSE);

        if ($tbl != '') {
            $this->db->from($tbl);
        } else {
            $this->db->from($this->main_table);
        }
        if (trim($condition) != '') {
            $this->db->where($condition, false, false);
        }
        $list_data = $this->db->get()->row_array();
        return $list_data;
    }

    function message_vendor_list($customer_id) {
        $this->db->select('app_chat_master.*,app_admin.profile_image,app_admin.first_name,app_admin.last_name,app_admin.id as vendor_id');
        $this->db->join('app_admin', 'app_admin.id=app_chat_master.vendor_id');
        $this->db->where('app_chat_master.customer_id', $customer_id);
        $res = $this->db->get('app_chat_master')->result_array();
        return $res;
    }

    function msg_group_list($vendor_id, $customer_id) {
        $this->db->select('DATE(app_chat.created_on) as date,app_chat.chat_id');
        $this->db->join('app_chat_master', 'app_chat_master.id=app_chat.chat_id');
        $this->db->where("app_chat_master.vendor_id='$vendor_id' AND app_chat_master.customer_id='$customer_id'");
        $this->db->group_by("DATE(app_chat.created_on),`app_chat`.`chat_id`");
        $res = $this->db->get('app_chat')->result_array();
        return $res;
    }

    function get_chat_id($vendor_id, $customer_id) {
        $this->db->select('id');
        $this->db->where("vendor_id='$vendor_id' AND customer_id='$customer_id'");
        $res = $this->db->get('app_chat_master')->result_array();
        return isset($res) && count($res) > 0 ? $res[0]['id'] : 0;
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

    function getData($tbl = '', $fields, $condition = '', $join_ary = array(), $orderby = '', $groupby = '', $having = '', $climit = '', $paging_array = array(), $reply_msgs = '', $like = array(), $order = '', $start_limit = '', $sort_by = '') {

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
            $this->db->order_by($orderby, isset($order) && $order != '' ? $order : FALSE);
        }
        if (trim($climit) != '') {
            $this->db->limit($climit);
        }
        if (trim($start_limit) != '') {
            $length = $this->Per_Page;
            $this->db->limit($length, $start_limit);
        }
        if ($tbl != '') {
            $this->db->from($tbl);
        } else {
            $this->db->from($this->main_table);
        }
        if (is_array($like) && count($like) > 0) {
            $this->db->group_start();
            foreach ($like as $ky => $vl) {

                $this->db->or_like($vl['column'], $vl['pattern']);
            }
            $this->db->group_end();
        }
        if (isset($sort_by) && $sort_by != '') {
            if ($sort_by == "P") {
                $this->db->order_by("(SELECT COUNT(id) FROM app_event_book WHERE event_id = app_event.id)", "DESC");
            }
            if ($sort_by == "N") {
                $this->db->order_by("app_event.created_on", "DESC");
            }
            if ($sort_by == "H") {
                $this->db->order_by("app_event.price", "DESC");
            }
            if ($sort_by == "L") {
                $this->db->order_by("app_event.price", "ASC");
            }
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

    function get_row_result($cond = array(), $tbl_name = '') {
        if (!empty($cond)) {
            $this->db->where($cond);
        }
        $query = $this->db->get($tbl_name);
        $res = $query->row_array();
        if (isset($res) && !empty($res)) {
            return $res;
        } else {
            return FALSE;
        }
    }

    public function get_chats_messages($chat_id, $last_chat_message_id = 0) {
        $query = "SELECT
                     DISTINCT(cm.id),
                     cm.msg_read,
                    cm.chat_type,
                    cm.from_id,
                    cm.message,
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
        return $result;
    }

    function insertBatch($tbl = '', $data = array()) {
        if ($tbl == '') {
            $tbl = $this->main_table;
        }
        $res = $this->db->insert_batch($tbl, $data);
        $rs = $this->db->affected_rows();
        return $rs;
    }

    function Totalcount($table, $condition = '') {
        $this->db->select('*');
        if (trim($condition) != '') {
            $this->db->where($condition, false, false);
        }
        $total = $this->db->get($table)->num_rows();
        return $total;
    }

}

?>