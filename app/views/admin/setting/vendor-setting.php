<?php
include VIEWPATH . 'admin/header.php';
$allow_city_location = isset($vendor_data['allow_city_location']) ? $vendor_data['allow_city_location'] : set_value('allow_city_location');
$allow_service_category = isset($vendor_data['allow_service_category']) ? $vendor_data['allow_service_category'] : set_value('allow_service_category');
$allow_event_category = isset($vendor_data['allow_event_category']) ? $vendor_data['allow_event_category'] : set_value('allow_event_category');
?>
<link href="<?php echo $this->config->item('css_url'); ?>jquery.minicolors.css" rel="stylesheet">
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
                                    <li><a href="<?php echo base_url('admin/sitesetting'); ?>"><?php echo translate('site_setting'); ?></a></li>
                                    <li><a href="<?php echo base_url('admin/email-setting'); ?>"><?php echo translate('email_setting'); ?></a></li>
                                    <li>
                                        <a href="<?php echo base_url('admin/currency-setting'); ?>"><?php echo translate('currency') . ' ' . translate('setting'); ?></a>
                                    </li>
                                    <li><a href="<?php echo base_url('admin/business-setting'); ?>"><?php echo translate('business') . ' ' . translate('setting'); ?></a></li>
                                    <li><a href="<?php echo base_url('admin/display-setting'); ?>"><?php echo translate('display_setting'); ?></a></li>
                                    <li><a href="<?php echo base_url('admin/payment-setting'); ?>"><?php echo translate('payment_setting'); ?></a></li>
                                    <li class="active"><a href="<?php echo base_url('admin/vendor-setting'); ?>"><?php echo translate('vendor') . ' ' . translate('setting'); ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php $this->load->view('message'); ?>

                    <div class="header bg-color-base p-3">
                        <h3 class="black-text font-bold mb-0"> <?php echo translate('vendor'); ?> <?php echo translate('setting'); ?></h3>
                    </div>

                    <div class="card">
                        <div class="card-body resp_mx-0">
                            <?php echo form_open('admin/save-vendor-setting', array('name' => 'vendor_setting_form', 'id' => 'vendor_setting_form')); ?>
                            <div class="row">
                                <div class="col-md-6 ">
                                    <!-- Switch -->
                                    <?php echo form_label(translate('allow_city_location') . ' : <small class ="required">*</small>', 'is_display_vendor', array('class' => 'control-label')); ?>
                                    <div class="switch round blue-white-switch">
                                        <label>
                                            No
                                            <input type="checkbox" <?php echo $allow_city_location == 'Y' ? "checked='checked'" : ""; ?> id="allow_city_location" value="Y" name="allow_city_location">
                                            <span class="lever"></span>
                                            Yes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <!-- Switch -->
                                    <?php echo form_label(translate('allow_service_category') . ' : <small class ="required">*</small>', 'is_display_category', array('class' => 'control-label')); ?>
                                    <div class="switch round blue-white-switch">
                                        <label>
                                            No
                                            <input type="checkbox"  <?php echo $allow_service_category == 'Y' ? "checked='checked'" : ""; ?> id="allow_service_category" value="Y" name="allow_service_category">
                                            <span class="lever"></span>
                                            Yes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <!-- Switch -->
                                    <?php echo form_label(translate('allow_event_category') . ' : <small class ="required">*</small>', 'is_display_location', array('class' => 'control-label')); ?>
                                    <div class="switch round blue-white-switch">
                                        <label>
                                            No
                                            <input type="checkbox"  <?php echo $allow_event_category == 'Y' ? "checked='checked'" : ""; ?> id="allow_event_category" value="Y" name="allow_event_category">
                                            <span class="lever"></span>
                                            Yes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success waves-effect"><?php echo translate('update'); ?></button>
                                    </div>
                                </div>

                            </div>
                            <hr/>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                    <!--/Form with header-->
                </div>
            </div>
            <!--Row-->
        </div>        
    </div>
</div>
<script src="<?php echo $this->config->item('js_url'); ?>jquery.minicolors.js"></script>
<script src="<?php echo $this->config->item('js_url'); ?>module/sitesetting.js" type="text/javascript"></script>

<?php include VIEWPATH . 'admin/footer.php'; ?>
