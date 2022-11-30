<?php
$status = "";
if ($row['status'] == 'P') {
    $status = "<span class='badge badge-warning'>" . translate('pending') . "</span>";
} else if ($row['status'] == 'S') {
    $status = "<span class='badge badge-success'>" . translate('payout_successful') . "</span>";
} else if ($row['status'] == 'H') {
    $status = "<span class='badge badge-info'>" . translate('on_hold') . "</span>";
} else if ($row['status'] == 'F') {
    $status = "<span class='badge badge-danger'>" . translate('failed') . "</span>";
}

$get_current_currency = get_current_currency();

$other_charge = "";
if (isset($row['other_charge']) && $row['other_charge'] != 0) {
    $other_charge = " + " . price_format($row['other_charge']);
}
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
    .slidePanel-right, .slidePanel-left {
        width: 550px;
    }

</style>
<div class="card">
    <div class="card-body">
        <div class="card-header">
            <?php echo translate('payout_request') . " " . translate('details'); ?>
            <a href="#" class="pull-right close" ><i class="fa fa-times-circle"></i> </a>
        </div>
        <hr/>
        <div class="table-responsive">
            <table class="table table-striped" id="example">
                <tr>
                    <td><?php echo translate('payment_gateway'); ?></td>
                    <td><?php echo isset($row['choose_payment_gateway']) ? $row['choose_payment_gateway'] : "NA"; ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('payout_reference'); ?></td>
                    <td><?php echo isset($row['payment_gateway_ref']) && $row['payment_gateway_ref'] != NULL ? "" . $row['payment_gateway_ref'] : "NA"; ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('amount'); ?></td>
                    <td><?php echo price_format($row['amount']); ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('reference_no'); ?></td>
                    <td><?php echo isset($row['reference_no']) ? $row['reference_no'] : "NA"; ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('payment_gateway_fee'); ?></td>
                    <td><?php echo isset($row['payment_gateway_fee']) && ($row['payment_gateway_fee'] != "") ? $row['payment_gateway_fee'] . "%" . $other_charge : "NA"; ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('status'); ?></td>
                    <td><?php echo $status; ?></td>
                </tr>
                <?php if (isset($row['cash_payment']) && $row['cash_payment'] > 0): ?>
                    <tr>
                        <td><?php echo translate('cash_payment_fee'); ?></td>
                        <td><?php echo price_format($row['cash_payment']); ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td><?php echo translate('request_date'); ?></td>
                    <td><?php echo get_formated_date($row['created_date']); ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('processed_date'); ?></td>
                    <td><?php echo isset($row['processed_date']) ? get_formated_date($row['processed_date']) : '-'; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

