<?php
include VIEWPATH . 'admin/header.php';
$commission_percentage = isset($business_data->commission_percentage) ? $business_data->commission_percentage : set_value('commission_percentage');
$minimum_vendor_payout = isset($business_data->minimum_vendor_payout) ? $business_data->minimum_vendor_payout : set_value('minimum_vendor_payout');
$slot_display_days = isset($business_data->slot_display_days) ? $business_data->slot_display_days : set_value('slot_display_days');
$enable_membership = (set_value("enable_membership")) ? set_value("enable_membership") : (!empty($business_data) ? $business_data->enable_membership : 'N');

$package_yes = $package_no = "";
if ($enable_membership == 'Y') {
    $package_yes = 'checked';
} else {
    $package_no = 'checked';
}
?>
<style>
    .select-wrapper input.select-dropdown {
        color: black;
    }
</style>
<div class="dashboard-body">
    <!-- Start Content -->
    <div class="content">
        <!-- Start Container -->
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="card">
                        <div class="p-3">
                            <div class="sidebar_section">
                                <ul class="list-inline">
                                    <li>
                                        <a href="<?php echo base_url('admin/sitesetting'); ?>"><?php echo translate('site_setting'); ?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('admin/email-setting'); ?>"><?php echo translate('email_setting'); ?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('admin/currency-setting'); ?>"><?php echo translate('currency') . ' ' . translate('setting'); ?></a>
                                    </li>
                                    <li class="active">
                                        <a href="<?php echo base_url('admin/business-setting'); ?>"><?php echo translate('business') . ' ' . translate('setting'); ?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('admin/display-setting'); ?>"><?php echo translate('display_setting'); ?></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url('admin/payment-setting'); ?>"><?php echo translate('payment_setting'); ?></a>
                                    </li>
                                    <li><a href="<?php echo base_url('admin/vendor-setting'); ?>"><?php echo translate('vendor') . ' ' . translate('setting'); ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php $this->load->view('message'); ?>

                    <div class="header bg-color-base p-3">
                        <h3 class="black-text font-bold mb-0"><?php echo translate('manage'); ?> <?php echo translate('business'); ?> <?php echo translate('setting'); ?></h3>
                    </div>

                    <div class="card">
                        <div class="card-body resp_mx-0">
                            <?php echo form_open('admin/save-business-setting', array('name' => 'site_business_form', 'id' => 'site_business_form')); ?>
                            <div class="row">
                                <div class="col-md-6 ">
                                    <?php echo form_label(translate('enable') . ' ' . translate('membership') . ' : <small class ="required">*</small>', 'commission_percentage', array('class' => 'control-label')); ?>
                                    <div class="form-group form-inline">

                                        <div class="form-group">
                                            <input name='membership' value="Y" type='radio' id='package_yes'   <?php echo isset($package_yes) ? $package_yes : ''; ?> onchange="check_package_val(this.value);">
                                            <label for="package_yes"><?php echo translate('yes'); ?></label>
                                        </div>
                                        <div class="form-group">
                                            <input name='membership' type='radio'  value='N' id='package_no'  <?php echo isset($package_no) ? $package_no : ''; ?> onchange="check_package_val(this.value);">
                                            <label for='package_no'><?php echo translate('no'); ?></label>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <?php echo form_label(translate('minimum') . ' ' . translate('vendor') . ' ' . translate('payout') . ' : <small class ="required">*</small>', 'minimum_vendor_payout', array('class' => 'control-label')); ?>
                                        <?php echo form_input(array('id' => 'minimum_vendor_payout', 'class' => 'form-control integers', 'name' => 'minimum_vendor_payout', 'value' => $minimum_vendor_payout, 'placeholder' => translate('minimum') . ' ' . translate('vendor') . ' ' . translate('payout'))); ?>
                                        <?php echo form_error('minimum_vendor_payout'); ?>
                                    </div>
                                    <div class="error" id="minimum_vendor_payout"></div>
                                </div>
                            </div>
                            
                            <div class="row" id="commission_percentage_div">
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <?php echo form_label(translate('comission') . ' ' . translate('in') . ' ' . translate('percentage') . ' : <small class ="required">*</small>', 'commission_percentage', array('class' => 'control-label')); ?>
                                        <?php echo form_input(array('id' => 'commission_percentage', "min" => 1, 'class' => 'form-control integers', 'name' => 'commission_percentage', 'value' => $commission_percentage, 'placeholder' => translate('comission') . ' ' . translate('in') . ' ' . translate('percentage'))); ?>
                                        <?php echo form_error('commission_percentage'); ?>
                                    </div>
                                    <div class="error" id="commission_percentage"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Display Booking Slot for Next X Days</label>
                                        <?php echo form_input(array("max" => 365, "maxlength" => 3, 'type' => "number", "min" => 1, 'id' => 'slot_display_days', 'class' => 'form-control integers', 'name' => 'slot_display_days', 'value' => $slot_display_days, 'placeholder' => "Booking Slot Days")); ?>
                                        <?php echo form_error('slot_display_days'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success waves-effect"><?php echo translate('update'); ?></button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                    <!--/Form with header-->
                </div>
            </div>
        </div>
        <!--Row-->
        <!-- End Login-->
    </div>
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/sitesetting.js" type="text/javascript"></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
<script>
                                                check_package_val('<?php echo $enable_membership; ?>');
                                                function check_package_val(e) {
                                                    if (e == 'Y') {
                                                        $('#commission_percentage_div').hide();
                                                        $("#commission_percentage").removeClass("error");
                                                        $('#commission_percentage').attr('required', false);
                                                        $('#commission_percentage').attr('aria-invalid', false);
                                                        $('#commission_percentage').attr('aria-required', false);
                                                    } else {
                                                        $('#commission_percentage_div').show();
                                                        $('#commission_percentage').attr('required', true);
                                                    }
                                                }
</script>
