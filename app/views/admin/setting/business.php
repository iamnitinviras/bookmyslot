<?php
include VIEWPATH . 'admin/header.php';
$commission_percentage = isset($business_data->commission_percentage) ? $business_data->commission_percentage : set_value('commission_percentage');
$minimum_vendor_payout = isset($business_data->minimum_vendor_payout) ? $business_data->minimum_vendor_payout : set_value('minimum_vendor_payout');
$slot_display_days = isset($business_data->slot_display_days) ? $business_data->slot_display_days : set_value('slot_display_days');

$allow_city_location = isset($vendor_data['allow_city_location']) ? $vendor_data['allow_city_location'] : set_value('allow_city_location');
$allow_service_category = isset($vendor_data['allow_service_category']) ? $vendor_data['allow_service_category'] : set_value('allow_service_category');
$allow_event_category = isset($vendor_data['allow_event_category']) ? $vendor_data['allow_event_category'] : set_value('allow_event_category');
?>
<style>
    .select-wrapper input.select-dropdown {
        color: black;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            <h4 class="card-title"><?php echo translate('business'); ?> <?php echo translate('setting'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo translate('business'); ?> <?php echo translate('setting'); ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link "  href="<?php echo base_url('admin/setting/site'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('site_setting'); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  href="<?php echo base_url('admin/setting/email'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('email_setting'); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  href="<?php echo base_url('admin/setting/currency'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('currency'); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active"  href="<?php echo base_url('admin/setting/business'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('business'); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  href="<?php echo base_url('admin/setting/display'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('display_setting'); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  href="<?php echo base_url('admin/setting/payment'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('payment_setting'); ?></span>
                            </a>
                        </li>
                    </ul>

                    <?php $this->load->view('message'); ?>
                    <?php echo form_open('admin/save-business-setting', array('name' => 'site_business_form', 'id' => 'site_business_form')); ?>

                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php echo form_label(translate('minimum') . ' ' . translate('vendor') . ' ' . translate('payout') . '<small class ="required">*</small>', 'minimum_vendor_payout', array('class' => 'control-label')); ?>
                                        <?php echo form_input(array('id' => 'minimum_vendor_payout', 'class' => 'form-control integers', 'name' => 'minimum_vendor_payout', 'value' => $minimum_vendor_payout, 'placeholder' => translate('minimum') . ' ' . translate('vendor') . ' ' . translate('payout'))); ?>
                                        <?php echo form_error('minimum_vendor_payout'); ?>
                                    </div>
                                    <div class="error" id="minimum_vendor_payout"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Display Booking Slot for Next X Days</label>
                                        <?php echo form_input(array("max" => 365, "maxlength" => 3, 'type' => "number", "min" => 1, 'id' => 'slot_display_days', 'class' => 'form-control integers', 'name' => 'slot_display_days', 'value' => $slot_display_days, 'placeholder' => "Booking Slot Days")); ?>
                                        <?php echo form_error('slot_display_days'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2 mt-5">
                                <div class="col-md-4 ">
                                    <div class="form-group">
                                        <!-- Switch -->
                                        <?php echo form_label(translate('allow_city_location'), 'is_display_vendor', array('class' => 'control-label')); ?>
                                        <div class="switch round blue-white-switch">
                                            <label>
                                                No
                                                <input type="checkbox" <?php echo $allow_city_location == 'Y' ? "checked='checked'" : ""; ?> id="allow_city_location" value="Y" name="allow_city_location">
                                                <span class="lever"></span>
                                                Yes
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 ">
                                    <div class="form-group">
                                        <!-- Switch -->
                                        <?php echo form_label(translate('allow_service_category'), 'is_display_category', array('class' => 'control-label')); ?>
                                        <div class="switch round blue-white-switch">
                                            <label>
                                                No
                                                <input type="checkbox"  <?php echo $allow_service_category == 'Y' ? "checked='checked'" : ""; ?> id="allow_service_category" value="Y" name="allow_service_category">
                                                <span class="lever"></span>
                                                Yes
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 ">
                                    <div class="form-group">
                                        <!-- Switch -->
                                        <?php echo form_label(translate('allow_event_category'), 'is_display_location', array('class' => 'control-label')); ?>
                                        <div class="switch round blue-white-switch">
                                            <label>
                                                No
                                                <input type="checkbox"  <?php echo $allow_event_category == 'Y' ? "checked='checked'" : ""; ?> id="allow_event_category" value="Y" name="allow_event_category">
                                                <span class="lever"></span>
                                                Yes
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary"><?php echo translate('submit'); ?></button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/sitesetting.js" type="text/javascript"></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
