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

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?php echo translate('currency'); ?> <?php echo translate('setting'); ?></h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                        <li class="breadcrumb-item active"><?php echo translate('currency'); ?></li>
                    </ol>
                </div>
                <div class="card-body">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active"  href="<?php echo base_url('admin/setting/site'); ?>" role="tab">
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
                            <a class="nav-link"  href="<?php echo base_url('admin/setting/vendor'); ?>" role="tab">
                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                <span class="d-none d-sm-block"><?php echo translate('vendor') . ' ' . translate('setting'); ?></span>
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content p-3 text-muted">
                        <div class="tab-pane active" role="tabpanel">
                            <?php $this->load->view('message'); ?>
                            <?php echo form_open('admin/sitesetting/save_curenncy_setting', array('name' => 'site_business_form', 'id' => 'site_business_form')); ?>
                            <div class="row mb-5">
                                <div class="col-md-3">
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
                                <div class="col-md-3">
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
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/sitesetting.js" type="text/javascript"></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
