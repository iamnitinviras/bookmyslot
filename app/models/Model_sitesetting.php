<?php

class Model_sitesetting extends CI_Model {

    public function insert($data) {
        $this->db->insert('app_site_setting', $data);
        $id = $this->db->insert_ID();
        return $id;
    }

    public function insert_data($tbl, $data) {
        $this->db->insert($tbl, $data);
        $id = $this->db->insert_ID();
        return $id;
    }

    public function edit($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('app_site_setting', $data);
        return true;
    }

    public function edit_data($tbl, $id, $data) {
        $this->db->where('id', $id);
        $this->db->update($tbl, $data);
    }

    public function edit_email($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('app_email_setting', $data);
    }

    public function get() {
        $this->db->where('id', 1);
        $query = $this->db->get('app_site_setting');
        return $query->result();
    }

    public function get_email() {
        $this->db->where('id', 1);
        $query = $this->db->get('app_email_setting');
        return $query->row();
    }

    function delete($tbl = '', $where = '') {
        if ($tbl == '') {
            $tbl = $this->main_table;
        }
        $this->db->where($where);
        $this->db->delete($tbl);
        return 'deleted';
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