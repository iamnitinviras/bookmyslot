<?php include VIEWPATH . 'front/header.php'; ?>
<!--Section-->
<section class="form-light">
    <div class="container-fluid">
        <!-- Row -->
        <div class="row">

            <div class="col-lg-4 col-md-7 mx-md-auto my-4">
                <!--Form with header-->
                <div class="card my-3">
                    <!--Header-->
                    <div class="header">
                        <h3 class="my-3 text-center"><?php echo translate('forgot_password'); ?></h3>
                    </div>
                    <!--Header-->

                    <div class="card-body mt-2 resp_mx-0">
                        <?php $this->load->view('message'); ?>
                        <?php
                        $attributes = array('id' => 'Forgot_password', 'name' => 'Forgot_password', 'method' => "post");
                        echo form_open('forgot-password-action', $attributes);
                        ?>
                        <div class="form-group">
                            <label for="email"> <?php echo translate('email'); ?> <small class="required">*</small></label>
                            <input type="email" id="email" name="email"  autocomplete="off"  value='<?php echo set_value('email'); ?>'  class="form-control" placeholder="<?php echo translate('email'); ?>">                                
                            <?php echo form_error('email'); ?>
                        </div>
                        <!--Grid row-->
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <button type="submit" class="btn btn-dark button_common"><?php echo translate('submit'); ?></button>
                            </div>
                            <div class="col-md-6 col-6 text-right">
                                <p class="mt-3"><?php echo translate('return'); ?> <a href="<?php echo base_url("login"); ?>" class="ml-1 font-bold"> <?php echo translate('login'); ?></a></p>
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