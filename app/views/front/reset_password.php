<?php include VIEWPATH . 'front/header.php'; ?>
<!--Section-->
<section class="form-light">
    <div class="container-fluid">
        <!-- Row -->
        <div class="row">
            <!-- Col -->
            <div class="col-lg-4 col-md-7 mx-md-auto my-4">
                <!--Form with header-->
                <div class="card my-3">
                    <?php $this->load->view('message'); ?>
                    <!--Header-->
                    <div class="header">
                        <h3 class="my-3 text-center"><?php echo translate('reset_password'); ?></h3>
                    </div>
                    <!--Header-->
                    <div class="card-body mt-4 resp_mx-0">
                        <?php
                        $hidden = array("id" => $id);
                        $attributes = array('id' => 'Reset_password', 'name' => 'Reset_password', 'method' => "post");
                        echo form_open('reset-password-action', $attributes, $hidden);
                        ?>
                        <div class="form-group">
                            <label for="password"> <?php echo translate('password'); ?> <small class="required">*</small></label>
                            <input type="password" required="" id="password" autocomplete="off"  name="password" value="" class="form-control" placeholder="<?php echo translate('password'); ?>">                                
                            <?php echo form_error('password'); ?>
                        </div>
                        <div class="form-group">
                            <label for="Cpassword"><?php echo translate('confirm_password'); ?><small class="required">*</small></label>
                            <input type="password" required="" id="Cpassword"  autocomplete="off" name="Cpassword" value="" class="form-control" placeholder="<?php echo translate('confirm_password'); ?>">                                
                            <?php echo form_error('Cpassword'); ?>
                        </div>
                        <!--Grid row-->

                        <div class="row">
                            <div class="col-md-6 col-6">
                                <button type="submit" class="btn btn-dark button_common"><?php echo translate('submit'); ?></button>
                            </div>

                            <div class="n_page-redirect col-6 col-md-6 text-right">
                                <p class="mt-3"><?php echo translate('now'); ?> <a href="<?php echo base_url("login"); ?>" class="ml-1 font-bold"> <?php echo translate('login'); ?></a></p>
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
<script src="<?php echo $this->config->item('js_url'); ?>module/content.js" type="text/javascript"></script>
<?php include VIEWPATH . 'front/footer.php'; ?>