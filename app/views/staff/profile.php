<?php include VIEWPATH . 'staff/header.php'; ?>
<?php
$first_name = (set_value("first_name")) ? set_value("first_name") : $staff_data['first_name'];
$last_name = (set_value("last_name")) ? set_value("last_name") : $staff_data['last_name'];
$email = (set_value("email")) ? set_value("email") : $staff_data['email'];
$phone = (set_value("phone")) ? set_value("phone") : $staff_data['phone'];
$fb_link = (set_value("fb_link")) ? set_value("fb_link") : $staff_data['fb_link'];
$twitter_link = (set_value("twitter_link")) ? set_value("twitter_link") : $staff_data['twitter_link'];
$google_link = (set_value("google_link")) ? set_value("google_link") : $staff_data['google_link'];
$instagram_link = (set_value("instagram_link")) ? set_value("instagram_link") : $staff_data['instagram_link'];
$profile_text = (set_value("profile_text")) ? set_value("profile_text") : $staff_data['profile_text'];
$company_name = (set_value("company_name")) ? set_value("company_name") : $staff_data['company_name'];
$website = (set_value("website")) ? set_value("website") : $staff_data['website'];
$profile_image = $staff_data['profile_image'];
$profile_cover_image = $staff_data['profile_cover_image'];
?>
<!-- Additional method Script -->
<script src="<?php echo $this->config->item('js_url'); ?>module/additional-methods.js" type="text/javascript"></script>
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
                            <h3 class="black-text font-bold mb-0">
                                <?php echo translate('profile'); ?> <?php echo translate('update'); ?>
                            </h3>
                        </div>

                        <div class="card">
                            <div class="card-body resp_mx-0">
                                <?php
                                $attributes = array('id' => 'Profile', 'name' => 'Profile', 'method' => "post");
                                echo form_open_multipart('staff/profile-save', $attributes);
                                ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_name"> <?php echo translate('first_name'); ?> <small class="required">*</small></label>
                                            <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" class="form-control" placeholder="<?php echo translate('first'); ?><?php echo translate('name'); ?>">                                            
                                            <?php echo form_error('first_name'); ?>

                                        </div>
                                        <div class="error" id="first_name_validate"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name"> <?php echo translate('last_name'); ?> <small class="required">*</small></label>
                                            <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" class="form-control" placeholder="<?php echo translate('last'); ?><?php echo translate('name'); ?>">                                            
                                            <?php echo form_error('last_name'); ?>
                                        </div>
                                        <div class="error" id="last_name_validate"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email"> <?php echo translate('email'); ?> <small class="required">*</small></label>
                                            <input type="email" placeholder="<?php echo translate('email'); ?>" id="email" name="email" value="<?php echo $email; ?>" class="form-control">                                            
                                            <?php echo form_error('email'); ?>

                                        </div>
                                        <div class="error" id="email_validate"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone"> <?php echo translate('phone'); ?> <small class="required">*</small></label>
                                            <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" class="form-control" placeholder="<?php echo translate('phone'); ?>">                                            
                                            <?php echo form_error('phone'); ?>
                                        </div>
                                        <div class="error" id="phone_validate"></div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="select_image"> <?php echo translate('select'); ?> <?php echo translate('image'); ?> </label>
                                        <div class="file-field">
                                            <div class="btn btn-primary btn-sm">
                                                <span><?php echo translate('choose_file'); ?></span>
                                                <input onchange="readURL(this)" id="imageurl"  type="file" name="profile_image"/>
                                            </div>
                                            <div class="file-path-wrapper" style="padding-top: 4px;">
                                                <input class="file-path validate form-control readonly" readonly type="text" placeholder="<?php echo translate('upload'); ?> <?php echo translate('your'); ?> <?php echo translate('file'); ?>" value="<?php echo $profile_image; ?>">
                                            </div>
                                            <?php echo form_error('profile_image'); ?>
                                        </div>
                                        <div class="error" id="Pro_img_validate"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <?php
                                        if (file_exists(dirname(BASEPATH) . "/" . uploads_path . "/profiles/" . $staff_data['profile_image']) && $staff_data['profile_image'] != '') {
                                            $img_src = base_url() . uploads_path . "/profiles/" . $staff_data['profile_image'];
                                        } else {
                                            $img_src = base_url() . img_path . "/user.png";
                                        }
                                        ?> 
                                        <h5 style="font-size: .8rem; color: #757575"><?php echo translate('profile_image'); ?> </h5>
                                        <img id="imageurl"  class="img"  style="border-radius:50%;" src="<?php echo $img_src; ?>" alt="<?php echo translate('profile'); ?> <?php echo translate('image'); ?>" width="100" height="100">
                                    </div>
                                </div>
                                <div class="md-form ">
                                    <button type="submit" class="btn btn-success waves-effect"><?php echo translate('update'); ?></button>
                                    <a  class="btn btn-info waves-effect" href="<?php echo base_url('staff/dashboard'); ?>"><?php echo translate('cancel'); ?></a>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                            <!--/Form with header-->
                        </div>
                        <!--Card-->
                    </div>
                    <!-- End Col -->
                </div>
                <!--Row-->
            </section>
            <!-- End Login-->
        </div>
    </div>
</div>
<!-- Custom Script -->
<script src="<?php echo $this->config->item('js_url'); ?>module/staff_content.js" type="text/javascript"></script>
<?php include VIEWPATH . 'staff/footer.php'; ?> 