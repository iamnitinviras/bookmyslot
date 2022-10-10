<?php

/* Create MY_Form_validation.php  in ci_root/application/libraries */

class MY_Form_validation extends CI_Form_validation {

    public $CI;

    public function is_unique($str, $field) {

        if (substr_count($field, '.') == 3) {
            list($table, $field, $id_field, $id_val) = explode('.', $field);
            $query = $this->CI->db->limit(1)->where($field, $str)->where($id_field . ' != ', $id_val)->get($table);
           
        } else {
            list($table, $field) = explode('.', $field);
            $query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
        }

        return $query->num_rows() === 0;
    }

    function add_to_error_array($field = '', $message = '') {
        if (!isset($this->_error_array[$field])) {
            $this->_error_array[$field] = $message;
        }

        return;
    }

    /**
     * Error Array
     *
     * Returns the error messages as an array
     *
     * @return  array
     */
    function error_array() {
        if (count($this->_error_array) === 0)
            return FALSE;
        else
            return $this->_error_array;
    }

}

?>