<?php
include VIEWPATH . 'admin/header.php';
?>
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid ">
            <section class="form-light px-2 sm-margin-b-20">
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12 m-auto">
                        <?php $this->load->view('message'); ?>
                        <div class="header bg-color-base">
                            <div class="d-flex">
                                <span style="width: 70%;" class="text-left">
                                    <h3 class="white-black font-bold pt-3"><?php echo translate('membership') . " " . translate('payment') ?></h3>
                                </span>  
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mdl-data-table" id="example">
                                        <thead>
                                            <tr>
                                                <th class="text-center font-bold dark-grey-text">#</th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('Name'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('Title'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('Price'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('validity'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('Status'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('Created_Date'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($payment_history) && count($payment_history) > 0) {
                                                foreach ($payment_history as $payment_key => $payment_row) {
                                                    if ($payment_row['membership_status'] == "A") {
                                                        $status_string = '<span class="badge badge-success">' . translate('Active') . '</span>';
                                                    } else {
                                                        $status_string = '<span class="badge badge-danger">' . translate('Expired') . '</span>';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $payment_key + 1; ?></td>
                                                        <td class="text-center"><?php echo ($payment_row['first_name']) . " " . ($payment_row['last_name']); ?></td>
                                                        <td class="text-center"><?php echo $payment_row['title']; ?></td>
                                                        <td class="text-center"><?php echo price_format(number_format($payment_row['price'], 0)); ?></td>
                                                        <td class="text-center"><?php echo $payment_row['package_month']; ?> <?php echo translate('month'); ?></td>
                                                        <td class="text-center"><?php echo $status_string; ?></td>
                                                        <td class="text-center"><?php echo date("d-m-Y", strtotime($payment_row['created_on'])); ?></td>
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
                    <!--col-md-12-->
                </div>
                <!--Row-->
            </section>
        </div>
    </div>   
</div>
<?php
include VIEWPATH . 'admin/footer.php';
?>