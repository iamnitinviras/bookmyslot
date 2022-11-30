<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
        <meta name="viewport" content="width=device-width">
        <link rel="icon" type="image/x-icon" href="<?php echo get_fevicon(); ?>"/>
        <!-- SITE META -->
        <title ><?php
            echo get_CompanyName();
            if (!empty($title))
                echo " | " . $title;
            ?></title>
        <!-- Font Awesome -->           
        <link href="<?php echo $this->config->item('css_url'); ?>font-awesome.css" rel="stylesheet" type="text/css"/>
        <!-- Bootstrap core CSS -->     
        <link href="<?php echo $this->config->item('css_url'); ?>bootstrap.css" rel="stylesheet" type="text/css"/>
        <!--Material Design Bootstrap-->
        <link href="<?php echo $this->config->item('css_url'); ?>module/bookmyslot.css" rel="stylesheet" type="text/css"/>
        <!-- Your custom styles -->     
        <link href="<?php echo $this->config->item('css_url'); ?>module/admin_panel.css" rel="stylesheet" type="text/css"/>
        <!-- Your custom styles -->     
        <link href="<?php echo $this->config->item('css_url'); ?>module/custom.css" rel="stylesheet" type="text/css"/>

        <!-- J-Query -->      
        <script src="<?php echo $this->config->item('js_url'); ?>jquery-3.2.1.min.js" type="text/javascript"></script>
        <!-- Validation JS -->
        <script src="<?php echo $this->config->item('js_url'); ?>jquery.validate.min.js"></script>

        <!-- Loader -->  
        <script src="<?php echo $this->config->item('assets_url'); ?>loader/js/jquery.preloader.min.js"></script>
        <!-- Loader -->  
        <link href ="<?php echo $this->config->item('assets_url'); ?>loader/css/preloader.css" rel="stylesheet">
    </head>
    <body>

        <!--Section-->
        <section class="form-light content-sm px-2 sm-margin-b-20 ">
            <!-- Row -->
            <div class="row">
                <!-- Col -->
                <div class="col-md-5 m-auto">
                    <a href="<?php echo base_url(); ?>">
                        <div class="text-center">
                            <img id="imageurl" class="img-responsive" style="" src="<?php echo get_CompanyLogo(); ?>" alt="<?php echo translate('image'); ?>">
                        </div>
                    </a>
                    <div class="card mt-3">
                        <?php $this->load->view('message'); ?>
                        <!--Header-->
                        <div class="header p-4 btn-blue-grey">
                            <div class="text-center">
                                <h3 class="white-text mb-3 font-bold" style="margin-bottom: 0 !important;"><?php echo translate('reset_password'); ?></h3>
                            </div>
                        </div>
                        <!--Header-->

                        <!--Header-->
                        <div class="card-body mx-4 mt-4 resp_mx-0">
                            <?php
                            $hidden = array("id" => $id);
                            $attributes = array('id' => 'Reset_password', 'name' => 'Reset_password', 'method' => "post");
                            echo form_open('admin/reset-password-action', $attributes, $hidden);
                            ?>
                            <div class="form-group">
                                <label for="password"> <?php echo translate('password'); ?> <small class="required">*</small></label>
                                <input type="password" required="" autocomplete="off"  id="password" name="password" value="" class="form-control" placeholder="<?php echo translate('password'); ?>">                                
                                <?php echo form_error('password'); ?>
                            </div>
                            <div class="form-group">
                                <label for="cpassword"> <?php echo translate('confirm') . " " . translate('password'); ?> <small class="required">*</small></label>
                                <input type="password" required=""  autocomplete="off" id="cpassword" name="cpassword" value="" class="form-control" placeholder="<?php echo translate('confirm') . " " . translate('password'); ?>">
                                <?php echo form_error('cpassword'); ?>
                            </div>
                            <!--Grid row-->
                            <div class="row d-flex align-items-center">
                                <div class="col-md-1 col-md-5 d-flex align-items-start">
                                    <div class="text-center">
                                        <button type="submit" class="btn  btn-blue-grey btn-rounded"><?php echo translate('submit'); ?></button>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <p class="font-small grey-text d-flex justify-content-end mt-3"> <?php echo translate('now'); ?> <a href="<?php echo base_url("admin"); ?>" class="dark-grey-text ml-1 font-bold"> <?php echo translate('login'); ?></a></p>
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
        <!-- knowledge base core JS --><script src="<?php echo $this->config->item('js_url'); ?>module/bookmyslot.js" type="text/javascript"></script>
        <!-- Bootstrap Tooltips--><script src="<?php echo $this->config->item('js_url'); ?>popper.min.js" type="text/javascript"></script>
        <!-- Bootstrap core JS --><script src="<?php echo $this->config->item('js_url'); ?>bootstrap.min.js" type="text/javascript"></script>
        <!-- Bootstrap core JS --><script src="<?php echo $this->config->item('js_url'); ?>sidebar.js" type="text/javascript"></script>
        <!-- Custom Script -->    <script src="<?php echo $this->config->item('js_url'); ?>module/content.js" type="text/javascript"></script>
    </body>
</html>
<?php
exit;
?>