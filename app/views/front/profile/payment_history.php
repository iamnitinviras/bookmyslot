<?php
include VIEWPATH . 'front/header.php';
$customer_data = get_CustomerDetails();
$first_name = $customer_data['first_name'];
$last_name = $customer_data['last_name'];
$email = $customer_data['email'];

if (file_exists(FCPATH . uploads_path . "/profiles/" . $customer_data['profile_image']) && $customer_data['profile_image'] != '') {
    $img_src = base_url() . uploads_path . "/profiles/" . $customer_data['profile_image'];
} else {
    $img_src = base_url() . img_path . "/user.png";
}
?>
<!-- Custom Script -->
<link href="<?php echo $this->config->item('css_url'); ?>module/user_panel.css" rel="stylesheet"/>
<div class="container  mt-20"  style="min-height:653px;">
    <div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card mb-3">

                <div class="card-body text-center">
                    <img src="<?php echo $img_src; ?>" alt="<?php echo $first_name . " " . $last_name; ?>" class="rounded-circle mb-2" width="100" height="100"/>
                    <h4 class="card-title mb-0"><?php echo $first_name . " " . $last_name; ?></h4>
                </div>

                <hr class="my-0">
                <div class="card-body">
                    <nav class="side-menu">
                        <ul class="nav">

                            <li><a href="<?php echo base_url('profile'); ?>"><span class="fa fa-user"></span> <?php echo translate('profile'); ?></a></li>
                            <li ><a href="<?php echo base_url('change-password'); ?>"><span class="fa fa-cog"></span> <?php echo translate('Change_password'); ?></a></li>
                            
                            <?php if (get_site_setting('enable_service') == 'Y'): ?>
                                <li><a href="<?php echo base_url('appointment'); ?>"><span class="fa fa-clock-o"></span> <?php echo translate('my_appointment'); ?></a></li>
                            <?php endif; ?>

                            <?php if (get_site_setting('enable_event') == 'Y'): ?>
                                <li><a href="<?php echo base_url('event-booking'); ?>"><span class="fa fa-ticket"></span> <?php echo translate('event') . " " . translate('booking'); ?></a></li>
                            <?php endif; ?>

                            <li class="active"><a href="<?php echo base_url('payment-history'); ?>"><span class="fa fa-credit-card"></span> <?php echo translate('payment_history'); ?></a></li>
                            <li><a href="<?php echo base_url('logout'); ?>"><span class="fa fa-power-off"></span> <?php echo translate('logout'); ?></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-xl-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><?php echo translate('payment_history'); ?></h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="example">
                            <thead>
                                <tr>
                                    <th class="text-center font-bold dark-grey-text">#</th>
                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('title'); ?></th>
                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('price'); ?></th>
                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('payment_method'); ?></th>
                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('payment_status'); ?></th>
                                    <th class="text-center font-bold dark-grey-text"><?php echo translate('created_date'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
//                                        echo "<pre>";
//                                        print_r($payment_history);exit;
                                if (isset($payment_history) && count($payment_history) > 0) {
                                    foreach ($payment_history as $key => $row) {
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $key + 1; ?></td>
                                            <td class="text-center"><?php echo $row['title']; ?></td>
                                            <td class="text-center"><?php echo ($row['payment_price']>0)?price_format($row['payment_price']): price_format(0); ?></td>
                                            <td class="text-center"><?php echo $row['Payment_method'] == '' ? "-" : $row['Payment_method']; ?></td>
                                            <td class="text-center"><?php echo check_appointment_pstatus($row['Payment_status']); ?></td>
                                            <td class="text-center"><?php echo get_formated_date($row['payment_date']); ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Custom Script -->
<?php include VIEWPATH . 'front/footer.php'; ?>
<script src="<?php echo $this->config->item('js_url'); ?>module/appointment.js" type='text/javascript'></script>
<script src="<?php echo $this->config->item('js_url'); ?>jquery.rating-stars.js" type="text/javascript"></script>