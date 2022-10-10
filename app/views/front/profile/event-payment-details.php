<?php
$event_type = $payment_data['event_type'];
$get_ticket_details_by_booking_id = array();
$title = translate('service') . " " . translate('payment') . " " . translate('details');
if ($event_type == 'E'):
    $title = translate('event') . " " . translate('payment') . " " . translate('details');
    $get_ticket_details_by_booking_id = get_ticket_details_by_booking_id($payment_data['booking_id']);
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
</style>

<div class="card">
    <div class="card-body">
        <div class="card-header">

            <?php echo isset($title) ? $title : ""; ?>
            <a href="#" class="pull-right close" ><i class="fa fa-close"></i> </a>
        </div>
        <hr/>
        <div class="detail_content">
            <table class="table mdl-data-table">
                <tr>
                    <th><?php echo translate('customer_name'); ?></th>
                    <td><?php echo $payment_data['customer_name']; ?></td>
                </tr>
                <tr>
                    <?php if ($event_type == 'E'): ?>
                        <th><?php echo translate('event'); ?></th>
                    <?php else: ?>
                        <th><?php echo translate('service'); ?></th>
                    <?php endif; ?>
                    <td><?php echo $payment_data['event_name']; ?></td>
                </tr>
                <?php if ($event_type == 'E'): ?>
                    <tr>
                        <td><?php echo translate('ticket'); ?></td>
                        <td>
                            <?php
                            $total_seat = 0;
                            foreach ($get_ticket_details_by_booking_id as $val):
                                $total_seat = $total_seat + $val['total_ticket'];
                                ?>
                                <p><?php echo $val['ticket_type_title'] ?> - <?php echo $val['total_ticket']; ?></p>
                            <?php endforeach; ?>
                            <?php echo translate('total'); ?> - <?php echo $total_seat; ?>
                        </td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <th><?php echo translate('vendor_name'); ?></th>
                    <td><?php echo $payment_data['company_name']; ?></td>
                </tr>
                <tr>
                    <th><?php echo translate('payment_gateway'); ?></th>
                    <td><?php echo $payment_data['payment_method']; ?></td>
                </tr>


                <tr>
                    <th><?php echo translate('total') . " " . translate('payment'); ?></th>
                    <td><?php echo price_format($payment_data['payment_price']); ?></td>
                </tr>
                <tr>
                    <th><?php echo translate('vendor_amount'); ?></th>
                    <td><?php echo price_format($payment_data['vendor_price']); ?></td>
                </tr>
                <tr>
                    <th><?php echo translate('fee'); ?></th>
                    <td><?php echo price_format($payment_data['admin_price']); ?></td>
                </tr>
                <tr>
                    <th><?php echo translate('status'); ?></th>
                    <td><?php echo check_appointment_pstatus($payment_data['payment_status']); ?></td>
                </tr>
                <tr>
                    <th><?php echo translate('created_date'); ?></th>
                    <td><?php echo get_formated_date(($payment_data['created_on']), 'N'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

