<?php

class Model_admin extends CI_Model {

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
        $ext = 'password = "' . md5($password) . '" AND  email = ' . $username . ' AND status="A" AND type="A"';
        $this->db->select('*');
        $this->db->from($this->main_table);
        $this->db->where($ext);
        $result = $this->db->get();
        $record = $result->result_array();
        if (is_array($record) && count($record) > 0) {
            $this->session->set_userdata("ADMIN_ID", $record[0]["id"]);
            $this->session->set_userdata("Type_Admin", $record[0]["type"]);
            $this->session->set_userdata("DefaultPassword", $record[0]["default_password_changed"]);
            $this->errorCode = 1;
        } else {
            $this->errorCode = 0;
            $this->errorMessage = translate('login_failure');
        }
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

    function getAdminDetail($id) {
        $this->db->select('*');
        $this->db->where('ID', $id);
        $array = $this->db->get($this->main_table)->result_array();
        return $array;
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

    function delete($tbl = '', $where = '') {
        if ($tbl == '') {
            $tbl = $this->main_table;
        }
        $this->db->where($where);
        $this->db->delete($tbl);
        return 'deleted';
    }

    public function checkReset($emailid = '') {
        $query = "SELECT iPublisherUserId,eStatus FROM publisher_users where vEmail= '$emailid'";
        $record = $this->db->query($query)->result_array();
        $result = $record[0];
        return $result;
    }

    function getCountryDetail($id = '') {
        $this->db->select('*');
        if ($id != '') {
            $this->db->where('Country_Id', $id);
        }
        $array = $this->db->get('country')->result_array();
        return $array;
    }

    function checkEmail($email = '', $phone = '') {
        $id = $this->session->userdata('iCompanyId');
        $query = "SELECT distinct(contact_book.iContactId) FROM contact_book_fields JOIN contact_book ON contact_book.iContactId = contact_book_fields.iContactId and contact_book.iCompanyId = '$id' WHERE ((contact_book_fields.vField = 'Email' AND contact_book_fields.vValue in $email) or contact_book.vEmail in $email)";
        if ($phone != '') {
            $query .= " OR ((contact_book_fields.vField = 'Phone' AND contact_book_fields.vValue in $phone))";
        }
        $result = $this->db->query($query)->result_array();
        if (count($result) > 0) {
            $result[0]['tot'] = count($result);
        } else {
            $result[0]['tot'] = 0;
        }
        return $result;
    }

    function query($sql, $type = "") {

        $data = $this->db->query($sql);
        if ($type == '')
            $data = $data->result_array();
        return $data;
    }

    function getRecordData($tbl = '', $id = '', $field = '') {
        $this->db->select('*');
        $this->db->from($tbl);
        if ($id != '') {
            $this->db->where($field, $id);
        }
        $data = $this->db->get()->result_array();
        return $data;
    }

    function getExportData($tbl, $fields, $join_ary, $cond) {
        $this->db->select($fields, false);
        $this->db->from($tbl);
        if (is_array($join_ary) && count($join_ary) > 0) {
            foreach ($join_ary as $ky => $vl) {
                $this->db->join($vl['tbl'], $vl['cond'], $vl['type']);
            }
        }
        $this->db->where($cond);
        $data = $this->db->get()->result_array();
        return $data;
    }

    function getCountry($fields, $condition) {
        $this->db->select($fields);
        $this->db->where($condition);
        $this->db->from("country");
        $this->db->stop_cache();
        $list_data = $this->db->get()->result_array();
        $this->db->flush_cache();

        return $list_data;
    }

    function getCurrency() {
        $response = array();
        $currencydata = array();
        $this->db->select('*');
        $this->db->where("eStatus = 'Active'");
        $this->db->from("country");
        $this->db->stop_cache();
        $list_data = $this->db->get()->result_array();
        $this->db->flush_cache();
        foreach ($list_data as $currency) {
            $currencyname = $currency['vCurrencyName'] . " (" . $currency['vCountry'] . ")";
            $currencydata['currencyid'] = $currency['iCountryId'];
            $currencydata['currencyname'] = $currencyname;
            $currencydata['currencycode'] = $currency['vCurrencyCode'];
            array_push($response, $currencydata);
        }

        return $response;
    }

    function getIdustry($fields, $condition) {
        $this->db->select($fields);
        $this->db->where($condition);
        $this->db->from("industry_master");
        $this->db->stop_cache();
        $list_data = $this->db->get()->result_array();
        $this->db->flush_cache();

        return $list_data;
    }

    function getDepartment($fields, $condition) {
        $this->db->select($fields);
        $this->db->where($condition);
        $this->db->from("department_master");
        $this->db->stop_cache();
        $list_data = $this->db->get()->result_array();
        $this->db->flush_cache();

        return $list_data;
    }

    function getFields($fields, $condition) {
        $this->db->select($fields);
        $this->db->where($condition);
        $this->db->from("fields_master");
        $this->db->stop_cache();
        $list_data = $this->db->get()->result_array();
        $this->db->flush_cache();

        return $list_data;
    }

    function getStages($fields, $condition) {
        $this->db->select($fields);
        $this->db->where($condition);
        $this->db->from("stage_master");
        $this->db->stop_cache();
        $list_data = $this->db->get()->result_array();
        $this->db->flush_cache();

        return $list_data;
    }

    function getVendorType($fields, $condition) {
        $this->db->select($fields);
        $this->db->where($condition);
        $this->db->from("vendor_type_master");
        $this->db->stop_cache();
        $list_data = $this->db->get()->result_array();
        $this->db->flush_cache();

        return $list_data;
    }

    function getVendorName($type) {
        $condition = 'iVendorTypeId = (SELECT iVendorTypeID FROM vendor_type_master WHERE vName = "' . $type . '") AND isDelete != "1" AND eStatus = "Active"';
        $this->db->select("iVendorId, vName");
        $this->db->where($condition);
        $this->db->from("vendor_master");
        $this->db->stop_cache();
        $list_data = $this->db->get()->result_array();
        $this->db->flush_cache();
        return $list_data;
    }

    function delete_fields($where = '') {
        if ($tbl == '') {
            $tbl = $this->main_table;
        }
        $this->db->where($where);
        $this->db->delete("custom_fields");
        return 'deleted';
    }

    function insert_fields($data = array()) {
        if ($tbl == '') {
            $tbl = $this->main_table;
        }

        $this->db->insert("custom_fields", $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function getTimeZone() {
        $this->db->select('*');
        $this->db->from("time_zone_master");
        $result = $this->db->get()->result_array();

        return $result;
    }

    function getCompanySettings($companyId, $tbl = "company_master") {
        $this->db->select("*");
        $this->db->from($tbl);
        $this->db->where('iCompanyId = ' . $companyId);
        $result = $this->db->get()->result_array();
        return $result;
    }

    function getShiftTiming($itemid, $itemtype) {
        $this->db->select('*');
        $this->db->from("shift_master");
        $this->db->where("iItemId", $itemid);
        $this->db->where("eItemType", $itemtype);
        $data = $this->db->get()->result_array();
        return $data;
    }

    function updateShiftTiming($data) {
        $this->db->where("vDays", $data['vDays']);
        $this->db->where("eItemType", $data['eItemType']);
        $this->db->where("iItemId", $data['iItemId']);
        $res = $this->db->update("shift_master", $data);
        $rs = $this->db->affected_rows();
        return $rs;
    }

    function getOwnerList($id = '') {
        $this->db->select('user_master.iUserId, CONCAT(CONCAT(user_master.vSalutation," ",user_master.vFirstName," ",user_master.vMiddleName," ",vLastName)," - ",user_role_master.vRole) as ownername', false);
        $this->db->from("user_master");
        $this->db->join('user_role_master', 'user_role_master.iUserRoleId = user_master.iUserRoleId', 'inner');
        $this->db->where("user_master.iCompanyId", $this->config->item('CRM_USER_COMPANY_ID'));
        $this->db->where("user_master.eStatus", "Active");
        $this->db->where("user_master.isDelete", "0");
        if ($id != "") {
            $this->db->where("user_master.iUserId != ", $id);
        }
        $data = $this->db->get()->result_array();
        return $data;
    }

    function checkDataExist($tbl) {
        if ($tbl == "activities") {
            // Task Master
            $this->db->select("count(*) as taskcount");
            $this->db->from("task_master");
            if (strtolower($this->config->item('CRM_USER_PROFILE')) == "administration") {
                $this->db->where("iCompanyId", $this->config->item('CRM_USER_COMPANY_ID'));
            } else {
                $this->db->where("iAssignedId", $this->session->userdata('iUserId'));
            }
            $this->db->where("isDelete != ", 1);
            $result1 = $this->db->get()->result_array();
            $taskcount = $result1[0]['taskcount'];

            // Event Master
            $this->db->select("count(*) as eventcount");
            $this->db->from("event_master");
            if (strtolower($this->config->item('CRM_USER_PROFILE')) == "administration") {
                $this->db->where("iCompanyId", $this->config->item('CRM_USER_COMPANY_ID'));
            } else {
                $this->db->where("iAssignedId", $this->session->userdata('iUserId'));
            }
            $this->db->where("isDelete != ", 1);
            $result2 = $this->db->get()->result_array();
            $eventcount = $result2[0]['eventcount'];

            // Call Master
            $this->db->select("count(*) as callcount");
            $this->db->from("call_master");
            if (strtolower($this->config->item('CRM_USER_PROFILE')) == "administration") {
                $this->db->where("iCompanyId", $this->config->item('CRM_USER_COMPANY_ID'));
            } else {
                $this->db->where("iAssignedId", $this->session->userdata('iUserId'));
            }
            $this->db->where("isDelete != ", 1);
            $result3 = $this->db->get()->result_array();
            $callcount = $result3[0]['callcount'];

            if ($taskcount == 0 && $eventcount == 0 && $callcount == 0) {
                return false;
            } else {
                return true;
            }
        } else {
            $this->db->select("count(*) as count");
            $this->db->from($tbl);

            if (strtolower($this->config->item('CRM_USER_PROFILE')) == "administration" && $tbl != "activities") {
                $this->db->where("iCompanyId", $this->config->item('CRM_USER_COMPANY_ID'));
            } else if ($tbl == "user_master") {
                $this->db->where("iParentId", $this->session->userdata('iUserId'));
            } else {
                $this->db->where("iUserId", $this->session->userdata('iUserId'));
            }
            $this->db->where("isDelete != ", 1);

            $result = $this->db->get()->result_array();
            $count = $result[0]['count'];
            if ($count == 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    function package_order_list() {
        $orderListArr = array();
        $orderListArr[] = array('value' => 'all_packages', 'label' => 'All Packages');
        $fields = "*";
        $ext_cond = 'isDelete != "1" AND eStatus = "Active"';
        $packageType = $this->model_support->getData('package_type_master', $fields, array(), $ext_cond);
        foreach ($packageType as $type) {
            $label = $type['vPackageType'] . " Packages";
            $value = "package_type," . $type['iPackageTypeId'];
            $orderListArr[] = array('value' => $value, 'label' => $label);
        }
        $orderListArr[] = array('value' => 'my_packages', 'label' => 'My Packages');
        $orderListArr[] = array('value' => 'new_last_week_packages', 'label' => 'New Last Week');
        $orderListArr[] = array('value' => 'new_this_week_packages', 'label' => 'New This Week');
        $orderListArr[] = array('value' => 'recently_created_packages', 'label' => 'Recently Created Packages');
        $orderListArr[] = array('value' => 'recently_modified_packages', 'label' => 'Recently Modified Packages');
        return $orderListArr;
    }

    function quotation_order_list() {
        $orderListArr = array();
        $orderListArr[] = array('value' => 'all_quotations', 'label' => 'All Packages');
        $fields = "*";
        $ext_cond = 'isDelete != "1" AND eStatus = "Active"';
        $quotationType = $this->model_support->getData('package_type_master', $fields, array(), $ext_cond);
        foreach ($quotationType as $type) {
            $label = $type['vPackageType'] . " Packages";
            $value = "quotation_type," . $type['iPackageTypeId'];
            $orderListArr[] = array('value' => $value, 'label' => $label);
        }
        $orderListArr[] = array('value' => 'my_quotations', 'label' => 'My Packages');
        $orderListArr[] = array('value' => 'new_last_week_quotations', 'label' => 'New Last Week');
        $orderListArr[] = array('value' => 'new_this_week_quotations', 'label' => 'New This Week');
        $orderListArr[] = array('value' => 'recently_created_quotations', 'label' => 'Recently Created Packages');
        $orderListArr[] = array('value' => 'recently_modified_quotations', 'label' => 'Recently Modified Packages');
        return $orderListArr;
    }

    function insertActivity($data = array()) {
        //pr($data);
        $tbl = 'activity_master';

        $type = $data['eItemType'];
        $id = $data['iItemId'];

        $this->db->select('iActivityId', false);
        $this->db->from($tbl);
        $this->db->where('eItemType', $type);
        $this->db->where('iItemId', $id);
        $this->db->order_by('dtCreated ASC');
        $this->db->limit(1);

        $result = $this->db->get()->result_array();
        $upActivityId = $result[0]['iActivityId'];

        $data['iCompanyId'] = $this->config->item('CRM_USER_COMPANY_ID');
        $data['dtModify'] = date('Y-m-d H:i:s');
        $this->db->insert($tbl, $data);
        $insert_id = $this->db->insert_id();

        if ($upActivityId != "") {
            $upwhere = 'iActivityId = ' . $upActivityId;
            $updata['dtModify'] = date('Y-m-d H:i:s');
            $this->update($tbl, $updata, $upwhere);
        }
        return $insert_id;
    }

    function createActivityMessage($tbl, $idField, $id, $postArray) {

        $user_timezone = $this->config->item('CRM_USER_TIME_ZONE');

        switch ($tbl) {
            case 'lead_master':
                $module = "Lead";
                $exclude_fields = array('iLeadId', 'iCompanyId', 'eConverted', 'iConvertedAccountId', 'iConvertedContactId', 'iConvertedPotentialId', 'dtConvertedDate', 'iCreatedBy', 'dtCreated', 'iModifyBy', 'dtModify', 'isDelete', 'eStatus');
                $referenceField = array('iUserId' => 'user_master:iUserId:CONCAT(vSalutation," ",vFirstName," ",vMiddleName," ",vLastName)', 'iCountryId' => 'country:iCountryId:vCountry', 'iIndustryId' => 'industry_master:iIndustryId:vName');
                break;

            case 'account_master':
                $module = "Account";
                $exclude_fields = array('iAccountId', 'iCompanyId', 'iCreatedBy', 'dtCreated', 'iModifyBy', 'dtModify', 'isDelete', 'eStatus');
                $referenceField = array('iParentAccountId' => 'account_master:iAccountId:vName', 'iUserId' => 'user_master:iUserId:CONCAT(vSalutation," ",vFirstName," ",vMiddleName," ",vLastName)', 'iBillingCountryId' => 'country:iCountryId:vCountry', 'iShippingCountryId' => 'country:iCountryId:vCountry', 'iIndustryId' => 'industry_master:iIndustryId:vName');
                break;

            case 'contact_master':
                $module = "Contact";
                $exclude_fields = array('iContactId', 'iCompanyId', 'iCreatedBy', 'dtCreated', 'iModifyBy', 'dtModify', 'isDelete', 'eStatus');
                $referenceField = array('iVendorId' => 'vendor_master:iVendorId:vName', 'iAccountId' => 'account_master:iAccountId:vName', 'iUserId' => 'user_master:iUserId:CONCAT(vSalutation," ",vFirstName," ",vMiddleName," ",vLastName)', 'iCountryId' => 'country:iCountryId:vCountry', 'iIndustryId' => 'industry_master:iIndustryId:vName');
                break;

            case 'vendor_master':
                $module = "Vendor";
                $exclude_fields = array('iVendorId', 'iCompanyId', 'iCreatedBy', 'dtCreated', 'iModifyBy', 'dtModify', 'isDelete', 'eStatus');
                $referenceField = array('iPrimaryRepId' => 'vendor_representative:iRepId:vName', 'iUserId' => 'user_master:iUserId:CONCAT(vSalutation," ",vFirstName," ",vMiddleName," ",vLastName)', 'iCountryId' => 'country:iCountryId:vCountry', 'iIndustryId' => 'industry_master:iIndustryId:vName');
                break;

            case 'vendor_representative':
                $module = "Representative";
                $exclude_fields = array('iRepId', 'iCompanyId', 'iCreatedBy', 'dtCreated', 'iModifyBy', 'dtModify', 'isDelete', 'eStatus');
                $referenceField = array('iVendorId' => 'vendor_master:iVendorId:vName', 'iUserId' => 'user_master:iUserId:CONCAT(vSalutation," ",vFirstName," ",vMiddleName," ",vLastName)', 'iCountryId' => 'country:iCountryId:vCountry');
                break;

            case 'product_master':
                $module = "Product";
                $exclude_fields = array('iProductId', 'iCompanyId', 'iCreatedBy', 'dtCreated', 'iModifyBy', 'dtModify', 'isDelete', 'eStatus');
                $referenceField = array('iVendorId' => 'vendor_master:iVendorId:vName', 'iUserId' => 'user_master:iUserId:CONCAT(vSalutation," ",vFirstName," ",vMiddleName," ",vLastName)', 'iCountryId' => 'country:iCountryId:vCountry');
                break;

            case 'potential_master':
                $module = "Potential";
                $exclude_fields = array('iPotentialId', 'iCompanyId', 'iCreatedBy', 'dtCreated', 'iModifyBy', 'dtModify', 'isDelete', 'eStatus');
                $referenceField = array('iAccountId' => 'account_master:iAccountId:vName', 'iContactId' => 'contact_master:iContactId:CONCAT(vSalutation," ",vFirstName," ",vMiddleName," ",vLastName)', 'iStageId' => 'stage_master:iStageId:vName', 'iUserId' => 'user_master:iUserId:CONCAT(vSalutation," ",vFirstName," ",vMiddleName," ",vLastName)', 'iCountryId' => 'country:iCountryId:vCountry');
                break;

            case 'user_master':
                $module = "User";
                $exclude_fields = array('iUserId', 'iCompanyId', 'vPassword', 'iCreatedBy', 'dtCreated', 'iModifyBy', 'dtModify', 'isDelete', 'eStatus');
                $referenceField = array('iUserId' => 'account_master:iAccountId:vName', 'iUserRoleId' => 'user_role_master:iUserRoleId:vRole', 'iDepartmentId' => 'department_master:iDepartmentId:vDepartment', 'iCountryId' => 'country:iCountryId:vCountry');
                break;

            case 'task_master':
                $module = "Task";
                $name = "vSubject";
                $fid = 'iTaskId';
                $exclude_fields = array('iTaskId', 'iCompanyId', 'iCreatedBy', 'dtCreated', 'iModifyBy', 'dtModify', 'isDelete');
                $referenceField = array('iAssignedId' => 'user_master:iUserId:CONCAT(vSalutation," ",vFirstName," ",vMiddleName," ",vLastName)', 'iCountryId' => 'country:iCountryId:vCountry', 'iCallId' => 'dynamictable:eCallTo:name', 'iRelatedId' => 'dynamictable:eRelatedTo:name');
                $otherField = array('Lead' => 'lead_master:iLeadId:CONCAT(vFirstName," ",vLastName)', 'Contact' => 'contact_master:iContactId:CONCAT(vSalutation," ",vFirstName," ",vMiddleName," ",vLastName)', 'Account' => 'account_master:iAccountId:vName', 'Potential' => 'potential_master:iPotentialId:vName', 'Vendor' => 'vendor_master:iVendorId:vName', 'Representative' => 'vendor_representative:iRepId:vName', 'Product' => 'product_master:iProductId:vName', 'Case' => 'case_master:iCaseId:vSubject');
                break;

            case 'event_master':
                $module = "Event";
                $name = "vTitle";
                $fid = 'iEventId';
                $exclude_fields = array('iEventId', 'iCompanyId', 'iCreatedBy', 'dtCreated', 'iModifyBy', 'dtModify', 'isDelete');
                $referenceField = array('iAssignedId' => 'user_master:iUserId:CONCAT(vSalutation," ",vFirstName," ",vMiddleName," ",vLastName)', 'iCountryId' => 'country:iCountryId:vCountry', 'iCallId' => 'dynamictable:eCallTo:name', 'iRelatedId' => 'dynamictable:eRelatedTo:name');
                $otherField = array('Lead' => 'lead_master:iLeadId:CONCAT(vFirstName," ",vLastName)', 'Contact' => 'contact_master:iContactId:CONCAT(vSalutation," ",vFirstName," ",vMiddleName," ",vLastName)', 'Account' => 'account_master:iAccountId:vName', 'Potential' => 'potential_master:iPotentialId:vName', 'Vendor' => 'vendor_master:iVendorId:vName', 'Representative' => 'vendor_representative:iRepId:vName', 'Product' => 'product_master:iProductId:vName', 'Case' => 'case_master:iCaseId:vSubject');
                break;

            case 'call_master':
                $module = "Call";
                $name = "vSubject";
                $fid = 'iCallId';
                $exclude_fields = array('iCallId', 'iCompanyId', 'iCreatedBy', 'dtCreated', 'iModifyBy', 'dtModify', 'isDelete');
                $referenceField = array('iAssignedId' => 'user_master:iUserId:CONCAT(vSalutation," ",vFirstName," ",vMiddleName," ",vLastName)', 'iCountryId' => 'country:iCountryId:vCountry', 'iCallToId' => 'dynamictable:eCallTo:name', 'iRelatedId' => 'dynamictable:eRelatedTo:name');
                $otherField = array('Lead' => 'lead_master:iLeadId:CONCAT(vFirstName," ",vLastName)', 'Contact' => 'contact_master:iContactId:CONCAT(vSalutation," ",vFirstName," ",vMiddleName," ",vLastName)', 'Account' => 'account_master:iAccountId:vName', 'Potential' => 'potential_master:iPotentialId:vName', 'Vendor' => 'vendor_master:iVendorId:vName', 'Representative' => 'vendor_representative:iRepId:vName', 'Product' => 'product_master:iProductId:vName', 'Case' => 'case_master:iCaseId:vSubject');
                break;
            case 'route_detail':
                $module = "Transport";
                $name = "vRoute";
                $fid = 'iRouteId';
                $exclude_fields = array('iRouteId', 'iCompanyId', 'iCreatedBy', 'dtCreated', 'iModifyBy', 'dtModify', 'isDelete');
                $referenceField = array('iCabId' => 'cab_master:iCabId:vName', 'iCountryId' => 'country:iCountryId:vCountry', 'iCurrencyId' => 'country:iCountryId:vCurrencyName');
                break;
            default:
                break;
        }



        $result = $this->db->query("SELECT * FROM " . $tbl . " WHERE " . $idField . " = " . $id)->result_array();
        $result1 = $result[0];
        $message = "";
        foreach ($result1 as $key => $value) {
            if ($tbl == 'product_master' && $postArray['iPrimaryRepId'] != '' && $j == 0) {
                $repassociationquery = 'SELECT group_concat(pra.iRepId) as rep_list_id,group_concat((SELECT vName FROM vendor_representative as vr WHERE vr.iRepId = pra.iRepId)) as rep_list from product_representative_association as pra WHERE iProductId ="' . $id . '"';
                $replist = $this->db->query($repassociationquery)->result_array();
                $fromreplistid = $replist[0]['rep_list_id'];
                $fromreplist = $replist[0]['rep_list'];
                $fromreplistidArr = explode(",", $fromreplistid);

                if (count($fromreplistidArr) > count($postArray['iPrimaryRepId'])) {
                    $arraydiff = array_diff($fromreplistidArr, $postArray['iPrimaryRepId']);
                } else {
                    $arraydiff = array_diff($postArray['iPrimaryRepId'], $fromreplistidArr);
                }

                if (count($arraydiff) > 0) {

                    for ($v = 0; $v < count($postArray['iPrimaryRepId']); $v++) {
                        $repid = $postArray['iPrimaryRepId'][$v];
                        $repquery = 'SELECT vName FROM vendor_representative as vr WHERE vr.iRepId ="' . $repid . '"';
                        $replist1 = $this->db->query($repquery)->result_array();
                        $toreplistArr[] = $replist1[0]['vName'];
                    }
                    $toreplist = implode(",", $toreplistArr);

                    $message .= 'Primary Representative from ' . $fromreplist . ' to ' . $toreplist . '.<br/>';
                }
            }


            if (array_key_exists($key, $postArray) && !in_array($key, $exclude_fields)) {
                $postArray[$key] = str_replace(' 00:00:00', '', $postArray[$key]);
                $value = str_replace(' 00:00:00', '', $value);

                if (strtolower($value) == 'none') {
                    $value = '';
                }
                if (strtolower($postArray[$key]) == 'none') {
                    $postArray[$key] = '';
                }

                $prefix = substr($key, 0, 2);

                if ($prefix == 'dt') {
                    $postdateArr = explode(' ', $postArray[$key]);
                    $dbdateArr = explode(' ', $value);

                    if (!isset($postdateArr[1])) {
                        $postArray[$key] = $postdateArr[0];
                        $value = $dbdateArr[0];
                    }
                }

                $tofield = '';

                if (($key == "iCallId" || $key == "iCallToId" || $key == "iRelatedId") && ($postArray[$key] == $value)) {
                    if ($key == "iCallId" || $key == "iCallToId") {
                        $tofield = "eCallTo";
                    } else if ($key == "iRelatedId") {
                        $tofield = "eRelatedTo";
                    }
                }

                if (($postArray[$key] != $value) || ($tofield != "" && $postArray[$tofield] != $result1[$tofield])) {

                    if (trim($value) != '' && trim($value) != '0000-00-00' && trim($value) != '0000-00-00 00:00:00' && trim($value) != '0') {
                        $fromvalue = $value;
                    } else {
                        $fromvalue = 'blank value';
                    }

                    $tovalue = $postArray[$key];

                    if (array_key_exists($key, $referenceField)) {
                        $referenceFieldArray = explode(":", $referenceField[$key]);
                        $fromtable = $fromtable1 = $totable = $referenceFieldArray[0];
                        $fromfetchId = $tofetchId = $referenceFieldArray[1];
                        $fromfetchField = $tofetchField = $referenceFieldArray[2];

                        if ($fromtable == "dynamictable") {

                            $fromtype = $result1[$fromfetchId];  // "Lead";
                            $totype = $postArray[$fromfetchId]; // 'none'

                            if ($fromtype != '') {
                                $type = $fromtype;
                            } else if (strtolower($totype) != 'none') {
                                $type = $totype;
                            }

                            if ($type == '') {
                                $value = 0;
                                $postArray[$key] = 0;
                            }
                            $referenceFieldArrayFrom = explode(":", $otherField[$fromtype]);
                            $referenceFieldArrayTo = explode(":", $otherField[$totype]);
                            $fromtable = $referenceFieldArrayFrom[0];
                            $fromfetchId = $referenceFieldArrayFrom[1];
                            $fromfetchField = $referenceFieldArrayFrom[2];

                            $totable = $referenceFieldArrayTo[0];
                            $tofetchId = $referenceFieldArrayTo[1];
                            $tofetchField = $referenceFieldArrayTo[2];
                        }

                        if ($key == 'iAssignedId' || $fromtable1 == "dynamictable") {
                            $addString = "added";
                            if ($key == 'iAssignedId') {
                                $fromtype = $totype = "User";
                                $addString = "assigned";
                            }
                            if ($value != 0) {
                                $addActivity['iUserId'] = $this->session->userdata('iUserId');
                                $addActivity['iItemId'] = $value;
                                $addActivity['eItemType'] = $fromtype;
                                $addActivity['eItemUrl'] = strtolower($module) . '_view?id=' . urlencode($this->general->encryptData($result1[$fid])) . '&m=' . urlencode($this->general->encryptData('view')) . '&f=' . urlencode($this->general->encryptData('activities'));
                                $addActivity['tMessage'] = 'The ' . $module . ' - (' . $result1[$name] . ') has been removed.';
                                $addActivity['dtCreated'] = date('Y-m-d H:i:s');
                                $this->insertActivity($addActivity);
                                $modify['dtModify'] = date('Y-m-d H:i:s');
                                $modify['iModifyBy'] = $this->session->userdata('iUserId');
                                $this->update($fromtable, $modify, $fromfetchId . " = " . $value);
                                unset($addActivity);
                            } else {
                                $fromvalue = 'blank value';
                            }

                            if ($postArray[$key] != 0) {
                                $addActivity['iUserId'] = $this->session->userdata('iUserId');
                                $addActivity['iItemId'] = $postArray[$key];
                                $addActivity['eItemType'] = $totype;
                                $addActivity['eItemUrl'] = strtolower($module) . '_view?id=' . urlencode($this->general->encryptData($result1[$fid])) . '&m=' . urlencode($this->general->encryptData('view')) . '&f=' . urlencode($this->general->encryptData('activities'));
                                $addActivity['tMessage'] = 'The new ' . $module . ' - (<a href="' . $addActivity['eItemUrl'] . '">' . $result1[$name] . '</a>) has been ' . $addString . '.';
                                $addActivity['tChange'] = $tbl . ':edit:' . $id;
                                $addActivity['dtCreated'] = date('Y-m-d H:i:s');
                                $this->insertActivity($addActivity);
                                $modify['dtModify'] = date('Y-m-d H:i:s');
                                $modify['iModifyBy'] = $this->session->userdata('iUserId');
                                $this->update($totable, $modify, $tofetchId . " = " . $postArray[$key]);
                                unset($addActivity);
                            } else {
                                $tovalue = 'blank value';
                            }
                        }
                        if ($value != 0) {
                            $fromData = $this->db->query("SELECT " . $fromfetchField . " FROM " . $fromtable . " WHERE " . $fromfetchId . " = " . $value)->result_array();
                            $fromvalue = $fromData[0][$fromfetchField];
                        } else {
                            $fromvalue = 'blank value';
                        }


                        if ($postArray[$key] != 0) {
                            $toData = $this->db->query("SELECT " . $tofetchField . " FROM " . $totable . " WHERE " . $tofetchId . " = " . $postArray[$key])->result_array();

                            $tovalue = $toData[0][$tofetchField];
                        } else {
                            $tovalue = 'blank value';
                        }
                    }


                    $prefix = substr($key, 0, 2);

                    if ($prefix == 'dt') {
                        if ($fromvalue != 'blank value' && $tovalue != '-0001-11-30') {
                            $dateArr['#from' . $key . '#'] = $fromvalue;
                            $dateArr['#to' . $key . '#'] = $tovalue;
                            $fromvalue = '#from' . $key . '#';
                            $tovalue = '#to' . $key . '#';
                        }
                        $skipCharNum = 2;
                    } else {
                        $skipCharNum = 1;
                    }

                    $field = $this->general->addSpaceInString(substr($key, $skipCharNum));
                    $originalArray = array('I ', ' Id');
                    $replaceArray = array('I', '');
                    $field = str_replace($originalArray, $replaceArray, $field);

                    if ($key == 'iTimeFormat') {
                        $fromvalue = $fromvalue . " Hours";
                        $tovalue = $tovalue . " Hours";
                    }

                    if ($key == "vCallDuration") {
                        $fromtimeAray = explode(":", $fromvalue);
                        $totimeAray = explode(":", $tovalue);

                        if ($fromtimeAray[1] != '00') {
                            $fromvalue = $fromtimeAray[1] . " Minutes";
                        } else {
                            $fromvalue = $fromtimeAray[2] . " Seconds";
                        }

                        if ($totimeAray[1] != '00') {
                            $tovalue = $totimeAray[1] . " Minutes";
                        } else {
                            $tovalue = $totimeAray[2] . " Seconds";
                        }
                    }
                    if (($fromvalue != 'blank value' || $tovalue != 'blank value') && ($fromvalue != 'blank value' || $tovalue != '-0001-11-30')) {
                        $field = ($key == 'iUserId') ? $module . " Owner" : $field;
                        if (trim($tovalue) == "") {
                            $tovalue = "blank value";
                        }
                        $message .= $field . ' from ' . $fromvalue . ' to ' . $tovalue . '.<br/>';
                    }
                }
            }
            $j++;
        }
        if ($message != '') {
            if (isset($dateArr) && count($dateArr) > 0) {
                $str = serialize($dateArr);
                $returnstring[] = "modified " . $message;
                $returnstring[] = $str;
            } else {
                $returnstring = "modified " . $message;
            }
        }
        return $returnstring;
    }

    function insertUpdateMessage($tbl, $id, $postArray, $actType, $ownername = '') {
        $callToField = array('Lead' => 'lead_master:iLeadId', 'Contact' => 'contact_master:iContactId');

        $relatedToField = array('Account' => 'account_master:iAccountId', 'Potential' => 'potential_master:iPotentialId', 'Vendor' => 'vendor_master:iVendorId', 'Representative' => 'vendor_representative:iRepId:vName', 'Product' => 'product_master:iProductId:vName', 'Case' => 'case_master:iCaseId');

        $tableField = array('task_master' => 'iTaskId:vSubject:Task', 'event_master' => 'iEventId:vTitle:Event', 'call_master' => 'iCallId:vSubject:Call');

        if (isset($postArray) && count($postArray) == 0) {
            $fields = "*";
            $tableString = $tableField[$tbl];
            $tableFieldArr = explode(":", $tableString);
            $idfield = $tableFieldArr[0];
            $ext_cond = $idfield . ' = "' . $id . '"';
            $reply = $this->getData($tbl, $fields, array(), $ext_cond);
            $postArray = $reply[0];
        }

        $callTo = $postArray['eCallTo'];
        $relatedTo = $postArray['eRelatedTo'];
        $relatedToId = $postArray['iRelatedId'];

        if ($tbl == "call_master") {
            $callToId = $postArray['iCallToId'];
        } else {
            $callToId = $postArray['iCallId'];
        }

        $tableString = $tableField[$tbl];
        $tableFieldArr = explode(":", $tableString);
        $title = $postArray[$tableFieldArr[1]];
        $module = $tableFieldArr[2];
        $view_url = strtolower($module) . '_view?id=' . urlencode($this->general->encryptData($id)) . '&m=' . urlencode($this->general->encryptData('view')) . '&f=' . urlencode($this->general->encryptData('activities'));

        if ($actType == "add") {
            $message = 'New ' . $module . ' - (<a href="' . $view_url . '">' . $title . '</a>) has been assigned.';
            $change = $tbl . ':add:' . $id;
            $itemUrl = strtolower($module) . '_add';
        } else if ($actType == "edit") {
            $message = 'Some fields has been updated in ' . $module . ' - (<a href="' . $view_url . '">' . $title . '</a>).';
            $change = $tbl . ':edit:' . $id;
            $itemUrl = '';
        } else if ($actType == "delete") {
            $message = 'The ' . $module . ' - (' . $title . ') has been deleted.';
            $change = $tbl . ':delete:' . $id;
            $itemUrl = strtolower($module) . '_delete';
        } else if ($actType == "owner" || $actType == "assignee") {
            $ownernameArr = explode(":", $ownername);
            $fromid = $ownernameArr[0];
            $toid = $ownernameArr[1];
            $fromname = $this->general->getFullName("User", $fromid);
            $toname = $this->general->getFullName("User", $toid);
            $message = 'The ' . $actType . ' has been changed from ' . $fromname . ' to ' . $toname . ' of ' . $module . ' - (<a href="' . $view_url . '">' . $title . '</a>).';
            $change = $tbl . ':owner:' . $id;
            $itemUrl = '';
        }

        $addActivity['iUserId'] = $this->session->userdata('iUserId');
        $addActivity['tChange'] = $change;
        $addActivity['tMessage'] = $message;
        $addActivity['eItemUrl'] = $itemUrl;
        $addActivity['dtCreated'] = date('Y-m-d H:i:s');

        if ($postArray['iAssignedId'] != 0) {

            $addActivity['iItemId'] = $postArray['iAssignedId'];
            $addActivity['eItemType'] = 'User';

            $updateField['dtModify'] = date('Y-m-d H:i:s');
            $ext_cond = 'iUserId = ' . $postArray['iAssignedId'];
            $this->update('user_master', $updateField, $ext_cond);

            $fields = "iActivityId, count(*) as count";
            $ext_cond = 'tChange = "' . $change . '" AND eItemType = "User" AND iItemId = ' . $postArray['iAssignedId'] . ' AND DATE(dtCreated) = "' . date('Y-m-d') . '"';
            $reply = $this->getData('activity_master', $fields, array(), $ext_cond);

            if ($actType != "edit") {
                $this->insertActivity($addActivity);
            } else if ($reply[0]['count'] == 0 && $actType == "edit") {
                $this->insertActivity($addActivity);
            } else {
                $actUpdateField['dtCreated'] = date('Y-m-d H:i:s');
                $ext_cond = 'iActivityId = ' . $reply[0]['iActivityId'];
                $this->update('activity_master', $actUpdateField, $ext_cond);
            }
        }

        if (array_key_exists($callTo, $callToField)) {
            $callToString = $callToField[$callTo];
            $callToArr = explode(":", $callToString);
            $callToTable = $callToArr[0];
            $callToIdField = $callToArr[1];
            $addActivity['iItemId'] = $callToId;
            $addActivity['eItemType'] = $callTo;

            if (strtolower($callToId) != "none" && $callToId != '' && $callToId != 0) {
                $updateField['dtModify'] = date('Y-m-d H:i:s');
                $ext_cond = $callToIdField . ' = ' . $callToId;
                $this->update($callToTable, $updateField, $ext_cond);

                $fields = "iActivityId, count(*) as count";
                $ext_cond = 'tChange = "' . $change . '" AND eItemType ="' . $callTo . '" AND iItemId = ' . $callToId . ' AND DATE(dtCreated) = "' . date('Y-m-d') . '"';
                $reply = $this->getData('activity_master', $fields, array(), $ext_cond);

                if ($actType != "edit") {
                    $this->insertActivity($addActivity);
                } else if ($reply[0]['count'] == 0 && $actType == "edit") {
                    $this->insertActivity($addActivity);
                } else {
                    $actUpdateField['dtCreated'] = date('Y-m-d H:i:s');
                    $ext_cond = 'iActivityId = ' . $reply[0]['iActivityId'];
                    $this->update('activity_master', $actUpdateField, $ext_cond);
                }
            }
        }

        if (array_key_exists($relatedTo, $relatedToField)) {
            $relatedToString = $relatedToField[$relatedTo];
            $relatedToArr = explode(":", $relatedToString);
            $relatedToTable = $relatedToArr[0];
            $relatedToIdField = $relatedToArr[1];

            $addActivity['iItemId'] = $relatedToId;
            $addActivity['eItemType'] = $relatedTo;

            if (strtolower($relatedToId) != "none" && $relatedToId != '' && $relatedToId != 0) {
                $updateField['dtModify'] = date('Y-m-d H:i:s');
                $ext_cond = $relatedToIdField . ' = ' . $relatedToId;
                $this->update($relatedToTable, $updateField, $ext_cond);

                $fields = "iActivityId, count(*) as count";
                $ext_cond = 'tChange = "' . $change . '" AND eItemType ="' . $relatedTo . '" AND iItemId = ' . $relatedToId . ' AND DATE(dtCreated) = "' . date('Y-m-d') . '"';
                $reply = $this->getData('activity_master', $fields, array(), $ext_cond);

                if ($actType != "edit") {
                    $this->insertActivity($addActivity);
                } else if ($reply[0]['count'] == 0 && $actType == "edit") {
                    $this->insertActivity($addActivity);
                } else {
                    $actUpdateField['dtCreated'] = date('Y-m-d H:i:s');
                    $ext_cond = 'iActivityId = ' . $reply[0]['iActivityId'];
                    $this->update('activity_master', $actUpdateField, $ext_cond);
                }
            }
        }
    }

    function insertNoteAttachmentActivity($subItemType, $subItemId, $itemType, $itemId, $actType) {

        if (strtolower($subItemType) == "note") {
            $fields = "*";
            $ext_cond = 'iNoteId ="' . $subItemId . '"';
            $reply = $this->getData('note_master', $fields, array(), $ext_cond);

            $itemType = $reply[0]['eItemType'];
            $itemId = $reply[0]['iItemId'];

            if ($actType == "add") {
                $type = "added";
            } else if ($actType == "delete") {
                $type = "removed";
            }

            if ($reply[0]['iThreadId'] != 0) {
                $fields = "parent.tDescription as parentnote, child.tDescription as childnote";
                $ext_cond = 'child.iNoteId ="' . $subItemId . '"';
                $join = array(array('table' => 'note_master as parent', 'condition' => 'child.iThreadId = parent.iNoteId', 'inner'));
                $reply1 = $this->getData('note_master as child', $fields, $join, $ext_cond);

                $message = "New Note - (" . $reply1[0]['childnote'] . ") has been " . $type . " under Note - (" . $reply1[0]['parentnote'] . ").";
            } else {
                $message = "New Note - (" . $reply[0]['tDescription'] . ") has been " . $type . ".";
            }
        }

        if (strtolower($subItemType) == "attachment") {

            $fields = "*";
            $ext_cond = 'iDocumentId ="' . $subItemId . '"';
            $reply = $this->getData('document_master', $fields, array(), $ext_cond);
            $itemType = $reply[0]['eItemType'];
            $itemId = $reply[0]['iItemId'];

            if ($actType == "add") {
                $type = "added";
            } else if ($actType == "delete") {
                $type = "removed";
            }

            $message = "New Attachment - (" . $reply[0]['vFileName'] . ") has been " . $type . ".";
        }

        if ($itemType == 'Representative') {
            $itemTable = 'vendor_representative';
            $itemIdField = 'iRepId';
        } else {
            $itemTable = strtolower($itemType) . '_master';
            $itemIdField = 'i' . $itemType . 'Id';
        }

        $updateField['dtModify'] = date('Y-m-d H:i:s');
        $ext_cond = $itemIdField . ' = ' . $itemId;
        $this->update($itemTable, $updateField, $ext_cond);

        // insert activity code
        $addActivity['iUserId'] = $this->session->userdata('iUserId');
        $addActivity['iItemId'] = $itemId;
        $addActivity['eItemType'] = $itemType;
        $addActivity['eChildItemType'] = $subItemType;
        $addActivity['iChildItemId'] = $subItemId;
        $addActivity['eItemUrl'] = strtolower($subItemType) . '_add';
        $addActivity['tMessage'] = $message;
        $addActivity['dtCreated'] = date('Y-m-d H:i:s');
        $this->insertActivity($addActivity);
        unset($addActivity);
    }

    function insertDeletedActivity($type, $typeId, $name, $childId = '', $childType = '') {
        $itemUrl = strtolower($type) . '_delete';
        $message = "The " . $type . " - (" . $name . ') has been deleted';

        $addActivity['iUserId'] = $this->session->userdata('iUserId');
        $addActivity['iItemId'] = $typeId;
        $addActivity['eItemType'] = $type;
        if ($childType != '') {
            $addActivity['eChildItemType'] = $childType;
            $addActivity['iChildItemId'] = $childId;
            $addActivity['eItemUrl'] = $childId;
            $itemUrl = strtolower($childType) . '_delete';
            $message = "The " . $childType . " - (" . $name . ') has been deleted';
        }
        $addActivity['eItemUrl'] = $itemUrl;
        $addActivity['tMessage'] = $message;
        $addActivity['dtCreated'] = date('Y-m-d H:i:s');
        $this->insertActivity($addActivity);
    }

    function deleteRelatedRecords($type, $id) {
        if (strtolower($type) == "lead" || strtolower($type) == "contact") {
            $activityWhere = 'eCallTo = "' . $type . '" AND iCallId = "' . $id . '"';
            $activityWhere1 = 'eCallTo = "' . $type . '" AND iCallToId = "' . $id . '"';
        } else {
            $activityWhere = $activityWhere1 = 'eRelatedTo = "' . $type . '" AND iRelatedId = "' . $id . '"';
        }

        $noteAttwhere = 'eItemType = "' . $type . '" AND iItemId = "' . $id . '"';
        $customFieldWhere = 'eType = "' . $type . '" AND iTypeId = "' . $id . '"';

        $datadel['isDelete'] = 1;
        $datadel['iDeleteBy'] = $this->session->userdata('iUserId');
        $datadel['iModifyBy'] = $this->session->userdata('iUserId');
        $datadel['dtModify'] = date('Y-m-d H:i:s');

        /* Custom Fields */
        $this->update('custom_fields', $datadel, $customFieldWhere);

        /* Task Master */
        $this->db->where($activityWhere);
        $tasks = $this->db->get('task_master')->result();
        foreach ($tasks as $t) {
            $this->insertUpdateMessage('task_master', $t->iTaskId, array(), 'delete');
        }
        $this->update('task_master', $datadel, $activityWhere);

        /* Event Master */
        $this->db->where($activityWhere);
        $events = $this->db->get('event_master')->result();
        foreach ($events as $e) {
            $this->insertUpdateMessage('event_master', $e->iEventId, array(), 'delete');
        }
        $this->update('event_master', $datadel, $activityWhere);

        /* Call Master */
        $this->db->where($activityWhere1);
        $calls = $this->db->get('call_master')->result();
        foreach ($calls as $c) {
            $this->insertUpdateMessage('call_master', $c->iCallId, array(), 'delete');
        }
        $this->update('call_master', $datadel, $activityWhere1);

        /* Document Master */
        $this->db->where($noteAttwhere);
        $documents = $this->db->get('document_master')->result();
        foreach ($documents as $d) {
            $this->insertDeletedActivity($type, $id, $d->vFileName, $d->iDocumentId, "Attachment");
        }
        $this->update('document_master', $datadel, $noteAttwhere);

        /* Note Master */
        $this->db->where($noteAttwhere);
        $notes = $this->db->get('note_master')->result();
        foreach ($notes as $n) {
            if ($n->iThreadId == 0) {
                $this->insertDeletedActivity($type, $id, $n->tDescription, $n->iNoteId, "Note");
            }
        }
        $this->update('note_master', $datadel, $noteAttwhere);

        switch ($type) {
            case "Lead":
                $tbl = "lead_master";
                $leadWhere = "iLeadId = " . $id;
                $this->db->where($leadWhere);
                $leadName = $this->general->getFullName("Lead", $id);
                $this->update($tbl, $datadel, $leadWhere);
                $this->insertDeletedActivity("Lead", $id, $leadName);
                break;
            case "Account":
                $tbl = "account_master";
                $accountWhere = "iAccountId = " . $id;
                $this->db->where($accountWhere);
                $accountname = trim($this->db->get($tbl)->row()->vName);

                $parentAccountwhere = "iParentAccountId = " . $id;
                $this->db->where($parentAccountwhere);
                $accountResult = $this->db->get($tbl)->result();

                foreach ($accountResult as $a) {
                    $this->deleteRelatedRecords("Account", $a->iAccountId);
                }
                $this->update($tbl, $datadel, $accountWhere);
                $this->insertDeletedActivity("Account", $id, $accountname);
                break;
            case "Contact":
                $tbl = "contact_master";
                $contactWhere = "iContactId = " . $id;
                $this->db->where($contactWhere);
                $contactName = $this->general->getFullName("Contact", $id);
                $this->update($tbl, $datadel, $contactWhere);
                $this->insertDeletedActivity("Contact", $id, $contactName);
                break;
            case "Potential":
                $tbl = "potential_master";
                $potentialWhere = "iPotentialId = " . $id;
                $this->db->where($potentialWhere);
                $potentialname = trim($this->db->get($tbl)->row()->vName);
                $this->update($tbl, $datadel, $potentialWhere);
                $this->insertDeletedActivity("Potential", $id, $potentialname);
                break;
            case "Product":
                $tbl = "product_master";
                $productWhere = "iProductId = " . $id;
                $this->db->where($productWhere);
                $this->db->delete('product_representative_association');
                $this->db->where($productWhere);
                $productname = trim($this->db->get($tbl)->row()->vName);
                $this->update($tbl, $datadel, $productWhere);
                $this->insertDeletedActivity("Product", $id, $productname);
                break;
            case "Representative":
                $tbl = "vendor_representative";
                $repWhere = "iRepId = " . $id;
                $this->db->where($repWhere);
                $repname = trim($this->db->get($tbl)->row()->vName);
                $this->update($tbl, $datadel, $repWhere);
                $this->insertDeletedActivity("Representative", $id, $repname);
                break;
            case "Vendor":
                $tbl = "vendor_master";
                $vendorWhere = "iVendorId = " . $id;
                $this->db->where($vendorWhere);

                $productWhere = "iVendorId = " . $id;
                $this->db->where($productWhere);
                $productResult = $this->db->get('product_master')->result();

                foreach ($productResult as $p) {
                    $this->deleteRelatedRecords("Product", $p->iProductId);
                }

                $repWhere = "iVendorId = " . $id;
                $this->db->where($repWhere);
                $repResult = $this->db->get('vendor_representative')->result();

                foreach ($repResult as $r) {
                    $this->deleteRelatedRecords("Representative", $r->iRepId);
                }

                $vendorname = trim($this->db->get($tbl)->row()->vName);
                $this->update($tbl, $datadel, $vendorWhere);
                $this->insertDeletedActivity("Vendor", $id, $vendorname);
                break;
            case "Case":
                $tbl = "case_master";
                break;
            case "PackageType":
                $tbl = "package_type_master";
                $packageTypeWhere = "iPackageTypeId = " . $id;
                $this->db->where($packageTypeWhere);
                $packageTypename = trim($this->db->get($tbl)->row()->vPackageType);
                $this->update($tbl, $datadel, $packageTypeWhere);
                $this->insertDeletedActivity("PackageType", $id, $packageTypename);
                break;
            case "Transport":
                $tbl = "cab_master";
                $transportWhere = "iCabId = " . $id;
                $this->db->where($transportWhere);
                $transportname = trim($this->db->get($tbl)->row()->vName);
                $this->update($tbl, $datadel, $transportWhere);
                $this->insertDeletedActivity("Transport", $id, $transportname);
                break;
            case "Mealplan":
                $tbl = "mealplan_master";
                $mealWhere = "iMealplanId = " . $id;
                $this->db->where($mealWhere);
                $mealname = trim($this->db->get($tbl)->row()->vName);
                $this->update($tbl, $datadel, $mealWhere);
                $this->insertDeletedActivity("Mealplan", $id, $mealname);
                break;
        }
    }

    function getSelectedValues($moduleid, $moduletype) {
        if (strtolower($moduletype) == "lead") {
            $callId = $this->general->decryptData($moduleid);
            $callTo = ucfirst($moduletype);
        } else if (strtolower($moduletype) == "contact") {
            $callId = $this->general->decryptData($moduleid);
            $callTo = ucfirst($moduletype);

            $fields = "iAccountId,iVendorId";
            $ext_cond = 'iContactId ="' . $callId . '"';
            $reply = $this->getData('contact_master', $fields, array(), $ext_cond);
            $iAccountId = $reply[0]['iAccountId'];
            $iVendorId = $reply[0]['iVendorId'];
            if ($iAccountId != 0) {
                $relatedTo = "Account";
                $relatedId = $iAccountId;
            } else if ($iVendorId != 0) {
                $relatedTo = "Vendor";
                $relatedId = $iVendorId;
            }
        } else if (strtolower($moduletype) == "account" || strtolower($moduletype) == "potential" || strtolower($moduletype) == "vendor" || strtolower($moduletype) == "representative" || strtolower($moduletype) == "product" || strtolower($moduletype) == "case") {
            $callTo = "Contact";
            $relatedId = $this->general->decryptData($moduleid);
            $relatedTo = ucfirst($moduletype);

            if (strtolower($moduletype) == "account") {
                $fields = "iContactId";
                $ext_cond = 'iAccountId ="' . $relatedId . '"';
                $contactData = $this->getData('contact_master', $fields, array(), $ext_cond);
                $contactCount = count($contactData);
                if ($contactCount == 1 || $contactCount > 1) {
                    $callId = $contactData[0]['iContactId'];
                }
            } else if (strtolower($moduletype) == "potential") {
                $fields = "iContactId";
                $ext_cond = 'iPotentialId ="' . $relatedId . '"';
                $contactData = $this->getData('potential_master', $fields, array(), $ext_cond);
                $contactCount = count($contactData);
                if ($contactCount == 1 || $contactCount > 1) {
                    $callId = $contactData[0]['iContactId'];
                }
            } else if (strtolower($moduletype) == "vendor") {
                $fields = "iContactId";
                $ext_cond = 'iVendorId ="' . $relatedId . '"';
                $contactData = $this->getData('contact_master', $fields, array(), $ext_cond);
                $contactCount = count($contactData);
                if ($contactCount == 1 || $contactCount > 1) {
                    $callId = $contactData[0]['iContactId'];
                }
            } else if (strtolower($moduletype) == "representative" || strtolower($moduletype) == "product") {
                $callId = 0;
            }
        }

        $data['eCallTo'] = $callTo;
        $data['iCallId'] = $callId;
        $data['eRelatedTo'] = $relatedTo;
        $data['iRelatedId'] = $relatedId;
        return $data;
    }

    function checkUserRole($roleId) {
        $this->db->select("count(*) as rolecount");
        $this->db->from("publisher_users");
        $this->db->where("iPublisherRoleId", $roleId);
        $this->db->where("isDelete != ", 1);
        $result = $this->db->get()->result_array();
        $count = $result[0]['rolecount'];
        return $count;
    }

    function insertChangeOwnerActivity($module, $id, $name, $ownerids, $childId = '', $childType = '') {
        $itemUrl = '';
        $view_url = strtolower($module) . '_view?id=' . urlencode($this->general->encryptData($id)) . '&m=' . urlencode($this->general->encryptData('view')) . '&f=' . urlencode($this->general->encryptData('activities'));

        $ownernameArr = explode(":", $ownerids);
        $fromid = $ownernameArr[0];
        $toid = $ownernameArr[1];
        $fromname = $this->general->getFullName("User", $fromid);
        $toname = $this->general->getFullName("User", $toid);

        $message = 'The owner has been changed from ' . $fromname . ' to ' . $toname . ' of ' . $module . ' - (<a href="' . $view_url . '">' . $name . '</a>).';

        $addActivity['iUserId'] = $this->session->userdata('iUserId');
        $addActivity['iItemId'] = $id;
        $addActivity['eItemType'] = $module;
        if ($childType != '') {
            $addActivity['eChildItemType'] = $childType;
            $addActivity['iChildItemId'] = $childId;
            $addActivity['eItemUrl'] = $childId;
            $itemUrl = strtolower($childType) . '_delete';
            $message = 'The owner has been changed from ' . $fromname . ' to ' . $toname . ' of ' . $module . ' - (<a href="' . $view_url . '">' . $name . '</a>).';
        }
        $addActivity['eItemUrl'] = $itemUrl;
        $addActivity['tMessage'] = $message;
        $addActivity['dtCreated'] = date('Y-m-d H:i:s');
        $this->insertActivity($addActivity);
    }

    function changeOwnerAllModule($fromUserId, $toUserId, $toAssignedId) {

        $ownerids1 = $fromUserId . ":" . $toAssignedId;

        $postvar2['iAssignedId'] = $toAssignedId;
        $postvar2['iModifyBy'] = $this->session->userdata('iUserId');
        $postvar2['dtModify'] = date('Y-m-d H:i:s');

        $where2 = 'iAssignedId = ' . $fromUserId . ' AND isDelete != 1';

        // Task Master
        $this->db->where($where2);
        $tasks1 = $this->db->get('task_master')->result();
        foreach ($tasks1 as $t1) {
            $this->insertUpdateMessage('task_master', $t1->iTaskId, array(), 'assignee', $ownerids1);
        }
        $this->update('task_master', $postvar2, $where2);

        // Event Master
        $this->db->where($where2);
        $events1 = $this->db->get('event_master')->result();
        foreach ($events1 as $e1) {
            $this->insertUpdateMessage('event_master', $e1->iEventId, array(), 'assignee', $ownerids1);
        }
        $this->update('event_master', $postvar2, $where2);

        // Call Master
        $this->db->where($where2);
        $calls1 = $this->db->get('call_master')->result();
        foreach ($calls1 as $c1) {
            $this->insertUpdateMessage('call_master', $c1->iCallId, array(), 'assignee', $ownerids1);
        }
        $this->update('call_master', $postvar2, $where2);

        $ownerids = $fromUserId . ":" . $toUserId;

        $where1 = 'iCreatedBy = ' . $fromUserId . ' AND isDelete != 1';

        $postvar1['iCreatedBy'] = $toUserId;
        $postvar1['iModifyBy'] = $this->session->userdata('iUserId');
        $postvar1['dtModify'] = date('Y-m-d H:i:s');

        // Task Master
        $this->db->where($where1);
        $tasks = $this->db->get('task_master')->result();

        foreach ($tasks as $t) {
            $this->insertUpdateMessage('task_master', $t->iTaskId, array(), 'owner', $ownerids);
        }
        $this->update('task_master', $postvar1, $where1);

        // Event Master
        $this->db->where($where1);
        $events = $this->db->get('event_master')->result();
        foreach ($events as $e) {
            $this->insertUpdateMessage('event_master', $e->iEventId, array(), 'owner', $ownerids);
        }
        $this->update('event_master', $postvar1, $where1);

        // Call Master
        $this->db->where($where1);
        $calls = $this->db->get('call_master')->result();
        foreach ($calls as $c) {
            $this->insertUpdateMessage('call_master', $c->iCallId, array(), 'owner', $ownerids);
        }
        $this->update('call_master', $postvar1, $where1);

        $where = 'iUserId = ' . $fromUserId . ' AND isDelete != 1';

        $postvar['iUserId'] = $toUserId;
        $postvar['iModifyBy'] = $this->session->userdata('iUserId');
        $postvar['dtModify'] = date('Y-m-d H:i:s');

        // Lead Master
        $this->db->where($where);
        $leads = $this->db->get('lead_master')->result();
        foreach ($leads as $l) {
            $leadname = $this->general->getFullName('Lead', $l->iLeadId);
            $this->insertChangeOwnerActivity('Lead', $l->iLeadId, $leadname, $ownerids);
        }
        $this->update('lead_master', $postvar, $where);

        // Account Master
        $this->db->where($where);
        $accounts = $this->db->get('account_master')->result();
        foreach ($accounts as $a) {
            $this->insertChangeOwnerActivity('Account', $a->iAccountId, $a->vName, $ownerids);
        }
        $this->update('account_master', $postvar, $where);

        // Contact Master
        $this->db->where($where);
        $contacts = $this->db->get('contact_master')->result();
        foreach ($contacts as $c) {
            $contactname = $this->general->getFullName('Contact', $c->iContactId);
            $this->insertChangeOwnerActivity('Contact', $c->iContactId, $contactname, $ownerids);
        }
        $this->update('contact_master', $postvar, $where);

        // Vendor Master
        $this->db->where($where);
        $vendors = $this->db->get('vendor_master')->result();
        foreach ($vendors as $v) {
            $this->insertChangeOwnerActivity('Vendor', $v->iVendorId, $v->vName, $ownerids);
        }
        $this->update('vendor_master', $postvar, $where);

        // Vendor Representative
        $this->db->where($where);
        $representatives = $this->db->get('vendor_representative')->result();
        foreach ($representatives as $r) {
            $this->insertChangeOwnerActivity('Representative', $r->iRepId, $r->vName, $ownerids);
        }
        $this->update('vendor_representative', $postvar, $where);

        // Product Master
        $this->db->where($where);
        $products = $this->db->get('product_master')->result();
        foreach ($products as $p) {
            $this->insertChangeOwnerActivity('Representative', $p->iProductId, $p->vName, $ownerids);
        }
        $this->update('product_master', $postvar, $where);

        // Potential Master
        $this->db->where($where);
        $potentials = $this->db->get('potential_master')->result();
        foreach ($potentials as $p) {
            $this->insertChangeOwnerActivity('Product', $r->iProductId, $r->vName, $ownerids);
        }
        $this->update('potential_master', $postvar, $where);

        $where2 = 'iUserId = ' . $fromUserId;

        $deletevar['isDelete'] = 1;
        $deletevar['iModifyBy'] = $this->session->userdata('iUserId');
        $deletevar['dtModify'] = date('Y-m-d H:i:s');

        $this->update('user_master', $deletevar, $where2);
    }

    /* By Nitin */

    function getUserData() {
        $this->db->select('*', false);
        $this->db->from("tbl_end_user");
        $list_data = $this->db->get()->result_array();
        return $list_data;
    }

    function getStylistData() {
        $this->db->select('*', false);
        $this->db->from("tbl_stylist_user");
        $list_data = $this->db->get()->result_array();
        return $list_data;
    }

    function check_username($username) {
        $this->db->where('email', $username);
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

    function EditAdmin($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->main_table, $data);
    }

}

?>