<?php
$status = "";
if ($result_data['status'] == 'A') {
    $status = "<span class='badge badge-success'>" . translate('approved') . "</span>";
} else if ($result_data['status'] == 'R') {
    $status = "<span class='badge badge-danger'>" . translate('Rejected') . "</span>";
}  else if ($result_data['status'] == 'P') {
    $status = "<span class='badge badge-info'>" . translate('pending') . "</span>";
} else if ($result_data['status'] == 'C') {
    $status = "<span class='badge badge-primary'>" . translate('completed') . "</span>";
}


$get_ticket_details_by_booking_id = get_ticket_details_by_booking_id($result_data['booking_id']);
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
                    <td><?php echo translate('event'); ?></td>
                    <td><?php echo isset($result_data['title']) ? $result_data['title'] : ""; ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('price'); ?></td>
                    <td><?php echo ($result_data['price'] == 0) ? translate('free') : price_format($result_data['price']); ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('event') . " " . translate('date'); ?></td>
                    <td><?php echo get_formated_date($result_data['event_start_date']) . " to <br/> " . get_formated_date($result_data['event_end_date']); ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('ticket'); ?></td>
                    <td>
                        <?php foreach ($get_ticket_details_by_booking_id as $val): ?>
                            <p><?php echo $val['ticket_type_title'] ?> - <?php echo $val['total_ticket']; ?></p>
                        <?php endforeach; ?>
                        <?php echo translate('total'); ?> - <?php echo $result_data['event_booked_seat']; ?>
                    </td>
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
                    <td><?php echo translate('venue'); ?></td>
                    <td><?php echo (isset($result_data['address']) && $result_data['address'] != '') ? $result_data['address'] : $result_data['vendor_address']; ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('organizer'); ?></td>
                    <td><?php echo isset($result_data['company_name']) ? $result_data['company_name'] : ""; ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('city') . "/" . translate('location'); ?></td>
                    <td><?php echo isset($result_data['city_title']) ? $result_data['city_title'] . "/" . $result_data['loc_title'] : ""; ?></td>
                </tr>

            </table>
        </div>
    </div>
</div>

