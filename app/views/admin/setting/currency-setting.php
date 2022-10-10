<?php
include VIEWPATH . 'admin/header.php';
$currency_id = isset($currency_data['currency_id']) ? $currency_data['currency_id'] : 25;
$currency_position = isset($currency_data['currency_position']) ? $currency_data['currency_position'] : set_value('currency_position');
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
                                    <li class="active">
                                        <a href="<?php echo base_url('admin/currency-setting'); ?>"><?php echo translate('currency') . ' ' . translate('setting'); ?></a>
                                    </li>
                                    <li>
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
                        <h3 class="black-text font-bold mb-0"><?php echo translate('manage'); ?> <?php echo translate('currency'); ?> <?php echo translate('setting'); ?></h3>
                    </div>

                    <div class="card">
                        <div class="card-body resp_mx-0">
                            <?php echo form_open('admin/sitesetting/save_curenncy_setting', array('name' => 'site_business_form', 'id' => 'site_business_form')); ?>
                            <div class="row">
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <?php echo form_label(translate('currency'), 'currency', array('class' => 'control-label')); ?>
                                        <select style="display:block !important;" class="form-control" id="currency_id" name="currency_id">
                                            <?php foreach($app_currency as $val): ?>
                                                <option <?php echo ($currency_id==$val['id'])?'selected="selected"':"";?> value="<?php echo $val['id']; ?>"><?php echo $val['title']." (".$val['currency_code'].")"; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        
                                    </div>
                                    <div class="error" id="commission_percentage"></div>
                                </div>
                                <div class="col-md-6 ">
                                <?php echo form_label('Currency Display Position', 'currency', array('class' => 'control-label')); ?>
                                        <select style="display:block !important;"  class="form-control" id="currency_position" name="currency_position">
                                            <option <?php echo ($currency_position=='L')?'selected="selected"':"";?> value="L">Left</option>
                                            <option <?php echo ($currency_position=='R')?'selected="selected"':"";?> value="R">Right</option>
                                        </select>
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
