<?php
include VIEWPATH . 'admin/header.php';
$time_format = isset($company_data->time_format) ? $company_data->time_format : set_value('time_format');
$is_display_vendor = isset($company_data->is_display_vendor) ? $company_data->is_display_vendor : set_value('is_display_vendor');
$is_display_category = isset($company_data->is_display_category) ? $company_data->is_display_category : set_value('is_display_category');
$is_display_location = isset($company_data->is_display_location) ? $company_data->is_display_location : set_value('is_display_location');
$is_display_searchbar = isset($company_data->is_display_searchbar) ? $company_data->is_display_searchbar : set_value('is_display_searchbar');
$is_display_language = isset($company_data->is_display_language) ? $company_data->is_display_language : set_value('is_display_language');
$is_maintenance_mode = isset($company_data->is_maintenance_mode) ? $company_data->is_maintenance_mode : set_value('is_maintenance_mode');
$display_record_per_page = isset($company_data->display_record_per_page) ? $company_data->display_record_per_page : set_value('is_display_searchbar');
$header_color_code = isset($company_data->header_color_code) ? $company_data->header_color_code : (set_value('header_color_code') != '' ? set_value('header_color_code') : '#4b6499');
$footer_color_code = isset($company_data->footer_color_code) ? $company_data->footer_color_code : (set_value('footer_color_code') != '' ? set_value('footer_color_code') : '#4b6499');
$footer_text = isset($company_data->footer_text) ? $company_data->footer_text : set_value('footer_text');
$enable_event = isset($company_data->enable_event) ? $company_data->enable_event : set_value('enable_event');
$enable_service = isset($company_data->enable_service) ? $company_data->enable_service : set_value('enable_service');
$enable_testimonial = isset($company_data->enable_testimonial) ? $company_data->enable_testimonial : set_value('enable_testimonial');
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
                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            <h4 class="card-title"><?php echo translate('display'); ?> <?php echo translate('setting'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item active"><?php echo translate('display'); ?> <?php echo translate('setting'); ?></li>
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
                            <a class="nav-link"  href="<?php echo base_url('admin/setting/business'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('business'); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active"  href="<?php echo base_url('admin/setting/display'); ?>" role="tab">
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
                    <?php echo form_open('admin/save-display-setting', array('name' => 'site_email_form', 'id' => 'site_email_form')); ?>

                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">

                                <div class="col-md-4 ">
                                    <!-- Switch -->
                                    <?php echo form_label(translate('enable') . ' ' . translate('service') . ' : ', 'enable_service', array('class' => 'control-label')); ?>
                                    <div class="switch round blue-white-switch">
                                        <label>
                                            <?php echo translate('no'); ?>
                                            <input type="checkbox" <?php echo $enable_service == 'Y' ? "checked='checked'" : ""; ?> id="enable_service" name="enable_service" onchange="update_display_setting(this);">
                                            <span class="lever"></span>
                                            <?php echo translate('yes'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 ">
                                    <!-- Switch -->
                                    <?php echo form_label(translate('enable') . ' ' . translate('event') . ' : ', 'is_display_vendor', array('class' => 'control-label')); ?>
                                    <div class="switch round blue-white-switch">
                                        <label>
                                            <?php echo translate('no'); ?>
                                            <input type="checkbox" <?php echo $enable_event == 'Y' ? "checked='checked'" : ""; ?> id="enable_event" name="enable_event" onchange="update_display_setting(this);">
                                            <span class="lever"></span>
                                            Yes
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-4 ">
                                    <!-- Switch -->
                                    <?php echo form_label(translate('enable') . ' ' . translate('vendor') . ' ' . translate('module') . ' : ', 'is_display_vendor', array('class' => 'control-label')); ?>
                                    <div class="switch round blue-white-switch">
                                        <label>
                                            <?php echo translate('no'); ?>
                                            <input type="checkbox" <?php echo $is_display_vendor == 'Y' ? "checked='checked'" : ""; ?> id="is_display_vendor" name="is_display_vendor" onchange="update_display_setting(this);">
                                            <span class="lever"></span>
                                            <?php echo translate('yes'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-md-4 ">
                                    <!-- Switch -->
                                    <?php echo form_label(translate('enable') . ' ' . translate('category') . ' ' . translate('module') . ' : ', 'is_display_category', array('class' => 'control-label')); ?>
                                    <div class="switch round blue-white-switch">
                                        <label>
                                            <?php echo translate('no'); ?>
                                            <input type="checkbox"  <?php echo $is_display_category == 'Y' ? "checked='checked'" : ""; ?> id="is_display_category" name="is_display_category" onchange="update_display_setting(this);">
                                            <span class="lever"></span>
                                            <?php echo translate('yes'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- Switch -->
                                    <?php echo form_label(translate('enable') . ' ' . translate('location') . ' ' . translate('module') . ' : ', 'is_display_location', array('class' => 'control-label')); ?>
                                    <div class="switch round blue-white-switch">
                                        <label>
                                            <?php echo translate('no'); ?>
                                            <input type="checkbox"  <?php echo $is_display_location == 'Y' ? "checked='checked'" : ""; ?> id="is_display_location" name="is_display_location" onchange="update_display_setting(this);">
                                            <span class="lever"></span>
                                            <?php echo translate('yes'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- Switch -->
                                    <?php echo form_label(translate('enable') . ' ' . translate('searching') . ' ' . translate('module') . ' : ', 'is_display_searchbar', array('class' => 'control-label')); ?>

                                    <div class="switch round blue-white-switch">
                                        <label>
                                            <?php echo translate('no'); ?>
                                            <input type="checkbox"  <?php echo $is_display_searchbar == 'Y' ? "checked='checked'" : ""; ?> id="is_display_searchbar" name="is_display_searchbar" onchange="update_display_setting(this);">
                                            <span class="lever"></span>
                                            <?php echo translate('yes'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <!-- Switch -->
                                    <?php echo form_label(translate('enable') . ' ' . translate('language') . ' ' . translate('module') . ' : ', 'is_display_language', array('class' => 'control-label')); ?>

                                    <div class="switch round blue-white-switch">
                                        <label>
                                            <?php echo translate('no'); ?>
                                            <input type="checkbox"  <?php echo $is_display_language == 'Y' ? "checked='checked'" : ""; ?> id="is_display_language" name="is_display_language" onchange="update_display_setting(this);">
                                            <span class="lever"></span>
                                            <?php echo translate('yes'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- Switch -->
                                    <?php echo form_label(translate('enable') . ' ' . translate('maintenance_mode') . ' : ', 'is_display_language', array('class' => 'control-label')); ?>

                                    <div class="switch round blue-white-switch">
                                        <label>
                                            <?php echo translate('no'); ?>
                                            <input type="checkbox"  <?php echo $is_maintenance_mode == 'Y' ? "checked='checked'" : ""; ?> id="is_maintenance_mode" name="is_maintenance_mode" onchange="update_display_setting(this);">
                                            <span class="lever"></span>
                                            <?php echo translate('yes'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- Switch -->
                                    <?php echo form_label(translate('enable') . ' ' . translate('testimonial') . ' : ', 'enable_testimonial', array('class' => 'control-label')); ?>
                                    <div class="switch round blue-white-switch">
                                        <label>
                                            <?php echo translate('no'); ?>
                                            <input type="checkbox"  <?php echo $enable_testimonial == 'Y' ? "checked='checked'" : ""; ?> id="enable_testimonial" name="enable_testimonial" onchange="update_display_setting(this);">
                                            <span class="lever"></span>
                                            <?php echo translate('yes'); ?>
                                        </label>
                                    </div>
                                </div>

                            </div>
                            <hr/>
                            <div class="row mb-2">
                                <div class="col-md-4 ">
                                    <div class="form-group">
                                        <?php echo form_label(translate('display_datetime_form') . ' : ', 'display_record_per_page', array('class' => 'control-label')); ?>
                                        <select  onchange="update_date_time(this.value);" style="display: block !important;" name="time_format" id="time_format" class="form-control" >
                                            <optgroup label="12hr format">
                                                <option <?php echo ($time_format == "d/m/y h:i") ? "selected='selected'" : ""; ?> value="d/m/Y h:i"><?php echo date('d/m/Y h:i'); ?></option>
                                                <option <?php echo ($time_format == "d-m-Y h:i") ? "selected='selected'" : ""; ?> value="d-m-Y h:i"><?php echo date('d-m-Y h:i'); ?></option>
                                                <option <?php echo ($time_format == "m-d-Y h:i") ? "selected='selected'" : ""; ?>  value="m-d-Y h:i"><?php echo date('m-d-Y h:i'); ?></option>
                                                <option <?php echo ($time_format == "m/d/Y h:i") ? "selected='selected'" : ""; ?>  value="m/d/Y h:i"><?php echo date('m/d/Y h:i'); ?></option>
                                                <option <?php echo ($time_format == "Y/m/d h:i") ? "selected='selected'" : ""; ?>  value="Y/m/d h:i"><?php echo date('Y/m/d h:i'); ?></option>
                                                <option <?php echo ($time_format == "Y-m-d h:i") ? "selected='selected'" : ""; ?>  value="Y-m-d h:i"><?php echo date('Y-m-d h:i'); ?></option>
                                            </optgroup>

                                            <optgroup label="24hr formar">
                                                <option <?php echo ($time_format == "d-m-Y H:i") ? "selected='selected'" : ""; ?> value="d-m-Y H:i"><?php echo date('d-m-Y H:i'); ?></option>
                                                <option <?php echo ($time_format == "d/m/Y H:i") ? "selected='selected'" : ""; ?> value="d/m/Y H:i"><?php echo date('d/m/Y H:i'); ?></option>
                                                <option <?php echo ($time_format == "m-d-Y H:i") ? "selected='selected'" : ""; ?> value="m-d-Y H:i"><?php echo date('m-d-Y H:i'); ?></option>
                                                <option <?php echo ($time_format == "m/d/Y H:i") ? "selected='selected'" : ""; ?> value="m/d/Y H:i"><?php echo date('m/d/Y H:i'); ?></option>
                                                <option <?php echo ($time_format == "Y/m/d H:i") ? "selected='selected'" : ""; ?>  value="Y/m/d H:i"><?php echo date('Y/m/d H:i'); ?></option>
                                                <option <?php echo ($time_format == "Y-m-d H:i") ? "selected='selected'" : ""; ?>  value="Y-m-d H:i"><?php echo date('Y-m-d H:i'); ?></option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php echo form_label(translate('display') . ' ' . translate('records') . ' ' . translate('per_page') . ' : ', 'display_record_per_page', array('class' => 'control-label')); ?>
                                        <?php echo form_input(array('type' => 'number', 'id' => 'display_record_per_page', 'class' => 'form-control', 'name' => 'display_record_per_page', 'value' => $display_record_per_page, 'placeholder' => translate('display') . ' ' . translate('records') . ' ' . translate('per_page'), 'onblur' => 'update_display_setting(this)')); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php echo form_label(translate('footer') . ' ' . translate('text') . ' : ', 'footer_text', array('class' => 'control-label')); ?>
                                        <textarea class="form-control" id="footer_text" name="footer_text" placeholder="<?php echo translate("footer") . " " . translate("text"); ?>" onblur="update_display_setting(this);"><?php echo $footer_text; ?></textarea>
                                        <span id="spnCharLeft"></span>
                                    </div>
                                </div>
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
<script src="<?php echo $this->config->item('js_url'); ?>jquery.minicolors.js"></script>
<script src="<?php echo $this->config->item('js_url'); ?>module/sitesetting.js" type="text/javascript"></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
