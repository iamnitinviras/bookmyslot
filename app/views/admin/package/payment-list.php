<?php
include VIEWPATH . 'admin/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 col-xl-6">
                            <h4 class="card-title"><?php echo $title; ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>

                            <div class="table-responsive">
                                <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
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
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
<?php
include VIEWPATH . 'admin/footer.php';
?>