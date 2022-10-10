<?php
$first_name = isset($vendor_data['first_name']) ? $vendor_data['first_name'] : set_value('first_name');
$last_name = isset($vendor_data['last_name']) ? $vendor_data['last_name'] : set_value('last_name');
$email = isset($vendor_data['email']) ? $vendor_data['email'] : set_value('email');
$company = isset($vendor_data['company_name']) ? $vendor_data['company_name'] : set_value('company');
$website = isset($vendor_data['website']) ? $vendor_data['website'] : set_value('website');
$phone = isset($vendor_data['phone']) ? $vendor_data['phone'] : set_value('phone');
$address = isset($vendor_data['address']) ? $vendor_data['address'] : set_value('address');
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
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
            <section class="form-light px-2 sm-margin-b-20 ">
                <!-- Row -->
                <div class="row">
                    <div class="col-md-12 m-auto">
                        <?php $this->load->view('message'); ?>

                        <div class="header bg-color-base p-3">
                            <?php if (isset($vendor_data['id']) && $vendor_data['id'] > 0): ?>
                                <h3 class="black-text mb-0 font-bold"><?php echo translate('update'); ?> <?php echo translate('vendor'); ?></h3>
                            <?php else: ?>
                                <h3 class="black-text mb-0 font-bold"><?php echo translate('add'); ?> <?php echo translate('vendor'); ?></h3>
                            <?php endif; ?>
                        </div>

                        <div class="card">
                            <div class="card-body resp_mx-0">
                                <?php $this->load->view('message'); ?>
                                <?php
                                $attributes = array('id' => 'Register_user', 'name' => 'Register_user', 'method' => "post");
                                echo form_open_multipart($folder_name . '/save-vendor', $attributes);
                                ?>
                                <input type="hidden" name="vendor_id" id="vendor_id" value="<?php echo isset($vendor_data['id']) ? $vendor_data['id'] : 0; ?>"/>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php echo form_label(translate('first_name') . ' : <small class ="required">*</small>', 'first_name', array('class' => 'control-label')); ?>
                                            <?php echo form_input(array('autocomplete' => 'off', 'id' => 'first_name', 'class' => 'form-control', 'name' => 'first_name', 'value' => $first_name, 'placeholder' => translate('first_name'))); ?>
                                            <?php echo form_error('first_name'); ?>
                                        </div>
                                        <div class="error" id="first_name_validate"></div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php echo form_label(translate('last_name') . ' : <small class ="required">*</small>', 'last_name', array('class' => 'control-label')); ?>
                                            <?php echo form_input(array('autocomplete' => 'off','id' => 'last_name', 'class' => 'form-control', 'name' => 'last_name', 'value' => $last_name, 'placeholder' => translate('last_name'))); ?>
                                            <?php echo form_error('last_name'); ?>
                                        </div>
                                        <div class="error" id="last_name_validate"></div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php echo form_label(translate('email') . ' : <small class ="required">*</small>', 'email', array('class' => 'control-label')); ?>
                                            <?php echo form_input(array('autocomplete' => 'off','type' => 'email', 'id' => 'email', 'class' => 'form-control', 'name' => 'email', 'value' => $email, 'placeholder' => translate('email'))); ?>
                                            <?php echo form_error('email'); ?>
                                        </div>
                                        <div class="error" id="email_validate"></div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="address"> <?php echo translate('address'); ?> <small class="required">*</small></label>
                                            <textarea class="form-control" name="address" id="address" placeholder="<?php echo translate('address'); ?>"><?php echo $address; ?></textarea>
                                            <?php echo form_error('address'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php echo form_label(translate('phone') . ' : <small class ="required">*</small>', 'phone', array('class' => 'control-label')); ?>
                                            <?php echo form_input(array('autocomplete' => 'off','minlength' => "10", 'maxlength' => "10", 'id' => 'phone', 'class' => 'form-control', 'name' => 'phone', 'value' => $phone, 'placeholder' => translate('phone') . ' ' . translate('phone'))); ?>
                                            <?php echo form_error('phone'); ?>
                                        </div>
                                        <div class="error" id="phone_validate"></div>
                                    </div> 
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php echo form_label(translate('company') . ' : <small class ="required">*</small>', 'company', array('class' => 'control-label')); ?>
                                            <?php echo form_input(array('autocomplete' => 'off','id' => 'company', 'class' => 'form-control', 'name' => 'company', 'value' => $company, 'placeholder' => translate('company'))); ?>
                                            <?php echo form_error('company'); ?>
                                        </div>
                                        <div class="error" id="company_username_validate"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <?php echo form_label(translate('website'), 'website', array('class' => 'control-label')); ?>
                                            <?php echo form_input(array('autocomplete' => 'off','id' => 'website', 'class' => 'form-control', 'type' => 'text', 'name' => 'website', 'value' => $website, 'placeholder' => translate('website'))); ?>
                                            <?php echo form_error('website'); ?>
                                        </div>
                                        <div class="error" id="website_validate"></div>
                                    </div> 

                                </div>
                                <div class="row">
                                    <div class="col-sm-6 b-r">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success waves-effect"><?php echo translate('submit'); ?></button>
                                            <a href="<?php echo base_url('admin/vendor'); ?>" class="btn btn-info waves-effect"><?php echo translate('cancel'); ?></a>
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
<script src="<?php echo $this->config->item('js_url'); ?>module/vendor_register.js" type="text/javascript"></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
