<?php
include VIEWPATH . 'admin/header.php';
?>
<style>
    .box-border{
        position: relative;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid;
        border-radius: 0.25rem;
    }
</style>
<!-- start dashboard -->
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content pt-0">
        <!-- Start Container -->
        <div class="container-fluid">
            <!-- Start Section -->
            <div class="row">
                <div class="col-md-12 pt-2">
                    <?php $this->load->view('message'); ?>
                </div>
            </div>
            <!-- Card Color Section -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center"><?php echo translate('mandatory_update'); ?></h3>
                    <hr>
                    <div class="col-md-12">
                        <h6 class="alert alert-danger"><?php echo translate('mandatory_message'); ?></h6>
                    </div>
                    <?php if (isset($total_event_category) && $total_event_category == 0) { ?>
                        <div class="col-md-12 mt-40">
                            <h6 class="box-border"><?php echo translate('mandatory_category'); ?> <a href="<?php echo base_url('admin/add-service-category'); ?>" class="btn btn-sm blue-gradient font-bold pull-right mt-0"><?php echo translate('add'); ?></a></h6>
                        </div>
                    <?php } ?>
                    <?php if (isset($total_city) && $total_city == 0) { ?>
                        <div class="col-md-12">
                            <h6 class="box-border"><?php echo translate('mandatory_city'); ?> <a href="<?php echo base_url('admin/add-city'); ?>" class="btn btn-sm blue-gradient font-bold pull-right mt-0"><?php echo translate('add'); ?></a></h6>
                        </div>
                    <?php } ?>
                    <?php if (isset($total_location) && $total_location == 0) { ?>
                        <div class="col-md-12">
                            <h6 class="box-border"><?php echo translate('mandatory_location'); ?> <a href="<?php echo base_url('admin/add-location'); ?>" class="btn btn-sm blue-gradient font-bold pull-right mt-0"><?php echo translate('add'); ?></a></h6>
                        </div>
                    <?php } ?>
                    <?php if (isset($total_payment) && $total_payment == 0) { ?>
                        <div class="col-md-12">
                            <h6 class="box-border"><?php echo translate('mandatory_payment'); ?> <a href="<?php echo base_url('admin/payment-setting'); ?>" class="btn btn-sm blue-gradient font-bold pull-right mt-0"><?php echo translate('add'); ?></a></h6>
                        </div>
                    <?php } ?>
                    <?php if (get_site_setting('commission_percentage') == "" || get_site_setting("minimum_vendor_payout") <= 0) { ?>
                        <div class="col-md-12">
                            <h6 class="box-border"><?php echo translate('mandatory_commission'); ?> <a href="<?php echo base_url('admin/business-setting'); ?>" class="btn btn-sm blue-gradient font-bold pull-right mt-0"><?php echo translate('add'); ?></a></h6>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- End Container -->
    </div> 
    <!-- End Content -->
</div> 
<!-- End dashboard -->
<?php include VIEWPATH . 'admin/footer.php'; ?>
