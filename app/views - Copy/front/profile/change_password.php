<?php include VIEWPATH . 'front/header.php'; ?>
<?php
$customer_data = get_CustomerDetails();
$first_name = $customer_data['first_name'];
$last_name = $customer_data['last_name'];
$email = $customer_data['email'];

if (file_exists(FCPATH . uploads_path . "/profiles/" . $customer_data['profile_image']) && $customer_data['profile_image'] != '') {
    $img_src = base_url() . uploads_path . "/profiles/" . $customer_data['profile_image'];
} else {
    $img_src = base_url() . img_path . "/user.png";
}
?>
<!-- Custom Script -->
<script src="<?php echo $this->config->item('js_url'); ?>module/additional-methods.js" type="text/javascript"></script>
<link href="<?php echo $this->config->item('css_url'); ?>module/user_panel.css" rel="stylesheet"/>
<div class="container mt-20"  style="min-height:653px;">
    <div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card mb-3">

                <div class="card-body text-center">
                    <img src="<?php echo $img_src; ?>" alt="<?php echo $first_name . " " . $last_name; ?>" class="rounded-circle mb-2" width="100" height="100"/>
                    <h4 class="card-title mb-0"><?php echo $first_name . " " . $last_name; ?></h4>
                </div>

                <hr class="my-0">
                <div class="card-body">
                    <nav class="side-menu">
                        <ul class="nav">
                            
                            <li><a href="<?php echo base_url('profile'); ?>"><span class="fa fa-user"></span> <?php echo translate('profile'); ?></a></li>
                            <li class="active"><a href="<?php echo base_url('change-password'); ?>"><span class="fa fa-cog"></span> <?php echo translate('Change_password'); ?></a></li>

                            <?php if (get_site_setting('enable_service') == 'Y'): ?>
                                <li><a href="<?php echo base_url('appointment'); ?>"><span class="fa fa-clock-o"></span> <?php echo translate('my_appointment'); ?></a></li>
                                <?php endif; ?>

                                <?php if (get_site_setting('enable_event') == 'Y'): ?>
                            <li><a href="<?php echo base_url('event-booking'); ?>"><span class="fa fa-ticket"></span> <?php echo translate('event') . " " . translate('booking'); ?></a></li>
                            <?php endif; ?>
                            
                            <li><a href="<?php echo base_url('payment-history'); ?>"><span class="fa fa-credit-card"></span> <?php echo translate('payment_history'); ?></a></li>
                            <li><a href="<?php echo base_url('logout'); ?>"><span class="fa fa-power-off"></span> <?php echo translate('logout'); ?></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-xl-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><?php echo translate('change_password'); ?></h5>
                </div>
                <div class="card-body h-100">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>
                            <?php
                            $hidden = array("ID" => $ID);
                            $attributes = array('id' => 'Update_password', 'name' => 'Update_password', 'method' => "post");
                            echo form_open('update-password-action', $attributes);
                            ?>
                            <div class="form-group">
                                <label for="old_password"> <?php echo translate('current'); ?> <?php echo translate('password'); ?> <small class="required">*</small></label>
                                <input type="password" id="old_password" name="old_password" class="form-control" placeholder="<?php echo translate('current'); ?> <?php echo translate('password'); ?>">                                    
                                <?php echo form_error('old_password'); ?>
                            </div>
                            <div class="form-group">
                                <label for="password"> <?php echo translate('password'); ?> <small class="required">*</small></label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="<?php echo translate('password'); ?>">                                    
                                <?php echo form_error('password'); ?>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password"> <?php echo translate('confirm_password'); ?> <small class="required">*</small></label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="<?php echo translate('confirm_password'); ?>">                                    
                                <?php echo form_error('confirm_password'); ?>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-dark button_common"><?php echo translate('update'); ?></button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                        <!-- End Col -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Custom Script -->
<script src="<?php echo $this->config->item('js_url'); ?>module/content.js" type="text/javascript"></script>
<?php include VIEWPATH . 'front/footer.php'; ?>