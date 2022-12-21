<?php

class Model_dashboard extends CI_Model {

    private $primary_key;
    private $main_table;
    public $errorCode;
    public $errorMessage;

    public function __construct() {
        parent::__construct();
        $this->main_table = "app_users";
        $this->primary_key = "id";
    }

    function vendor_appointment_payment($transfer_status, $vendor_id = NULL, $admin_id = NULL) {
        $this->db->select('SUM(payment_price) AS total');
        if (!is_null($admin_id)) {
            $this->db->where("vendor_id != '$admin_id'");
        }
        if (!is_null($vendor_id)) {
            $this->db->where("vendor_id='$vendor_id'");
        }
        $this->db->where("transfer_status='$transfer_status'");
        $res = $this->db->get('app_appointment_payment')->result_array();
        return isset($res) && count($res) > 0 && (int) $res[0]['total'] > 0 ? $res[0]['total'] : 0;
    }

    function vendor_total_appointment($vendor_id) {
        $this->db->select('app_event_book.id');
        $this->db->join('app_event', 'app_event.id=app_event_book.event_id', 'left');
        $this->db->where("app_event.created_by=" . $vendor_id . " AND app_event.type='S'");
        $res = $this->db->get('app_event_book')->result_array();
        return count($res);
    }

    function total_my_wallet($login_id) {
        $this->db->select('my_wallet as total');
        $this->db->where('id', $login_id);
        $res = $this->db->get('app_users')->result_array();
        return isset($res) && count($res) > 0 && (int) $res[0]['total'] > 0 ? $res[0]['total'] : 0;
    }

    function check_payment() {
        $this->db->select('id');
        $res = $this->db->where("on_cash='Y' OR stripe='Y' OR paypal='Y' OR 2checkout='Y'")->get('app_payment_setting')->result_array();
        return count($res);
    }

    function get_vendor_payment($login_id = NULL) {
        $this->db->select('SUM(app_event.price) as total');
        $this->db->join('app_event', 'app_event.id=app_event_book.event_id', 'left');
        if (!is_null($login_id)) {
            $this->db->where("app_event.created_by != '$login_id'");
        }
        $res = $this->db->get('app_event_book')->result_array();
        return isset($res) && count($res) > 0 && $res[0]['total'] != '' ? $res[0]['total'] : 0;
    }

    function insert($tbl = '', $data = array()) {
        if ($tbl == '') {
            $tbl = $this->main_table;
        }

        $this->db->insert($tbl, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function delete($table, $id) {
        $this->db->where('id', $id);
        $del = $this->db->delete($table);
        return $del;
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