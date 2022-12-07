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
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?php echo translate('vendor'); ?> <?php echo translate('setting'); ?></h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                        <li class="breadcrumb-item active"><?php echo translate('vendor'); ?></li>
                    </ol>
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
                            <a class="nav-link"  href="<?php echo base_url('admin/setting/business'); ?>" role="tab">
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
                        <li class="nav-item">
                            <a class="nav-link active"  href="<?php echo base_url('admin/setting/vendor'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('vendor') . ' ' . translate('setting'); ?></span>
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content p-3 text-muted">
                        <div class="tab-pane active" role="tabpanel">
                            <?php $this->load->view('message'); ?>
                            <?php echo form_open('admin/save-vendor-setting', array('name' => 'vendor_setting_form', 'id' => 'vendor_setting_form')); ?>
                            <div class="row mb-2">
                                <div class="col-md-6 ">
                                    <div class="form-group">
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
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
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
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
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
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success waves-effect"><?php echo translate('update'); ?></button>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
<script src="<?php echo $this->config->item('js_url'); ?>jquery.minicolors.js"></script>
<script src="<?php echo $this->config->item('js_url'); ?>module/sitesetting.js" type="text/javascript"></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
