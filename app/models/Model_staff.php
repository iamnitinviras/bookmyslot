<?php

class Model_staff extends CI_Model {

    private $primary_key;
    private $main_table;
    public $errorCode;
    public $errorMessage;

    public function __construct() {
        parent::__construct();
        $this->main_table = "app_admin";
        $this->primary_key = "id";
    }

    public function authenticate($username, $password) {
        $ext = 'password = "' . md5($password) . '" AND  email = ' . $username . ' AND type="S"';
        $this->db->select('*');
        $this->db->from($this->main_table);
        $this->db->where($ext);
        $result = $this->db->get();
        $record = $result->result_array();
        
        if (is_array($record) && count($record) > 0) {
            if (isset($record) && $record[0]['status'] == 'A') {
                $this->session->set_userdata("staff_id", $record[0]["id"]);
                $this->session->set_userdata("type", $record[0]["type"]);
                $this->session->set_userdata("DefaultPassword", $record[0]["default_password_changed"]);
                $this->errorCode = 1;
            } else {
                $this->errorCode = 0;
                $this->errorMessage = translate('login_failure');
            }
        } else {
            $this->errorCode = 0;
            $this->errorMessage = translate('login_failure');
        }
        $error['errorCode'] = $this->errorCode;
        $error['errorMessage'] = $this->errorMessage;
        return $error;
    }

    function check_username($username) {
        $this->db->where('email', $username);
        $this->db->where('type', 'S');
        $this->db->from($this->main_table);
        $list_data = $this->db->get()->row_array();
        if (is_array($list_data) && count($list_data) > 0) {
            $this->errorCode = 1;
            $this->errorMessage = translate('forgot_success');
        } else {
            $this->errorCode = 0;
            $this->errorMessage = translate('forgot_failure');
        }
        $error['id'] = $list_data['id'];
        $error['first_name'] = $list_data['first_name'];
        $error['last_name'] = $list_data['last_name'];
        $error['email'] = $list_data['email'];
        $error['errorCode'] = $this->errorCode;
        $error['errorMessage'] = $this->errorMessage;
        return $error;
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

}

?>