<?php
$staff_id = $result_data['staff_id'];

$status = "";
if ($result_data['status'] == 'A') {
    $status = "<span class='badge badge-success'>" . translate('approved') . "</span>";
} else if ($result_data['status'] == 'R') {
    $status = "<span class='badge badge-danger'>" . translate('Rejected') . "</span>";
} else if ($result_data['status'] == 'P') {
    $status = "<span class='badge badge-info'>" . translate('pending') . "</span>";
} else if ($result_data['status'] == 'C') {
    $status = "<span class='badge badge-primary'>" . translate('completed') . "</span>";
}

//Get addon
$get_service_addons_by_id = 0;
if (isset($result_data['addons_id']) && $result_data['addons_id'] != ""):
    $get_service_addons_by_id = get_service_addons_by_id($result_data['addons_id']);
endif;
?>
<style>
    .slidePanel {
        padding: 20px;
        color: #000;
        background: #f8f9fb;
        z-index: 999;
        box-shadow: 0px 0px 50px 10px #888888; 
    }
    .title{
        padding: 15px 0px;
    }
    .attendee_tbl tbody {
        display:block;
        max-height:200px;
        overflow-y:auto;
    }
    .attendee_tbl thead, .attendee_tbl tbody tr {
        display:table;
        width:100%;
        table-layout:fixed;
    }
</style>
<div class="card">
    <div class="card-body">
        <div class="card-header">
            <?php echo isset($title) ? $title : ""; ?>
            <a href="#" class="pull-right close" ><i class="fa fa-times-circle"></i> </a>
        </div>
        <hr/>
        <div class="table-responsive">
            <table class="table table-striped" id="example">
                <tr>
                    <td><?php echo translate('service'); ?></td>
                    <td><?php echo isset($result_data['title']) ? $result_data['title'] : ""; ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('price'); ?></td>
                    <td><?php echo (isset($result_data['payment_type']) && $result_data['payment_type'] == 'P') ? price_format($result_data['price']) : translate('free'); ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('appointment_date'); ?></td>
                    <td><?php echo get_formated_date($result_data['start_date'] . " " . $result_data['start_time']); ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('status'); ?></td>
                    <td><?php echo $status; ?></td>
                </tr>
                <?php if ($result_data['payment_type'] == 'P'): ?>
                    <tr>
                        <td><?php echo translate('payment') . " " . translate('status'); ?></td>
                        <td><?php echo check_appointment_pstatus($result_data['payment_status']); ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td><?php echo translate('booking_note'); ?></td>
                    <td><?php echo isset($result_data['description']) ? $result_data['description'] : ""; ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('address'); ?></td>
                    <td><?php echo (isset($result_data['address']) && $result_data['address'] != '') ? $result_data['address'] : $result_data['vendor_address']; ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('vendor'); ?></td>
                    <td><?php echo isset($result_data['company_name']) ? $result_data['company_name'] : ""; ?></td>
                </tr>
                <?php
                if ($staff_id > 0):
                    $staff_data = get_staff_row_by_id($staff_id);
                    ?>
                    <tr>
                        <td><?php echo translate('staff'); ?></td>
                        <td><?php echo isset($staff_data['first_name']) ? $staff_data['first_name'] . " " . $staff_data['last_name'] : ""; ?>/<?php echo $staff_data['designation']; ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td><?php echo translate('city') . "/" . translate('location'); ?></td>
                    <td><?php echo isset($result_data['city_title']) ? $result_data['city_title'] . "/" . $result_data['loc_title'] : ""; ?></td>
                </tr>

            </table>
            <hr/>
            <?php if (isset($get_service_addons_by_id) && $get_service_addons_by_id != 0): ?>
                <h5><?php echo translate('add_ons'); ?></h5>
                <table class="table table-striped" id="example">
                    <?php foreach ($get_service_addons_by_id as $val): ?>
                        <tr>
                            <td><?php echo $val['title']; ?></td>
                            <td><?php echo price_format($val['price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

