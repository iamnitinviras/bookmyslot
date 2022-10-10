<?php
include VIEWPATH . 'vendor/header.php';
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

                        <div class="header bg-color-base p-3">
                            <h3 class="black-text font-bold mb-0">
                                <?php echo translate('membership')." ".translate('payment') ?>
                            </h3>
                        </div>

                        <div class="card">
                            <div class="card-body">
                            <a class="btn btn-success pull-right" href="<?php echo base_url('vendor/membership-purchase'); ?>"><?php echo translate('purchase')." ".translate('now'); ?></a>
                                <div class="table-responsive">
                                    <table class="table mdl-data-table" id="example">
                                        <thead>
                                            <tr>
                                                <th class="text-center font-bold dark-grey-text">#</th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('name'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('price'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('validity'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('expire_date'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('created_date'); ?></th>
                                                <th class="text-center font-bold dark-grey-text"><?php echo translate('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($membership_history) && count($membership_history) > 0) {
                                                foreach ($membership_history as $mem_key => $mem_row) {
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $mem_key + 1; ?></td>
                                                        <td class="text-center"><?php echo $mem_row['title']; ?></td>
                                                        <td class="text-center"><?php echo price_format($mem_row['price']); ?></td>
                                                        <td class="text-center"><?php echo $mem_row['package_month']; ?> <?php echo translate('month'); ?></td>
                                                        <td class="text-center"><?php echo $mem_row['membership_till']; ?></td>
                                                        <td class="text-center"><?php echo get_formated_date($mem_row['created_on'], "N"); ?></td>
                                                        <td class="text-center"><a href="<?php echo base_url('vendor/get-membership-details/' . (int) $mem_row['app_membership_id']); ?>"  data-direction="right" class="btn btn-xs fs-10 btn-bold btn-info bookslide" title="<?php echo translate('details'); ?>"><?php echo translate('details'); ?></i></a></td>
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
include VIEWPATH . 'vendor/footer.php';
?>

<script>
    $('.bookslide').slidePanel();
    $(document).on('slidePanel::beforeShow', function (e) {
        $('#loadingmessage').show();
    });
    $(document).on('slidePanel::afterShow', function (e) {
        $('#loadingmessage').hide();
    });
</script>