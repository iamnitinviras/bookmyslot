<?php include VIEWPATH . 'front/header.php'; ?>
<!--Section-->
<section class="form-light">
    <div class="container-fluid">
        <!-- Row -->
        <div class="row">
            <!-- Col -->

            <div class="col-lg-4 col-md-7 mx-md-auto my-0">
                <!--Form with header-->
                <div class="card my-4">

                    <!--Header-->
                    <div class="header">
                        <h3 class="my-3 text-center" style="margin-bottom: 0 !important;"><?php echo translate('forgot_password'); ?></h3>
                    </div>
                    <!--Header-->

                    <div class="card-body mt-4 resp_mx-0">
                        <?php $this->load->view('message'); ?>
                        <?php
                        $attributes = array('id' => 'Forgot_password', 'name' => 'Forgot_password', 'method' => "post");
                        echo form_open('vendor/forgot-password-action', $attributes);
                        ?>
                        <div class="form-group">
                            <label for="email"> <?php echo translate('email'); ?> <small class="required">*</small></label>
                            <input type="email" placeholder="<?php echo translate('email'); ?>" autocomplete="off"  id="email" name="email" value='<?php if (!empty($enter_email)) echo $enter_email; ?>' class="form-control">                                
                            <?php echo form_error('email'); ?>
                        </div>
                        <!--Grid row-->
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <button type="submit" class="btn btn-dark button_common"><?php echo translate('submit'); ?></button>
                            </div>

                            <div class="text-right col-6 col-md-6">
                                <p class="mt-3"><?php echo translate('return'); ?> <a href="<?php echo base_url("vendor/login"); ?>" class="dark-grey-text ml-1 font-bold"> <?php echo translate('login'); ?></a></p>
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
<!-- End Section-->

<!-- Custom Script -->
<script src="<?php echo $this->config->item('js_url'); ?>module/vendor_content.js" type="text/javascript"></script>
<?php include VIEWPATH . 'front/footer.php'; ?>