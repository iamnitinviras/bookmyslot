<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width">
        <link rel="icon" type="image/x-icon" href="<?php echo get_fevicon(); ?>"/>
        <!-- SITE META -->
        <title ><?php
            echo get_CompanyName();
            if (!empty($title))
                echo " | " . $title;
            ?></title>
        <!-- Font Awesome -->           <link href="<?php echo $this->config->item('css_url'); ?>font-awesome.css" rel="stylesheet" type="text/css"/>
        <!-- Bootstrap core CSS -->     <link href="<?php echo $this->config->item('css_url'); ?>bootstrap.css" rel="stylesheet" type="text/css"/>
        <!--Material Design Bootstrap--><link href="<?php echo $this->config->item('css_url'); ?>module/bookmyslot.css" rel="stylesheet" type="text/css"/>
        <!-- Your custom styles -->     <link href="<?php echo $this->config->item('css_url'); ?>module/admin_panel.css" rel="stylesheet" type="text/css"/>
        <!-- Your custom styles -->     <link href="<?php echo $this->config->item('css_url'); ?>module/custom.css" rel="stylesheet" type="text/css"/>
        <!-- J-Query -->      <script src="<?php echo $this->config->item('js_url'); ?>jquery-3.2.1.min.js" type="text/javascript"></script>
        <!-- Validation JS --><script src="<?php echo $this->config->item('js_url'); ?>jquery.validate.min.js"></script>
        <!-- Loader -->  <script src="<?php echo $this->config->item('assets_url'); ?>loader/js/jquery.preloader.min.js"></script>
        <!-- Loader -->  <link href ="<?php echo $this->config->item('assets_url'); ?>loader/css/preloader.css" rel="stylesheet">
    </head>
    <body>
        <!--Section-->
        <section class="form-light content-sm px-2 sm-margin-b-20" style="margin: 0 0 !important;">
            <!-- Row -->
            <div class="row">
                <!-- Col -->
                <div class="col-md-5 m-auto">
                    <a href="<?php echo base_url(); ?>">
                        <div class="text-center">
                            <img id="imageurl" class="img-responsive" style="" src="<?php echo get_CompanyLogo(); ?>" alt="<?php echo translate('image'); ?>">
                        </div>
                    </a>
                    <!--Form with header-->
                    <div class="card mt-3">
                        <!--Header-->
                        <div class="header p-4 btn-blue-grey">
                            <div class="text-center">
                                <h3 class="white-text mb-3 font-bold" style="margin-bottom: 0 !important;"><?php echo translate('forgot_password'); ?></h3>
                            </div>
                        </div>
                        <!--Header-->
                        <div class="p-4 resp_mx-0">
                            <?php $this->load->view('message'); ?>
                            <?php
                            $attributes = array('id' => 'Forgot_password', 'name' => 'Forgot_password', 'method' => "post");
                            echo form_open('admin/forgot-password-action', $attributes);
                            ?>
                            <div class="form-group">
                                <label for="email"> <?php echo translate('email'); ?> <small class="required">*</small></label>
                                <input type="email" autocomplete="off"  placeholder="<?php echo translate('email'); ?>" id="email" name="email" value='<?php if (!empty($enter_email)) echo $enter_email; ?>' class="form-control">                                
                                <?php echo form_error('email'); ?>
                            </div>
                            <!--Grid row-->
                            <div class="row ">
                                <div class="col-md-1 col-md-5">
                                    <button type="submit" class="btn  btn-blue-grey btn-rounded"><?php echo translate('submit'); ?></button>
                               </div>
                                <div class="col-md-7 text-right">
                                    <p class="font-small grey-text mt-3"><?php echo translate('return'); ?> <a href="<?php echo base_url("admin/login"); ?>" class="dark-grey-text ml-1 font-bold"> <?php echo translate('login'); ?></a></p>
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
        </section>
        <!-- End Section-->
        <!-- SCRIPTS -->
        <!-- knowledge base core JS -->      <script src="<?php echo $this->config->item('js_url'); ?>module/bookmyslot.js" type="text/javascript"></script>
        <!-- Bootstrap tooltips--><script src="<?php echo $this->config->item('js_url'); ?>popper.min.js" type="text/javascript"></script>
        <!-- Bootstrap core JS --><script src="<?php echo $this->config->item('js_url'); ?>bootstrap.min.js" type="text/javascript"></script>
        <!-- Bootstrap core JS --><script src="<?php echo $this->config->item('js_url'); ?>sidebar.js" type="text/javascript"></script>
        <!-- Custom Script -->    <script src="<?php echo $this->config->item('js_url'); ?>module/content.js" type="text/javascript"></script>
    </body>
</html>
