<?php
$status = "";
if ($result_data['status'] == 'A') {
    $status = "<span class='badge badge-success'>" . translate('approved') . "</span>";
} else if ($result_data['status'] == 'R') {
    $status = "<span class='badge badge-danger'>" . translate('Rejected') . "</span>";
} else if ($result_data['status'] == 'A') {
    $status = "<span class='badge badge-danger'>" . translate('canceled') . "</span>";
} else if ($result_data['status'] == 'P') {
    $status = "<span class='badge badge-info'>" . translate('pending') . "</span>";
} else if ($result_data['status'] == 'C') {
    $status = "<span class='badge badge-primary'>" . translate('completed') . "</span>";
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
</style>
<div class="card">
    <div class="card-body">
        <div class="card-header">
        <?php echo translate('membership')." ".translate('payment') ?>
            <a href="#" class="pull-right close" ><i class="fa fa-times-circle"></i> </a>
        </div>
        <hr/>
        <div class="table-responsive">
            <table class="table table-striped" id="example">
                <tr>
                    <td><?php echo translate('price'); ?></td>
                    <td><?php echo ($result_data['price'] == 0) ? translate('free') : price_format($result_data['price']); ?></td>
                </tr>   
                <tr>
                    <td><?php echo translate('payment_gateway'); ?></td>
                    <td><?php echo $result_data['payment_method']; ?></td>
                </tr>  
                <tr>
                    <td>Transaction ID</td>
                    <td><?php echo $result_data['transaction_id']; ?></td>
                </tr>
                <tr>
                    <td><?php echo translate('payment')." ".translate('status'); ?></td>
                    <td><?php echo $result_data['payment_status']; ?></td>
                </tr>           
                <tr>
                    <td><?php echo translate('payment')." ".translate('date'); ?></td>
                    <td><?php echo get_formated_date($result_data['created_on']); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

