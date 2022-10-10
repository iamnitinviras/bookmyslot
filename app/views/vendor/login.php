<?php include VIEWPATH . 'front/header.php'; ?>
<section class="form-light">
    <div class="container-fluid">
        <!-- Row -->
        <div class="row">
            <!-- Col -->

            <div class="col-lg-4 col-md-7 mx-md-auto my-4">
                <!--Form with header-->
                <div class="card my-3">
                    <!--Header-->
                    <div class="header">
                        <h3 class="text-center my-3"><?php echo translate('vendor_login'); ?></h3>
                    </div>
                    <!--Header-->

                    <div class="n_page-redirect">
                        <p><?php echo translate('dont_have_account'); ?>? : <a href="<?php echo base_url("vendor-register"); ?>" class="ml-1 font-bold"> <?php echo translate('create_account'); ?></a></p>
                    </div>

                    <div class="card-body mt-4 resp_mx-0">
                        <?php $this->load->view('message'); ?>
                        <?php
                        $attributes = array('id' => 'Login', 'name' => 'Login', 'method' => "post");
                        echo form_open('vendor/login-action', $attributes);
                        ?>
                        <div class="form-group">
                            <label for="username"> <?php echo translate('email'); ?> <small class="required">*</small></label>
                            <input type="email" placeholder="<?php echo translate('email'); ?>" autocomplete="off"  id="username" name="username" value='<?php if (!empty($enter_email)) echo $enter_email; ?>' class="form-control">                                
                            <?php echo form_error('username'); ?>
                        </div>
                        <div class="form-group">
                            <label for="password"> <?php echo translate('password'); ?> <small class="required">*</small></label>
                            <input type="password" placeholder="<?php echo translate('password'); ?>" id="password" name="password" class="form-control">                                
                            <?php echo form_error('password'); ?>
                        </div>
                        <!--Grid row-->
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <button type="submit" class="btn btn-dark button_common color-white"><?php echo translate('login'); ?></button>
                            </div>
                            <div class="col-md-6 col-6 text-right">
                                <p class="mt-3"> <a href="<?php echo base_url("vendor/forgot-password"); ?>" class="dark-grey-text ml-1 font-bold"><?php echo translate('forgot_password'); ?>?</a></p>
                            </div>
                        </div>
                        <!--Grid row-->
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <!--/Form with header-->
            </div>
            <!-- End Col -->
        </div>
        <!-- End Row -->
    </div>
</section>

<!-- Custom Script -->
<script src="<?php echo $this->config->item('js_url'); ?>module/vendor_content.js" type="text/javascript"></script>
<?php include VIEWPATH . 'front/footer.php'; ?>