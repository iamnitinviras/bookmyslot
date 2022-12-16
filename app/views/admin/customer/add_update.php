<?php
$first_name = isset($customer_data['first_name']) ? $customer_data['first_name'] : set_value('first_name');
$last_name = isset($customer_data['last_name']) ? $customer_data['last_name'] : set_value('last_name');
$email = isset($customer_data['email']) ? $customer_data['email'] : set_value('email');
$phone = isset($customer_data['phone']) ? $customer_data['phone'] : set_value('phone');
if ($this->session->userdata('Type_' . ucfirst($this->uri->segment(1))) == 'V') {
    include VIEWPATH . 'vendor/header.php';
    $folder_name = 'vendor';
} else {
    include VIEWPATH . 'admin/header.php';
    $folder_name = 'admin';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            <h4 class="card-title"><?php echo translate('customer'); ?></h4>
                            <div class="page-title-box pb-0 d-sm-flex">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>"><?php echo translate('dashboard'); ?></a></li>
                                        <li class="breadcrumb-item"><a href="<?php echo base_url($folder_name.'/customer'); ?>"><?php echo translate('customer'); ?></a></li>
                                        <li class="breadcrumb-item active">
                                            <?php if (isset($customer_data['id']) && $customer_data['id'] > 0): ?>
                                                <?php echo translate('update'); ?> <?php echo translate('customer'); ?>
                                            <?php else: ?>
                                                <?php echo translate('add'); ?> <?php echo translate('customer'); ?>
                                            <?php endif; ?>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-auto">

                            <?php $this->load->view('message'); ?>
                            <?php
                            $attributes = array('id' => 'frmCustomer', 'name' => 'frmCustomer', 'method' => "post");
                            echo form_open_multipart($folder_name . '/save-customer', $attributes);
                            ?>
                            <div class="card">
                                <div class="card-body">
                                    <input type="hidden" name="customer_id" id="customer_id" value="<?php echo isset($customer_data['id']) ? $customer_data['id'] : 0; ?>"/>
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo form_label(translate('first_name') . ':<small class ="required">*</small>', 'first_name', array('class' => 'control-label')); ?>
                                                <?php echo form_input(array('autocomplete' => "off", 'id' => 'first_name', 'class' => 'form-control', 'name' => 'first_name', 'value' => $first_name, 'placeholder' => translate('first_name'))); ?>
                                                <?php echo form_error('first_name'); ?>
                                            </div>
                                            <div class="error" id="first_name_validate"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo form_label(translate('last_name') . ':<small class ="required">*</small>', 'last_name', array('class' => 'control-label')); ?>
                                                <?php echo form_input(array('autocomplete' => "off",'id' => 'last_name', 'class' => 'form-control', 'name' => 'last_name', 'value' => $last_name, 'placeholder' => translate('last_name'))); ?>
                                                <?php echo form_error('last_name'); ?>
                                            </div>
                                            <div class="error" id="last_name_validate"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo form_label(translate('email') . ':<small class ="required">*</small>', 'email', array('class' => 'control-label')); ?>
                                                <?php echo form_input(array('autocomplete' => "off",'type' => 'email', 'id' => 'email', 'class' => 'form-control', 'name' => 'email', 'value' => $email, 'placeholder' => translate('email'))); ?>
                                                <?php echo form_error('email'); ?>
                                            </div>
                                            <div class="error" id="email_validate"></div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?php echo form_label(translate('phone') . ':', 'phone', array('class' => 'control-label')); ?>
                                                <?php echo form_input(array('autocomplete' => "off",'minlength' => "10", 'maxlength' => "10", 'id' => 'phone', 'class' => 'form-control', 'name' => 'phone', 'value' => $phone, 'placeholder' => translate('phone') . ' ' . translate('phone'))); ?>
                                                <?php echo form_error('phone'); ?>
                                            </div>
                                            <div class="error" id="phone_validate"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success waves-effect"><?php echo translate('submit'); ?></button>
                                        <a href="<?php echo base_url('admin/customer'); ?>" class="btn btn-info waves-effect"><?php echo translate('cancel'); ?></a>
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
</div>
<script src="<?php echo $this->config->item('js_url'); ?>module/customer.js" type="text/javascript"></script>
<?php include VIEWPATH . 'admin/footer.php'; ?>
