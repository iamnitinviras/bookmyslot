<?php include VIEWPATH . 'front/header.php'; ?>
<!-- Start Content -->
<script src="<?php echo $this->config->item('js_url'); ?>module/additional-methods.js" type="text/javascript"></script>

<section class="form-light">
    <div class="container-fluid">
        <!-- Row -->
        <div class="row">
            <div class="col-lg-4 col-md-7 mx-md-auto my-4">

                <div class="card my-3">
                    <div class="header">
                        <h3 class="my-3 text-center"><?php echo translate('user_registration'); ?></h3>
                    </div>

                    <div class="n_page-redirect">
                        <p><?php echo translate('already_created_account?'); ?> <a href="<?php echo base_url("login"); ?>" class="ml-1 font-bold"> <?php echo translate('login'); ?></a></p>
                    </div>

                    <div class="card-body mt-4 resp_mx-0">
                        <?php $this->load->view('message'); ?>
                        <?php
                        $attributes = array('id' => 'Register_user', 'name' => 'Register_user', 'method' => "post");
                        echo form_open_multipart('register-save', $attributes);
                        ?>
                        <input type="hidden" id="next" name="next" value="<?php echo isset($next) ? $next : set_value('next'); ?>"/>
                        <div class="form-group">
                            <label for="first_name"> <?php echo translate('first_name'); ?> <small class="required">*</small></label>
                            <input type="text" value="<?php echo set_value('first_name'); ?>" id="first_name" name="first_name" autocomplete="off"  class="form-control" placeholder="<?php echo translate('first_name'); ?>">                                        
                            <?php echo form_error('first_name'); ?>
                        </div>
                        <div class="error" id="first_name_validate"></div>
                        <div class="form-group">
                            <label for="last_name"> <?php echo translate('last_name'); ?><small class="required">*</small></label>
                            <input type="text"  value="<?php echo set_value('last_name'); ?>" id="last_name" name="last_name"  autocomplete="off" class="form-control" placeholder="<?php echo translate('last_name'); ?>">                                        
                            <?php echo form_error('last_name'); ?>
                        </div>
                        <div class="error" id="last_name_validate"></div>
                        <div class="form-group">
                            <label for="email"> <?php echo translate('email'); ?> <small class="required">*</small></label>
                            <input type="email"  value="<?php echo set_value('email'); ?>" id="email" name="email"  autocomplete="off" class="form-control" placeholder="<?php echo translate('email'); ?>">                                        
                            <?php echo form_error('email'); ?>
                        </div>
                        <div class="error" id="email_validate"></div>
                        <div class="form-group">
                            <label for="password"> <?php echo translate('password'); ?> <small class="required mr-5px">*</small>
                                <i class="fa fa-info-circle" tabindex="0" data-html="true" data-toggle="popover" title="<b>Password</b> - Rules" data-content='<span class="d-block"><b> <?php echo translate('info'); ?> - </b></span><span class="d-block">- <?php echo translate('password_length'); ?></span>'></i></label>
                            <input type="password"  id="password" name="password" class="form-control" placeholder="<?php echo translate('password'); ?>">                                        
                            <?php echo form_error('password'); ?>
                        </div>
                        <div class="error" id="password_validate"></div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-dark button_common"> <?php echo translate('register'); ?> </button>                        
                        </div>

                        <?php echo form_close(); ?>

                    </div>
                    <!--/Form with header-->
                </div>
            </div>
            <!-- End Col -->
        </div>
        <!--Row-->
    </div>
</section>

<script src="<?php echo $this->config->item('js_url'); ?>module/register.js" type="text/javascript"></script>
<?php include VIEWPATH . 'front/footer.php'; ?>