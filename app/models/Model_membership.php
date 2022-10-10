<?php

class Model_membership extends CI_Model {

    private $primary_key;
    private $main_table;
    public $errorCode;
    public $errorMessage;

    public function __construct() {
        parent::__construct();
        $this->main_table = "app_membership_history";
        $this->primary_key = "id";
    }
    function get_package_history($vendor_id = NULL, $package_id = NULL) {
        $this->db->select('app_package.*,app_membership_history.id as app_membership_id,app_membership_history.membership_till,app_membership_history.remaining_event,app_membership_history.status as membership_status,app_admin.first_name,app_admin.last_name');
        $this->db->join('app_package', 'app_package.id=app_membership_history.package_id', 'left');
        $this->db->join('app_admin', 'app_admin.id=app_membership_history.customer_id', 'left');
        $this->db->order_by('app_membership_history.created_on DESC');
        if (!is_null($vendor_id)) {
            $this->db->where('customer_id', $vendor_id);
        }
        if (!is_null($package_id)) {
            $this->db->where('package_id', $package_id);
        }
        $res = $this->db->get($this->main_table)->result_array();
        return $res;
    }

    function get_package($id = NULL) {
        $this->db->select('*');
        $this->db->where('status', 'A');
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }
        $res = $this->db->get('app_package')->result_array();
        return $res;
    }

    function get_package_price($id) {
        $this->db->select('price');
        $this->db->where('id', $id);
        $res = $this->db->get('app_package')->result_array();
        return isset($res) && count($res) > 0 ? $res[0]['price'] : '';
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

}

?>