<?php include VIEWPATH . 'front/header.php'; ?>
<?php
$first_name = (set_value("first_name")) ? set_value("first_name") : $customer_data['first_name'];
$last_name = (set_value("last_name")) ? set_value("last_name") : $customer_data['last_name'];
$email = (set_value("email")) ? set_value("email") : $customer_data['email'];
$phone = (set_value("phone")) ? set_value("phone") : $customer_data['phone'];
$profile_image = set_value("profile_image") ? set_value("profile_image") : $customer_data['profile_image'];

if (file_exists(FCPATH . uploads_path . "/profiles/" . $customer_data['profile_image']) && $customer_data['profile_image'] != '') {
    $img_src = base_url() . uploads_path . "/profiles/" . $customer_data['profile_image'];
} else {
    $img_src = base_url() . img_path . "/user.png";
}
?>
<!-- Custom Script -->
<script src="<?php echo $this->config->item('js_url'); ?>module/additional-methods.js" type="text/javascript"></script>
<link href="<?php echo $this->config->item('css_url'); ?>module/user_panel.css" rel="stylesheet"/>
<div class="container  mt-20" style="min-height:653px;">
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
                            <li class="active"><a href="<?php echo base_url('profile'); ?>"><span class="fa fa-user"></span> <?php echo translate('profile'); ?></a></li>
                            <li><a href="<?php echo base_url('change-password'); ?>"><span class="fa fa-cog"></span> <?php echo translate('Change_password'); ?></a></li>
                            
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
                    <h5 class="card-title mb-0"><?php echo translate('profile'); ?></h5>
                </div>
                <div class="card-body h-100">
                    <div class="row">
                        <div class="col-md-12 m-auto">
                            <?php $this->load->view('message'); ?>
                            <?php
                            $attributes = array('id' => 'UserProfile', 'name' => 'Profile', 'method' => "post");
                            echo form_open_multipart('profile-save', $attributes);
                            ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group resp_mb-0">
                                        <label for="first_name"> <?php echo translate('first_name'); ?> <small class="required">*</small></label>
                                        <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" class="form-control" placeholder="<?php echo translate('first_name'); ?>">                                            
                                        <?php echo form_error('firstname'); ?>

                                    </div>
                                    <div class="error" id="first_name_validate"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group resp_mb-0">
                                        <label for="Lastname"> <?php echo translate('last_name'); ?> <small class="required">*</small></label>
                                        <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" class="form-control" placeholder="<?php echo translate('last_name'); ?>">                                            
                                        <?php echo form_error('last_name'); ?>
                                    </div>
                                    <div class="error" id="last_name_validate"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group resp_mb-0">
                                        <label for="Email"> <?php echo translate('email'); ?> <small class="required">*</small></label>
                                        <input type="email" id="email" name="email" value="<?php echo $email; ?>" class="form-control" placeholder="<?php echo translate('email'); ?>">                                            
                                        <?php echo form_error('email'); ?>

                                    </div>
                                    <div class="error" id="email_validate"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group resp_mb-0">
                                        <label for="phone"> <?php echo translate('phone'); ?></label>
                                        <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" class="form-control integers" placeholder="<?php echo translate('phone'); ?>" maxlength="10" minlength="10">                                            
                                        <?php echo form_error('phone'); ?>
                                    </div>
                                    <div class="error" id="Phone_validate"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group resp_mb-0">
                                        <label><?php echo translate('select'); ?> <?php echo translate('image'); ?></label>                                        
                                        <div class="file-field">
                                            <div class="btn btn-primary btn-sm">
                                                <span><?php echo translate('choose_file'); ?></span>
                                                <input onchange="readURL(this)" id="imageurl"  type="file" name="profile_image"/>
                                            </div>
                                            <div class="file-path-wrapper" style="padding-top: 4px;">
                                                <input class="file-path validate form-control readonly" readonly type="text" placeholder="<?php echo translate('upload_your_file'); ?>" >
                                            </div>
                                            <?php echo form_error('profile_image'); ?>
                                        </div>
                                        <div class="error" id="profile_image_validate"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 profile-img">
                                    <div class="form-group resp_mb-0">
                                        <?php
                                        if (file_exists(FCPATH . uploads_path . "/profiles/" . $customer_data['profile_image']) && $customer_data['profile_image'] != '') {
                                            $img_src = base_url() . uploads_path . "/profiles/" . $customer_data['profile_image'];
                                        } else {
                                            $img_src = base_url() . img_path . "/user.png";
                                        }
                                        ?> 
                                        <img id="imageurl"  class="img-thumbnail img-fluid p-8p"  style="border-radius:50%;" src="<?php echo $img_src; ?>" alt="<?php echo translate('profile'); ?> <?php echo translate('image'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <button type="submit" class="btn btn-dark button_common waves-effect"><?php echo translate('update'); ?></button>
                            </div>
                            <?php echo form_close(); ?>
                            <!--/Form with header-->
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