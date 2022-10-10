<?php
include VIEWPATH . 'admin/header.php';

$id = isset($currency_data['id']) ? $currency_data['id'] : set_value('id');
$title = isset($currency_data['title']) ? $currency_data['title'] : set_value('title');
$code = isset($currency_data['code']) ? $currency_data['code'] : set_value('code');
$is_default = isset($currency_data['is_default']) ? $currency_data['is_default'] : set_value('is_default');
$currency_position = isset($currency_data['currency_position']) ? $currency_data['currency_position'] : set_value('currency_position');
$stripe_support = isset($currency_data['stripe_support']) ? $currency_data['stripe_support'] : set_value('stripe_support');
$paypal_support = isset($currency_data['paypal_support']) ? $currency_data['paypal_support'] : set_value('paypal_support');

$is_default = (set_value("is_default")) ? set_value("is_default") : (!empty($category_data) ? $currency_data['is_default'] : '');
$folder_name = 'admin';
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
            <section class="form-light px-2 sm-margin-b-20 ">
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12 m-auto">
                        <?php $this->load->view('message'); ?>

                        <div class="header bg-color-base p-3">
                            <?php if (isset($currency_data['id']) && $currency_data['id'] > 0): ?>
                                <h3 class="black-text mb-0 font-bold"><?php echo translate('update'); ?> <?php echo translate('currency'); ?></h3>
                            <?php else: ?>
                                <h3 class="black-text mb-0 font-bold"><?php echo translate('add'); ?> <?php echo translate('currency'); ?></h3>
                            <?php endif; ?>
                        </div>

                        <div class="card">
                            <div class="card-body resp_mx-0">
                                <?php
                                $attributes = array('id' => 'frmStaff', 'name' => 'frmCustomer', 'method' => "post");
                                echo form_open_multipart($folder_name . '/save-currency', $attributes);
                                ?>
                                <input type="hidden" id="folder_name" value="<?php echo $folder_name; ?>"/>
                                <input type="hidden" name="id" id="id" value="<?php echo isset($currency_data['id']) ? $currency_data['id'] : 0; ?>"/>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php echo form_label(translate('title') . ' : <small class ="required">*</small>', 'title', array('class' => 'control-label')); ?>
                                            <?php echo form_input(array('id' => 'title','autocomplete' => 'off', 'class' => 'form-control', 'name' => 'title', 'value' => $title, 'placeholder' => translate('title'))); ?>
                                            <?php echo form_error('title'); ?>
                                        </div>
                                        <div class="error" id="first_name_validate"></div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php echo form_label(translate('currency')." ".translate('code') . ' : <small class ="required">*</small>', 'code', array('class' => 'control-label')); ?>
                                            <?php echo form_input(array('id' => 'code','autocomplete' => 'off', 'class' => 'form-control', 'name' => 'code', 'value' => $code, 'placeholder' => translate('code'))); ?>
                                            <?php echo form_error('code'); ?>
                                        </div>
                                        <div class="error" id="last_name_validate"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php echo form_label('Currency Symbol' . ' : <small class ="required">*</small>', 'currency_code', array('class' => 'control-label')); ?>
                                            <?php echo form_input(array('id' => 'currency_code','autocomplete' => 'off', 'class' => 'form-control', 'name' => 'currency_code', 'value' => $title, 'placeholder' => 'Currency Code')); ?>
                                            <?php echo form_error('currency_code'); ?>
                                        </div>
                                        <div class="error" id="first_name_validate"></div>
                                    </div>
                                    
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-6 b-r">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success waves-effect" style="margin-top: 25px;"><?php echo translate('submit'); ?></button>
                                            <a href="<?php echo base_url('admin/currency'); ?>" class="btn btn-info waves-effect" style="margin-top: 25px;"><?php echo translate('cancel'); ?></a>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                        <!--/Form with header-->
                    </div>
                    <!--Card-->
                </div>
                <!-- End Col -->
            </section>
        </div>
        <!--Row-->
        <!-- End Login-->
    </div>
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/staff.js" type="text/javascript"></script>
<?php include VIEWPATH . $folder_name . '/footer.php'; ?>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#preview').attr('src', e.target.result);
                $('#image_validate').attr('value', 1);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#profile_image").change(function () {
        readURL(this);
    });
</script>